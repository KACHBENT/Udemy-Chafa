<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>
<style>
  .auth-hero-bg {
    background:
      radial-gradient(circle at top left, rgba(37, 99, 235, .18), transparent 28%),
      radial-gradient(circle at bottom right, rgba(124, 58, 237, .18), transparent 30%),
      linear-gradient(135deg, #f8fbff 0%, #eef4ff 45%, #f7f3ff 100%);
  }

  .auth-glass-card {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.6);
  }

  .auth-icon-circle {
    width: 72px;
    height: 72px;
    border-radius: 999px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    box-shadow: 0 15px 35px rgba(37, 99, 235, 0.25);
  }

  .auth-feature-card {
    border-radius: 1.25rem;
    border: 1px solid rgba(0,0,0,.06);
    background: rgba(255,255,255,.88);
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
  }

  .auth-input {
    border-radius: 1rem;
    min-height: 54px;
    border: 1px solid #dbe3f0;
    box-shadow: none !important;
    transition: .2s ease;
  }

  .auth-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .12) !important;
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
  }

  .brand-chip {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .65rem 1rem;
    border-radius: 999px;
    background: rgba(37,99,235,.10);
    color: #1d4ed8;
    font-weight: 700;
    border: 1px solid rgba(37,99,235,.12);
  }

  .auth-btn-primary {
    min-height: 54px;
    border-radius: 1rem;
    font-weight: 700;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    border: none;
    box-shadow: 0 12px 30px rgba(37, 99, 235, .22);
  }

  .auth-btn-primary:hover {
    filter: brightness(.98);
    transform: translateY(-1px);
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="auth-hero-bg min-vh-100 d-flex align-items-center py-5 overflow-hidden position-relative">
  <div class="container position-relative">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 d-none d-lg-block">
        <div class="pe-lg-5">
          <div class="brand-chip mb-4">
            <span class="material-symbols-rounded">workspace_premium</span>
            <span>Campus virtual con certificados</span>
          </div>

          <h1 class="display-4 fw-bold text-dark mb-3">
            Aprende en línea y obtén tus
            <span class="text-primary">certificados digitales</span>
          </h1>

          <p class="lead text-secondary mb-4">
            Accede a tus cursos, revisa tu avance académico, completa tus módulos y descarga
            tus constancias desde una sola plataforma.
          </p>

          <div class="row g-3">
            <div class="col-md-6">
              <div class="auth-feature-card p-4 h-100">
                <div class="mb-3 text-primary">
                  <span class="material-symbols-rounded fs-1">school</span>
                </div>
                <h5 class="fw-bold mb-2">Formación flexible</h5>
                <p class="text-muted mb-0">
                  Estudia desde cualquier lugar con acceso continuo a tus contenidos.
                </p>
              </div>
            </div>

            <div class="col-md-6">
              <div class="auth-feature-card p-4 h-100">
                <div class="mb-3 text-success">
                  <span class="material-symbols-rounded fs-1">military_tech</span>
                </div>
                <h5 class="fw-bold mb-2">Certificados en línea</h5>
                <p class="text-muted mb-0">
                  Obtén reconocimiento digital al finalizar tus cursos y evaluaciones.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="auth-glass-card rounded-5 shadow-lg p-4 p-md-5 mx-auto" style="max-width: 520px;">
          <div class="text-center mb-4">
            <div class="auth-icon-circle mx-auto mb-3">
              <span class="material-symbols-rounded text-white fs-2">login</span>
            </div>
            <h2 class="fw-bold mb-1">Iniciar sesión</h2>
            <p class="text-muted mb-0">Ingresa a tu aula virtual</p>
          </div>

          <form action="<?= base_url('acceso/autenticar') ?>" method="post" autocomplete="on">
            <?= csrf_field() ?>

            <div class="mb-3">
              <label for="username" class="form-label fw-semibold">Usuario</label>
              <div class="position-relative">
                <input
                  type="text"
                  name="username"
                  id="username"
                  value="<?= old('username') ?>"
                  class="form-control auth-input ps-5"
                  placeholder="Tu usuario institucional"
                  required>
                <span class="material-symbols-rounded position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">
                  person
                </span>
              </div>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Contraseña</label>
              <div class="position-relative">
                <input
                  type="password"
                  name="password"
                  id="password"
                  class="form-control auth-input ps-5 pe-5"
                  placeholder="••••••••"
                  required>
                <span class="material-symbols-rounded position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">
                  lock
                </span>

                <button type="button" class="toggle-password-btn" data-toggle-password="password" aria-label="Mostrar contraseña">
                  <span class="material-symbols-rounded">visibility</span>
                </button>
              </div>
            </div>

            <div class="d-flex justify-content-end mb-4">
              <a href="<?= base_url('acceso/recuperar') ?>" class="text-decoration-none fw-semibold">
                ¿Olvidaste tu contraseña?
              </a>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn auth-btn-primary text-white">
                Entrar al campus
              </button>
            </div>

            <div class="text-center">
              <span class="text-muted">¿No tienes cuenta?</span>
              <a href="<?= base_url('acceso/registro') ?>" class="fw-bold text-decoration-none ms-1">
                Crear cuenta
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