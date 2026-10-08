<?php
$escape = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
$formatDate = static function ($value): string {
    return $value ? date('d/m/Y H:i', strtotime($value)) : '—';
};
$technicien = trim($intervention['technicien_prenom'] . ' ' . $intervention['technicien_nom']);
?>
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <a href="/interventions" class="text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i>Retour aux interventions
            </a>
            <h1 class="h3 fw-bold mt-2 mb-1">Intervention n°<?= $escape($intervention['id_intervention']) ?></h1>
            <p class="text-muted mb-0">Détail complet de l’intervention.</p>
        </div>
        <span class="badge text-bg-primary fs-6"><?= $escape($intervention['statut']) ?></span>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-7">
            <section class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h2 class="h5 fw-bold mb-0">Informations de l’intervention</h2>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 mb-2">Client</dt>
                        <dd class="col-sm-8 mb-3"><?= $escape($intervention['client']) ?></dd>
                        <dt class="col-sm-4 mb-2">Équipement</dt>
                        <dd class="col-sm-8 mb-3">
                            <?= $escape($intervention['equipement']) ?>
                            <span class="text-muted">(<?= $escape($intervention['type_equipement']) ?>)</span>
                        </dd>
                        <dt class="col-sm-4 mb-2">N° de série</dt>
                        <dd class="col-sm-8 mb-3"><?= $escape($intervention['num_serie']) ?></dd>
                        <dt class="col-sm-4 mb-2">Technicien</dt>
                        <dd class="col-sm-8 mb-3"><?= $escape($technicien) ?></dd>
                        <dt class="col-sm-4 mb-2">Date d’intervention</dt>
                        <dd class="col-sm-8 mb-3"><?= $escape($formatDate($intervention['date_intervention'])) ?></dd>
                        <dt class="col-sm-4 mb-2">Date de clôture</dt>
                        <dd class="col-sm-8 mb-3"><?= $escape($formatDate($intervention['date_cloture'])) ?></dd>
                        <dt class="col-sm-4 mb-2">Statut</dt>
                        <dd class="col-sm-8 mb-0"><?= $escape($intervention['statut']) ?></dd>
                    </dl>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-5">
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h2 class="h5 fw-bold mb-0">Description de la panne</h2>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-break"><?= nl2br($escape($intervention['desc_panne'])) ?></p>
                </div>
            </section>

            <section class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h2 class="h5 fw-bold mb-0">Rapport d’intervention</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($intervention['rapport'])): ?>
                        <p class="mb-0 text-break"><?= nl2br($escape($intervention['rapport'])) ?></p>
                    <?php else: ?>
                        <p class="text-muted mb-0">Aucun rapport n’a encore été renseigné.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</div>
