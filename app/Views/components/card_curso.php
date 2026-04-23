<?php
    $curso = $curso ?? [];

    $idCurso         = $curso['id_curso'] ?? '';
    $nombreCurso     = $curso['nombre_curso'] ?? 'Curso sin nombre';
    $descripcion     = $curso['descripcion'] ?? 'Sin descripción disponible.';
    $categoria       = $curso['nombre_categoria'] ?? 'General';
    $imagenFondo     = $curso['imagen_fondo'] ?? null;
    $especialidad    = $curso['especialidad'] ?? '';
    $docenteNombre   = trim(
        ($curso['nombre'] ?? '') . ' ' .
        ($curso['apellido_paterno'] ?? '') . ' ' .
        ($curso['apellido_materno'] ?? '')
    );

    $imagenCurso = !empty($imagenFondo)
        ? base_url(ltrim($imagenFondo, '/'))
        : base_url('images/default-course.jpg');

    $descripcionCorta = mb_strlen(strip_tags($descripcion)) > 120
        ? mb_substr(strip_tags($descripcion), 0, 120) . '...'
        : strip_tags($descripcion);
?>

<div class="col-12 col-sm-6 col-lg-4 col-xl-3 d-flex">
    <div class="course-card w-100">
        <div class="course-card-image-wrapper">
            <img src="<?= esc($imagenCurso) ?>" alt="<?= esc($nombreCurso) ?>" class="course-card-image">

            <span class="course-badge">
                <span class="material-symbols-rounded">workspace_premium</span>
                <?= esc($categoria) ?>
            </span>
        </div>

        <div class="course-card-body d-flex flex-column">
            <div class="mb-2">
                <h3 class="course-card-title"><?= esc($nombreCurso) ?></h3>
                <p class="course-card-text"><?= esc($descripcionCorta) ?></p>
            </div>

            <div class="course-card-meta mt-auto">
                <div class="course-meta-item">
                    <span class="material-symbols-rounded">person</span>
                    <span><?= esc($docenteNombre ?: 'Docente no asignado') ?></span>
                </div>

                <?php if (!empty($especialidad)): ?>
                    <div class="course-meta-item">
                        <span class="material-symbols-rounded">school</span>
                        <span><?= esc($especialidad) ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-3">
                <a href="<?= base_url('curso/' . $idCurso) ?>" class="btn course-card-btn w-100">
                    Ver curso
                </a>
            </div>
        </div>
    </div>
</div>