@extends('layouts.profile')

@php $breadcrumb = 'profile'; @endphp

@section('content')

<style>
    .page-tag   { font-family:'Share Tech Mono',monospace; font-size:.65rem; color:var(--cyan); letter-spacing:.1em; margin-bottom:.4rem; }
    .page-title { font-size:1.5rem; font-weight:700; color:#fff; margin-bottom:.3rem; }
    .page-sub   { font-size:.85rem; color:var(--ice3); margin-bottom:1.5rem; }

    /* TABS */
    .cyber-tabs { display:flex; gap:.5rem; border-bottom:1px solid rgba(74,144,217,.15); margin-bottom:1.5rem; }
    .cyber-tab  {
        padding:.55rem 1.1rem; font-size:.8rem;
        font-family:'Share Tech Mono',monospace; color:var(--ice3);
        cursor:pointer; border-bottom:2px solid transparent;
        transition:all .2s; margin-bottom:-1px; background:none; border-top:none; border-left:none; border-right:none;
    }
    .cyber-tab:hover  { color:var(--ice1); }
    .cyber-tab.active { color:var(--cyan); border-bottom-color:var(--cyan); }

    /* PANEL */
    .cyber-panel {
        background: rgba(8,20,45,.8);
        border: 1px solid rgba(74,144,217,.18);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        position: relative;
    }
    .cyber-panel::before {
        content:''; position:absolute; top:0; left:0; right:0; height:2px;
        background:linear-gradient(90deg,transparent,rgba(0,212,255,.4),transparent);
    }
    .panel-head {
        padding:.9rem 1.2rem;
        border-bottom:1px solid rgba(74,144,217,.12);
        display:flex; align-items:center; gap:8px;
    }
    .panel-head-title { font-family:'Share Tech Mono',monospace; font-size:.72rem; color:var(--ice2); flex:1; }
    .panel-head-status {
        font-family:'Share Tech Mono',monospace; font-size:.62rem; color:var(--green);
        display:flex; align-items:center; gap:5px;
    }
    .phs-dot { width:5px;height:5px;border-radius:50%;background:var(--green);box-shadow:0 0 5px var(--green);animation:pulse 2s infinite; }
    .panel-body { padding:1.2rem; }

    /* FORM */
    .form-grid  { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem; }
    .form-full  { grid-column:1/-1; }
    .form-group { display:flex; flex-direction:column; gap:.4rem; }
    .form-label { font-family:'Share Tech Mono',monospace; font-size:.65rem; color:var(--ice3); letter-spacing:.06em; }
    .form-input {
        background:rgba(0,15,40,.6); border:1px solid rgba(74,144,217,.2);
        border-radius:8px; padding:.6rem .9rem;
        color:var(--ice1); font-family:'Exo 2',sans-serif; font-size:.85rem;
        outline:none; transition:all .2s; width:100%;
    }
    .form-input:focus { border-color:rgba(0,212,255,.5); box-shadow:0 0 0 2px rgba(0,212,255,.1); }
    .form-input.disabled { opacity:.45; cursor:not-allowed; }
    .form-error { font-size:.72rem; color:#f87171; font-family:'Share Tech Mono',monospace; margin-top:3px; }

    /* BUTTONS */
    .btn-row { display:flex; gap:.7rem; margin-top:1rem; align-items:center; flex-wrap:wrap; }
    .btn-cyber {
        display:inline-flex; align-items:center; gap:7px;
        padding:.65rem 1.4rem; border-radius:9px;
        font-family:'Exo 2',sans-serif; font-size:.85rem; font-weight:600;
        color:#fff; background:linear-gradient(135deg,#1e4d99,#2563c7);
        border:1px solid rgba(74,144,217,.4); cursor:pointer; transition:all .2s;
        box-shadow:0 3px 14px rgba(30,77,153,.4);
    }
    .btn-cyber:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(59,130,246,.5); }
    .btn-ghost {
        display:inline-flex; align-items:center; gap:7px;
        padding:.65rem 1.4rem; border-radius:9px;
        font-family:'Exo 2',sans-serif; font-size:.85rem;
        color:var(--ice2); background:transparent;
        border:1px solid rgba(74,144,217,.2); cursor:pointer; transition:all .2s;
    }
    .btn-ghost:hover { background:rgba(30,77,153,.2); border-color:rgba(74,144,217,.4); color:#fff; }
    .btn-danger {
        display:inline-flex; align-items:center; gap:7px;
        padding:.65rem 1.4rem; border-radius:9px;
        font-family:'Exo 2',sans-serif; font-size:.85rem; font-weight:600;
        color:#fff; background:linear-gradient(135deg,#7f1d1d,#991b1b);
        border:1px solid rgba(249,115,22,.3); cursor:pointer; transition:all .2s;
        box-shadow:0 3px 14px rgba(249,115,22,.2);
    }
    .btn-danger:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(249,115,22,.4); }

    /* ALERT */
    .alert-success {
        background:rgba(74,222,128,.08); border:1px solid rgba(74,222,128,.25);
        border-radius:8px; padding:.7rem 1rem; margin-bottom:1rem;
        font-family:'Share Tech Mono',monospace; font-size:.72rem; color:var(--green);
        display:flex; align-items:center; gap:8px;
    }
    .alert-danger {
        background:rgba(248,113,113,.08); border:1px solid rgba(248,113,113,.25);
        border-radius:8px; padding:.7rem 1rem; margin-bottom:1rem;
        font-family:'Share Tech Mono',monospace; font-size:.72rem; color:#f87171;
    }

    /* DANGER ZONE */
    .danger-zone { border-color: rgba(249,115,22,.3) !important; }
    .danger-zone .panel-head { border-bottom-color: rgba(249,115,22,.15) !important; }
    .danger-zone .panel-head-title { color: #f97316; }
    .danger-note { font-size:.82rem; color:var(--ice3); line-height:1.65; margin-bottom:1rem; }
</style>

{{-- ── PAGE HEADER ── --}}
<div>
    <div class="page-tag">⬡ USER_CONFIG</div>
    <h1 class="page-title">Mi Perfil</h1>
    <p class="page-sub">Gestiona tu información personal y credenciales de acceso</p>
</div>

{{-- ── TABS ── --}}
<div class="cyber-tabs">
    <button class="cyber-tab active" onclick="showTab('info', this)">◉ PERFIL_INFO</button>
    <button class="cyber-tab" onclick="showTab('pass', this)">↯ UPDATE_PASS</button>
    <button class="cyber-tab" onclick="showTab('del', this)">⚠ DELETE_ACCOUNT</button>
</div>

{{-- ═══════════════════════
     TAB 1 — PERFIL INFO
════════════════════════ --}}
<div id="tab-info">

    @if (session('status') === 'profile-updated')
        <div class="alert-success">✓ PROFILE_UPDATED — Cambios guardados correctamente</div>
    @endif

    <div class="cyber-panel">
        <div class="panel-head">
            <span class="panel-head-title">update_profile_information.php — ejecutando</span>
            <span class="panel-head-status"><span class="phs-dot"></span>EDITABLE</span>
        </div>
        <div class="panel-body">
            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="form-grid">
                    {{-- NAME --}}
                    <div class="form-group">
                        <label for="name" class="form-label">NAME_FIELD</label>
                        <input id="name" name="name" type="text"
                               class="form-input"
                               value="{{ old('name', $user->name) }}"
                               required autofocus autocomplete="name">
                        @error('name')
                            <span class="form-error">⚠ {{ $message }}</span>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="form-group">
                        <label for="email" class="form-label">EMAIL_FIELD</label>
                        <input id="email" name="email" type="email"
                               class="form-input"
                               value="{{ old('email', $user->email) }}"
                               required autocomplete="username">
                        @error('email')
                            <span class="form-error">⚠ {{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Email no verificado --}}
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="alert-danger" style="margin-top:.5rem">
                        ⚠ EMAIL_UNVERIFIED — Tu dirección de correo no está verificada.
                        <form method="POST" action="{{ route('verification.send') }}" style="display:inline;margin-left:.5rem">
                            @csrf
                            <button type="submit" class="btn-ghost" style="padding:.3rem .8rem;font-size:.72rem">
                                Reenviar verificación
                            </button>
                        </form>
                        @if (session('status') === 'verification-link-sent')
                            <span style="color:var(--green);font-size:.7rem;margin-left:.5rem">✓ Enlace enviado</span>
                        @endif
                    </div>
                @endif

                <div class="btn-row">
                    <button type="submit" class="btn-cyber">↯ SAVE_CHANGES</button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- ═══════════════════════
     TAB 2 — CONTRASEÑA
════════════════════════ --}}
<div id="tab-pass" style="display:none">

    @if (session('status') === 'password-updated')
        <div class="alert-success">✓ PASSWORD_UPDATED — Contraseña actualizada correctamente</div>
    @endif

    <div class="cyber-panel">
        <div class="panel-head">
            <span class="panel-head-title">update_password.php — esperando input</span>
        </div>
        <div class="panel-body">
            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="form-grid">
                    <div class="form-group">
                        <label for="current_password" class="form-label">CURRENT_PASS</label>
                        <input id="current_password" name="current_password"
                               type="password" class="form-input"
                               autocomplete="current-password" placeholder="••••••••">
                        @error('current_password', 'updatePassword')
                            <span class="form-error">⚠ {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">NEW_PASS</label>
                        <input id="password" name="password"
                               type="password" class="form-input"
                               autocomplete="new-password" placeholder="••••••••">
                        @error('password', 'updatePassword')
                            <span class="form-error">⚠ {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">CONFIRM_PASS</label>
                        <input id="password_confirmation" name="password_confirmation"
                               type="password" class="form-input"
                               autocomplete="new-password" placeholder="••••••••">
                        @error('password_confirmation', 'updatePassword')
                            <span class="form-error">⚠ {{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="btn-row">
                    <button type="submit" class="btn-cyber">↯ UPDATE_PASSWORD</button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- ═══════════════════════
     TAB 3 — ELIMINAR
════════════════════════ --}}
<div id="tab-del" style="display:none">

    <div class="cyber-panel danger-zone">
        <div class="panel-head">
            <span class="panel-head-title">⚠ DELETE_ACCOUNT — zona peligrosa</span>
        </div>
        <div class="panel-body">
            <p class="danger-note">
                Una vez eliminada la cuenta, todos los datos e información serán
                destruidos de forma permanente. Antes de eliminarla, descarga cualquier
                información que desees conservar. Esta acción <strong>no se puede deshacer</strong>.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="form-grid" style="grid-template-columns:1fr">
                    <div class="form-group">
                        <label for="del_password" class="form-label">CONFIRM_WITH_PASSWORD</label>
                        <input id="del_password" name="password"
                               type="password" class="form-input"
                               placeholder="Ingresa tu contraseña para confirmar"
                               style="max-width:400px">
                        @error('password', 'userDeletion')
                            <span class="form-error">⚠ {{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="btn-row">
                    <button type="submit" class="btn-danger"
                            onclick="return confirm('¿Estás seguro? Esta acción no se puede deshacer.')">
                        ⚠ DELETE_ACCOUNT
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function showTab(name, btn) {
    ['info','pass','del'].forEach(t => {
        document.getElementById('tab-' + t).style.display = t === name ? 'block' : 'none';
    });
    document.querySelectorAll('.cyber-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

// Si hay errores de validación, abrir el tab correcto automáticamente
@if ($errors->updatePassword->any())
    document.querySelector('.cyber-tab:nth-child(2)').click();
@elseif ($errors->userDeletion->any())
    document.querySelector('.cyber-tab:nth-child(3)').click();
@endif
</script>

@endsection