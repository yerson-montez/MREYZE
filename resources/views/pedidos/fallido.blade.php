@extends('layouts.app')
@section('content')
<div style="text-align:center;padding:80px 20px;font-family:'DM Sans',sans-serif;color:#e2ecff;">
    <div style="font-size:4rem;margin-bottom:16px;">❌</div>
    <h1 style="color:#ff6b6b;font-size:2rem;margin-bottom:12px;">Pago rechazado</h1>
    <p style="color:#7ab8f5;margin-bottom:32px;">Hubo un problema con tu pago. Puedes intentarlo de nuevo.</p>
    <a href="javascript:history.back()" style="padding:14px 32px;background:#1e4d99;color:#fff;border-radius:12px;text-decoration:none;font-weight:600;">
        Volver e intentar de nuevo
    </a>
</div>
@endsection