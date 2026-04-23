<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>
<style>
  .reset-bg {
    background:
      radial-gradient(circle at top left, rgba(16, 185, 129, .16), transparent 30%),
      radial-gradient(circle at bottom right, rgba(99, 102, 241, .16), transparent 30%),
      linear-gradient(135deg, #effdf6 0%, #f5f7ff 100%);
  }

  .reset-card {
    background: rgba(255,255,255,.93);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,.6);
  }

  .reset-icon {
    width: 72px;
    height: 72px;
    border-radius: 999px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #10b981, #2563eb);
    box-shadow: 0 15px 35px rgba(37,99,235,.18);
  }

  .reset-input {
    border-radius: 1rem;
    min-height: 54px;
    border: 1px solid #dbe3f0;
  }

  .reset-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 .2rem rgba(37,99,235,.12) !important;
  }

  .reset-btn {
    min-height: 54px;
    border-radius: 1rem;
    font-weight: 700;
    border: none;
    background: linear-gradient(135deg, #10b981, #2563eb);
    box-shadow: 0 12px 28px rgba(37,99,235,.20);
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
</style>
<?= $this->endSection() ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="reset-bg d-flex align-items-center py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-9 col-lg-5">
        <div class="reset-card rounded-5 shadow-lg p-4 p-md-5">
          <div class="text-center mb-4">
            <div class="reset-icon mx-auto mb-3">
              <span class="material-symbols-rounded text-white fs-2">shield_lock</span>
            </div>
            <h2 class="fw-bold mb-1">Restablecer contraseña</h2>
            <p class="text-muted mb-0">
              Introduce el código recibido y define una nueva contraseña.
            </p>
          </div>

          <form action="<?= base_url('acceso/actualizar-password') ?>" method="post" autocomplete="off">
            <?= csrf_field() ?>

            <div class="mb-3">
              <label for="codigo" class="form-label fw-semibold">Código de verificación</label>
              <div class="position-relative">
                <input
                  type="text"
                  name="codigo"
                  id="codigo"
                  value="<?= old('codigo') ?>"
                  maxlength="6"
                  class="form-control reset-input text-center fw-bold ps-5"
                  placeholder="123456"
                  required>
                <span class="material-symbols-rounded position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">
                  password
                </span>
              </div>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Nueva contraseña</label>
              <div class="position-relative">
                <input
                  type="password"
                  name="password"
                  id="password"
                  class="form-control reset-input ps-5 pe-5"
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

            <div class="mb-4">
              <label for="password_confirm" class="form-label fw-semibold">Confirmar nueva contraseña</label>
              <div class="position-relative">
                <input
                  type="password"
                  name="password_confirm"
                  id="password_confirm"
                  class="form-control reset-input ps-5 pe-5"
                  placeholder="Repite la nueva contraseña"
                  required>
                <span class="material-symbols-rounded position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">
                  verified_user
                </span>
                <button type="button" class="toggle-password-btn" data-toggle-password="password_confirm">
                  <span class="material-symbols-rounded">visibility</span>
                </button>
              </div>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn reset-btn text-white">
                Guardar nueva contraseña
              </button>
            </div>

            <div class="text-center">
              <a href="<?= base_url('acceso/recuperar') ?>" class="text-decoration-none fw-semibold">
                Volver a recuperación
              </a>
            </div>
          </form>

          <div class="mt-4 rounded-4 p-3 border bg-light">
            <div class="d-flex align-items-start gap-2">
              <span class="material-symbols-rounded text-primary">schedule</span>
              <div class="small text-muted">
                El código tiene una vigencia limitada por seguridad.
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