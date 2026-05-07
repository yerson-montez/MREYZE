@extends('layouts.app')
@section('content')
<div style="text-align:center;padding:80px 20px;font-family:'DM Sans',sans-serif;color:#e2ecff;">
    <div style="font-size:4rem;margin-bottom:16px;">✅</div>
    <h1 style="color:#00d4aa;font-size:2rem;margin-bottom:12px;">¡Pago exitoso!</h1>
    <p style="color:#7ab8f5;margin-bottom:8px;">Tu pedido <strong style="color:#fff;">#{{ $pedidoId }}</strong> fue confirmado.</p>
    <p style="color:#7ab8f5;margin-bottom:32px;">Te contactaremos pronto para coordinar la entrega.</p>
    <a href="{{ route('inicio') }}" style="padding:14px 32px;background:#1e4d99;color:#fff;border-radius:12px;text-decoration:none;font-weight:600;">
        Volver al inicio
    </a>
</div>
@endsection