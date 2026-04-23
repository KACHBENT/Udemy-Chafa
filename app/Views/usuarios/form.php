<?= $this->extend('layout/main') ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    $esEdicion = $modo === 'editar';

    $action = $esEdicion
        ? base_url('usuarios/actualizar/' . $usuario['id_usuario'])
        : base_url('usuarios/guardar');
?>

<section class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-lg rounded-5">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <h1 class="fw-bold mb-1"><?= $esEdicion ? 'Editar usuario' : 'Nuevo usuario' ?></h1>
                        <p class="text-muted mb-0">
                            <?= $esEdicion ? 'Actualiza los datos del usuario.' : 'Registra un nuevo usuario en la plataforma.' ?>
                        </p>
                    </div>

                    <form action="<?= $action ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input
                                    type="text"
                                    name="nombre"
                                    class="form-control rounded-4"
                                    value="<?= old('nombre', $persona['nombre'] ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Apellido paterno</label>
                                <input
                                    type="text"
                                    name="apellido_paterno"
                                    class="form-control rounded-4"
                                    value="<?= old('apellido_paterno', $persona['apellido_paterno'] ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Apellido materno</label>
                                <input
                                    type="text"
                                    name="apellido_materno"
                                    class="form-control rounded-4"
                                    value="<?= old('apellido_materno', $persona['apellido_materno'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre de usuario</label>
                            <input
                                type="text"
                                name="username"
                                class="form-control rounded-4"
                                value="<?= old('username', $usuario['username'] ?? '') ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Contraseña <?= $esEdicion ? '(solo si deseas cambiarla)' : '' ?>
                            </label>
                            <input
                                type="password"
                                name="password"
                                class="form-control rounded-4"
                                <?= $esEdicion ? '' : 'required' ?>>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Rol</label>
                                <select name="fk_rol" class="form-select rounded-4" required>
                                    <option value="">Selecciona un rol</option>
                                    <?php foreach ($roles as $rol): ?>
                                        <option value="<?= esc($rol['id_rol']) ?>"
                                            <?= old('fk_rol', $usuario['fk_rol'] ?? '') == $rol['id_rol'] ? 'selected' : '' ?>>
                                            <?= esc($rol['nombre_rol']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <div class="form-check form-switch fs-5">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="tema_oscuro"
                                        value="1"
                                        id="tema_oscuro"
                                        <?= old('tema_oscuro', $usuario['tema_oscuro'] ?? 0) ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold ms-2" for="tema_oscuro">
                                        Tema oscuro
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-3 pt-2">
                            <button type="submit" class="btn btn-primary rounded-4 px-4">
                                <?= $esEdicion ? 'Actualizar usuario' : 'Guardar usuario' ?>
                            </button>

                            <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-secondary rounded-4 px-4">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->include('layout/Footer') ?>
<?= $this->endSection() ?>