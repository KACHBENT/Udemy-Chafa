<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('css/table.styles.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="container py-4">
    <div class="mb-4">
        <h1 class="fw-bold mb-1">Gestión de usuarios</h1>
        <p class="text-muted mb-0">Administra los usuarios registrados en la plataforma.</p>
    </div>

    <?php
        echo view('components/table', [
            'title'               => 'Listado de usuarios',
            'buttons'             => $buttons,
            'sectionId'           => 'usuariosTable',
            'defaultSize'         => 10,
            'pageSizes'           => [5, 10, 20, 50],
            'columns'             => [
                'id_usuario'      => 'ID',
                'nombre_completo' => 'Nombre completo',
                'username'        => 'Usuario',
                'rol'             => 'Rol',
                'tema_oscuro'     => 'Tema oscuro',
                'acciones'        => 'Acciones',
            ],
            'rows'                => $rows,
            'rawColumns'          => ['acciones'],
            'cardTitleField'      => 'nombre_completo',
            'cardSubtitleField'   => 'username',
            'cardBadgeField'      => 'rol',
        ]);
    ?>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('javascript/table.script.js') ?>"></script>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->include('layout/Footer') ?>
<?= $this->endSection() ?>