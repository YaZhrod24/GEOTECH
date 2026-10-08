<?php
require_once Racine . '/../app/vue/layout/entete.php';
$technicienErreur = $_SESSION['technicien_erreur'] ?? null;
unset($_SESSION['technicien_erreur']);
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Techniciens</h1>
            <p class="text-muted mb-0">Gérez les techniciens affectés aux interventions.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#technicienFormModal">
            <i class="bi bi-person-plus me-2" aria-hidden="true"></i>
            Nouveau technicien
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 p-md-4">
            <?php if ($technicienErreur !== null): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($technicienErreur, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <div class="table-responsive">
                <table id="techniciensTable" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($techniciens as $technicien): ?>
                            <tr>
                                <td><?= (int) $technicien['id_employe'] ?></td>
                                <td><?= htmlspecialchars($technicien['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($technicien['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <a href="mailto:<?= htmlspecialchars($technicien['email'], ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($technicien['email'], ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($technicien['tel'] ?? 'Non renseigné', ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Actions du technicien">
                                        <button type="button" class="btn btn-outline-secondary btn-details-technicien"
                                            data-bs-toggle="modal" data-bs-target="#technicienDetailsModal"
                                            data-id="<?= (int) $technicien['id_employe'] ?>"
                                            data-nom="<?= htmlspecialchars($technicien['nom'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-prenom="<?= htmlspecialchars($technicien['prenom'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-email="<?= htmlspecialchars($technicien['email'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-tel="<?= htmlspecialchars($technicien['tel'] ?? 'Non renseigné', ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                            <span class="visually-hidden">Voir</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-edit-technicien"
                                            data-bs-toggle="modal" data-bs-target="#technicienFormModal"
                                            data-id="<?= (int) $technicien['id_employe'] ?>"
                                            data-nom="<?= htmlspecialchars($technicien['nom'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-prenom="<?= htmlspecialchars($technicien['prenom'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-email="<?= htmlspecialchars($technicien['email'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-tel="<?= htmlspecialchars($technicien['tel'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                            <span class="visually-hidden">Modifier</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-history-technicien"
                                            data-bs-toggle="modal" data-bs-target="#technicienHistoryModal"
                                            data-nom="<?= htmlspecialchars($technicien['prenom'] . ' ' . $technicien['nom'], ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="bi bi-clock-history" aria-hidden="true"></i>
                                            <span class="visually-hidden">Historique</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-delete-technicien"
                                            data-bs-toggle="modal" data-bs-target="#deleteTechnicienModal"
                                            data-id="<?= (int) $technicien['id_employe'] ?>"
                                            data-nom="<?= htmlspecialchars($technicien['prenom'] . ' ' . $technicien['nom'], ENT_QUOTES, 'UTF-8') ?>">
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
            <?php if (empty($techniciens)): ?>
                <p class="text-muted text-center mb-0">Aucun technicien trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="technicienDetailsModal" tabindex="-1" aria-labelledby="technicienDetailsLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="technicienDetailsLabel">Informations du technicien</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">ID</dt>
                    <dd class="col-sm-8" id="detailsId"></dd>
                    <dt class="col-sm-4">Nom complet</dt>
                    <dd class="col-sm-8" id="detailsNom"></dd>
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8" id="detailsEmail"></dd>
                    <dt class="col-sm-4">Téléphone</dt>
                    <dd class="col-sm-8" id="detailsTel"></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="technicienHistoryModal" tabindex="-1" aria-labelledby="technicienHistoryLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="technicienHistoryLabel">Historique des interventions</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">
                    Historique de <strong id="historyTechnicienName"></strong>.
                    Les interventions seront affichées ici lorsque le module correspondant sera disponible.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="technicienFormModal" tabindex="-1" aria-labelledby="technicienFormLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="technicienFormLabel">Nouveau technicien</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="/techniciens/nouveau" method="post" id="technicienForm">
                <div class="modal-body">
                    <p class="alert alert-info small">
                        Le formulaire est prêt pour être relié aux actions de création et de modification.
                    </p>
                    <input type="hidden" name="id_employe" id="formId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="formNom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="formNom" name="nom" required>
                        </div>
                        <div class="col-md-6">
                            <label for="formPrenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="formPrenom" name="prenom" required>
                        </div>
                        <div class="col-md-6">
                            <label for="formEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="formEmail" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="formTel" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="formTel" name="tel">
                        </div>
                        <div class="col-12">
                            <label for="formMdp" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="formMdp" name="mdp">
                            <div class="form-text">Obligatoire à la création, facultatif lors d'une modification.</div>
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

<div class="modal fade" id="deleteTechnicienModal" tabindex="-1" aria-labelledby="deleteTechnicienLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="deleteTechnicienLabel">Confirmer la suppression</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimer <strong id="deleteTechnicienName"></strong> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <form action="#" method="post" id="deleteTechnicienForm">
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
            jQuery('#techniciensTable').DataTable({
                language: {
                    search: 'Rechercher :',
                    lengthMenu: '_MENU_ techniciens par page',
                    info: 'Affichage de _START_ à _END_ sur _TOTAL_ techniciens',
                    infoEmpty: 'Aucun technicien',
                    zeroRecords: 'Aucun résultat',
                    paginate: {
                        first: 'Premier',
                        last: 'Dernier',
                        next: 'Suivant',
                        previous: 'Précédent'
                    }
                },
                pageLength: 10,
                order: [[1, 'asc']]
            });
        }

        document.querySelectorAll('.btn-details-technicien').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('detailsId').textContent = button.dataset.id;
                document.getElementById('detailsNom').textContent =
                    button.dataset.prenom + ' ' + button.dataset.nom;
                document.getElementById('detailsEmail').textContent = button.dataset.email;
                document.getElementById('detailsTel').textContent = button.dataset.tel;
            });
        });

        document.querySelectorAll('.btn-edit-technicien').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('technicienFormLabel').textContent = 'Modifier le technicien';
                document.getElementById('technicienForm').action =
                    '/techniciens/' + button.dataset.id + '/modifier';
                document.getElementById('formId').value = button.dataset.id;
                document.getElementById('formNom').value = button.dataset.nom;
                document.getElementById('formPrenom').value = button.dataset.prenom;
                document.getElementById('formEmail').value = button.dataset.email;
                document.getElementById('formTel').value = button.dataset.tel;
                document.getElementById('formMdp').value = '';
            });
        });

        document.querySelectorAll('.btn-history-technicien').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('historyTechnicienName').textContent = button.dataset.nom;
            });
        });

        document.querySelector('[data-bs-target="#technicienFormModal"]').addEventListener('click', function () {
            document.getElementById('technicienForm').reset();
            document.getElementById('technicienForm').action = '/techniciens/nouveau';
            document.getElementById('formId').value = '';
            document.getElementById('technicienFormLabel').textContent = 'Nouveau technicien';
        });

        document.querySelectorAll('.btn-delete-technicien').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('deleteTechnicienName').textContent = button.dataset.nom;
                document.getElementById('deleteTechnicienForm').action =
                    '/technicien/' + button.dataset.id + '/supprimer';
            });
        });
    });
</script>

<?php require_once Racine . '/../app/vue/layout/pied.php'; ?>
