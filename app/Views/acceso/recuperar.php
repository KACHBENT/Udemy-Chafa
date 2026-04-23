<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>
<style>
  .recovery-bg {
    background:
      radial-gradient(circle at top center, rgba(245, 158, 11, .16), transparent 28%),
      radial-gradient(circle at bottom right, rgba(59, 130, 246, .14), transparent 30%),
      linear-gradient(135deg, #fffaf2 0%, #f6f9ff 100%);
  }

  .recovery-card {
    background: rgba(255,255,255,.93);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,.6);
  }

  .recovery-icon {
    width: 72px;
    height: 72px;
    border-radius: 999px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f59e0b, #f97316);
    box-shadow: 0 15px 35px rgba(249, 115, 22, 0.22);
  }

  .recovery-input {
    border-radius: 1rem;
    min-height: 54px;
    border: 1px solid #dbe3f0;
  }

  .recovery-input:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 .2rem rgba(245,158,11,.12) !important;
  }

  .recovery-btn {
    min-height: 54px;
    border-radius: 1rem;
    font-weight: 700;
    border: none;
    background: linear-gradient(135deg, #f59e0b, #f97316);
    box-shadow: 0 12px 28px rgba(249,115,22,.20);
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="recovery-bg min-vh-100 d-flex align-items-center py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-9 col-lg-5">
        <div class="recovery-card rounded-5 shadow-lg p-4 p-md-5">
          <div class="text-center mb-4">
            <div class="recovery-icon mx-auto mb-3">
              <span class="material-symbols-rounded text-white fs-2">mark_email_read</span>
            </div>
            <h2 class="fw-bold mb-1">Recuperar contraseña</h2>
            <p class="text-muted mb-0">
              Ingresa tu correo y te enviaremos un código de verificación.
            </p>
          </div>

          <form action="<?= base_url('acceso/enviar-recuperacion') ?>" method="post" autocomplete="on">
            <?= csrf_field() ?>

            <div class="mb-4">
              <label for="correo" class="form-label fw-semibold">Correo electrónico</label>
              <div class="position-relative">
                <input
                  type="email"
                  name="correo"
                  id="correo"
                  value="<?= old('correo') ?>"
                  class="form-control recovery-input ps-5"
                  placeholder="tu-correo@institucion.edu"
                  required>
                <span class="material-symbols-rounded position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">
                  mail
                </span>
              </div>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" class="btn recovery-btn text-white">
                Enviar código
              </button>
            </div>

            <div class="text-center">
              <a href="<?= base_url('acceso/login') ?>" class="text-decoration-none fw-semibold">
                Volver al inicio de sesión
              </a>
            </div>
          </form>

          <div class="mt-4 rounded-4 p-3 border bg-light">
            <div class="d-flex align-items-start gap-2">
              <span class="material-symbols-rounded text-warning">info</span>
              <div class="small text-muted">
                Revisa también la carpeta de spam o correo no deseado si no ves el mensaje.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->include('layout/Footer') ?>
<?= $this->endSection() ?>