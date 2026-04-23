<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>
<style>
    .profile-bg {
        background:
            radial-gradient(circle at top right, rgba(37, 99, 235, .10), transparent 28%),
            radial-gradient(circle at bottom left, rgba(124, 58, 237, .10), transparent 30%),
            linear-gradient(135deg, #f8fbff 0%, #f3f7ff 45%, #f8f5ff 100%);
    }

    .profile-card {
        background: rgba(255,255,255,.94);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(15,23,42,.06);
        border-radius: 1.75rem;
        box-shadow: 0 20px 45px rgba(15,23,42,.08);
    }

    .profile-avatar-box {
        width: 128px;
        height: 128px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 12px 30px rgba(15,23,42,.12);
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 2rem;
        text-transform: uppercase;
    }

    .profile-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-label {
        font-weight: 700;
        color: #0f172a;
    }

    .profile-input {
        min-height: 54px;
        border-radius: 1rem;
        border: 1px solid #dbe3f0;
        box-shadow: none !important;
    }

    .profile-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 .2rem rgba(37,99,235,.12) !important;
    }

    .profile-btn {
        min-height: 54px;
        border-radius: 1rem;
        border: none;
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: #fff;
        font-weight: 700;
        box-shadow: 0 12px 28px rgba(37,99,235,.20);
    }

    .profile-badge {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem 1rem;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
        border: 1px solid #dbeafe;
    }

    .profile-info-chip {
        border-radius: 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    $username = old('username', $usuario['username'] ?? '');
    $nombre = old('nombre', $persona['nombre'] ?? '');
    $apellidoPaterno = old('apellido_paterno', $persona['apellido_paterno'] ?? '');
    $apellidoMaterno = old('apellido_materno', $persona['apellido_materno'] ?? '');
    $rol = session('nombre_rol') ?: 'Usuario';

    $imagen = !empty($usuario['imagen_perfil']) ? base_url(ltrim($usuario['imagen_perfil'], '/')) : null;

    $iniciales = 'US';
    if (!empty($username)) {
        $partes = preg_split('/[\s._-]+/', $username);
        $partes = array_values(array_filter($partes));

        if (count($partes) >= 2) {
            $iniciales = strtoupper(
                mb_substr($partes[0], 0, 1) .
                mb_substr($partes[1], 0, 1)
            );
        } else {
            $iniciales = strtoupper(mb_substr($username, 0, 2));
        }
    }
?>

<section class="profile-bg py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="profile-card p-4 h-100">
                    <div class="text-center">
                        <div class="profile-avatar-box mx-auto mb-3" id="avatarPreviewBox">
                            <?php if ($imagen): ?>
                                <img src="<?= esc($imagen) ?>" alt="Foto de perfil" id="avatarPreviewImg">
                            <?php else: ?>
                                <span id="avatarFallback"><?= esc($iniciales) ?></span>
                            <?php endif; ?>
                        </div>

                        <h3 class="fw-bold mb-1"><?= esc(trim($nombre . ' ' . $apellidoPaterno . ' ' . $apellidoMaterno)) ?></h3>
                        <div class="text-muted mb-3">@<?= esc($username) ?></div>

                        <div class="profile-badge justify-content-center mx-auto">
                            <span class="material-symbols-rounded">badge</span>
                            <span><?= esc($rol) ?></span>
                        </div>

                        <div class="row g-3 mt-4 text-start">
                            <div class="col-12">
                                <div class="profile-info-chip p-3">
                                    <div class="small text-muted">Usuario</div>
                                    <div class="fw-semibold"><?= esc($username) ?></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="profile-info-chip p-3">
                                    <div class="small text-muted">Rol</div>
                                    <div class="fw-semibold"><?= esc($rol) ?></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="profile-info-chip p-3">
                                    <div class="small text-muted">Perfil</div>
                                    <div class="fw-semibold">Información personal y foto de cuenta</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="profile-card p-4 p-md-5">
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 text-primary mb-2">
                            <span class="material-symbols-rounded">person_edit</span>
                            <span class="fw-semibold">Configuración de perfil</span>
                        </div>
                        <h1 class="fw-bold mb-1">Editar perfil</h1>
                        <p class="text-muted mb-0">
                            Modifica tus datos personales y actualiza tu imagen de perfil.
                        </p>
                    </div>

                    <form action="<?= base_url('perfil/actualizar') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="nombre" class="form-label profile-label">Nombre</label>
                                <input
                                    type="text"
                                    name="nombre"
                                    id="nombre"
                                    value="<?= esc($nombre) ?>"
                                    class="form-control profile-input"
                                    required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="apellido_paterno" class="form-label profile-label">Apellido paterno</label>
                                <input
                                    type="text"
                                    name="apellido_paterno"
                                    id="apellido_paterno"
                                    value="<?= esc($apellidoPaterno) ?>"
                                    class="form-control profile-input"
                                    required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="apellido_materno" class="form-label profile-label">Apellido materno</label>
                                <input
                                    type="text"
                                    name="apellido_materno"
                                    id="apellido_materno"
                                    value="<?= esc($apellidoMaterno) ?>"
                                    class="form-control profile-input">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label profile-label">Nombre de usuario</label>
                            <input
                                type="text"
                                name="username"
                                id="username"
                                value="<?= esc($username) ?>"
                                class="form-control profile-input"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="foto_perfil" class="form-label profile-label">Foto de perfil</label>
                            <input
                                type="file"
                                name="foto_perfil"
                                id="foto_perfil"
                                class="form-control profile-input"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                            <div class="form-text">
                                Formatos permitidos: JPG, JPEG, PNG y WEBP. Tamaño máximo: 4 MB.
                            </div>
                        </div>

                        <div class="d-grid d-md-flex gap-3">
                            <button type="submit" class="btn profile-btn px-4">
                                Guardar cambios
                            </button>

                            <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary px-4 rounded-4">
                                Volver al inicio
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const inputFoto = document.getElementById('foto_perfil');
    const avatarBox = document.getElementById('avatarPreviewBox');

    inputFoto?.addEventListener('change', function () {
        const file = this.files?.[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            avatarBox.innerHTML = `<img src="${e.target.result}" alt="Vista previa de perfil">`;
        };
        reader.readAsDataURL(file);
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->include('layout/Footer') ?>
<?= $this->endSection() ?>