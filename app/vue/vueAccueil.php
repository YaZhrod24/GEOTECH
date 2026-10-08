<div class="container-fluid px-4 mt-4">
    <!-- En-tête avec titre et bouton d'action -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Tableau de bord</h2>
            <p class="text-muted small mb-0">Aperçu général des interventions et statistiques du jour</p>
        </div>
        <div>
            <a href="interventions/nouvelle" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-plus-lg"></i>
                <span>+ Planifier une intervention</span>
            </a>
        </div>
    </div>

    <!-- Cartes de statistiques du jour -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold text-white-50">Aujourd'hui</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $totalAujourdhui ?? 0 ?></h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold text-dark-50">En attente</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $countEnAttente ?? 0 ?></h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold text-white-50">En cours</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $countEnCours ?? 0 ?></h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase small fw-bold text-white-50">Clôturées</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $countCloturees ?? 0 ?></h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des interventions du jour -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title fw-bold mb-0">
                <i class="bi bi-list-task me-2 text-primary"></i>Interventions du jour
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tableInterventions">
                    <thead class="table-light">
                        <tr>
                            <th>Heure</th>
                            <th>Client</th>
                            <th>Équipement(s)</th>
                            <th>Technicien</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($interventionsDuJour)): ?>
                            <?php foreach ($interventionsDuJour as $interv): ?>
                                <tr>
                                    <td class="fw-bold">
                                        <?= date('H:i', strtotime($interv['date_heure'] ?? $interv['date_intervention'])) ?>
                                    </td>
                                    <td><?= htmlspecialchars($interv['client_nom'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($interv['equipement_nom'] ?? 'N/A') ?></td>
                                    <td>
                                        <i class="bi bi-person me-1 text-muted"></i>
                                        <?= htmlspecialchars(($interv['technicien_prenom'] ?? '') . ' ' . ($interv['technicien_nom'] ?? 'Non assigné')) ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $statut = strtolower($interv['statut'] ?? '');
                                            $badgeClass = 'bg-secondary';
                                            if ($statut === 'clôturée' || $statut === 'cloturee') {
                                                $badgeClass = 'bg-success';
                                            } elseif ($statut === 'en cours') {
                                                $badgeClass = 'bg-info text-white';
                                            } elseif ($statut === 'en attente') {
                                                $badgeClass = 'bg-warning text-dark';
                                            }
                                        ?>
                                        <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2">
                                            <?= htmlspecialchars($interv['statut'] ?? 'En attente') ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="interventions/<?= $interv['id_intervention'] ?? $interv['id'] ?>/modifier" class="btn btn-sm btn-outline-primary me-1">
                                            Modifier
                                        </a>
                                        <a href="interventions/<?= $interv['id_intervention'] ?? $interv['id'] ?>/supprimer" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette intervention ?');">
                                            Supprimer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Aucune intervention prévue aujourd'hui.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>