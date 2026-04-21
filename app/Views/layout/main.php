<!--Main-->

<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= esc($title ?? 'Udemy-Chafa') ?></title>

  <link rel="stylesheet" href="<?= base_url('bootstrap-5.3.3/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('css/index.styles.css') ?>">
  <link rel="stylesheet" href="<?= base_url('css/footer.styles.css') ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,700,1,0" />
   <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
  <?= $this->renderSection('css') ?>

  <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
</head>


<body class="fondo">

  <?= $this->renderSection('navbar') ?>

  <!-- Contenedor de toasts -->

  <div id="toastMount">
    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3"></div>
  </div>

  <?= $this->renderSection('content') ?>
  <?= $this->renderSection('footer') ?>


  <script src="<?= base_url('bootstrap-5.3.3/js/bootstrap.bundle.min.js') ?>"></script>
  <script>
    (function () {
      const IMG_SUCCESS = "<?= base_url('images/perrito-feliz.webp') ?>";
      const IMG_ERROR = "<?= base_url('images/perrito-triste.webp') ?>";

      function escapeHtml(s) {
        return String(s)
          .replaceAll("&", "&amp;")
          .replaceAll("<", "&lt;")
          .replaceAll(">", "&gt;")
          .replaceAll('"', "&quot;")
          .replaceAll("'", "&#039;");
      }

      function formatMessage(message) {
        if (Array.isArray(message)) {
          return `<ul class="mb-0 ps-3">${message.map(m => `<li>${escapeHtml(m)}</li>`).join('')}</ul>`;
        }
        if (typeof message === 'string') {
          return escapeHtml(message).replace(/\n/g, '<br>');
        }
        return escapeHtml(message);
      }

      function makeToast(message, bsType, opts = {}) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const delay = Number(opts.delay ?? 7000);
        const title = opts.title ?? (bsType === 'success' ? 'Éxito' : 'Error');
        const img = (bsType === 'success') ? IMG_SUCCESS : IMG_ERROR;

        const el = document.createElement('div');
        el.className = `toast toast-glass align-items-stretch text-bg-${bsType} border-0 shadow-lg mb-2`;
        el.setAttribute('role', 'alert');
        el.setAttribute('aria-live', 'assertive');
        el.setAttribute('aria-atomic', 'true');

        el.innerHTML = `
      <div class="d-flex gap-2 p-3">
        <div class="toast-icon flex-shrink-0">
          <img src="${img}" alt="${bsType}" loading="lazy">
        </div>

        <div class="flex-grow-1">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <strong class="me-2">${escapeHtml(title)}</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Cerrar"></button>
          </div>
          <div class="toast-body p-0">${formatMessage(message)}</div>
        </div>
      </div>

      <!-- barra que dura EXACTAMENTE lo mismo que el toast -->
      <div class="toast-progress bg-light" style="animation-duration:${delay}ms"></div>
    `;
        container.appendChild(el);
        const inst = bootstrap.Toast.getOrCreateInstance(el, {
          autohide: false,
          animation: true
        });

        inst.show();
        const progress = el.querySelector('.toast-progress');
        if (progress) {
          progress.addEventListener('animationend', () => inst.hide(), { once: true });
        } else {
          setTimeout(() => inst.hide(), delay);
        }

        el.addEventListener('hidden.bs.toast', () => el.remove());
      }

      window.showToastSuccess = (m, opts) => makeToast(m, 'success', opts);
      window.showToastError = (m, opts) => makeToast(m, 'danger', opts);
    })();
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script src="https://unpkg.com/@ericblade/quagga2/dist/quagga.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
  <?= $this->renderSection('scripts') ?>



  <?php if ($ok = session()->getFlashdata('toast_success')): ?>
    <script>
      showToastSuccess(<?= json_encode($ok, JSON_UNESCAPED_UNICODE) ?>);
    </script>
  <?php endif; ?>

  <?php if ($err = session()->getFlashdata('toast_error')): ?>
    <script>
      showToastError(<?= json_encode($err, JSON_UNESCAPED_UNICODE) ?>);
    </script>
  <?php endif; ?>

</body>

</html>