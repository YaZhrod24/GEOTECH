<?php
$escape = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
$fuseauUtc = new DateTimeZone('UTC');
$fuseauParis = new DateTimeZone('Europe/Paris');
$formatDate = static function ($value) use ($fuseauUtc, $fuseauParis): string {
    if (!$value) {
        return '—';
    }

    return (new DateTimeImmutable($value, $fuseauUtc))
        ->setTimezone($fuseauParis)
        ->format('d/m/Y H:i');
};
$datePourChamp = static function ($value) use ($fuseauUtc, $fuseauParis): string {
    if (!$value) {
        return '';
    }

    return (new DateTimeImmutable($value, $fuseauUtc))
        ->setTimezone($fuseauParis)
        ->format('Y-m-d\TH:i');
};
$technicien = trim($intervention->getTechnicienPrenom() . ' ' . $intervention->getTechnicienNom());
$statutIntervention = $intervention->getStatut();
$estOuverte = $statutIntervention === 'OUVERTE';
$badgeStatut = match ($statutIntervention) {
    'OUVERTE' => 'text-bg-warning',
    'EN_COURS' => 'text-bg-info',
    'CLOTUREE' => 'text-bg-success',
    default => 'text-bg-secondary'
};
$dateSaisie = $valeursModification['date_intervention'] ?? $datePourChamp($intervention->getDateIntervention());
$descriptionSaisie = $valeursModification['desc_panne'] ?? $intervention->getDescPanne();
$rapportSaisi = $valeursModification['rapport'] ?? $intervention->getRapport();
$equipementSaisi = $valeursModification['id_equipement'] ?? $intervention->getIdEquipement();
$technicienSaisi = $valeursModification['id_employe'] ?? $intervention->getIdEmploye();
?>
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <a href="/interventions" class="text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i>Retour aux interventions
            </a>
            <h1 class="h3 fw-bold mt-2 mb-1">Intervention n°<?= $escape($intervention->getId()) ?></h1>
            <p class="text-muted mb-0">Détail et gestion de l’intervention.</p>
        </div>
        <span class="badge <?= $badgeStatut ?> fs-6"><?= $escape($statutIntervention) ?></span>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="status">
            <?= $escape($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger" role="alert"><?= $escape($erreur) ?></div>
    <?php endif; ?>

    <section class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h2 class="h5 fw-bold mb-1">Statut de l’intervention</h2>
                <p class="small text-muted mb-0">Le statut peut être changé à tout moment.</p>
            </div>
            <form method="post" action="/intervention/modifier/<?= $escape($intervention->getId()) ?>" class="d-flex flex-wrap gap-2">
                <input type="hidden" name="formulaire" value="statut">
                <label class="visually-hidden" for="statutIntervention">Statut</label>
                <select class="form-select" id="statutIntervention" name="statut" required>
                    <?php foreach (['OUVERTE', 'EN_COURS', 'CLOTUREE'] as $statut): ?>
                        <option value="<?= $escape($statut) ?>" <?= $statutIntervention === $statut ? 'selected' : '' ?>>
                            <?= $escape($statut) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary text-nowrap">
                    <i class="bi bi-arrow-repeat me-1"></i>Mettre à jour
                </button>
            </form>
        </div>
    </section>

    <?php if ($estOuverte): ?>
        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h2 class="h5 fw-bold mb-1">Modifier les informations</h2>
                <p class="small text-muted mb-0">Ces champs ne sont modifiables que lorsque le statut est OUVERTE.</p>
            </div>
            <div class="card-body">
                <form method="post" action="/intervention/modifier/<?= $escape($intervention->getId()) ?>">
                    <input type="hidden" name="formulaire" value="informations">
                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label for="editEquipement" class="form-label">Équipement</label>
                            <select class="form-select" id="editEquipement" name="id_equipement" required>
                                <?php foreach ($equipements as $equipement): ?>
                                    <option value="<?= $escape($equipement->getId()) ?>"
                                        <?= (string) $equipementSaisi === (string) $equipement->getId() ? 'selected' : '' ?>>
                                        <?= $escape($equipement->getClient() . ' — ' . $equipement->getNom() . ' (' . $equipement->getType() . ', ' . $equipement->getNumSerie() . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="editTechnicien" class="form-label">Technicien</label>
                            <select class="form-select" id="editTechnicien" name="id_employe" required>
                                <?php foreach ($techniciens as $technicienOption): ?>
                                    <option value="<?= $escape($technicienOption->getId()) ?>"
                                        <?= (string) $technicienSaisi === (string) $technicienOption->getId() ? 'selected' : '' ?>>
                                        <?= $escape($technicienOption->getPrenom() . ' ' . $technicienOption->getNom()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-lg-6">
                            <label for="editDateIntervention" class="form-label">Date et heure</label>
                            <input
                                type="datetime-local"
                                class="form-control"
                                id="editDateIntervention"
                                name="date_intervention"
                                value="<?= $escape($dateSaisie) ?>"
                                required>
                        </div>
                        <div class="col-12">
                            <label for="editDescription" class="form-label">Description de la panne</label>
                            <textarea
                                class="form-control"
                                id="editDescription"
                                name="desc_panne"
                                rows="4"
                                placeholder="Décrivez la panne constatée"
                                required><?= $escape($descriptionSaisie) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label for="editRapport" class="form-label">Rapport (facultatif)</label>
                            <textarea
                                class="form-control"
                                id="editRapport"
                                name="rapport"
                                rows="4"
                                placeholder="Ajouter un rapport si nécessaire"><?= $escape($rapportSaisi) ?></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </section>
    <?php else: ?>
        <div class="alert alert-info" role="status">
            Les informations sont en lecture seule tant que l’intervention est EN_COURS ou CLOTUREE.
            Vous pouvez la repasser en statut OUVERTE pour les modifier.
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-12 col-xl-7">
            <section class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h2 class="h5 fw-bold mb-0">Informations de l’intervention</h2>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 mb-2">Client</dt>
                        <dd class="col-sm-8 mb-3"><?= $escape($intervention->getClient()) ?></dd>
                        <dt class="col-sm-4 mb-2">Équipement</dt>
                        <dd class="col-sm-8 mb-3">
                            <?= $escape($intervention->getNomEquipement()) ?>
                            <span class="text-muted">(<?= $escape($intervention->getTypeEquipement()) ?>)</span>
                        </dd>
                        <dt class="col-sm-4 mb-2">N° de série</dt>
                        <dd class="col-sm-8 mb-3"><?= $escape($intervention->getNumSerie()) ?></dd>
                        <dt class="col-sm-4 mb-2">Technicien</dt>
                        <dd class="col-sm-8 mb-3"><?= $escape($technicien) ?></dd>
                        <dt class="col-sm-4 mb-2">Date d’intervention</dt>
                        <dd class="col-sm-8 mb-3"><?= $escape($formatDate($intervention->getDateIntervention())) ?></dd>
                        <dt class="col-sm-4 mb-2">Date de clôture</dt>
                        <dd class="col-sm-8 mb-3"><?= $escape($formatDate($intervention->getDateCloture())) ?></dd>
                        <dt class="col-sm-4 mb-2">Statut</dt>
                        <dd class="col-sm-8 mb-0"><?= $escape($statutIntervention) ?></dd>
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
                    <p class="mb-0 text-break"><?= nl2br($escape($intervention->getDescPanne())) ?></p>
                </div>
            </section>

            <section class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h2 class="h5 fw-bold mb-0">Rapport d’intervention</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($intervention->getRapport())): ?>
                        <p class="mb-0 text-break"><?= nl2br($escape($intervention->getRapport())) ?></p>
                    <?php else: ?>
                        <p class="text-muted mb-0">Aucun rapport n’a encore été renseigné.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</div>
