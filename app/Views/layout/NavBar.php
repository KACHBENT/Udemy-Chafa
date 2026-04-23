<?php
$isLoggedIn     = session('isLoggedIn');
$nombreCompleto = session('nombre_completo') ?: 'Invitado';
$nombreRol      = session('nombre_rol') ?: 'Visitante';
$username       = trim((string) (session('username') ?: ''));
$imagenPerfil   = session('imagen_perfil');

$iniciales = 'US';

if ($username !== '') {
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

$avatar = !empty($imagenPerfil)
    ? base_url(ltrim($imagenPerfil, '/'))
    : null;

/*
|--------------------------------------------------------------------------
| Segmentación por rol
|--------------------------------------------------------------------------
| Si session('nombre_rol') trae un string, lo convertimos a array.
| Si algún día trae varios roles, también lo soporta.
*/
$rolesSession = session('nombre_rol');

if (is_array($rolesSession)) {
    $roles = array_map(fn($r) => strtoupper(trim((string) $r)), $rolesSession);
} else {
    $roles = [strtoupper(trim((string) $rolesSession))];
}

$can = function (array $allowedRoles = []) use ($roles): bool {
    if (empty($allowedRoles)) {
        return true;
    }

    $allowedRoles = array_map(
        fn($r) => strtoupper(trim((string) $r)),
        $allowedRoles
    );

    return count(array_intersect($roles, $allowedRoles)) > 0;
};
?>



<nav class="navbar sticky-top shadow-sm app-navbar">
    <div class="container py-2">
        <div class="d-flex align-items-center justify-content-between w-100 gap-3 flex-wrap">

            <!-- Marca -->
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold m-0" href="<?= base_url('/') ?>">
                <div class="brand-logo d-flex align-items-center justify-content-center">
                    <span class="material-symbols-rounded text-white">school</span>
                </div>
                <div class="d-flex flex-column lh-sm">
                    <span class="brand-title">Udemy-Chafa</span>
                    <small class="text-muted fw-normal">Campus virtual</small>
                </div>
            </a>

            <!-- Usuario -->
            <div class="d-flex align-items-center ms-auto">
                <?php if ($isLoggedIn): ?>
                    <div class="dropdown">
                        <button class="btn user-menu-btn d-flex align-items-center gap-2 px-2 py-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar-wrapper">
                                <?php if (!empty($avatar)): ?>
                                    <img src="<?= esc($avatar) ?>" alt="Foto de perfil" class="user-avatar">
                                <?php else: ?>
                                    <div class="user-avatar user-avatar-fallback">
                                        <?= esc($iniciales) ?>
                                    </div>
                                <?php endif; ?>
                                <span class="status-dot"></span>
                            </div>

                            <div class="text-start d-none d-md-block">
                                <div class="fw-semibold text-dark user-name"><?= esc($nombreCompleto) ?></div>
                                <div class="small text-muted d-flex align-items-center gap-1">
                                    <span class="material-symbols-rounded role-icon">badge</span>
                                    <span><?= esc($nombreRol) ?></span>
                                </div>
                            </div>

                            <span class="material-symbols-rounded text-secondary d-none d-md-inline">expand_more</span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2 user-dropdown">
                            <li class="px-3 py-2 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar-wrapper-lg">
                                        <?php if (!empty($avatar)): ?>
                                            <img src="<?= esc($avatar) ?>" alt="Foto de perfil" class="user-avatar-lg">
                                        <?php else: ?>
                                            <div class="user-avatar-lg user-avatar-fallback">
                                                <?= esc($iniciales) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= esc($nombreCompleto) ?></div>
                                        <div class="small text-muted">@<?= esc($username) ?></div>
                                        <div class="small text-primary fw-semibold mt-1"><?= esc($nombreRol) ?></div>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <a class="dropdown-item rounded-3 d-flex align-items-center gap-2 py-2" href="<?= base_url('perfil') ?>">
                                    <span class="material-symbols-rounded">person</span>
                                    Mi perfil
                                </a>
                            </li>
                            <?php if ($can(['Alumno'])): ?>


                                <li>
                                    <a class="dropdown-item rounded-3 d-flex align-items-center gap-2 py-2" href="<?= base_url('mis-cursos') ?>">
                                        <span class="material-symbols-rounded">school</span>
                                        Mis cursos
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item rounded-3 d-flex align-items-center gap-2 py-2" href="<?= base_url('mis-certificados') ?>">
                                        <span class="material-symbols-rounded">workspace_premium</span>
                                        Mis certificados
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item rounded-3 d-flex align-items-center gap-2 py-2 text-danger" href="<?= base_url('acceso/logout') ?>">
                                    <span class="material-symbols-rounded">logout</span>
                                    Cerrar sesión
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="d-flex gap-2 auth-actions-desktop">
                        <a href="<?= base_url('acceso/login') ?>" class="btn btn-outline-primary rounded-pill px-4">
                            Iniciar sesión
                        </a>
                        <a href="<?= base_url('acceso/registro') ?>" class="btn btn-primary rounded-pill px-4">
                            Registrarse
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Nav items SIEMPRE visibles -->
        <div class="nav-mobile-scroll mt-3">
            <?php if ($can(['Alumno'])): ?>
                <a class="nav-link-custom active" href="<?= base_url('/') ?>">
                    <span class="material-symbols-rounded">home</span>
                    <span>Inicio</span>
                </a>

                <a class="nav-link-custom" href="<?= base_url('cursos') ?>">
                    <span class="material-symbols-rounded">menu_book</span>
                    <span>Cursos</span>
                </a>

                <a class="nav-link-custom" href="<?= base_url('certificados') ?>">
                    <span class="material-symbols-rounded">workspace_premium</span>
                    <span>Certificados</span>
                </a>
            <?php endif; ?>
            <?php if ($can(['Administrador'])): ?>
                <a class="nav-link-custom" href="<?= base_url('usuarios') ?>">
                    <span class="material-symbols-rounded">supervised_user_circle</span>
                    <span>Usuarios</span>
                </a>
            <?php endif; ?>
            <?php if (!$isLoggedIn): ?>
                <a class="nav-link-custom d-md-none" href="<?= base_url('acceso/login') ?>">
                    <span class="material-symbols-rounded">login</span>
                    <span>Entrar</span>
                </a>

                <a class="nav-link-custom d-md-none" href="<?= base_url('acceso/registro') ?>">
                    <span class="material-symbols-rounded">person_add</span>
                    <span>Registro</span>
                </a>
            <?php endif; ?>

        </div>
    </div>
</nav>

<style>
    .app-navbar {
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    }

    .brand-logo {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.25);
        flex-shrink: 0;
    }

    .brand-title {
        font-size: 1rem;
        color: #0f172a;
    }

    .nav-mobile-scroll {
        display: flex;
        gap: .75rem;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: .35rem;
        scrollbar-width: thin;
        -webkit-overflow-scrolling: touch;
    }

    .nav-mobile-scroll::-webkit-scrollbar {
        height: 6px;
    }

    .nav-mobile-scroll::-webkit-scrollbar-thumb {
        background: rgba(100, 116, 139, .35);
        border-radius: 999px;
    }

    .nav-link-custom {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        white-space: nowrap;
        text-decoration: none;
        color: #334155;
        font-weight: 600;
        padding: .75rem 1rem;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: .2s ease;
        flex-shrink: 0;
    }

    .nav-link-custom:hover {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }

    .nav-link-custom.active {
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.20);
    }

    .nav-link-custom .material-symbols-rounded {
        font-size: 20px;
    }

    .user-menu-btn {
        border: 1px solid rgba(15, 23, 42, 0.08);
        background: rgba(255, 255, 255, 0.96);
        border-radius: 18px;
        min-height: 56px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
    }

    .user-menu-btn:hover {
        background: #f8fafc;
    }

    .user-avatar-wrapper,
    .user-avatar-wrapper-lg {
        position: relative;
        flex-shrink: 0;
    }

    .user-avatar-wrapper {
        width: 46px;
        height: 46px;
    }

    .user-avatar-wrapper-lg {
        width: 52px;
        height: 52px;
    }

    .user-avatar,
    .user-avatar-lg {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-avatar-fallback {
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: #fff;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .user-avatar {
        font-size: .95rem;
    }

    .user-avatar-lg {
        font-size: 1rem;
    }

    .status-dot {
        position: absolute;
        right: 2px;
        bottom: 2px;
        width: 12px;
        height: 12px;
        background: #22c55e;
        border-radius: 50%;
        border: 2px solid #fff;
    }

    .user-name {
        max-width: 180px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .role-icon {
        font-size: 18px;
    }

    .user-dropdown {
        min-width: 290px;
        background: rgba(255, 255, 255, .98);
        backdrop-filter: blur(12px);
    }

    .dropdown-item .material-symbols-rounded {
        font-size: 20px;
    }

    @media (min-width: 992px) {
        .nav-mobile-scroll {
            justify-content: center;
            overflow: visible;
        }
    }

    @media (max-width: 767.98px) {
        .auth-actions-desktop {
            display: none !important;
        }

        .brand-title {
            font-size: 1rem;
        }

        .navbar-brand small {
            font-size: .78rem;
        }

        .user-menu-btn {
            min-height: 48px;
            border-radius: 14px;
        }

        .user-avatar-wrapper {
            width: 42px;
            height: 42px;
        }
    }
</style>