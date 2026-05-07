@extends('layouts.app')
@section('content')
<div style="text-align:center;padding:80px 20px;font-family:'DM Sans',sans-serif;color:#e2ecff;">
    <div style="font-size:4rem;margin-bottom:16px;">⏳</div>
    <h1 style="color:#00d4ff;font-size:2rem;margin-bottom:12px;">Pago pendiente</h1>
    <p style="color:#7ab8f5;margin-bottom:32px;">Tu pago está siendo procesado. Te avisaremos cuando se confirme.</p>
    <a href="{{ route('inicio') }}" style="padding:14px 32px;background:#1e4d99;color:#fff;border-radius:12px;text-decoration:none;font-weight:600;">
        Volver al inicio
    </a>
</div>
@endsection