<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout — {{ $producto['nombre'] }}</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Culqi Checkout JS (oficial) --}}
    <script src="https://checkout.culqi.com/js/v4"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0c0e14;
            --surface:   #14161f;
            --card:      #1a1d28;
            --border:    #2a2e42;
            --accent:    #5b6af0;
            --accent2:   #7c8cf8;
            --success:   #22c55e;
            --error:     #ef4444;
            --warning:   #f59e0b;
            --text:      #e8eaf6;
            --muted:     #7b82a8;
            --font:      'Sora', sans-serif;
            --mono:      'JetBrains Mono', monospace;
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        /* Grid de fondo */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(91,106,240,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(91,106,240,.04) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .checkout-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 820px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: 0 40px 80px rgba(0,0,0,.5);
        }

        @media (max-width: 680px) {
            .checkout-wrapper { grid-template-columns: 1fr; }
        }

        /* ── Panel izquierdo: producto ── */
        .product-panel {
            background: linear-gradient(135deg, #1e2236 0%, #151824 100%);
            padding: 40px 36px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            border-right: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .product-panel::after {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(91,106,240,.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--accent2);
            background: rgba(91,106,240,.12);
            border: 1px solid rgba(91,106,240,.25);
            border-radius: 99px;
            padding: 4px 12px;
            width: fit-content;
        }

        .badge::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--success);
            box-shadow: 0 0 6px var(--success);
        }

        .product-img {
            width: 72px;
            height: 72px;
            border-radius: 16px;
            background: rgba(91,106,240,.1);
            border: 1px solid rgba(91,106,240,.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
        }

        .product-name {
            font-size: 22px;
            font-weight: 700;
            line-height: 1.3;
            color: var(--text);
        }

        .product-desc {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.6;
        }

        .features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .features li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: var(--muted);
        }

        .features li::before {
            content: '✓';
            width: 20px; height: 20px;
            border-radius: 50%;
            background: rgba(34,197,94,.12);
            color: var(--success);
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .price-block {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .price-label {
            font-size: 12px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 4px;
        }

        .price-value {
            font-size: 36px;
            font-weight: 700;
            color: var(--text);
            font-family: var(--mono);
        }

        .price-value span {
            font-size: 18px;
            color: var(--muted);
        }

        /* ── Panel derecho: formulario ── */
        .payment-panel {
            background: var(--card);
            padding: 40px 36px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .panel-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
        }

        .panel-subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-top: 4px;
            line-height: 1.5;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .form-input {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            color: var(--text);
            font-family: var(--font);
            transition: border-color .2s, box-shadow .2s;
            outline: none;
            width: 100%;
        }

        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(91,106,240,.15);
        }

        .form-input::placeholder { color: var(--muted); }

        /* ── Métodos de pago ── */
        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .method-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .method-card:hover { border-color: var(--accent); }

        .method-card.active {
            border-color: var(--accent);
            background: rgba(91,106,240,.08);
            box-shadow: 0 0 0 1px var(--accent);
        }

        .method-icon { font-size: 22px; }

        .method-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .method-desc {
            font-size: 11px;
            color: var(--muted);
        }

        /* ── Botón de pago ── */
        .btn-pay {
            width: 100%;
            padding: 16px;
            background: var(--accent);
            color: #fff;
            font-family: var(--font);
            font-size: 15px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: auto;
            position: relative;
            overflow: hidden;
        }

        .btn-pay::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.1) 0%, transparent 60%);
        }

        .btn-pay:hover {
            background: var(--accent2);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(91,106,240,.4);
        }

        .btn-pay:active { transform: translateY(0); }

        .btn-pay:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-pay .spinner {
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            display: none;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .btn-pay.loading .spinner { display: block; }
        .btn-pay.loading .btn-text { opacity: 0; }

        /* ── Alerta de resultado ── */
        .alert {
            padding: 16px;
            border-radius: 12px;
            font-size: 14px;
            line-height: 1.5;
            display: none;
            align-items: flex-start;
            gap: 12px;
        }

        .alert.show { display: flex; }

        .alert-icon { font-size: 20px; flex-shrink: 0; }

        .alert-success {
            background: rgba(34,197,94,.08);
            border: 1px solid rgba(34,197,94,.25);
            color: #86efac;
        }

        .alert-error {
            background: rgba(239,68,68,.08);
            border: 1px solid rgba(239,68,68,.25);
            color: #fca5a5;
        }

        .alert strong { display: block; margin-bottom: 4px; color: inherit; }

        /* ── Seguridad ── */
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 12px;
            color: var(--muted);
        }

        /* ── Tarjetas de prueba ── */
        .test-cards {
            background: var(--surface);
            border: 1px dashed var(--border);
            border-radius: 12px;
            padding: 16px;
        }

        .test-cards-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--warning);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .test-card-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            padding: 6px 0;
            border-bottom: 1px solid var(--border);
            gap: 8px;
        }

        .test-card-row:last-child { border-bottom: none; }

        .card-number {
            font-family: var(--mono);
            font-size: 12px;
            color: var(--text);
            cursor: pointer;
            transition: color .15s;
        }

        .card-number:hover { color: var(--accent2); }

        .card-status {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 99px;
            font-weight: 600;
        }

        .card-status.ok {
            background: rgba(34,197,94,.12);
            color: var(--success);
        }

        .card-status.fail {
            background: rgba(239,68,68,.12);
            color: var(--error);
        }

        /* ── Toast ── */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 13px;
            color: var(--text);
            box-shadow: 0 8px 32px rgba(0,0,0,.5);
            transform: translateY(80px);
            opacity: 0;
            transition: all .35s cubic-bezier(.34,1.56,.64,1);
            z-index: 9999;
            max-width: 300px;
        }

        .toast.show { transform: translateY(0); opacity: 1; }
    </style>
</head>
<body>

<div class="checkout-wrapper">

    {{-- ── Panel Producto ──────────────────────────────────────── --}}
    <div class="product-panel">

        <div class="badge">Modo Sandbox</div>

        <div class="product-img">🎓</div>

        <div>
            <div class="product-name">{{ $producto['nombre'] }}</div>
            <div class="product-desc" style="margin-top:8px">{{ $producto['descripcion'] }}</div>
        </div>

        <ul class="features">
            <li>Acceso de por vida al curso</li>
            <li>Certificado de completación</li>
            <li>Soporte por Discord</li>
            <li>Actualizaciones gratuitas</li>
        </ul>

        <div class="price-block">
            <div class="price-label">Total a pagar</div>
            <div class="price-value">
                <span>S/</span> {{ number_format($producto['precio'] / 100, 2) }}
            </div>
        </div>

        {{-- Tarjetas de prueba --}}
        <div class="test-cards">
            <div class="test-cards-title">⚠ Tarjetas de prueba</div>

            <div class="test-card-row">
                <div>
                    <div style="font-size:11px;color:var(--muted)">VISA Aprobada</div>
                    <div class="card-number" title="Clic para copiar" onclick="copyCard(this,'4111111111111111')">
                        4111 1111 1111 1111
                    </div>
                </div>
                <span class="card-status ok">Aprobada</span>
            </div>

            <div class="test-card-row">
                <div>
                    <div style="font-size:11px;color:var(--muted)">MasterCard Aprobada</div>
                    <div class="card-number" onclick="copyCard(this,'5111111111111118')">
                        5111 1111 1111 1118
                    </div>
                </div>
                <span class="card-status ok">Aprobada</span>
            </div>

            <div class="test-card-row">
                <div>
                    <div style="font-size:11px;color:var(--muted)">VISA Rechazada</div>
                    <div class="card-number" onclick="copyCard(this,'4000000000000002')">
                        4000 0000 0000 0002
                    </div>
                </div>
                <span class="card-status fail">Denegada</span>
            </div>

            <div style="font-size:11px;color:var(--muted);margin-top:10px;line-height:1.5">
                CVV: <code style="font-family:var(--mono)">123</code> &nbsp;|&nbsp;
                Vence: <code style="font-family:var(--mono)">09/{{ date('Y') + 2 }}</code>
            </div>
        </div>

    </div>

    {{-- ── Panel Pago ───────────────────────────────────────────── --}}
    <div class="payment-panel">

        <div>
            <div class="panel-title">Completa tu pago</div>
            <div class="panel-subtitle">Elige tu método de pago preferido.</div>
        </div>

        {{-- Métodos de pago --}}
        <div class="payment-methods">
            <div class="method-card active" id="method-card" onclick="selectMethod('card')">
                <div class="method-icon">💳</div>
                <div class="method-name">Tarjeta</div>
                <div class="method-desc">Visa, Mastercard, Amex</div>
            </div>
            <div class="method-card" id="method-yape" onclick="selectMethod('yape')">
                <div class="method-icon">📱</div>
                <div class="method-name">Yape</div>
                <div class="method-desc">Pago con QR</div>
            </div>
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label class="form-label" for="email">Correo electrónico</label>
            <input
                type="email"
                id="email"
                class="form-input"
                placeholder="tu@correo.com"
                value="test@test.com"
                required
            >
        </div>

        {{-- Alerta resultado --}}
        <div class="alert alert-success" id="alert-success">
            <span class="alert-icon">✅</span>
            <div>
                <strong>¡Pago exitoso!</strong>
                <span id="alert-success-msg"></span>
            </div>
        </div>

        <div class="alert alert-error" id="alert-error">
            <span class="alert-icon">❌</span>
            <div>
                <strong>Pago rechazado</strong>
                <span id="alert-error-msg"></span>
            </div>
        </div>

        {{-- Botón pagar --}}
        <button class="btn-pay" id="btn-pay" onclick="initPago()">
            <span class="btn-text">🔒 Pagar S/ {{ number_format($producto['precio'] / 100, 2) }}</span>
            <span class="spinner"></span>
        </button>

        <div class="security-note">
            🔐 Pago cifrado con TLS 1.3 · Procesado por Culqi
        </div>

    </div>
</div>

{{-- Toast --}}
<div class="toast" id="toast"></div>


<script>
// ═══════════════════════════════════════════════════════════
//  CONFIGURACIÓN
// ═══════════════════════════════════════════════════════════
const CULQI_PUBLIC_KEY = "{{ $publicKey }}";
const PAGO_URL         = "{{ route('pago.procesar') }}";
const CSRF_TOKEN       = document.querySelector('meta[name="csrf-token"]').content;

let selectedMethod = 'card';

// ── Configura Culqi Checkout ───────────────────────────────
Culqi.publicKey = CULQI_PUBLIC_KEY;

Culqi.settings({
    title   : '{{ $producto['nombre'] }}',
    currency: 'PEN',
    amount  : {{ $producto['precio'] }},
    order   : null,
});

Culqi.options({
    lang          : 'es',
    installments  : false,
    paymentMethods: {
        tarjeta : true,
        yape    : true,
        cuotealo: false,
    },
    style: {
        logo      : '',
        maincolor : '#5b6af0',
        buttontext: '#ffffff',
        maintext  : '#14161f',
        desctext  : '#4a5568',
    },
});

// ═══════════════════════════════════════════════════════════
//  CALLBACK DE CULQI: se ejecuta cuando el usuario genera token
// ═══════════════════════════════════════════════════════════
function culqi() {
    if (Culqi.token) {
        // ── Token generado exitosamente ──────────────────────────
        const token = Culqi.token.id;
        const email = document.getElementById('email').value || Culqi.token.email;
        enviarPagoAlBackend(token, email);

    } else if (Culqi.order) {
        // ── Para Yape u otros métodos con order ──────────────────
        const orderId = Culqi.order.id;
        console.log('Order ID Yape:', orderId);
        // En Yape el pago se confirma vía webhook
        showAlert('success', 'Pago con Yape iniciado. Confirma en tu app. Order: ' + orderId);
        setLoading(false);

    } else {
        // ── Error o modal cerrado ────────────────────────────────
        if (Culqi.error) {
            const msg = Culqi.error.user_message || Culqi.error.merchant_message || 'Error al obtener el token.';
            showAlert('error', msg);
        }
        setLoading(false);
    }
}

// ═══════════════════════════════════════════════════════════
//  INICIA EL FLUJO DE PAGO
// ═══════════════════════════════════════════════════════════
function initPago() {
    const email = document.getElementById('email').value.trim();

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showToast('Por favor ingresa un correo válido.');
        document.getElementById('email').focus();
        return;
    }

    // Actualizar email en Culqi antes de abrir
    Culqi.settings({ title: '{{ $producto['nombre'] }}', currency: 'PEN', amount: {{ $producto['precio'] }} });

    hideAlerts();
    setLoading(true);
    Culqi.open();
}

// ═══════════════════════════════════════════════════════════
//  ENVIAR TOKEN AL BACKEND
// ═══════════════════════════════════════════════════════════
async function enviarPagoAlBackend(token, email) {
    try {
        const response = await fetch(PAGO_URL, {
            method : 'POST',
            headers: {
                'Content-Type' : 'application/json',
                'X-CSRF-TOKEN' : CSRF_TOKEN,
                'Accept'       : 'application/json',
            },
            body: JSON.stringify({
                culqi_token: token,
                email      : email || document.getElementById('email').value,
                amount     : {{ $producto['precio'] }},
            }),
        });

        const data = await response.json();

        if (data.success) {
            showAlert('success',
                `Monto: ${data.amount} · ID: ${data.payment_id}`
            );
            showToast('✅ Pago completado');
        } else {
            showAlert('error', data.message || 'Error al procesar el pago.');
        }

    } catch (err) {
        console.error('Error de red:', err);
        showAlert('error', 'Error de conexión. Verifica tu internet e intenta de nuevo.');
    } finally {
        setLoading(false);
    }
}

// ═══════════════════════════════════════════════════════════
//  UTILIDADES UI
// ═══════════════════════════════════════════════════════════
function selectMethod(method) {
    selectedMethod = method;
    document.getElementById('method-card').classList.toggle('active', method === 'card');
    document.getElementById('method-yape').classList.toggle('active', method === 'yape');
}

function setLoading(on) {
    const btn = document.getElementById('btn-pay');
    btn.disabled = on;
    btn.classList.toggle('loading', on);
}

function showAlert(type, msg) {
    hideAlerts();
    const alertEl  = document.getElementById('alert-' + type);
    const msgEl    = document.getElementById('alert-' + type + '-msg');
    if (msgEl) msgEl.textContent = msg;
    alertEl.classList.add('show');
    alertEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function hideAlerts() {
    document.getElementById('alert-success').classList.remove('show');
    document.getElementById('alert-error').classList.remove('show');
}

function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3500);
}

function copyCard(el, number) {
    navigator.clipboard.writeText(number).then(() => {
        showToast('Número copiado: ' + number.replace(/(\d{4})/g, '$1 ').trim());
    });
}
</script>
</body>
</html>
