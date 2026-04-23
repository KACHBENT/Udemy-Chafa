<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>
<style>
  .register-page {
    padding-top: 2rem;
    padding-bottom: 3rem;
    background:
      radial-gradient(circle at top right, rgba(34, 197, 94, .14), transparent 30%),
      radial-gradient(circle at bottom left, rgba(59, 130, 246, .16), transparent 35%),
      linear-gradient(135deg, #f7fbff 0%, #eef8f1 45%, #f6f3ff 100%);
  }

  .register-wrapper {
    max-width: 1320px;
    margin-inline: auto;
  }

  .register-card {
    background: rgba(255,255,255,.95);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,.6);
    border-radius: 2rem;
    overflow: hidden;
    box-shadow: 0 24px 55px rgba(15, 23, 42, 0.12);
  }

  .register-side {
    background: linear-gradient(160deg, #0f172a 0%, #1d4ed8 52%, #7c3aed 100%);
    min-height: 100%;
    position: relative;
    overflow: hidden;
  }

  .register-side::before,
  .register-side::after {
    content: "";
    position: absolute;
    border-radius: 999px;
    background: rgba(255,255,255,.08);
  }

  .register-side::before {
    width: 210px;
    height: 210px;
    top: -50px;
    right: -70px;
  }

  .register-side::after {
    width: 150px;
    height: 150px;
    bottom: -50px;
    left: -40px;
  }

  .register-side-content {
    position: relative;
    z-index: 1;
  }

  .register-panel {
    padding: 2rem 2rem 2.25rem 2rem;
  }

  .register-input {
    border-radius: 1rem;
    min-height: 56px;
    border: 1px solid #dbe3f0;
    box-shadow: none !important;
    transition: .2s ease;
  }

  .register-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 .2rem rgba(37,99,235,.12) !important;
  }

  .benefit-card {
    border: 1px solid rgba(255,255,255,.18);
    background: rgba(255,255,255,.10);
    border-radius: 1.25rem;
    backdrop-filter: blur(8px);
  }

  .register-btn {
    min-height: 58px;
    border-radius: 1rem;
    font-weight: 800;
    border: none;
    background: linear-gradient(135deg, #16a34a, #2563eb);
    box-shadow: 0 16px 30px rgba(37,99,235,.20);
  }

  .register-btn:hover {
    transform: translateY(-1px);
    filter: brightness(.98);
  }

  .toggle-password-btn {
    border: none;
    background: transparent;
    position: absolute;
    top: 50%;
    right: 14px;
    transform: translateY(-50%);
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
  }

  .register-title {
    font-size: clamp(2rem, 3vw, 2.8rem);
    line-height: 1.05;
  }

  .register-form-title {
    font-size: clamp(1.8rem, 2.5vw, 2.5rem);
  }

  .register-footer-note {
    font-size: .95rem;
    color: #64748b;
  }

  @media (min-width: 992px) {
    .register-card > .row {
      min-height: 760px;
    }

    .register-panel {
      padding: 2.5rem 3rem;
    }
  }

  @media (max-width: 991.98px) {
    .register-page {
      min-height: auto;
      padding-top: 1.25rem;
      padding-bottom: 2rem;
    }

    .register-card {
      border-radius: 1.5rem;
    }

    .register-panel {
      padding: 1.5rem;
    }
  }

  @media (max-width: 767.98px) {
    .register-input {
      min-height: 52px;
    }

    .register-btn {
      min-height: 54px;
    }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="register-page">
  <div class="container register-wrapper">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="register-card">
          <div class="row g-0 align-items-stretch">
            
            <div class="col-lg-5 d-none d-lg-flex">
              <div class="register-side w-100 d-flex flex-column justify-content-center p-5 text-white">
                <div class="register-side-content">
                  <span class="badge rounded-pill bg-light text-primary px-3 py-2 mb-3 w-auto">
                    Nuevo alumno
                  </span>

                  <h2 class="fw-bold mb-3 register-title">
                    Regístrate y accede a tu formación certificada
                  </h2>

                  <p class="opacity-75 mb-4 fs-5">
                    Crea tu cuenta para ingresar a cursos, visualizar tu avance académico
                    y recibir certificados digitales al finalizar tus programas.
                  </p>

                  <div class="d-flex flex-column gap-3">
                    <div class="benefit-card p-4">
                      <div class="d-flex align-items-start gap-3">
                        <span class="material-symbols-rounded">menu_book</span>
                        <div>
                          <div class="fw-semibold fs-5">Acceso a cursos</div>
                          <div class="small opacity-75">Contenido disponible en línea y a tu ritmo.</div>
                        </div>
                      </div>
                    </div>

                    <div class="benefit-card p-4">
                      <div class="d-flex align-items-start gap-3">
                        <span class="material-symbols-rounded">analytics</span>
                        <div>
                          <div class="fw-semibold fs-5">Seguimiento académico</div>
                          <div class="small opacity-75">Consulta tu progreso en cada módulo.</div>
                        </div>
                      </div>
                    </div>

                    <div class="benefit-card p-4">
                      <div class="d-flex align-items-start gap-3">
                        <span class="material-symbols-rounded">workspace_premium</span>
                        <div>
                          <div class="fw-semibold fs-5">Certificados digitales</div>
                          <div class="small opacity-75">Obtén tus constancias al completar tus cursos.</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-lg-7">
              <div class="register-panel h-100 d-flex flex-column justify-content-center">
                <div class="mb-4">
                  <div class="d-flex align-items-center gap-2 mb-2 text-primary">
                    <span class="material-symbols-rounded">person_add</span>
                    <span class="fw-semibold">Registro académico</span>
                  </div>
                  <h1 class="fw-bold mb-2 register-form-title">Crear cuenta</h1>
                  <p class="text-muted mb-0">
                    Completa tus datos para registrarte en la plataforma educativa.
                  </p>
                </div>

                <form action="<?= base_url('acceso/guardar-registro') ?>" method="post" autocomplete="on">
                  <?= csrf_field() ?>

                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label for="nombre" class="form-label fw-semibold">Nombre</label>
                      <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        value="<?= old('nombre') ?>"
                        class="form-control register-input"
                        placeholder="Nombre"
                        required>
                    </div>

                    <div class="col-md-4 mb-3">
                      <label for="apellido_paterno" class="form-label fw-semibold">Apellido paterno</label>
                      <input
                        type="text"
                        name="apellido_paterno"
                        id="apellido_paterno"
                        value="<?= old('apellido_paterno') ?>"
                        class="form-control register-input"
                        placeholder="Apellido paterno"
                        required>
                    </div>

                    <div class="col-md-4 mb-3">
                      <label for="apellido_materno" class="form-label fw-semibold">Apellido materno</label>
                      <input
                        type="text"
                        name="apellido_materno"
                        id="apellido_materno"
                        value="<?= old('apellido_materno') ?>"
                        class="form-control register-input"
                        placeholder="Apellido materno">
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="correo" class="form-label fw-semibold">Correo electrónico</label>
                    <div class="position-relative">
                      <input
                        type="email"
                        name="correo"
                        id="correo"
                        value="<?= old('correo') ?>"
                        class="form-control register-input ps-5"
                        placeholder="nombre@institucion.edu"
                        required>
                      <span class="material-symbols-rounded position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">
                        mail
                      </span>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Nombre de usuario</label>
                    <div class="position-relative">
                      <input
                        type="text"
                        name="username"
                        id="username"
                        value="<?= old('username') ?>"
                        class="form-control register-input ps-5"
                        placeholder="Usuario para ingresar al sistema"
                        required>
                      <span class="material-symbols-rounded position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">
                        account_circle
                      </span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="password" class="form-label fw-semibold">Contraseña</label>
                      <div class="position-relative">
                        <input
                          type="password"
                          name="password"
                          id="password"
                          class="form-control register-input ps-5 pe-5"
                          placeholder="Mínimo 6 caracteres"
                          required>
                        <span class="material-symbols-rounded position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">
                          lock
                        </span>
                        <button type="button" class="toggle-password-btn" data-toggle-password="password">
                          <span class="material-symbols-rounded">visibility</span>
                        </button>
                      </div>
                    </div>

                    <div class="col-md-6 mb-4">
                      <label for="password_confirm" class="form-label fw-semibold">Confirmar contraseña</label>
                      <div class="position-relative">
                        <input
                          type="password"
                          name="password_confirm"
                          id="password_confirm"
                          class="form-control register-input ps-5 pe-5"
                          placeholder="Repite tu contraseña"
                          required>
                        <span class="material-symbols-rounded position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">
                          verified_user
                        </span>
                        <button type="button" class="toggle-password-btn" data-toggle-password="password_confirm">
                          <span class="material-symbols-rounded">visibility</span>
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="d-grid mb-3">
                    <button type="submit" class="btn register-btn text-white">
                      Crear cuenta
                    </button>
                  </div>

                  <div class="text-center register-footer-note">
                    <span class="text-muted">¿Ya tienes cuenta?</span>
                    <a href="<?= base_url('acceso/login') ?>" class="text-decoration-none fw-bold ms-1">
                      Iniciar sesión
                    </a>
                  </div>
                </form>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  document.querySelectorAll('[data-toggle-password]').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.getElementById(btn.getAttribute('data-toggle-password'));
      const icon = btn.querySelector('.material-symbols-rounded');
      if (!input) return;

      if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
      } else {
        input.type = 'password';
        icon.textContent = 'visibility';
      }
    });
  });
</script>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->include('layout/Footer') ?>
<?= $this->endSection() ?>