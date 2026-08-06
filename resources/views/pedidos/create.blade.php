@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Realizar Pedido — {{ $producto->nombre }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --ice-900:#071a2e;--ice-800:#0d1f3c;--ice-700:#112952;--ice-600:#1a3a6e;
  --ice-500:#1e4d99;--ice-400:#2563c7;--ice-300:#4a90d9;--ice-200:#7ab8f5;
  --ice-100:#b8d9f9;
  --glow:#3b82f6;--accent:#00d4ff;
  --danger:#ff6b6b;--success:#00d4aa;--warning:#f59e0b;
  --radius-sm:8px;--radius-md:12px;--radius-lg:16px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{font-family:'DM Sans',sans-serif;background:var(--ice-900);color:#e2ecff;min-height:100vh;}

body::before{
  content:'';position:fixed;inset:0;
  background:
    radial-gradient(ellipse 60% 40% at 15% 10%, rgba(30,77,153,.4) 0%, transparent 60%),
    radial-gradient(ellipse 50% 50% at 85% 85%, rgba(0,212,255,.1) 0%, transparent 55%),
    radial-gradient(ellipse 40% 30% at 50% 50%, rgba(17,41,82,.5) 0%, transparent 70%);
  pointer-events:none;z-index:0;
}

/* ── LAYOUT ── */
.page-main{position:relative;z-index:1;padding:48px 1rem 100px;}
.page-inner{max-width:680px;margin:0 auto;display:flex;flex-direction:column;gap:24px;}

/* ── CABECERA ── */
.page-header{text-align:center;padding-bottom:8px;}
.page-header h1{
  font-family:'Exo 2',sans-serif;font-size:2rem;font-weight:700;
  color:#fff;letter-spacing:-.03em;margin-bottom:8px;
}
.page-header h1 span{
  background:linear-gradient(135deg,var(--accent),var(--ice-200));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
}
.page-header p{font-size:.9rem;color:var(--ice-300);line-height:1.6;}

/* ── ALERTAS ── */
.alert-errors{
  background:rgba(255,107,107,.08);
  border:1px solid rgba(255,107,107,.3);
  border-left:4px solid var(--danger);
  border-radius:var(--radius-md);
  padding:14px 18px;
}
.alert-errors p{color:var(--danger);font-size:.82rem;padding:3px 0;display:flex;align-items:center;gap:6px;}
.alert-errors p::before{content:'⚠';font-size:.9rem;}

/* ── TARJETA PRODUCTO ── */
.product-card{
  background:linear-gradient(135deg, rgba(13,31,60,.95), rgba(17,41,82,.8));
  border:1px solid rgba(74,144,217,.25);
  border-radius:var(--radius-lg);
  padding:20px;position:relative;overflow:hidden;
}
.product-card::before{
  content:'';position:absolute;top:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,var(--ice-500),var(--accent),var(--ice-300));
}
.product-card::after{
  content:'';position:absolute;top:-40px;right:-40px;
  width:120px;height:120px;border-radius:50%;
  background:radial-gradient(circle, rgba(0,212,255,.06) 0%, transparent 70%);
  pointer-events:none;
}
.product-top{display:flex;align-items:center;gap:16px;margin-bottom:18px;}
.product-img{
  width:72px;height:72px;border-radius:12px;flex-shrink:0;
  border:1px solid rgba(74,144,217,.2);overflow:hidden;
  background:linear-gradient(135deg,var(--ice-700),var(--ice-600));
}
.product-img img{width:100%;height:100%;object-fit:cover;}
.product-name{font-size:1.05rem;font-weight:700;color:#fff;margin-bottom:4px;}
.product-meta{font-size:.75rem;color:var(--ice-300);}
.product-badge{
  display:inline-flex;align-items:center;gap:4px;margin-top:6px;
  padding:3px 10px;border-radius:20px;
  background:rgba(0,212,170,.1);border:1px solid rgba(0,212,170,.3);
  font-size:.68rem;font-weight:700;color:var(--success);letter-spacing:.06em;text-transform:uppercase;
}
.price-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;}
.price-box{
  background:rgba(10,22,40,.5);border:1px solid rgba(74,144,217,.12);
  border-radius:10px;padding:12px 10px;text-align:center;
  transition:border-color .2s;
}
.price-box:hover{border-color:rgba(74,144,217,.3);}
.price-box.main{border-color:rgba(0,212,255,.2);background:rgba(0,212,255,.04);}
.price-box.pay-now{border-color:rgba(0,212,170,.25);background:rgba(0,212,170,.05);}
.price-box.pay-later{border-color:rgba(37,99,199,.25);background:rgba(37,99,199,.06);}
.price-lbl{font-size:.62rem;color:var(--ice-300);margin-bottom:6px;letter-spacing:.04em;text-transform:uppercase;line-height:1.3;}
.price-val{font-size:1.05rem;font-weight:700;color:#fff;}
.price-box.pay-now .price-val{color:var(--success);}
.price-box.pay-later .price-val{color:var(--ice-200);}

/* ── SECCIÓN FORM ── */
.form-section{
  background:rgba(13,31,60,.8);
  border:1px solid rgba(74,144,217,.15);
  border-radius:var(--radius-lg);overflow:hidden;
  backdrop-filter:blur(8px);
}
.section-header{
  padding:14px 20px;
  background:rgba(17,41,82,.6);
  border-bottom:1px solid rgba(74,144,217,.12);
  display:flex;align-items:center;gap:10px;
}
.section-header .icon{
  width:30px;height:30px;border-radius:8px;flex-shrink:0;
  background:rgba(0,212,255,.1);border:1px solid rgba(0,212,255,.2);
  display:flex;align-items:center;justify-content:center;
}
.section-header .icon svg{width:14px;height:14px;color:var(--accent);}
.section-header h2{
  font-family:'Exo 2',sans-serif;font-size:.85rem;font-weight:600;
  color:#fff;letter-spacing:.06em;text-transform:uppercase;
}
.form-body{padding:20px;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.form-group{margin-bottom:14px;}
.form-group:last-child{margin-bottom:0;}

label.lbl{
  display:block;font-size:.72rem;font-weight:600;
  color:var(--ice-200);margin-bottom:6px;
  letter-spacing:.06em;text-transform:uppercase;
}
label.lbl .req{color:var(--danger);margin-left:2px;}

.input-wrap{position:relative;}
.input-icon{
  position:absolute;left:12px;top:50%;transform:translateY(-50%);
  width:15px;height:15px;color:var(--ice-400);pointer-events:none;flex-shrink:0;
}
.input-icon-ta{
  position:absolute;left:12px;top:13px;
  width:15px;height:15px;color:var(--ice-400);pointer-events:none;
}

input,select,textarea{
  width:100%;
  background:rgba(7,26,46,.7);
  border:1px solid rgba(74,144,217,.2);
  border-radius:var(--radius-sm);
  padding:11px 12px 11px 36px;
  font-size:.875rem;color:#e2ecff;
  outline:none;
  transition:border .2s,box-shadow .2s,background .2s;
  font-family:'DM Sans',sans-serif;
}
input:focus,select:focus,textarea:focus{
  border-color:var(--accent);
  box-shadow:0 0 0 3px rgba(0,212,255,.12);
  background:rgba(17,41,82,.6);
}
input::placeholder,textarea::placeholder{color:var(--ice-300);opacity:.5;}
input.no-icon,select.no-icon{padding-left:12px;}

select{
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%234a90d9' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 12px center;
  padding-right:34px;cursor:pointer;
}
select:disabled{opacity:.4;cursor:not-allowed;}
textarea{resize:vertical;min-height:88px;padding-left:36px;padding-top:11px;line-height:1.5;}

/* ── UBIGEO ── */
.ubigeo-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:14px;}
.spinner-row{
  display:flex;align-items:center;gap:10px;
  font-size:.8rem;color:var(--ice-300);padding:12px 0;
}
.spinner{
  width:18px;height:18px;flex-shrink:0;
  border:2px solid rgba(74,144,217,.15);border-top-color:var(--accent);
  border-radius:50%;animation:spin .7s linear infinite;
}
@keyframes spin{to{transform:rotate(360deg)}}
.ubigeo-selects{display:none;}
.ubigeo-selects.show{display:block;}

/* ── INFO NOTE ── */
.info-note{
  display:flex;align-items:flex-start;gap:10px;
  background:rgba(37,99,199,.08);border:1px solid rgba(74,144,217,.15);
  border-radius:10px;padding:11px 14px;font-size:.78rem;color:var(--ice-200);
  line-height:1.6;margin-top:4px;
}
.info-note svg{width:15px;height:15px;color:var(--accent);flex-shrink:0;margin-top:1px;}
.info-note.warn{background:rgba(245,158,11,.06);border-color:rgba(245,158,11,.2);}
.info-note.warn svg{color:var(--warning);}

/* ── BOTÓN ── */
.submit-area{padding:20px;}
.btn-submit{
  width:100%;
  background:linear-gradient(135deg,var(--ice-500) 0%,var(--ice-400) 50%,var(--glow) 100%);
  background-size:200% 200%;
  color:#fff;font-size:1rem;font-weight:700;
  padding:16px 24px;
  border:1px solid rgba(74,144,217,.35);
  border-radius:var(--radius-md);cursor:pointer;
  letter-spacing:.02em;font-family:'DM Sans',sans-serif;
  transition:all .3s;
  box-shadow:0 6px 28px rgba(30,77,153,.5);
  display:flex;align-items:center;justify-content:center;gap:10px;
  position:relative;overflow:hidden;
}
.btn-submit::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,transparent 0%,rgba(255,255,255,.06) 50%,transparent 100%);
  opacity:0;transition:opacity .3s;
}
.btn-submit:hover{
  background-position:right center;
  box-shadow:0 10px 36px rgba(59,130,246,.6);
  transform:translateY(-2px);
}
.btn-submit:hover::before{opacity:1;}
.btn-submit:active{transform:translateY(0);box-shadow:0 4px 16px rgba(30,77,153,.4);}
.btn-submit:disabled{opacity:.6;cursor:not-allowed;transform:none;}
.btn-submit svg{width:18px;height:18px;flex-shrink:0;}

.submit-note{
  text-align:center;font-size:.76rem;color:var(--ice-300);
  margin-top:12px;display:flex;align-items:center;justify-content:center;gap:6px;
}
.submit-note svg{width:13px;height:13px;color:var(--success);}

.divider{height:1px;background:rgba(74,144,217,.1);margin:0 20px;}

/* ── FIELD ERROR ── */
.field-err{color:var(--danger);font-size:.73rem;margin-top:5px;display:flex;align-items:center;gap:4px;}
.field-err::before{content:'↑';}

/* ── RESPONSIVE ── */
@media(max-width:600px){
  .form-row{grid-template-columns:1fr;}
  .ubigeo-grid{grid-template-columns:1fr;}
  .price-grid{grid-template-columns:1fr 1fr;}
  .price-box.main{grid-column:1/-1;}
  .page-main{padding:28px 1rem 80px;}
  .page-header h1{font-size:1.6rem;}
}
@media(max-width:380px){
  .price-grid{grid-template-columns:1fr;}
}
</style>
</head>
<body>

<main class="page-main">
  <div class="page-inner">

    {{-- ── ERRORES DE VALIDACIÓN ── --}}
    @if ($errors->any())
      <div class="alert-errors">
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    {{-- ── CABECERA ── --}}
    <div class="page-header">
      <h1>Confirmar <span>pedido</span></h1>
      <p>Completa tus datos. Pagas el 50% ahora y el saldo al recibir tu producto.</p>
    </div>

    {{-- ── RESUMEN DEL PRODUCTO ── --}}
   <div class="product-img">

@php
    $imagenProducto = $producto->icono_imagen;

    if ($imagenProducto && str_starts_with($imagenProducto, 'http')) {
        $imagenUrl = $imagenProducto;
    } else {
        $imagenUrl = $imagenProducto
            ? asset('storage/' . $imagenProducto)
            : asset('images/no-image.png');
    }
@endphp

<img src="{{ $imagenUrl }}"
     alt="{{ $producto->nombre }}"
     onerror="this.src='{{ asset('images/no-image.png') }}'">

</div>
        <div style="flex:1;">
          <p class="product-name">{{ $producto->nombre }}</p>
          <p class="product-meta">Ref: #PRD-{{ str_pad($producto->id, 3, '0', STR_PAD_LEFT) }} · Envío a todo el Perú</p>
          <span class="product-badge">✓ Disponible</span>
        </div>
      </div>
      <div class="price-grid">
        <div class="price-box main">
          <div class="price-lbl">Precio total</div>
          <div class="price-val">S/ {{ number_format($producto->precio, 2) }}</div>
        </div>
        <div class="price-box pay-now">
          <div class="price-lbl">Adelanto 50%<br>pagar ahora</div>
          <div class="price-val">S/ {{ number_format($adelanto, 2) }}</div>
        </div>
        <div class="price-box pay-later">
          <div class="price-lbl">Saldo restante<br>contra entrega</div>
          <div class="price-val">S/ {{ number_format($saldo, 2) }}</div>
        </div>
      </div>
    </div>

    {{-- ══ FORMULARIO — ABRE AQUÍ ══ --}}
    <form action="{{ route('pedidos.store', $producto->slug) }}"
          method="POST"
          id="form-pedido"
          novalidate>
      @csrf

      {{-- ── DATOS PERSONALES ── --}}
      <div class="form-section" style="margin-bottom:16px;">
        <div class="section-header">
          <div class="icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
          <h2>Datos del cliente</h2>
        </div>
        <div class="form-body">
          <div class="form-row">

            {{-- Nombre --}}
            <div class="form-group">
              <label class="lbl" for="cliente_nombre">Nombre completo <span class="req">*</span></label>
              <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input type="text" id="cliente_nombre" name="cliente_nombre"
                  value="{{ old('cliente_nombre') }}"
                  placeholder="Ej: María López"
                  autocomplete="name">
              </div>
              @error('cliente_nombre')
                <p class="field-err">{{ $message }}</p>
              @enderror
            </div>

            {{-- Teléfono --}}
            <div class="form-group">
              <label class="lbl" for="cliente_telefono">Teléfono <span class="req">*</span></label>
              <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.22 1.2a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 6a16 16 0 006.29 6.29l.38-.38a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                <input type="tel" id="cliente_telefono" name="cliente_telefono"
                  value="{{ old('cliente_telefono') }}"
                  placeholder="Ej: 999 888 777"
                  autocomplete="tel">
              </div>
              @error('cliente_telefono')
                <p class="field-err">{{ $message }}</p>
              @enderror
            </div>
          </div>

          {{-- Email --}}
          <div class="form-group">
            <label class="lbl" for="cliente_email">Correo electrónico <span class="req">*</span></label>
            <div class="input-wrap">
              <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <input type="email" id="cliente_email" name="cliente_email"
                value="{{ old('cliente_email', auth()->user()?->email) }}"
                placeholder="Ej: maria@gmail.com"
                autocomplete="email">
            </div>
            @error('cliente_email')
              <p class="field-err">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      {{-- ── UBICACIÓN ── --}}
      <div class="form-section" style="margin-bottom:16px;">
        <div class="section-header">
          <div class="icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <h2>Dirección de entrega</h2>
        </div>
        <div class="form-body">

          {{-- Spinner cargando --}}
          <div id="ubigeo-loading" class="spinner-row">
            <div class="spinner"></div>
            Cargando regiones de Perú…
          </div>

          <div id="ubigeo-selects" class="ubigeo-selects">
            <div class="ubigeo-grid">
              <div>
                <label class="lbl">Departamento <span class="req">*</span></label>
                <div class="input-wrap">
                  <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                  {{-- Input hidden es el que se envía al server --}}
                  <input type="hidden" id="h-dep" name="departamento" value="{{ old('departamento') }}">
                  {{-- Select es solo visual --}}
                  <select id="sel-dep">
                    <option value="">— Selecciona —</option>
                  </select>
               </div>
               @error('departamento')
                  <p class="field-err">{{ $message }}</p>
               @enderror
          </div>
          <div>
            <label class="lbl">Provincia <span class="req">*</span></label>
            <div class="input-wrap">
              <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
              <input type="hidden" id="h-prov" name="provincia" value="{{ old('provincia') }}">
              <select id="sel-prov" disabled>
                <option value="">— Provincia —</option>
              </select>
            </div>
            @error('provincia')
              <p class="field-err">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="lbl">Distrito <span class="req">*</span></label>
            <div class="input-wrap">
              <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
              <input type="hidden" id="h-dist" name="distrito" value="{{ old('distrito') }}">
              <select id="sel-dist" disabled>
                <option value="">— Distrito —</option>
              </select>
            </div>
            @error('distrito')
              <p class="field-err">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>
          {{-- Fallback manual — SIN required en HTML, JS lo gestiona --}}
          <div id="ubigeo-fallback" style="display:none;margin-bottom:14px;">
            <div class="info-note warn" style="margin-bottom:12px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              <span>No se pudo cargar las ubicaciones automáticamente. Escríbelas a mano.</span>
            </div>
            <div class="ubigeo-grid">
              <div>
                <label class="lbl">Departamento <span class="req">*</span></label>
                <input type="text" id="fb-dep"
                  placeholder="Ej: Lima" class="no-icon"
                  value="{{ old('departamento') }}">
              </div>
              <div>
                <label class="lbl">Provincia <span class="req">*</span></label>
                <input type="text" id="fb-prov"
                  placeholder="Ej: Lima" class="no-icon"
                  value="{{ old('provincia') }}">
              </div>
              <div>
                <label class="lbl">Distrito <span class="req">*</span></label>
                <input type="text" id="fb-dist"
                  placeholder="Ej: Miraflores" class="no-icon"
                  value="{{ old('distrito') }}">
              </div>
            </div>
          </div>

          {{-- Dirección exacta --}}
          <div class="form-group">
            <label class="lbl" for="cliente_direccion">Dirección exacta <span class="req">*</span></label>
            <div class="input-wrap">
              <svg class="input-icon-ta" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              <textarea id="cliente_direccion" name="cliente_direccion" rows="3"
                placeholder="Calle / Av., número, urbanización, piso, referencia cercana…">{{ old('cliente_direccion') }}</textarea>
            </div>
            @error('cliente_direccion')
              <p class="field-err">{{ $message }}</p>
            @enderror
          </div>

          <div class="info-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Incluye referencias claras (cerca de…, frente a…) para agilizar la entrega.
          </div>
        </div>
      </div>

      {{-- ── BOTÓN ENVIAR — sigue dentro del form ── --}}
      <div class="form-section">
        <div class="divider"></div>
        <div class="submit-area">
          <button type="submit" class="btn-submit" id="btn-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Confirmar pedido — Pagar S/ {{ number_format($adelanto, 2) }} ahora
          </button>
          <p class="submit-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            El saldo de S/ {{ number_format($saldo, 2) }} se abona al momento de la entrega
          </p>
        </div>
      </div>

    </form>
    {{-- ══ FIN FORMULARIO ══ --}}

  </div>
</main>

<script>
const elLoading  = document.getElementById('ubigeo-loading');
const elSelects  = document.getElementById('ubigeo-selects');
const elFallback = document.getElementById('ubigeo-fallback');

// Selects visuales (solo para mostrar)
const selDep  = document.getElementById('sel-dep');
const selProv = document.getElementById('sel-prov');
const selDist = document.getElementById('sel-dist');

// Inputs hidden (los que realmente se envían al server)
const hDep  = document.getElementById('h-dep');
const hProv = document.getElementById('h-prov');
const hDist = document.getElementById('h-dist');

// Fallback manual
const fbDep  = document.getElementById('fb-dep');
const fbProv = document.getElementById('fb-prov');
const fbDist = document.getElementById('fb-dist');

const form      = document.getElementById('form-pedido');
const btnSubmit = document.getElementById('btn-submit');

let ubigeo       = {};
let modoFallback = false;

const sortES = arr => arr.sort((a, b) => a.localeCompare(b, 'es'));

function fillSelect(sel, items, ph) {
  sel.innerHTML = `<option value="">${ph}</option>`;
  sortES(items).forEach(n => sel.add(new Option(n, n)));
  sel.disabled = false;
}

function resetSelect(sel, ph) {
  sel.innerHTML = `<option value="">${ph}</option>`;
  sel.disabled = true;
}

function cargarProvincias(dep) {
  if (!dep || !ubigeo[dep]) {
    resetSelect(selProv, '— Elige departamento primero —');
    resetSelect(selDist,  '— Elige provincia primero —');
    hProv.value = '';
    hDist.value = '';
    return;
  }
  fillSelect(selProv, Object.keys(ubigeo[dep]), '— Selecciona provincia —');
  resetSelect(selDist, '— Selecciona distrito —');
  hProv.value = '';
  hDist.value = '';
}

function cargarDistritos(dep, prov) {
  if (!dep || !prov || !ubigeo[dep]?.[prov]) {
    resetSelect(selDist, '— Elige provincia primero —');
    hDist.value = '';
    return;
  }
  fillSelect(selDist, Object.keys(ubigeo[dep][prov]), '— Selecciona distrito —');
  hDist.value = '';
}

// Cuando cambia el select → actualiza el hidden
selDep.addEventListener('change', () => {
  hDep.value = selDep.value;
  cargarProvincias(selDep.value);
});

selProv.addEventListener('change', () => {
  hProv.value = selProv.value;
  cargarDistritos(selDep.value, selProv.value);
});

selDist.addEventListener('change', () => {
  hDist.value = selDist.value;
});

// Submit: solo valida, no necesita habilitar nada porque los hidden siempre se envían
form.addEventListener('submit', function (e) {
  const errores = [];

  const nombre   = document.getElementById('cliente_nombre').value.trim();
  const telefono = document.getElementById('cliente_telefono').value.trim();
  const email    = document.getElementById('cliente_email').value.trim();
  const dir      = document.getElementById('cliente_direccion').value.trim();

  if (!nombre)   errores.push('El nombre es obligatorio.');
  if (!telefono) errores.push('El teléfono es obligatorio.');
  if (!email || !email.includes('@')) errores.push('Ingresa un correo válido.');
  if (!dir)      errores.push('La dirección exacta es obligatoria.');

  if (modoFallback) {
    if (!fbDep.value.trim())  errores.push('Escribe el departamento.');
    if (!fbProv.value.trim()) errores.push('Escribe la provincia.');
    if (!fbDist.value.trim()) errores.push('Escribe el distrito.');
  } else {
    if (!hDep.value)  errores.push('Selecciona el departamento.');
    if (!hProv.value) errores.push('Selecciona la provincia.');
    if (!hDist.value) errores.push('Selecciona el distrito.');
  }

  if (errores.length > 0) {
    e.preventDefault();
    let alerta = document.getElementById('js-errores');
    if (!alerta) {
      alerta = document.createElement('div');
      alerta.id = 'js-errores';
      alerta.className = 'alert-errors';
      form.parentNode.insertBefore(alerta, form);
    }
    alerta.innerHTML = errores.map(err => `<p>${err}</p>`).join('');
    alerta.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }

  btnSubmit.disabled = true;
  btnSubmit.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin .7s linear infinite"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Procesando…`;
});

// Cargar ubigeo
async function initUbigeo() {
  try {
    const res = await fetch('https://free.e-api.net.pe/ubigeos.json');
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    ubigeo = await res.json();

    fillSelect(selDep, Object.keys(ubigeo), '— Selecciona departamento —');
    elLoading.style.display = 'none';
    elSelects.classList.add('show');
    modoFallback = false;

    // Restaurar old() de Laravel
    const oldDep  = @json(old('departamento', ''));
    const oldProv = @json(old('provincia', ''));
    const oldDist = @json(old('distrito', ''));

    if (oldDep) {
      selDep.value = oldDep;
      hDep.value   = oldDep;
      cargarProvincias(oldDep);
      if (oldProv) {
        selProv.value = oldProv;
        hProv.value   = oldProv;
        cargarDistritos(oldDep, oldProv);
        if (oldDist) {
          selDist.value = oldDist;
          hDist.value   = oldDist;
        }
      }
    }

  } catch (err) {
    console.error('[Ubigeo]', err);
    elLoading.style.display = 'none';
    elFallback.style.display = 'block';
    fbDep.name = 'departamento';
    fbProv.name = 'provincia';
    fbDist.name = 'distrito';
    modoFallback = true;
  }
}

initUbigeo();
</script>

</body>
</html>
@endsection
