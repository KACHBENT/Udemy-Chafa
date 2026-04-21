<?= $this->extend('layout/main') ?> <!-- ➊ heredamos layout -->

<?= $this->section('css') ?><!-- ➋ CSS sólo de esta página -->
<link rel="stylesheet" href="<?= base_url('css/inicio.styles.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?> <!-- ➌ Contenedor de HTML principal -->
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-2xl shadow p-6">
        <h1 class="text-3xl font-bold text-blue-600">Hola desde CodeIgniter 4 + Tailwind</h1>
        <p class="mt-4 text-gray-600">
            Ya tienes Tailwind funcionando dentro de tu proyecto.
        </p>

        <button class="mt-6 px-4 py-2 rounded-xl bg-black text-white hover:bg-gray-800 transition">
            Botón de prueba
        </button>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?> <!-- ➍ scripts extra (opcional) -->

<?= $this->endSection() ?>
<?= $this->section('footer') ?>
<?= $this->include('layout/Footer') ?>
<?= $this->endSection() ?>