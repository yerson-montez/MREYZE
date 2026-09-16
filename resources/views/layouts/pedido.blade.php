<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Realizar Pedido</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
/* ── TOKENS ─────────────────────────────────────────────────── */
:root {
  --ice-900:#0a2826;--ice-800:#0d1f3c;--ice-700:#112952;--ice-600:#1a3a6e;
  --ice-500:#1e4d99;--ice-400:#2563c7;--ice-300:#4a90d9;--ice-200:#7ab8f5;
  --ice-100:#b8d9f9;--ice-50:#e8f4ff;
  --glow:#3b82f6;--accent:#00d4ff;
  --danger:#ff6b6b;--success:#00d4aa;
  --radius-sm:8px;--radius-md:12px;--radius-lg:16px;--radius-xl:20px;
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}

html,body{
  font-family:'DM Sans',sans-serif;
  background:var(--ice-900);
  color:#e2ecff;
  min-height:100vh;
}

/* ── FONDO DECORATIVO ───────────────────────────────────────── */
body::before{
  content:'';
  position:fixed;top:0;left:0;right:0;bottom:0;
  background:
    radial-gradient(ellipse 60% 40% at 20% 10%, rgba(30,77,153,.35) 0%, transparent 60%),
    radial-gradient(ellipse 50% 50% at 80% 80%, rgba(0,212,255,.08) 0%, transparent 55%);
  pointer-events:none;z-index:0;
}

/* ── NAVBAR ─────────────────────────────────────────────────── */
.nav-wrapper{
  position:sticky;top:0;z-index:1000;
  padding:0 2rem;
  background:rgba(10,22,40,.88);
  backdrop-filter:blur(18px) saturate(180%);
  border-bottom:1px solid rgba(74,144,217,.18);
  box-shadow:0 4px 32px rgba(0,90,200,.12);
}
.navbar{
  display:flex;align-items:center;justify-content:space-between;
  max-width:1280px;margin:0 auto;height:68px;
}
.nav-logo{display:flex;align-items:center;gap:10px;text-decoration:none;}
.nav-logo-icon{
  width:38px;height:38px;border-radius:10px;
  background:linear-gradient(135deg,var(--ice-500),var(--accent));
  display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.nav-logo-icon svg{width:20px;height:20px;color:#fff;}
.nav-logo-text{
  font-family:'Exo 2',sans-serif;font-weight:700;
  font-size:1.15rem;letter-spacing:.03em;color:#fff;line-height:1;
}
.nav-logo-text span{color:var(--accent);}
.nav-logo-sub{
  font-size:.58rem;font-weight:300;color:var(--ice-200);
  letter-spacing:.15em;text-transform:uppercase;display:block;margin-top:2px;
}
.nav-links{display:flex;align-items:center;gap:.2rem;list-style:none;}
.nav-links a{
  display:flex;align-items:center;gap:6px;padding:.45rem .9rem;
  border-radius:8px;font-size:.875rem;font-weight:500;
  color:var(--ice-100);text-decoration:none;transition:all .22s;
}
.nav-links a:hover{color:#fff;background:rgba(37,99,199,.25);}
.nav-links a.active{color:#fff;background:rgba(37,99,199,.2);}
.nav-links svg{width:15px;height:15px;opacity:.7;}
.nav-links a:hover svg{opacity:1;}
.breadcrumb-row{
  background:rgba(13,31,60,.6);border-bottom:1px solid rgba(74,144,217,.1);
  padding:.6rem 2rem;
}
.breadcrumb{
  max-width:700px;margin:0 auto;
  display:flex;align-items:center;gap:8px;
  font-size:.78rem;color:var(--ice-300);
}
.breadcrumb a{color:var(--ice-300);text-decoration:none;transition:color .2s;}
.breadcrumb a:hover{color:#fff;}
.breadcrumb .sep{color:var(--ice-600);}
.breadcrumb .current{color:var(--ice-100);}

/* ── PÁGINA ─────────────────────────────────────────────────── */
.page-main{position:relative;z-index:1;padding:40px 1rem 80px;}
.page-inner{max-width:700px;margin:0 auto;display:grid;grid-template-columns:1fr;gap:28px;}

/* ── CABECERA ───────────────────────────────────────────────── */
.page-header{
  display:flex;flex-direction:column;gap:6px;
  padding-bottom:24px;border-bottom:1px solid rgba(74,144,217,.15);
}
.page-header h1{
  font-family:'Exo 2',sans-serif;font-size:1.75rem;font-weight:700;
  color:#fff;letter-spacing:-.02em;
}
.page-header p{font-size:.9rem;color:var(--ice-200);}

/* ── RESUMEN DEL PRODUCTO ───────────────────────────────────── */
.product-card{
  background:var(--ice-800);
  border:1px solid rgba(74,144,217,.22);
  border-radius:var(--radius-lg);
  padding:20px;
  position:relative;overflow:hidden;
}
.product-card::before{
  content:'';position:absolute;top:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,var(--ice-500),var(--accent),var(--ice-500));
}
.product-card-top{display:flex;align-items:flex-start;gap:14px;margin-bottom:16px;}
.product-img-placeholder{
  width:64px;height:64px;border-radius:10px;flex-shrink:0;
  background:linear-gradient(135deg,var(--ice-700),var(--ice-600));
  border:1px solid rgba(74,144,217,.2);
  display:flex;align-items:center;justify-content:center;
}
.product-img-placeholder svg{width:28px;height:28px;color:var(--ice-300);}
.product-info{flex:1;}
.product-name{font-size:1rem;font-weight:600;color:#fff;margin-bottom:4px;}
.product-meta{font-size:.78rem;color:var(--ice-300);}
.product-badge{
  display:inline-flex;align-items:center;gap:5px;
  padding:4px 10px;border-radius:20px;
  background:rgba(0,212,255,.1);border:1px solid rgba(0,212,255,.25);
  font-size:.7rem;font-weight:600;color:var(--accent);letter-spacing:.08em;text-transform:uppercase;
}
.price-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;}
.price-box{
  background:var(--ice-700);border:1px solid rgba(74,144,217,.15);
  border-radius:10px;padding:12px;text-align:center;
}
.price-box.highlight-green{
  background:rgba(0,212,255,.06);border-color:rgba(0,212,255,.28);
}
.price-box.highlight-amber{
  background:rgba(37,99,199,.14);border-color:rgba(74,144,217,.28);
}
.price-lbl{font-size:.65rem;color:var(--ice-300);margin-bottom:5px;letter-spacing:.05em;}
.price-box.highlight-green .price-lbl{color:var(--accent);}
.price-box.highlight-amber .price-lbl{color:var(--ice-200);}
.price-val{font-size:1rem;font-weight:700;color:#fff;}
.price-box.highlight-green .price-val{color:var(--accent);}
.price-box.highlight-amber .price-val{color:var(--ice-100);}

/* ── SECCIÓN DEL FORMULARIO ─────────────────────────────────── */
.form-section{
  background:var(--ice-800);
  border:1px solid rgba(74,144,217,.18);
  border-radius:var(--radius-lg);
  overflow:hidden;
}
.form-section-header{
  padding:16px 22px;
  background:rgba(17,41,82,.7);
  border-bottom:1px solid rgba(74,144,217,.15);
  display:flex;align-items:center;gap:10px;
}
.form-section-header svg{width:16px;height:16px;color:var(--accent);flex-shrink:0;}
.form-section-header h2{
  font-family:'Exo 2',sans-serif;font-size:.9rem;font-weight:600;
  color:#fff;letter-spacing:.04em;text-transform:uppercase;
}
.form-body{padding:22px;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.form-group{margin-bottom:16px;}
.form-group:last-child{margin-bottom:0;}
label.field-label{
  display:block;font-size:.78rem;font-weight:600;
  color:var(--ice-200);margin-bottom:7px;letter-spacing:.04em;text-transform:uppercase;
}
label.field-label .req{color:var(--danger);}
.input-wrap{position:relative;}
.input-icon{
  position:absolute;left:13px;top:50%;transform:translateY(-50%);
  width:16px;height:16px;color:var(--ice-300);pointer-events:none;
}
.input-icon-ta{
  position:absolute;left:13px;top:14px;
  width:16px;height:16px;color:var(--ice-300);pointer-events:none;
}
input,select,textarea{
  width:100%;
  background:rgba(10,22,40,.6);
  border:1px solid rgba(74,144,217,.28);
  border-radius:var(--radius-sm);
  padding:11px 13px 11px 38px;
  font-size:.875rem;color:#e2ecff;
  outline:none;
  transition:border .2s,box-shadow .2s,background .2s;
  font-family:'DM Sans',sans-serif;
}
input:focus,select:focus,textarea:focus{
  border-color:var(--ice-400);
  box-shadow:0 0 0 3px rgba(37,99,199,.22);
  background:rgba(17,41,82,.5);
}
input::placeholder,textarea::placeholder{color:var(--ice-300);opacity:.6;}
select{
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%234a90d9' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 12px center;
  padding-right:36px;cursor:pointer;
}
select:disabled{opacity:.45;cursor:not-allowed;}
textarea{resize:vertical;min-height:90px;padding-left:38px;padding-top:11px;}
input.no-icon,select.no-icon{padding-left:13px;}

/* Ubigeo */
.ubigeo-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;}
.spinner-row{
  display:flex;align-items:center;gap:8px;
  font-size:.78rem;color:var(--ice-300);padding:10px 0;
}
.spinner{
  width:16px;height:16px;flex-shrink:0;
  border:2px solid rgba(74,144,217,.2);border-top-color:var(--accent);
  border-radius:50%;animation:spin .8s linear infinite;
}
@keyframes spin{to{transform:rotate(360deg)}}
.ubigeo-selects{display:none;}
.ubigeo-selects.show{display:block;}
@media (max-width: 600px){
  .price-grid,.ubigeo-grid,.form-row{grid-template-columns:1fr;}
  input,select,textarea{font-size:16px;min-height:44px;}
}

/* ── SEPARADOR INFO ─────────────────────────────────────────── */
.info-note{
  display:flex;align-items:flex-start;gap:10px;
  background:rgba(37,99,199,.1);border:1px solid rgba(74,144,217,.2);
  border-radius:10px;padding:12px 14px;font-size:.8rem;color:var(--ice-200);
  line-height:1.55;margin-top:4px;
}
.info-note svg{width:16px;height:16px;color:var(--accent);flex-shrink:0;margin-top:1px;}

/* ── BOTÓN PRINCIPAL ────────────────────────────────────────── */
.submit-area{padding:22px;border-top:1px solid rgba(74,144,217,.12);}
.btn-submit{
  width:100%;
  background:linear-gradient(135deg,var(--ice-500),var(--ice-400));
  color:#fff;font-size:.95rem;font-weight:700;
  padding:15px;border:1px solid rgba(74,144,217,.4);
  border-radius:var(--radius-md);cursor:pointer;
  letter-spacing:.03em;font-family:'DM Sans',sans-serif;
  transition:all .3s;
  box-shadow:0 6px 24px rgba(30,77,153,.45);
  display:flex;align-items:center;justify-content:center;gap:10px;
}
.btn-submit:hover{
  background:linear-gradient(135deg,var(--ice-400),var(--glow));
  box-shadow:0 8px 32px rgba(59,130,246,.55);transform:translateY(-1px);
}
.btn-submit:active{transform:translateY(0);box-shadow:0 4px 16px rgba(30,77,153,.4);}
.btn-submit svg{width:18px;height:18px;}
.submit-note{
  text-align:center;font-size:.78rem;color:var(--ice-300);
  margin-top:12px;display:flex;align-items:center;justify-content:center;gap:6px;
}
.submit-note svg{width:14px;height:14px;color:var(--accent);}

/* ── FOOTER ─────────────────────────────────────────────────── */
.site-footer{
  background:var(--ice-800);border-top:1px solid rgba(74,144,217,.18);
  padding:40px 2rem 24px;position:relative;z-index:1;
}
.footer-inner{max-width:1280px;margin:0 auto;}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;padding-bottom:32px;}
.footer-logo{display:flex;align-items:center;gap:10px;margin-bottom:12px;text-decoration:none;}
.footer-logo-icon{
  width:32px;height:32px;border-radius:8px;
  background:linear-gradient(135deg,var(--ice-500),var(--accent));
  display:flex;align-items:center;justify-content:center;
}
.footer-logo-icon svg{width:16px;height:16px;color:#fff;}
.footer-logo-name{
  font-family:'Exo 2',sans-serif;font-weight:700;
  font-size:1rem;color:#fff;
}
.footer-logo-name span{color:var(--accent);}
.footer-brand p{font-size:.82rem;color:var(--ice-300);line-height:1.65;max-width:260px;}
.footer-col h4{
  font-size:.68rem;font-weight:700;letter-spacing:.15em;
  text-transform:uppercase;color:var(--ice-300);margin:0 0 12px;
}
.footer-col a{
  display:block;font-size:.82rem;color:var(--ice-200);
  text-decoration:none;margin-bottom:8px;transition:color .2s,padding .2s;
}
.footer-col a:hover{color:#fff;padding-left:4px;}
.footer-bottom{
  border-top:1px solid rgba(74,144,217,.1);padding-top:18px;
  display:flex;justify-content:space-between;align-items:center;
  font-size:.76rem;color:var(--ice-300);
}
.footer-bottom a{color:var(--accent);text-decoration:none;}
.footer-bottom a:hover{color:#fff;}

/* ── RESPONSIVE ─────────────────────────────────────────────── */
@media(max-width:640px){
  .form-row{grid-template-columns:1fr;}
  .ubigeo-grid{grid-template-columns:1fr;}
  .price-grid{grid-template-columns:1fr 1fr;}
  .price-box:first-child{grid-column:1/-1;}
  .footer-grid{grid-template-columns:1fr 1fr;}
  .footer-brand{grid-column:1/-1;}
  .footer-bottom{flex-direction:column;gap:8px;text-align:center;}
  .nav-links{display:none;}
  .page-main{padding:24px 1rem 60px;}
}
@media(max-width:400px){
  .footer-grid{grid-template-columns:1fr;}
  .price-grid{grid-template-columns:1fr;}
}
</style>
</head>
<body>

<!-- ══ CONTENIDO PRINCIPAL ═════════════════════════════════════ -->
<main class="page-main">
  <div class="page-inner">

    <!-- Cabecera -->
    <div class="page-header">
      <h1>Realizar pedido</h1>
      <p>Completa tus datos para confirmar la compra. El adelanto se paga ahora y el saldo contra entrega.</p>
    </div>

    <!-- Resumen del producto -->
    <div class="product-card">
      <div class="product-card-top">
        <div class="product-img-placeholder">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
        </div>
        <div class="product-info">
          <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:6px;">
            <p class="product-name">{{ $producto->nombre }}</p>
            <span class="product-badge">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              Disponible
            </span>
          </div>
          <p class="product-meta">Referencia: #PRD-001 · Envío a todo el Perú</p>
        </div>
      </div>
      <div class="price-grid">
        <div class="price-box">
          <div class="price-lbl">Total del pedido</div>
          <div class="price-val">S/ {{ number_format($producto->precio, 2) }}</div>
        </div>
        <div class="price-box highlight-green">
          <div class="price-lbl">Adelanto 50% — pagar ahora</div>
          <div class="price-val">S/ {{ number_format($adelanto, 2) }}</div>
        </div>
        <div class="price-box highlight-amber">
          <div class="price-lbl">Saldo contra entrega</div>
          <div class="price-val">S/ {{ number_format($saldo, 2) }}</div>
        </div>
      </div>
    </div>

    <!-- Formulario -->
    <form action="{{ route('pedidos.store', $producto) }}" method="POST">
      @csrf

      <!-- DATOS PERSONALES -->
      <div class="form-section" style="margin-bottom:20px;">
        <div class="form-section-header">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <h2>Datos del cliente</h2>
        </div>
        <div class="form-body">
          <div class="form-row">
            <!-- Nombre -->
            <div class="form-group">
              <label class="field-label">Nombre completo <span class="req">*</span></label>
              <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input type="text" name="cliente_nombre"
                  value="{{ old('cliente_nombre') }}"
                  placeholder="Ej: María López" required>
              </div>
              @error('cliente_nombre')
                <p style="color:var(--danger);font-size:.75rem;margin-top:5px;">{{ $message }}</p>
              @enderror
            </div>

            <!-- Teléfono -->
            <div class="form-group">
              <label class="field-label">Número de teléfono <span class="req">*</span></label>
              <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.22 1.2a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 6a16 16 0 006.29 6.29l.38-.38a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                <input type="tel" name="cliente_telefono"
                  value="{{ old('cliente_telefono') }}"
                  placeholder="Ej: 999 888 777" required>
              </div>
              @error('cliente_telefono')
                <p style="color:var(--danger);font-size:.75rem;margin-top:5px;">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- Correo -->
          <div class="form-group">
            <label class="field-label">Correo electrónico <span class="req">*</span></label>
            <div class="input-wrap">
              <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <input type="email" name="cliente_email"
                value="{{ old('cliente_email', auth()->user()?->email) }}"
                placeholder="Ej: maria@gmail.com" required>
            </div>
            @error('cliente_email')
              <p style="color:var(--danger);font-size:.75rem;margin-top:5px;">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- UBICACIÓN Y DIRECCIÓN -->
      <div class="form-section" style="margin-bottom:20px;">
        <div class="form-section-header">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <h2>Ubicación de entrega</h2>
        </div>
        <div class="form-body">

          <!-- Spinner -->
          <div id="ubigeo-loading" class="spinner-row">
            <div class="spinner"></div>
            Cargando departamentos, provincias y distritos…
          </div>

          <!-- Selects encadenados -->
          <div id="ubigeo-selects" class="ubigeo-selects">
            <div class="ubigeo-grid" style="margin-bottom:16px;">
              <div>
                <label class="field-label">Departamento <span class="req">*</span></label>
                <div class="input-wrap">
                  <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                  <select id="sel-dep" name="departamento" required>
                    <option value="">— Departamento —</option>
                  </select>
                </div>
                @error('departamento')
                  <p style="color:var(--danger);font-size:.75rem;margin-top:5px;">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="field-label">Provincia <span class="req">*</span></label>
                <div class="input-wrap">
                  <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                  <select id="sel-prov" name="provincia" disabled required>
                    <option value="">— Provincia —</option>
                  </select>
                </div>
                @error('provincia')
                  <p style="color:var(--danger);font-size:.75rem;margin-top:5px;">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="field-label">Distrito <span class="req">*</span></label>
                <div class="input-wrap">
                  <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                  <select id="sel-dist" name="distrito" disabled required>
                    <option value="">— Distrito —</option>
                  </select>
                </div>
                @error('distrito')
                  <p style="color:var(--danger);font-size:.75rem;margin-top:5px;">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>

          <!-- Fallback manual -->
          <div id="ubigeo-fallback" style="display:none;margin-bottom:16px;">
            <div class="info-note" style="background:rgba(255,107,107,.07);border-color:rgba(255,107,107,.25);margin-bottom:14px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2" style="color:var(--danger)"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              <span>No se pudo cargar las ubicaciones. Escríbelas manualmente.</span>
            </div>
            <div class="ubigeo-grid">
              <div>
                <label class="field-label">Departamento <span class="req">*</span></label>
                <input type="text" name="departamento" placeholder="Departamento *" class="no-icon" required>
              </div>
              <div>
                <label class="field-label">Provincia <span class="req">*</span></label>
                <input type="text" name="provincia" placeholder="Provincia *" class="no-icon" required>
              </div>
              <div>
                <label class="field-label">Distrito <span class="req">*</span></label>
                <input type="text" name="distrito" placeholder="Distrito *" class="no-icon" required>
              </div>
            </div>
          </div>

          <!-- Dirección exacta -->
          <div class="form-group">
            <label class="field-label">Dirección exacta <span class="req">*</span></label>
            <div class="input-wrap">
              <svg class="input-icon-ta" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              <textarea name="cliente_direccion" rows="3"
                placeholder="Calle, número, urbanización, piso, referencia...">{{ old('cliente_direccion') }}</textarea>
            </div>
            @error('cliente_direccion')
              <p style="color:var(--danger);font-size:.75rem;margin-top:5px;">{{ $message }}</p>
            @enderror
          </div>

          <div class="info-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Asegúrate de ingresar una dirección completa con referencias claras para agilizar la entrega.
          </div>
        </div>
      </div>

      <!-- BOTÓN ENVIAR -->
      <div class="form-section">
        <div class="submit-area">
          <button type="submit" class="btn-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Confirmar pedido — Pagar adelanto S/ {{ number_format($adelanto, 2) }}
          </button>
          <p class="submit-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            El saldo de S/ {{ number_format($saldo, 2) }} se abona al momento de la entrega.
          </p>
        </div>
      </div>

    </form>
  </div>
</main>

<script>
const UBIGEO_URL='https://free.e-api.net.pe/ubigeos.json';
const elLoading=document.getElementById('ubigeo-loading');
const elSelects=document.getElementById('ubigeo-selects');
const elFallback=document.getElementById('ubigeo-fallback');
const selDep=document.getElementById('sel-dep');
const selProv=document.getElementById('sel-prov');
const selDist=document.getElementById('sel-dist');
let ubigeo={};

const sortES=arr=>arr.sort((a,b)=>a.localeCompare(b,'es'));

function fillSelect(sel,items,ph){
  sel.innerHTML=`<option value="">${ph}</option>`;
  sortES(items).forEach(n=>sel.add(new Option(n,n)));
  sel.disabled=false;
}
function resetSelect(sel,ph){
  sel.innerHTML=`<option value="">${ph}</option>`;
  sel.disabled=true;
}
function cargarProvincias(dep){
  if(!dep||!ubigeo[dep]){resetSelect(selProv,'— Primero selecciona departamento —');resetSelect(selDist,'— Primero selecciona provincia —');return;}
  fillSelect(selProv,Object.keys(ubigeo[dep]),'— Selecciona provincia —');
  resetSelect(selDist,'— Primero selecciona provincia —');
}
function cargarDistritos(dep,prov){
  if(!dep||!prov||!ubigeo[dep]?.[prov]){resetSelect(selDist,'— Primero selecciona provincia —');return;}
  fillSelect(selDist,Object.keys(ubigeo[dep][prov]),'— Selecciona distrito —');
}
selDep.addEventListener('change',()=>cargarProvincias(selDep.value));
selProv.addEventListener('change',()=>cargarDistritos(selDep.value,selProv.value));

async function initUbigeo(){
  try{
    const res=await fetch(UBIGEO_URL);
    if(!res.ok)throw new Error(`HTTP ${res.status}`);
    ubigeo=await res.json();
    fillSelect(selDep,Object.keys(ubigeo),'— Selecciona departamento —');
    elLoading.style.display='none';
    elSelects.classList.add('show');
    // Restaurar old() de Laravel
    const oldDep=document.querySelector('[data-old-dep]')?.dataset?.oldDep||'';
    const oldProv=document.querySelector('[data-old-prov]')?.dataset?.oldProv||'';
    const oldDist=document.querySelector('[data-old-dist]')?.dataset?.oldDist||'';
    if(oldDep){selDep.value=oldDep;cargarProvincias(oldDep);if(oldProv){selProv.value=oldProv;cargarDistritos(oldDep,oldProv);if(oldDist)selDist.value=oldDist;}}
  }catch(err){
    console.error('[Ubigeo]',err);
    elLoading.style.display='none';
    elFallback.style.display='block';
  }
}
initUbigeo();
</script>
<!-- old() helpers para Laravel -->
<span data-old-dep="{{ old('departamento') }}" data-old-prov="{{ old('provincia') }}" data-old-dist="{{ old('distrito') }}" style="display:none;"></span>

</body>
</html>