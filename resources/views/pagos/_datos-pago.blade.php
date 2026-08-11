@php
  $pagoInfo = config('empresa.pago');
@endphp
<style>
  .ypq-qr-btn{
    display:flex;align-items:center;justify-content:center;gap:10px;width:100%;
    margin-top:1rem;padding:.95rem 1.4rem;border-radius:12px;cursor:pointer;
    font-size:.95rem;font-weight:700;color:#fff;border:1px solid rgba(124,58,237,.4);
    background:linear-gradient(135deg,rgba(124,58,237,.22),rgba(124,58,237,.12));
    transition:all .25s;
  }
  .ypq-qr-btn:hover{transform:translateY(-2px);border-color:rgba(124,58,237,.7);box-shadow:0 10px 28px rgba(124,58,237,.3);}
  .ypq-qr-btn.activo{background:linear-gradient(135deg,rgba(124,58,237,.38),rgba(124,58,237,.2));}
  .ypq-qr-btn .ypq-flecha{transition:transform .3s ease;color:var(--ice-300);}
  .ypq-qr-btn.activo .ypq-flecha{transform:rotate(180deg);}

  .ypq-qr-wrap{display:grid;grid-template-rows:0fr;transition:grid-template-rows .5s ease;}
  .ypq-qr-wrap > div{overflow:hidden;}
  .ypq-qr-inner{opacity:0;transform:scale(.94);transition:opacity .4s ease .1s,transform .4s ease .1s;}
  .ypq-qr-wrap.abierto{grid-template-rows:1fr;}
  .ypq-qr-wrap.abierto .ypq-qr-inner{opacity:1;transform:scale(1);}

  .ypq-qr-caja{
    margin-top:1.1rem;padding:1.5rem;border-radius:16px;
    border:1px solid rgba(74,144,217,.25);background:rgba(13,31,60,.8);
    display:flex;flex-direction:column;align-items:center;gap:.8rem;text-align:center;
  }
  .ypq-qr-caja img{width:190px;height:190px;border-radius:14px;object-fit:contain;background:#fff;padding:8px;box-shadow:0 10px 30px rgba(0,0,0,.35);}
  .ypq-qr-caja p{font-size:.78rem;color:var(--ice-300);margin:0;}

  @media (max-width: 480px) {
    .pago-metodos .info-row{
      flex-direction:column;align-items:flex-start;gap:3px;padding:10px 0;
    }
    .pago-metodos .info-label{
      font-size:.7rem;letter-spacing:.04em;
    }
    .pago-metodos .info-value{
      width:100%;font-size:.9rem;line-height:1.45;
      overflow-wrap:anywhere;word-break:break-word;
    }
    .pago-metodos .ypq-qr-btn{
      padding:.9rem 1rem;font-size:.88rem;gap:8px;
    }
  }
</style>

<div class="pago-card pago-metodos">
  <h2>MÉTODOS DE PAGO</h2>

  {{-- Datos de pago: SIEMPRE visibles --}}
  <div class="info-row">
    <span class="info-label">Banco</span>
    <span class="info-value">{{ $pagoInfo['banco'] }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">CCI</span>
    <span class="info-value">{{ $pagoInfo['cci'] }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Número de cuenta</span>
    <span class="info-value">{{ $pagoInfo['cuenta'] }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Titular</span>
    <span class="info-value">{{ $pagoInfo['titular'] }}</span>
  </div>

  {{-- Botón: despliega SOLO el QR --}}
  <button type="button" class="ypq-qr-btn" id="yapeQrToggle" aria-expanded="false">
    <span>📱 Pagar con Yape / Plin</span>
    <svg class="ypq-flecha" viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M16.59 8.59 12 13.17 7.41 8.59 6 10l6 6 6-6z"/></svg>
  </button>

  <div class="ypq-qr-wrap" id="yapeQrWrap">
    <div>
      <div class="ypq-qr-inner">
        <div class="ypq-qr-caja">
          <img src="{{ asset($pagoInfo['qr']) }}" alt="Código QR {{ $pagoInfo['medios'] }}">
          <p>Escanea con tu app Yape o Plin y confirma el pago.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  if (window.__yapeQrInit) return;
  window.__yapeQrInit = true;

  document.addEventListener('click', function (e) {
    var btn = e.target.closest ? e.target.closest('#yapeQrToggle') : null;
    if (!btn) return;
    var wrap = document.getElementById('yapeQrWrap');
    if (!wrap) return;
    var abierto = wrap.classList.toggle('abierto');
    btn.classList.toggle('activo', abierto);
    btn.setAttribute('aria-expanded', abierto ? 'true' : 'false');
  });

  document.addEventListener('change', function (e) {
    if (e.target && e.target.name === 'metodo_pago') {
      var val = e.target.value || '';
      if (val !== 'yape' && val !== 'plin') {
        var wrap = document.getElementById('yapeQrWrap');
        var btn = document.getElementById('yapeQrToggle');
        if (wrap) wrap.classList.remove('abierto');
        if (btn) {
          btn.classList.remove('activo');
          btn.setAttribute('aria-expanded', 'false');
        }
      }
    }
  });
})();
</script>
