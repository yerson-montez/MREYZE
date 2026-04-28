<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Muestra la vista del checkout.
     */
    public function showCheckout()
    {
        $producto = [
            'nombre'      => 'Curso Laravel + Culqi',
            'descripcion' => 'Aprende a integrar pagos en Laravel con Culqi.',
            'precio'      => 1000,      // en céntimos (S/ 10.00)
            'imagen'      => 'https://laravel.com/img/logomark.min.svg',
        ];

        $publicKey = config('culqi.public_key');

        return view('checkout', compact('producto', 'publicKey'));
    }

    /**
     * Procesa el pago: recibe el token de Culqi desde el frontend
     * y crea el cargo en la API de Culqi.
     */
    public function procesarPago(Request $request)
    {
        // ── 1. Validar el payload entrante ───────────────────────────────
        $validator = Validator::make($request->all(), [
            'culqi_token' => 'required|string',
            'email'       => 'required|email',
            'amount'      => 'required|integer|min:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // ── 2. Crear la orden en estado "pendiente" ───────────────────────
        $order = Order::create([
            'product_name' => 'Curso Laravel + Culqi',
            'amount'       => $request->amount,
            'currency_code' => 'PEN',
            'email'        => $request->email,
            'status'       => 'pendiente',
            'culqi_token'  => $request->culqi_token,
        ]);

        // ── 3. Enviar cargo a Culqi API ────────────────────────────────────
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('culqi.secret_key'),
                'Content-Type'  => 'application/json',
            ])->post(config('culqi.api_url') . '/charges', [
                'amount'        => $request->amount,
                'currency_code' => 'PEN',
                'email'         => $request->email,
                'source_id'     => $request->culqi_token,
                'description'   => 'Pago de: Curso Laravel + Culqi',
                'capture'       => true,
                'antifraud_details' => [
                    'address'      => 'Av. Larco 123',
                    'address_city' => 'Lima',
                    'country_code' => 'PE',
                    'first_name'   => 'Cliente',
                    'last_name'    => 'Prueba',
                    'phone'        => '900000000',
                ],
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);

            $culqiData = $response->json();

            Log::info('Culqi response', ['order_id' => $order->id, 'data' => $culqiData]);

            // ── 4a. Pago exitoso ─────────────────────────────────────────
            if ($response->successful() && isset($culqiData['id'])) {
                $order->update([
                    'status'         => 'pagado',
                    'payment_id'     => $culqiData['id'],
                    'culqi_response' => $culqiData,
                ]);

                return response()->json([
                    'success'    => true,
                    'message'    => '¡Pago realizado con éxito!',
                    'order_id'   => $order->id,
                    'payment_id' => $culqiData['id'],
                    'amount'     => 'S/ ' . number_format($request->amount / 100, 2),
                ]);
            }

            // ── 4b. Error de Culqi (respuesta con código de error) ────────
            $errorMsg = $culqiData['user_message']
                ?? $culqiData['merchant_message']
                ?? 'Error al procesar el pago.';

            $order->update([
                'status'         => 'fallido',
                'culqi_response' => $culqiData,
                'error_message'  => $errorMsg,
            ]);

            return response()->json([
                'success' => false,
                'message' => $errorMsg,
                'code'    => $culqiData['code'] ?? null,
            ], 400);

        } catch (\Exception $e) {
            // ── 4c. Error de conexión / excepción ─────────────────────────
            Log::error('Culqi exception', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);

            $order->update([
                'status'        => 'fallido',
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error de conexión con la pasarela de pago. Intenta nuevamente.',
            ], 500);
        }
    }

    /**
     * Webhook de Culqi para eventos asíncronos
     * (tarjeta, Yape, transferencias, etc.)
     * URL: POST /webhook/culqi
     */
    public function webhook(Request $request)
    {
        // ── 1. Validar cabecera de autenticación de Culqi ─────────────────
        // Culqi envía: Authorization: Bearer TU_SECRET_KEY
        $authHeader = $request->header('Authorization');
        $expectedToken = 'Bearer ' . config('culqi.secret_key');

        if ($authHeader !== $expectedToken) {
            Log::warning('Culqi webhook: Authorization inválida', [
                'ip' => $request->ip(),
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $payload = $request->all();

        Log::info('Culqi Webhook recibido', $payload);

        // ── 2. Identificar el tipo de evento ─────────────────────────────
        $eventType = $payload['type'] ?? null;
        $eventData = $payload['data'] ?? null;

        if (!$eventType || !$eventData) {
            return response()->json(['error' => 'Payload inválido'], 400);
        }

        switch ($eventType) {

            // Cargo aprobado (Yape, transferencias, etc.)
            case 'charge.creation.succeeded':
                $this->handleChargeSucceeded($eventData);
                break;

            // Cargo rechazado
            case 'charge.creation.failed':
                $this->handleChargeFailed($eventData);
                break;

            // Cargo reembolsado
            case 'charge.refund.succeeded':
                $this->handleChargeRefunded($eventData);
                break;

            default:
                Log::info('Culqi Webhook: evento no manejado', ['type' => $eventType]);
        }

        // Siempre responder 200 a Culqi para evitar reintentos
        return response()->json(['received' => true], 200);
    }

    /**
     * Maneja el evento: cargo exitoso desde webhook.
     */
    private function handleChargeSucceeded(array $data): void
    {
        $paymentId = $data['id'] ?? null;
        if (!$paymentId) return;

        // Buscar la orden por payment_id o por metadata.order_id
        $orderId = $data['metadata']['order_id'] ?? null;

        $order = $orderId
            ? Order::find($orderId)
            : Order::where('payment_id', $paymentId)->first();

        if ($order && $order->status !== 'pagado') {
            $order->update([
                'status'         => 'pagado',
                'payment_id'     => $paymentId,
                'culqi_response' => $data,
            ]);

            Log::info('Webhook: orden actualizada a PAGADO', ['order_id' => $order->id]);
        }
    }

    /**
     * Maneja el evento: cargo fallido desde webhook.
     */
    private function handleChargeFailed(array $data): void
    {
        $paymentId = $data['id'] ?? null;
        $orderId   = $data['metadata']['order_id'] ?? null;

        $order = $orderId
            ? Order::find($orderId)
            : ($paymentId ? Order::where('payment_id', $paymentId)->first() : null);

        if ($order && $order->status !== 'pagado') {
            $order->update([
                'status'         => 'fallido',
                'payment_id'     => $paymentId,
                'culqi_response' => $data,
                'error_message'  => $data['outcome']['user_message'] ?? 'Pago rechazado.',
            ]);

            Log::info('Webhook: orden actualizada a FALLIDO', ['order_id' => $order->id]);
        }
    }

    /**
     * Maneja el evento: reembolso exitoso desde webhook.
     */
    private function handleChargeRefunded(array $data): void
    {
        $paymentId = $data['id'] ?? null;
        if (!$paymentId) return;

        $order = Order::where('payment_id', $paymentId)->first();

        if ($order) {
            $order->update([
                'status'         => 'fallido', // o crear un estado 'reembolsado'
                'culqi_response' => $data,
                'error_message'  => 'Pago reembolsado.',
            ]);

            Log::info('Webhook: orden marcada como REEMBOLSADA', ['order_id' => $order->id]);
        }
    }
}
