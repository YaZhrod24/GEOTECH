<?php
require_once Racine . '/../app/vue/layout/entete.php';
$equipementErreur = $_SESSION['equipement_erreur'] ?? null;
unset($_SESSION['equipement_erreur']);
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Équipements</h1>
            <p class="text-muted mb-0">Gérez les équipements associés à vos clients.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#equipementFormModal">
            <i class="bi bi-router me-2" aria-hidden="true"></i>
            Nouvel équipement
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 p-md-4">
            <?php if ($equipementErreur !== null): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($equipementErreur, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if (empty($clients)): ?>
                <div class="alert alert-warning">
                    Aucun client n'est disponible. Créez d'abord un client avant d'ajouter un équipement.
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table id="equipementsTable" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Type</th>
                            <th>Référence</th>
                            <th>État</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($equipements as $equipement): ?>
                            <tr>
                                <td><?= (int) $equipement['id_equipement'] ?></td>
                                <td><?= htmlspecialchars($equipement['client'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($equipement['type'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($equipement['num_serie'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><span class="badge text-bg-success">Actif</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Actions de l'équipement">
                                        <button type="button" class="btn btn-outline-secondary btn-details-equipement"
                                            data-bs-toggle="modal" data-bs-target="#equipementDetailsModal"
                                            data-id="<?= (int) $equipement['id_equipement'] ?>"
                                            data-nom="<?= htmlspecialchars($equipement['nom'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-client="<?= htmlspecialchars($equipement['client'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-type="<?= htmlspecialchars($equipement['type'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-reference="<?= htmlspecialchars($equipement['num_serie'], ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                            <span class="visually-hidden">Voir</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-edit-equipement"
                                            data-bs-toggle="modal" data-bs-target="#equipementFormModal"
                                            data-id="<?= (int) $equipement['id_equipement'] ?>"
                                            data-nom="<?= htmlspecialchars($equipement['nom'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-client-id="<?= (int) $equipement['id_client'] ?>"
                                            data-type="<?= htmlspecialchars($equipement['type'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-reference="<?= htmlspecialchars($equipement['num_serie'], ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                            <span class="visually-hidden">Modifier</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-history-equipement"
                                            data-bs-toggle="modal" data-bs-target="#equipementHistoryModal"
                                            data-nom="<?= htmlspecialchars($equipement['nom'], ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="bi bi-clock-history" aria-hidden="true"></i>
                                            <span class="visually-hidden">Historique</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-delete-equipement"
                                            data-bs-toggle="modal" data-bs-target="#deleteEquipementModal"
                                            data-id="<?= (int) $equipement['id_equipement'] ?>"
                                            data-nom="<?= htmlspecialchars($equipement['nom'], ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="bi bi-trash" aria-hidden="true"></i>
                                            <span class="visually-hidden">Supprimer</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (empty($equipements)): ?>
                <p class="text-muted text-center mb-0">Aucun équipement trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="equipementDetailsModal" tabindex="-1" aria-labelledby="equipementDetailsLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="equipementDetailsLabel">Informations de l'équipement</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">ID</dt><dd class="col-sm-8" id="detailsEquipementId"></dd>
                    <dt class="col-sm-4">Nom</dt><dd class="col-sm-8" id="detailsEquipementNom"></dd>
                    <dt class="col-sm-4">Client</dt><dd class="col-sm-8" id="detailsEquipementClient"></dd>
                    <dt class="col-sm-4">Type</dt><dd class="col-sm-8" id="detailsEquipementType"></dd>
                    <dt class="col-sm-4">Référence</dt><dd class="col-sm-8" id="detailsEquipementReference"></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="equipementHistoryModal" tabindex="-1" aria-labelledby="equipementHistoryLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="equipementHistoryLabel">Historique des interventions</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Historique de <strong id="historyEquipementName"></strong>.
                    Il sera alimenté avec les interventions associées.</p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="equipementFormModal" tabindex="-1" aria-labelledby="equipementFormLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="equipementFormLabel">Nouvel équipement</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="/equipements/nouveau" method="post" id="equipementForm">
                <div class="modal-body">
                    <input type="hidden" name="id_equipement" id="formEquipementId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="formEquipementNom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="formEquipementNom" name="nom" required>
                        </div>
                        <div class="col-md-6">
                            <label for="formEquipementClient" class="form-label">Client</label>
                            <select class="form-select" id="formEquipementClient" name="id_client" required>
                                <option value="">Sélectionner un client</option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= (int) $client['id_client'] ?>">
                                        <?= htmlspecialchars($client['raison_social'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="formEquipementType" class="form-label">Type</label>
                            <input type="text" class="form-control" id="formEquipementType" name="type" required>
                        </div>
                        <div class="col-md-6">
                            <label for="formEquipementReference" class="form-label">Référence / numéro de série</label>
                            <input type="text" class="form-control" id="formEquipementReference" name="num_serie" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteEquipementModal" tabindex="-1" aria-labelledby="deleteEquipementLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="deleteEquipementLabel">Confirmer la suppression</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">Voulez-vous vraiment supprimer <strong id="deleteEquipementName"></strong> ?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <form action="#" method="post" id="deleteEquipementForm">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && jQuery.fn.DataTable) {
            jQuery('#equipementsTable').DataTable({
                language: {
                    search: 'Rechercher :',
                    lengthMenu: '_MENU_ équipements par page',
                    info: 'Affichage de _START_ à _END_ sur _TOTAL_ équipements',
                    infoEmpty: 'Aucun équipement',
                    zeroRecords: 'Aucun résultat',
                    paginate: { first: 'Premier', last: 'Dernier', next: 'Suivant', previous: 'Précédent' }
                },
                pageLength: 10,
                order: [[1, 'asc']]
            });
        }

        document.querySelectorAll('.btn-details-equipement').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('detailsEquipementId').textContent = button.dataset.id;
                document.getElementById('detailsEquipementNom').textContent = button.dataset.nom;
                document.getElementById('detailsEquipementClient').textContent = button.dataset.client;
                document.getElementById('detailsEquipementType').textContent = button.dataset.type;
                document.getElementById('detailsEquipementReference').textContent = button.dataset.reference;
            });
        });

        document.querySelectorAll('.btn-edit-equipement').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('equipementFormLabel').textContent = 'Modifier l’équipement';
                document.getElementById('equipementForm').action =
                    '/equipements/' + button.dataset.id + '/modifier';
                document.getElementById('formEquipementId').value = button.dataset.id;
                document.getElementById('formEquipementNom').value = button.dataset.nom;
                document.getElementById('formEquipementClient').value = button.dataset.clientId;
                document.getElementById('formEquipementType').value = button.dataset.type;
                document.getElementById('formEquipementReference').value = button.dataset.reference;
            });
        });

        document.querySelector('[data-bs-target="#equipementFormModal"]').addEventListener('click', function () {
            document.getElementById('equipementForm').reset();
            document.getElementById('equipementForm').action = '/equipements/nouveau';
            document.getElementById('formEquipementId').value = '';
            document.getElementById('equipementFormLabel').textContent = 'Nouvel équipement';
        });

        document.querySelectorAll('.btn-history-equipement').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('historyEquipementName').textContent = button.dataset.nom;
            });
        });

        document.querySelectorAll('.btn-delete-equipement').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('deleteEquipementName').textContent = button.dataset.nom;
                document.getElementById('deleteEquipementForm').action =
                    '/equipements/' + button.dataset.id + '/supprimer';
            });
        });
    });
</script>

<?php require_once Racine . '/../app/vue/layout/pied.php'; ?>
