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
?>
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Interventions</h1>
            <p class="text-muted mb-0">Consultez les interventions et leurs informations.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#creationInterventionModal">
            <i class="bi bi-plus-lg me-1"></i>Nouvelle intervention
        </button>
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

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h2 class="h5 fw-bold mb-0">
                <i class="bi bi-tools me-2 text-primary"></i>Liste des interventions
            </h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="tableInterventions">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Équipement</th>
                            <th>Description de la panne</th>
                            <th>Date</th>
                            <th>Technicien</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($interventions as $intervention): ?>
                            <?php
                            $description = (string) $intervention->getDescPanne();
                            $descriptionCourte = strlen($description) > 100
                                ? substr($description, 0, 100) . '…'
                                : $description;
                            $technicien = trim($intervention->getTechnicienPrenom() . ' ' . $intervention->getTechnicienNom());
                            $statut = (string) $intervention->getStatut();
                            $badgeClass = 'text-bg-secondary';
                            if ($statut === 'CLOTUREE') {
                                $badgeClass = 'text-bg-success';
                            } elseif ($statut === 'EN_COURS') {
                                $badgeClass = 'text-bg-info';
                            } elseif ($statut === 'OUVERTE') {
                                $badgeClass = 'text-bg-warning';
                            }
                            ?>
                            <tr>
                                <td class="fw-semibold"><?= $escape($intervention->getId()) ?></td>
                                <td><?= $escape($intervention->getClient()) ?></td>
                                <td>
                                    <span class="d-block fw-semibold"><?= $escape($intervention->getNomEquipement()) ?></span>
                                    <span class="small text-muted"><?= $escape($intervention->getTypeEquipement()) ?></span>
                                </td>
                                <td title="<?= $escape($description) ?>"><?= $escape($descriptionCourte) ?></td>
                                <td data-order="<?= $escape($intervention->getDateIntervention()) ?>">
                                    <?= $escape($formatDate($intervention->getDateIntervention())) ?>
                                </td>
                                <td><?= $escape($technicien) ?></td>
                                <td><span class="badge <?= $badgeClass ?>"><?= $escape($statut) ?></span></td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary text-nowrap"
                                        data-bs-toggle="modal"
                                        data-bs-target="#interventionModal"
                                        data-id="<?= $escape($intervention->getId()) ?>"
                                        data-client="<?= $escape($intervention->getClient()) ?>"
                                        data-equipement="<?= $escape($intervention->getNomEquipement()) ?>"
                                        data-type-equipement="<?= $escape($intervention->getTypeEquipement()) ?>"
                                        data-num-serie="<?= $escape($intervention->getNumSerie()) ?>"
                                        data-description="<?= $escape($description) ?>"
                                        data-date="<?= $escape($formatDate($intervention->getDateIntervention())) ?>"
                                        data-date-cloture="<?= $escape($formatDate($intervention->getDateCloture())) ?>"
                                        data-technicien="<?= $escape($technicien) ?>"
                                        data-statut="<?= $escape($statut) ?>"
                                        data-rapport="<?= $escape($intervention->getRapport()) ?>"
                                        aria-label="Détails de l’intervention <?= $escape($intervention->getId()) ?>">
                                        <i class="bi bi-eye me-1"></i>Détails
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="creationInterventionModal" tabindex="-1" aria-labelledby="creationInterventionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form class="modal-content" method="post" action="/interventions">
            <input type="hidden" name="formulaire" value="creation">
            <div class="modal-header">
                <h2 class="modal-title h5 fw-bold" id="creationInterventionModalLabel">Créer une intervention</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="creationEquipement" class="form-label">Équipement</label>
                    <select class="form-select" id="creationEquipement" name="id_equipement" required>
                        <option value="">Sélectionner un équipement</option>
                        <?php foreach ($equipements as $equipement): ?>
                            <option
                                    value="<?= $escape($equipement->getId()) ?>"
                                    <?= (string) ($valeursFormulaire['id_equipement'] ?? '') === (string) $equipement->getId() ? 'selected' : '' ?>>
                                    <?= $escape($equipement->getClient() . ' — ' . $equipement->getNom() . ' (' . $equipement->getType() . ', ' . $equipement->getNumSerie() . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($equipements)): ?>
                        <div class="form-text text-danger">Ajoutez d’abord un équipement pour pouvoir créer une intervention.</div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="creationTechnicien" class="form-label">Technicien</label>
                    <select class="form-select" id="creationTechnicien" name="id_employe" required>
                        <option value="">Sélectionner un technicien</option>
                        <?php foreach ($techniciens as $technicien): ?>
                            <option
                                value="<?= $escape($technicien->getId()) ?>"
                                <?= (string) ($valeursFormulaire['id_employe'] ?? '') === (string) $technicien->getId() ? 'selected' : '' ?>>
                                <?= $escape($technicien->getPrenom() . ' ' . $technicien->getNom()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($techniciens)): ?>
                        <div class="form-text text-danger">Aucun employé avec le rôle TECHNICIEN n’est disponible.</div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="creationDate" class="form-label">Date et heure</label>
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="creationDate"
                        name="date_intervention"
                        value="<?= $escape($valeursFormulaire['date_intervention'] ?? (new DateTimeImmutable('now', $fuseauParis))->format('Y-m-d\TH:i')) ?>"
                        required>
                </div>
                <div class="mb-3">
                    <label for="creationDescription" class="form-label">Description de la panne</label>
                    <textarea
                        class="form-control"
                        id="creationDescription"
                        name="desc_panne"
                        rows="4"
                        placeholder="Décrivez la panne constatée"
                        required><?= $escape($valeursFormulaire['desc_panne'] ?? '') ?></textarea>
                </div>
                <div>
                    <label for="creationRapport" class="form-label">Rapport (facultatif)</label>
                    <textarea
                        class="form-control"
                        id="creationRapport"
                        name="rapport"
                        rows="3"
                        placeholder="Ajouter un rapport si nécessaire"><?= $escape($valeursFormulaire['rapport'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button
                    type="submit"
                    class="btn btn-primary"
                    <?= empty($equipements) || empty($techniciens) ? 'disabled' : '' ?>>
                    <i class="bi bi-check-lg me-1"></i>Créer l’intervention
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="interventionModal" tabindex="-1" aria-labelledby="interventionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title h5 fw-bold mb-1" id="interventionModalLabel">Détail de l’intervention</h2>
                    <span class="small text-muted">Intervention n°<span id="modalInterventionId"></span></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Client</dt>
                    <dd class="col-sm-8" id="modalClient"></dd>
                    <dt class="col-sm-4">Équipement</dt>
                    <dd class="col-sm-8">
                        <span id="modalEquipement"></span>
                        <span class="text-muted">— <span id="modalTypeEquipement"></span></span>
                    </dd>
                    <dt class="col-sm-4">N° de série</dt>
                    <dd class="col-sm-8" id="modalNumSerie"></dd>
                    <dt class="col-sm-4">Description de la panne</dt>
                    <dd class="col-sm-8 text-break" id="modalDescription"></dd>
                    <dt class="col-sm-4">Date d’intervention</dt>
                    <dd class="col-sm-8" id="modalDate"></dd>
                    <dt class="col-sm-4">Technicien</dt>
                    <dd class="col-sm-8" id="modalTechnicien"></dd>
                    <dt class="col-sm-4">Statut</dt>
                    <dd class="col-sm-8" id="modalStatut"></dd>
                    <dt class="col-sm-4">Date de clôture</dt>
                    <dd class="col-sm-8" id="modalDateCloture"></dd>
                    <dt class="col-sm-4">Rapport</dt>
                    <dd class="col-sm-8 text-break" id="modalRapport"></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary" id="modalFicheComplete" href="#">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Ouvrir la fiche complète
                </a>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(function () {
        $('#tableInterventions').DataTable({
            language: {
                decimal: ',',
                emptyTable: 'Aucune intervention à afficher.',
                info: 'Affichage de _START_ à _END_ sur _TOTAL_ interventions',
                infoEmpty: 'Aucune intervention',
                infoFiltered: '(filtré parmi _MAX_ interventions)',
                lengthMenu: 'Afficher _MENU_ interventions',
                loadingRecords: 'Chargement…',
                search: 'Rechercher :',
                zeroRecords: 'Aucune intervention correspondante trouvée',
                paginate: {
                    first: 'Premier',
                    last: 'Dernier',
                    next: 'Suivant',
                    previous: 'Précédent'
                }
            },
            order: [[4, 'desc']],
            columnDefs: [{ orderable: false, targets: 7 }],
            pageLength: 10
        });
    });

    document.getElementById('interventionModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) {
            return;
        }

        const fields = {
            modalInterventionId: 'id',
            modalClient: 'client',
            modalEquipement: 'equipement',
            modalTypeEquipement: 'typeEquipement',
            modalNumSerie: 'numSerie',
            modalDescription: 'description',
            modalDate: 'date',
            modalDateCloture: 'dateCloture',
            modalTechnicien: 'technicien',
            modalStatut: 'statut',
            modalRapport: 'rapport'
        };

        Object.entries(fields).forEach(([elementId, dataKey]) => {
            document.getElementById(elementId).textContent = button.dataset[dataKey] || '—';
        });

        document.getElementById('modalFicheComplete').href = '/intervention/' + encodeURIComponent(button.dataset.id);
    });

    <?php if (!empty($erreur)): ?>
        window.addEventListener('load', function () {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('creationInterventionModal')).show();
        });
    <?php endif; ?>
</script>
