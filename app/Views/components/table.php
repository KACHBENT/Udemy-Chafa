<?php
/**
 * Componente table (Tabla + Cards con Collapse)
 *
 * Parámetros:
 * - $title         : string (opcional) título del listado
 * - $buttons       : array (opcional) botones HTML
 * - $columns       : ['campo' => 'Etiqueta']
 * - $rows          : array de registros
 * - $sectionId     : string (opcional) id único del componente
 * - $pageSizes     : array (opcional) tamaños de página [5,10,20,...]
 * - $defaultSize   : int   (opcional) tamaño de página inicial
 * - $rawColumns    : array (opcional) campos que se imprimen como HTML sin esc()
 *
 * Cards (móvil):
 * - $cardTitleField      : string (opcional) campo que será título principal
 * - $cardSubtitleField   : string (opcional) campo que será subtítulo
 * - $cardBadgeField      : string (opcional) campo que será badge a la derecha
 * - $cardSubtitleAsHtml  : bool (opcional) permite HTML en subtítulo (si está en rawColumns)
 * - $cardBadgeAsHtml     : bool (opcional) permite HTML en badge (si está en rawColumns)
 */

$title = $title ?? 'Listado';
$buttons = $buttons ?? [];
$columns = $columns ?? [];
$rows = $rows ?? [];
$sectionId = $sectionId ?? uniqid('ds_');

$pageSizes = $pageSizes ?? [5, 10, 20, 50];
$defaultSize = $defaultSize ?? 10;

$rawColumns = $rawColumns ?? [];

$colKeys = array_keys($columns);

// Campos del header en cards
$cardTitleField = $cardTitleField ?? ($colKeys[0] ?? null);
$cardSubtitleField = $cardSubtitleField ?? null;
$cardBadgeField = $cardBadgeField ?? null;

// Permitir HTML en header (solo si tú lo decides)
$cardSubtitleAsHtml = $cardSubtitleAsHtml ?? false;
$cardBadgeAsHtml = $cardBadgeAsHtml ?? false;

/**
 * Construye texto de búsqueda por fila
 */
$buildSearchText = function (array $row) use ($columns, $rawColumns) {
    $parts = [];
    foreach ($columns as $field => $label) {
        $val = $row[$field] ?? '';
        if (in_array($field, $rawColumns, true)) {
            $val = strip_tags((string) $val);
        }
        $parts[] = (string) $val;
    }
    return mb_strtolower(trim(implode(' ', $parts)));
};

/**
 * Convierte a texto plano seguro
 */
$plain = function ($value) {
    return trim(strip_tags((string) $value));
};
?>

<div class="card-body shadow-sm data-section" id="<?= esc($sectionId) ?>"
    data-default-pagesize="<?= esc($defaultSize) ?>">

    <!-- Toolbar: buscador + botones -->
    <div class="d-flex gap-2 align-items-md-center mb-2 mt-2 flex-wrap">

        <!-- Buscador global -->
        <div class="input-group input-group-sm flex-grow-1" style="min-width:240px;">
            <span class="input-group-text">
                <img src="<?= base_url('images/icons/search.svg') ?>" alt="Buscar" class="content-image"
                    loading="lazy" />
            </span>
            <input type="text" class="form-control data-search-input" placeholder="Buscar en la tabla..."
                aria-label="Buscar en la tabla">
        </div>

        <!-- Botones -->
        <div class="d-flex gap-2 ms-md-auto justify-content-end flex-wrap">
            <?php foreach ($buttons as $btn): ?>
                <?= $btn ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container p-0">

        <!-- =========================
             TABLA
             ========================= -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-striped table-hover align-middle mb-0 data-table">
                <thead class="table-light">
                    <tr>
                        <?php foreach ($columns as $field => $label): ?>
                            <th><?= esc($label) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php foreach ($rows as $i => $row): ?>
                            <?php $searchText = $buildSearchText($row); ?>
                            <tr class="data-row" data-search="<?= esc($searchText) ?>">
                                <?php foreach ($columns as $field => $label): ?>
                                    <td>
                                        <?php if (in_array($field, $rawColumns, true)): ?>
                                            <?= $row[$field] ?? '' ?>
                                        <?php else: ?>
                                            <?= esc($row[$field] ?? '') ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr class="data-empty-row">
                            <td colspan="<?= count($columns) ?>" class="text-center text-muted py-4">
                                Sin registros
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- =========================
             CARDS (móvil) con collapse
             ========================= -->
        <div class="data-cards d-md-none">
            <?php if (!empty($rows)): ?>
                <div class="accordion" id="<?= esc($sectionId) ?>_accordion">
                    <?php foreach ($rows as $i => $row): ?>
                        <?php
                        $searchText = $buildSearchText($row);

                        $itemId = $sectionId . '_item_' . $i;
                        $collapseId = $sectionId . '_collapse_' . $i;

                        // Título
                        $titleVal = $cardTitleField ? ($row[$cardTitleField] ?? '') : ('Registro #' . ($i + 1));
                        $titleText = $plain($titleVal);

                        // Subtítulo
                        $subtitleVal = $cardSubtitleField ? ($row[$cardSubtitleField] ?? '') : '';
                        $subtitleText = $plain($subtitleVal);

                        // Badge
                        $badgeVal = $cardBadgeField ? ($row[$cardBadgeField] ?? '') : '';
                        $badgeText = $plain($badgeVal);
                        ?>

                        <div class="accordion-item data-card-item" data-search="<?= esc($searchText) ?>">
                            <h2 class="accordion-header" id="<?= esc($itemId) ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#<?= esc($collapseId) ?>" aria-expanded="false"
                                    aria-controls="<?= esc($collapseId) ?>">

                                    <span class="flex-grow-1 d-flex flex-column text-start gap-2">
                                        <span class="fw-semibold">
                                            <?= esc($titleText !== '' ? $titleText : ('Registro #' . ($i + 1))) ?>
                                        </span>

                                        <?php if (!empty($cardSubtitleField)): ?>
                                            <?php if ($cardSubtitleAsHtml && in_array($cardSubtitleField, $rawColumns, true)): ?>
                                                <small class="text-muted"><?= $subtitleVal ?? '' ?></small>
                                            <?php elseif ($subtitleText !== ''): ?>
                                                <small class="text-muted"><?= esc($subtitleText) ?></small>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </span>

                                    <?php if (!empty($cardBadgeField)): ?>
                                        <?php if ($cardBadgeAsHtml && in_array($cardBadgeField, $rawColumns, true)): ?>
                                            <span class="me-2"><?= $badgeVal ?? '' ?></span>
                                        <?php elseif ($badgeText !== ''): ?>
                                            <span class="badge bg-secondary me-2"><?= esc($badgeText) ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                </button>
                            </h2>

                            <div id="<?= esc($collapseId) ?>" class="accordion-collapse collapse"
                                aria-labelledby="<?= esc($itemId) ?>" data-bs-parent="#<?= esc($sectionId) ?>_accordion">
                                <div class="accordion-body">
                                    <?php foreach ($columns as $field => $label): ?>
                                        <div class="data-card-row">
                                            <div class="data-card-label"><?= esc($label) ?></div>
                                            <div class="data-card-value">
                                                <?php if (in_array($field, $rawColumns, true)): ?>
                                                    <?= $row[$field] ?? '' ?>
                                                <?php else: ?>
                                                    <?= esc($row[$field] ?? '') ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center text-muted py-4 data-cards-empty">
                    Sin registros
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Footer: page size + paginación -->
    <div class="card-footer">
        <div class="justify-content-center d-flex gap-2 flex-wrap w-100">
            <div class="d-flex align-items-center gap-2">
                <span>Mostrar</span>
                <select class="form-select form-select-sm data-page-size" style="width:auto;">
                    <?php foreach ($pageSizes as $size): ?>
                        <option value="<?= $size ?>" <?= $size == $defaultSize ? 'selected' : '' ?>>
                            <?= $size ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span>registros</span>
            </div>

            <div class="ms-md-auto">
                <ul class="pagination pagination-sm mb-0 data-pagination"></ul>
            </div>
        </div>
    </div>

</div>