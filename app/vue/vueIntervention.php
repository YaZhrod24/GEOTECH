<?php
$escape = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
$formatDate = static function ($value): string {
    return $value ? date('d/m/Y H:i', strtotime($value)) : '—';
};
?>
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Interventions</h1>
            <p class="text-muted mb-0">Consultez les interventions et leurs informations.</p>
        </div>
    </div>

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
                            $description = (string) $intervention['desc_panne'];
                            $descriptionCourte = strlen($description) > 100
                                ? substr($description, 0, 100) . '…'
                                : $description;
                            $technicien = trim($intervention['technicien_prenom'] . ' ' . $intervention['technicien_nom']);
                            $statut = (string) $intervention['statut'];
                            $statutLower = strtolower($statut);
                            $badgeClass = 'text-bg-secondary';
                            if (in_array($statutLower, ['clôturée', 'cloturee', 'cloturée', 'terminée', 'terminee'], true)) {
                                $badgeClass = 'text-bg-success';
                            } elseif (in_array($statutLower, ['en cours', 'encours'], true)) {
                                $badgeClass = 'text-bg-info';
                            } elseif (in_array($statutLower, ['ouverte', 'en attente'], true)) {
                                $badgeClass = 'text-bg-warning';
                            }
                            ?>
                            <tr>
                                <td class="fw-semibold"><?= $escape($intervention['id_intervention']) ?></td>
                                <td><?= $escape($intervention['client']) ?></td>
                                <td>
                                    <span class="d-block fw-semibold"><?= $escape($intervention['equipement']) ?></span>
                                    <span class="small text-muted"><?= $escape($intervention['type_equipement']) ?></span>
                                </td>
                                <td title="<?= $escape($description) ?>"><?= $escape($descriptionCourte) ?></td>
                                <td data-order="<?= $escape($intervention['date_intervention']) ?>">
                                    <?= $escape($formatDate($intervention['date_intervention'])) ?>
                                </td>
                                <td><?= $escape($technicien) ?></td>
                                <td><span class="badge <?= $badgeClass ?>"><?= $escape($statut) ?></span></td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary text-nowrap"
                                        data-bs-toggle="modal"
                                        data-bs-target="#interventionModal"
                                        data-id="<?= $escape($intervention['id_intervention']) ?>"
                                        data-client="<?= $escape($intervention['client']) ?>"
                                        data-equipement="<?= $escape($intervention['equipement']) ?>"
                                        data-type-equipement="<?= $escape($intervention['type_equipement']) ?>"
                                        data-num-serie="<?= $escape($intervention['num_serie']) ?>"
                                        data-description="<?= $escape($description) ?>"
                                        data-date="<?= $escape($formatDate($intervention['date_intervention'])) ?>"
                                        data-date-cloture="<?= $escape($formatDate($intervention['date_cloture'])) ?>"
                                        data-technicien="<?= $escape($technicien) ?>"
                                        data-statut="<?= $escape($statut) ?>"
                                        data-rapport="<?= $escape($intervention['rapport']) ?>"
                                        aria-label="Détails de l’intervention <?= $escape($intervention['id_intervention']) ?>">
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
</script>
