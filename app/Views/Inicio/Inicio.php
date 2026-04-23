<?= $this->extend('layout/main') ?>

<?= $this->section('css') ?>
<style>
    .home-page {
        background:
            radial-gradient(circle at top right, rgba(37, 99, 235, .10), transparent 25%),
            radial-gradient(circle at bottom left, rgba(124, 58, 237, .10), transparent 28%),
            linear-gradient(135deg, #f8fbff 0%, #f3f7ff 45%, #f8f5ff 100%);
    }

    .hero-home {
        border-radius: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 50%, #7c3aed 100%);
        color: white;
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(15, 23, 42, 0.16);
    }

    .hero-home::before,
    .hero-home::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        background: rgba(255,255,255,.08);
    }

    .hero-home::before {
        width: 220px;
        height: 220px;
        top: -60px;
        right: -80px;
    }

    .hero-home::after {
        width: 140px;
        height: 140px;
        bottom: -40px;
        left: -30px;
    }

    .hero-home-content {
        position: relative;
        z-index: 1;
    }

    .hero-chip {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .65rem 1rem;
        border-radius: 999px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.18);
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .hero-title {
        font-size: clamp(2rem, 4vw, 3.4rem);
        font-weight: 800;
        line-height: 1.05;
        margin-bottom: 1rem;
    }

    .hero-text {
        font-size: 1.05rem;
        color: rgba(255,255,255,.85);
        max-width: 760px;
    }

    .section-title {
        font-weight: 800;
        color: #0f172a;
        margin-bottom: .25rem;
    }

    .section-subtitle {
        color: #64748b;
        margin-bottom: 0;
    }

    .course-card {
        background: rgba(255,255,255,.96);
        border: 1px solid rgba(15,23,42,.06);
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.08);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .course-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 45px rgba(15, 23, 42, 0.12);
    }

    .course-card-image-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
        background: #e2e8f0;
    }

    .course-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .course-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        background: rgba(255,255,255,.92);
        color: #1d4ed8;
        border-radius: 999px;
        padding: .45rem .8rem;
        font-size: .85rem;
        font-weight: 700;
        box-shadow: 0 10px 20px rgba(15,23,42,.10);
    }

    .course-badge .material-symbols-rounded {
        font-size: 18px;
    }

    .course-card-body {
        padding: 1.2rem;
    }

    .course-card-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: .55rem;
        line-height: 1.2;
    }

    .course-card-text {
        color: #64748b;
        font-size: .95rem;
        line-height: 1.55;
        min-height: 70px;
        margin-bottom: 0;
    }

    .course-card-meta {
        display: flex;
        flex-direction: column;
        gap: .55rem;
        padding-top: .75rem;
    }

    .course-meta-item {
        display: flex;
        align-items: center;
        gap: .55rem;
        color: #475569;
        font-size: .92rem;
    }

    .course-meta-item .material-symbols-rounded {
        font-size: 18px;
        color: #2563eb;
    }

    .course-card-btn {
        min-height: 46px;
        border-radius: 1rem;
        font-weight: 700;
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: #fff;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 12px 24px rgba(37, 99, 235, 0.18);
    }

    .course-card-btn:hover {
        color: #fff;
        filter: brightness(.98);
    }

    .empty-courses {
        background: rgba(255,255,255,.96);
        border: 1px dashed #cbd5e1;
        border-radius: 1.5rem;
        padding: 2rem;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 767.98px) {
        .hero-home {
            padding: 2rem 1.25rem;
            border-radius: 1.5rem;
        }

        .course-card-image-wrapper {
            height: 200px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('navbar') ?>
<?= $this->include('layout/NavBar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="home-page py-4 py-md-5">
    <div class="container">
        <div class="hero-home mb-5">
            <div class="hero-home-content">
                <div class="hero-chip">
                    <span class="material-symbols-rounded">school</span>
                    <span>Plataforma educativa con certificados</span>
                </div>

                <h1 class="hero-title">
                    Aprende a tu ritmo y obtén tus certificados en línea
                </h1>

                <p class="hero-text mb-0">
                    Explora nuestros cursos disponibles, sigue tu progreso y fortalece tu formación
                    académica desde un solo lugar.
                </p>
            </div>
        </div>

        <div class="mb-4">
            <h2 class="section-title">Cursos disponibles</h2>
            <p class="section-subtitle">Selecciona un curso y comienza tu formación.</p>
        </div>

        <div class="row g-4">
            <?php if (!empty($cursos)): ?>
                <?php foreach ($cursos as $curso): ?>
                    <?= view('components/card_curso', ['curso' => $curso]) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-courses">
                        <span class="material-symbols-rounded d-block mb-2" style="font-size: 42px;">menu_book</span>
                        <h4 class="fw-bold mb-2">Aún no hay cursos disponibles</h4>
                        <p class="mb-0">Cuando existan cursos activos aparecerán aquí.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<?= $this->include('layout/Footer') ?>
<?= $this->endSection() ?>