@extends('welcome')

@section('title', 'Invertir — ' . $proyecto->titulo . ' · MREYZE')

@section('content')

@php
    $disponible = (float) $proyecto->inversion_monto_disponible;
@endphp

<style>
    .inv {
        max-width: 1280px;
        margin: 0 auto;
        padding: 6.5rem 2rem 5rem;
    }

    /* ── FORMULARIO ── */
    .inv-form-section { scroll-margin-top: 90px; }
    .inv-form-wrap {
        border-radius: 22px;
        background: linear-gradient(135deg, rgba(13,31,60,.95), rgba(17,41,82,.9));
        border: 1px solid rgba(0,212,255,.25);
        padding: 2.5rem 2rem;
        position: relative; overflow: hidden;
        max-width: 860px; margin: 0 auto;
    }
    .inv-form-wrap::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
    }
    .inv-form-title { font-family: 'Exo 2', sans-serif; font-size: 1.6rem; font-weight: 800; color: #fff; margin-bottom: .4rem; }
    .inv-form-sub { color: var(--ice-300); font-size: .88rem; margin-bottom: 2rem; }
    .inv-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; }
    .inv-campo { display: flex; flex-direction: column; gap: 6px; }
    .inv-campo.full { grid-column: 1 / -1; }
    .inv-campo label { font-size: .76rem; font-weight: 600; color: var(--ice-100); text-transform: uppercase; letter-spacing: .05em; }
    .inv-campo input, .inv-campo select {
        background: rgba(7,26,46,.6); border: 1px solid rgba(74,144,217,.25);
        border-radius: 10px; padding: .75rem 1rem; color: #fff; font-size: .9rem; outline: none; transition: border-color .2s, box-shadow .2s;
    }
    .inv-campo input:focus, .inv-campo select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0,212,255,.15); }
    .inv-campo input::placeholder { color: rgba(122,154,184,.6); }
    .inv-campo input[type="file"] { padding: .5rem; }
    .inv-campo input[type="file"]::file-selector-button {
        background: rgba(0,212,255,.15); border: 1px solid rgba(0,212,255,.35); color: var(--accent);
        border-radius: 8px; padding: .45rem .9rem; margin-right: .8rem; cursor: pointer; font-weight: 600; font-size: .8rem;
    }
    .inv-error { font-size: .74rem; color: #ff6b6b; }
    .inv-hint { font-size: .72rem; color: var(--ice-300); }

    /* Carga de archivos (comprobante y firma) */
    .inv-uploads { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; margin-top: 1.4rem; }
    .inv-upload { display: flex; flex-direction: column; }
    .inv-upload-label {
        display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 6px;
        border: 1.5px dashed rgba(74,144,217,.35); border-radius: 14px; padding: 1.6rem 1.2rem; min-height: 190px;
        background: rgba(7,26,46,.45); cursor: pointer; transition: all .25s; flex: 1;
    }
    .inv-upload-label:hover { border-color: var(--accent); background: rgba(0,212,255,.06); box-shadow: 0 10px 28px rgba(0,60,160,.25); }
    .inv-upload-icon { font-size: 1.7rem; line-height: 1; }
    .inv-upload-titulo { font-size: .86rem; font-weight: 700; color: #fff; }
    .inv-upload-hint { font-size: .72rem; color: var(--ice-300); }
    .inv-upload-boton {
        margin-top: 8px; padding: .55rem 1.3rem; border-radius: 10px; font-size: .8rem; font-weight: 700;
        background: rgba(0,212,255,.15); border: 1px solid rgba(0,212,255,.35); color: var(--accent);
        transition: all .2s;
    }
    .inv-upload-label:hover .inv-upload-boton { background: rgba(0,212,255,.28); }
    .inv-upload-file {
        min-height: 1.4rem; margin-top: 8px; font-size: .78rem; color: var(--accent);
        text-align: center; word-break: break-all;
    }

    /* Sección bancaria (inversionista) */
    .inv-pago-titulo {
        font-family: 'Exo 2', sans-serif; font-size: 1.05rem; font-weight: 700; color: #fff;
        display: flex; align-items: center; gap: 9px; margin-bottom: .4rem;
    }
    .inv-pago-titulo svg { width: 18px; height: 18px; color: var(--accent); flex-shrink: 0; }
    .inv-pago-seccion { border-top: 1px solid rgba(74,144,217,.18); margin-top: 1.8rem; padding-top: 1.8rem; }

    /* Frecuencia de ganancias (inversionista) */
    .inv-frecuencia { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .inv-frecuencia-opt { position: relative; cursor: pointer; }
    .inv-frecuencia-opt input { position: absolute; opacity: 0; pointer-events: none; }
    .inv-frecuencia-caja {
        display: flex; flex-direction: column; gap: 4px;
        border: 1.5px solid rgba(74,144,217,.25); border-radius: 14px;
        background: rgba(7,26,46,.5); padding: 1rem 1.1rem; transition: all .25s;
    }
    .inv-frecuencia-opt input:checked + .inv-frecuencia-caja {
        border-color: var(--accent); background: rgba(0,212,255,.08);
        box-shadow: 0 0 0 3px rgba(0,212,255,.15);
    }
    .inv-frecuencia-opt input:focus-visible + .inv-frecuencia-caja { border-color: var(--accent); }
    .inv-frecuencia-titulo { font-size: .88rem; font-weight: 700; color: #fff; }
    .inv-frecuencia-hint { font-size: .72rem; color: var(--ice-300); }

    /* Cuenta BBVA */
    .inv-pago-cuenta {
        border: 1px solid rgba(74,144,217,.25); border-radius: 14px;
        background: rgba(7,26,46,.5); padding: 1.1rem 1.3rem; margin-top: 1.2rem;
    }
    .inv-pago-cuenta-head { display: flex; align-items: center; margin-bottom: .6rem; }
    .inv-pago-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 14px; border-radius: 20px; font-size: .72rem; font-weight: 800; letter-spacing: .06em;
        background: rgba(0,212,255,.12); border: 1px solid rgba(0,212,255,.35); color: var(--accent);
    }
    .inv-pago-fila { display: flex; justify-content: space-between; gap: 1rem; padding: .5rem 0; border-bottom: 1px dashed rgba(74,144,217,.15); }
    .inv-pago-fila:last-child { border-bottom: none; }
    .inv-pago-fila span { font-size: .78rem; color: var(--ice-300); flex-shrink: 0; }
    .inv-pago-fila strong { font-size: .82rem; color: #fff; text-align: right; word-break: break-word; }

    /* Botón y QR Yape / Plin */
    .inv-qr-btn {
        display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%;
        margin-top: 1rem; padding: .95rem 1.4rem; border-radius: 12px; cursor: pointer;
        font-size: .95rem; font-weight: 700; color: #fff; border: 1px solid rgba(124,58,237,.4);
        background: linear-gradient(135deg, rgba(124,58,237,.22), rgba(124,58,237,.12));
        transition: all .25s;
    }
    .inv-qr-btn:hover { transform: translateY(-2px); border-color: rgba(124,58,237,.7); box-shadow: 0 10px 28px rgba(124,58,237,.3); }
    .inv-qr-btn.activo { background: linear-gradient(135deg, rgba(124,58,237,.38), rgba(124,58,237,.2)); }
    .inv-qr-btn .flecha { transition: transform .3s ease; }
    .inv-qr-btn.activo .flecha { transform: rotate(180deg); }

    .inv-qr-wrap { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .5s ease; }
    .inv-qr-wrap > div { overflow: hidden; }
    .inv-qr-inner { opacity: 0; transform: scale(.94); transition: opacity .4s ease .1s, transform .4s ease .1s; }
    .inv-qr-wrap.abierto { grid-template-rows: 1fr; }
    .inv-qr-wrap.abierto .inv-qr-inner { opacity: 1; transform: scale(1); }
    .inv-qr-caja {
        margin-top: 1.1rem; padding: 1.5rem; border-radius: 16px;
        border: 1px solid rgba(74,144,217,.25); background: rgba(13,31,60,.8);
        display: flex; flex-direction: column; align-items: center; gap: .8rem;
    }
    .inv-qr-caja img { width: 190px; height: 190px; border-radius: 14px; object-fit: contain; background: #fff; padding: 8px; box-shadow: 0 10px 30px rgba(0,0,0,.35); }
    .inv-qr-caja p { font-size: .78rem; color: var(--ice-300); margin: 0; }

    .inv-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        padding: 1rem 2.4rem; border-radius: 12px; border: none; cursor: pointer;
        font-size: 1rem; font-weight: 700; color: #fff; letter-spacing: .02em;
        background: linear-gradient(135deg, #0088cc, #00a8e8);
        box-shadow: 0 8px 26px rgba(0,136,204,.4);
        transition: all .25s; text-decoration: none;
    }
    .inv-btn:hover { background: linear-gradient(135deg, #0099dd, #00bbff); transform: translateY(-2px); box-shadow: 0 12px 34px rgba(0,136,204,.55); }
    .inv-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }

    @media (max-width: 900px) {
        .inv-form-grid { grid-template-columns: 1fr; }
        .inv-uploads { grid-template-columns: 1fr; }
        .inv { padding: 5.5rem 1rem 4rem; }
    }
</style>

<div class="inv">

    {{-- ══════════════════ FORMULARIO ══════════════════ --}}
    <section class="inv-form-section" id="inv-form">
        <div class="inv-form-wrap reveal">
            <h2 class="inv-form-title">Solicita tu inversión</h2>
            <p class="inv-form-sub">Completa tus datos y elige el medio de pago. Nuestro equipo confirmará tu aporte para activar tu participación.</p>

            @if($errors->any())
                <div style="background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.3);color:#ff9c9c;border-radius:10px;padding:.9rem 1.1rem;font-size:.85rem;margin-bottom:1.4rem;">
                    <strong>Revisa los siguientes campos:</strong>
                    <ul style="margin:6px 0 0 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('inversiones.store', $proyecto) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="inv-form-grid">
                    <div class="inv-campo">
                        <label for="nombre">Nombre completo *</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej. María Pérez" required>
                        @error('nombre') <span class="inv-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="inv-campo">
                        <label for="email">Correo electrónico *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="tu@correo.com" required>
                        @error('email') <span class="inv-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="inv-campo">
                        <label for="telefono">Teléfono / WhatsApp</label>
                        <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="999 999 999">
                    </div>

                    <div class="inv-campo">
                        <label for="dni">DNI / RUC</label>
                        <input type="text" id="dni" name="dni" value="{{ old('dni') }}" placeholder="Ej. 12345678">
                    </div>

                    <div class="inv-campo full">
                        <label for="monto">Monto a invertir (S/) * <span class="inv-hint">— Disponible: S/ {{ number_format($disponible, 2) }}</span></label>
                        <input type="number" step="0.01" min="50" max="{{ $disponible }}" id="monto" name="monto" value="{{ old('monto', 5000) }}" required>
                        @error('monto') <span class="inv-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                @php $metodoPagoId = old('metodo_pago_id', $metodosPago->first()->id ?? null); @endphp

                <div class="inv-pago-seccion">
                    <h3 class="inv-pago-titulo">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M5 22h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-4l-2-2H9L7 4H5C3.9 4 3 4.9 3 6v14c0 1.1.9 2 2 2zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm3.5-4.5h-2.5v-3h-2v3H8.5L12 16l3.5-3.5z"/></svg>
                        ¿Cómo deseas recibir tus ganancias?
                    </h3>
                    <p class="inv-hint" style="margin:0 0 1.2rem;">Elige la frecuencia con la que recibirás tus rendimientos.</p>

                    <div class="inv-frecuencia">
                        <label class="inv-frecuencia-opt">
                            <input type="radio" name="frecuencia_ganancias" value="mensual" required @checked(old('frecuencia_ganancias') === 'mensual')>
                            <span class="inv-frecuencia-caja">
                                <span class="inv-frecuencia-titulo">Mensualmente</span>
                                <span class="inv-frecuencia-hint">Recibe tus ganancias cada mes</span>
                            </span>
                        </label>
                        <label class="inv-frecuencia-opt">
                            <input type="radio" name="frecuencia_ganancias" value="anual" @checked(old('frecuencia_ganancias') === 'anual')>
                            <span class="inv-frecuencia-caja">
                                <span class="inv-frecuencia-titulo">Anualmente</span>
                                <span class="inv-frecuencia-hint">Recibe tus ganancias una vez al año</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="inv-pago-seccion">
                    <h3 class="inv-pago-titulo">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11 17h2v-1h1a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-3V9h4V7h-2V6h-2v1h-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h3v2H9v2h2v1zM4 22a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H4zm2-2h12V4H6v16z"/></svg>
                        Datos bancarios para recibir ganancias
                    </h3>
                    <p class="inv-hint" style="margin:0 0 1.2rem;">Cuenta donde depositaremos tus rendimientos.</p>

                    <div class="inv-form-grid">
                        <div class="inv-campo">
                            <label for="banco">Banco *</label>
                            <select id="banco" name="banco" required>
                                <option value="">Selecciona tu banco</option>
                                @foreach(['BBVA','BCP','Interbank','Scotiabank','BanBif','CaixaBank','Banco de la Nación','Otro'] as $opcion)
                                    <option value="{{ $opcion }}" {{ old('banco') == $opcion ? 'selected' : '' }}>{{ $opcion }}</option>
                                @endforeach
                            </select>
                            @error('banco') <span class="inv-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="inv-campo">
                            <label for="cuenta_bancaria">Número de cuenta *</label>
                            <input type="text" id="cuenta_bancaria" name="cuenta_bancaria" value="{{ old('cuenta_bancaria') }}" placeholder="Ej. 0011-0146-0100050182" required>
                            @error('cuenta_bancaria') <span class="inv-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="inv-campo">
                            <label for="cci_bancario">CCI *</label>
                            <input type="text" id="cci_bancario" name="cci_bancario" value="{{ old('cci_bancario') }}" placeholder="Ej. 011-146-000100050182-83" required>
                            @error('cci_bancario') <span class="inv-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="inv-campo">
                            <label for="titular_cuenta">Titular de la cuenta *</label>
                            <input type="text" id="titular_cuenta" name="titular_cuenta" value="{{ old('titular_cuenta') }}" placeholder="Nombre del titular" required>
                            @error('titular_cuenta') <span class="inv-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="inv-pago-seccion">
                    <h3 class="inv-pago-titulo">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 14H4V6h16v12zM7 15h4v2H7v-2z"/></svg>
                        ¿Cómo realizar tu pago?
                    </h3>
                    <p class="inv-hint" style="margin:0;">Deposita o transfiere a nuestra cuenta y envía tu comprobante.</p>

                    <div class="inv-pago-cuenta">
                        <div class="inv-pago-cuenta-head">
                            <span class="inv-pago-badge">🏦 Transferencia bancaria</span>
                        </div>
                        <div class="inv-pago-fila"><span>Banco</span><strong>{{ config('empresa.pago.banco') }}</strong></div>
                        <div class="inv-pago-fila"><span>Número de cuenta</span><strong>{{ config('empresa.pago.cuenta') }}</strong></div>
                        <div class="inv-pago-fila"><span>CCI</span><strong>{{ config('empresa.pago.cci') }}</strong></div>
                        <div class="inv-pago-fila"><span>Titular de la cuenta</span><strong>{{ config('empresa.pago.titular') }}</strong></div>
                    </div>

                    @if($metodosPago->count())
                        <input type="hidden" name="metodo_pago_id" value="{{ $metodoPagoId }}">
                    @endif

                        <button type="button" class="inv-qr-btn" id="inv-qr-btn" aria-expanded="false">
                            <span>📱 Pagar con Yape / Plin</span>
                            <svg class="flecha" viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M16.59 8.59 12 13.17 7.41 8.59 6 10l6 6 6-6z"/></svg>
                        </button>

                        <div class="inv-qr-wrap" id="inv-qr-wrap">
                            <div>
                                <div class="inv-qr-inner">
                                    <div class="inv-qr-caja">
                                        <img src="{{ asset(config('empresa.pago.qr')) }}" alt="Código QR Yape / Plin">
                                        <p>Escanea con tu app Yape o Plin y confirma el pago.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="inv-campo" style="margin-top:1.4rem;">
                    <label for="numero_operacion">N.º de operación</label>
                    <input type="text" id="numero_operacion" name="numero_operacion" value="{{ old('numero_operacion') }}" placeholder="Opcional">
                </div>

                <div class="inv-uploads">
                    <div class="inv-upload">
                        <input type="file" id="comprobante" name="comprobante" accept="image/*,.pdf" hidden>
                        <label for="comprobante" class="inv-upload-label">
                            <span class="inv-upload-icon">📄</span>
                            <span class="inv-upload-titulo">Adjuntar comprobante de pago</span>
                            <span class="inv-upload-hint">JPG, PNG, WEBP o PDF · máx. 5 MB</span>
                            <span class="inv-upload-boton">Seleccionar archivo</span>
                        </label>
                        <div class="inv-upload-file" id="nombre-comprobante"></div>
                        @error('comprobante') <span class="inv-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="inv-upload">
                        <input type="file" id="firma" name="firma" accept="image/jpeg,image/png,image/webp" hidden>
                        <label for="firma" class="inv-upload-label">
                            <span class="inv-upload-icon">✍️</span>
                            <span class="inv-upload-titulo">Adjuntar firma</span>
                            <span class="inv-upload-hint">JPG, JPEG, PNG o WEBP · máx. 5 MB</span>
                            <span class="inv-upload-boton">Seleccionar archivo</span>
                        </label>
                        <div class="inv-upload-file" id="nombre-firma"></div>
                        @error('firma') <span class="inv-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="margin-top:2rem;display:flex;flex-wrap:wrap;gap:12px;align-items:center;">
                    <button type="submit" class="inv-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px"><path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                        Solicitar contrato
                    </button>
                    <span class="inv-hint">Al enviar aceptas los términos y condiciones de MREYZE.</span>
                </div>
            </form>
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('inv-qr-btn');
    var wrap = document.getElementById('inv-qr-wrap');
    if (btn && wrap) {
        btn.addEventListener('click', function () {
            var abierto = wrap.classList.toggle('abierto');
            btn.classList.toggle('activo', abierto);
            btn.setAttribute('aria-expanded', abierto ? 'true' : 'false');
        });
    }

    function initInvUpload(inputId, targetId) {
        var input = document.getElementById(inputId);
        var target = document.getElementById(targetId);
        if (input && target) {
            input.addEventListener('change', function () {
                target.textContent = input.files && input.files.length
                    ? '✓ ' + input.files[0].name
                    : '';
            });
        }
    }
    initInvUpload('comprobante', 'nombre-comprobante');
    initInvUpload('firma', 'nombre-firma');

    var comprobanteInput = document.getElementById('comprobante');
    var numeroOperacion = document.getElementById('numero_operacion');
    var nombreComprobante = document.getElementById('nombre-comprobante');

    if (comprobanteInput && numeroOperacion) {
        comprobanteInput.addEventListener('change', function () {
            var file = this.files && this.files[0];
            if (!file) return;
            nombreComprobante.textContent = '🔍 Leyendo comprobante...';

            var fd = new FormData();
            fd.append('comprobante', file);

            fetch('{{ route('comprobante.leer') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: fd
            })
            .then(function (r) { return r.json().catch(function () { return null; }); })
            .then(function (data) {
                if (data && data.codigo) {
                    numeroOperacion.value = data.codigo;
                    nombreComprobante.textContent = '✅ Nro. de operación detectado: ' + data.codigo + ' (puedes editarlo)';
                } else {
                    numeroOperacion.value = '';
                    nombreComprobante.textContent = '✓ ' + file.name + ' — no se detectó el nro. automáticamente';
                }
            })
            .catch(function () {
                numeroOperacion.value = '';
                nombreComprobante.textContent = '✓ ' + file.name;
            });
        });
    }
});
</script>
@endpush
