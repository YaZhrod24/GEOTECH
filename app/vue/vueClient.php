<?php
require_once Racine . '/../app/vue/layout/entete.php';
$clientErreur = $_SESSION['client_erreur'] ?? null;
$clientSucces = $_SESSION['client_succes'] ?? null;
unset($_SESSION['client_erreur'], $_SESSION['client_succes']);
$escape = static fn($value): string => htmlspecialchars(
    (string) $value,
    ENT_QUOTES | ENT_SUBSTITUTE,
    'UTF-8'
);
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Clients</h1>
            <p class="text-muted mb-0">Gérez les informations de vos clients.</p>
        </div>
        <button type="button" class="btn btn-primary" id="nouveauClientButton"
            data-bs-toggle="modal" data-bs-target="#clientFormModal">
            <i class="bi bi-person-plus me-2" aria-hidden="true"></i>
            Nouveau client
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 p-md-4">
            <?php if ($clientErreur !== null): ?>
                <div class="alert alert-danger" role="alert"><?= $escape($clientErreur) ?></div>
            <?php endif; ?>
            <?php if ($clientSucces !== null): ?>
                <div class="alert alert-success" role="status"><?= $escape($clientSucces) ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table id="clientsTable" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Raison sociale</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Ville</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?= (int) $client->getId() ?></td>
                                <td><?= $escape($client->getRaisonSociale()) ?></td>
                                <td>
                                    <a href="mailto:<?= $escape($client->getEmail()) ?>">
                                        <?= $escape($client->getEmail()) ?>
                                    </a>
                                </td>
                                <td><?= $escape($client->getTel() ?: 'Non renseigné') ?></td>
                                <td><?= $escape($client->getVille()) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Actions du client">
                                        <button type="button" class="btn btn-outline-secondary btn-details-client"
                                            data-bs-toggle="modal" data-bs-target="#clientDetailsModal"
                                            data-id="<?= (int) $client->getId() ?>"
                                            data-raison-social="<?= $escape($client->getRaisonSociale()) ?>"
                                            data-email="<?= $escape($client->getEmail()) ?>"
                                            data-tel="<?= $escape($client->getTel() ?: 'Non renseigné') ?>"
                                            data-adresse="<?= $escape($client->getAdresse()) ?>"
                                            data-cp="<?= $escape($client->getCp()) ?>"
                                            data-ville="<?= $escape($client->getVille()) ?>">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                            <span class="visually-hidden">Voir</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-edit-client"
                                            data-bs-toggle="modal" data-bs-target="#clientFormModal"
                                            data-id="<?= (int) $client->getId() ?>"
                                            data-raison-social="<?= $escape($client->getRaisonSociale()) ?>"
                                            data-email="<?= $escape($client->getEmail()) ?>"
                                            data-tel="<?= $escape($client->getTel()) ?>"
                                            data-adresse="<?= $escape($client->getAdresse()) ?>"
                                            data-cp="<?= $escape($client->getCp()) ?>"
                                            data-ville="<?= $escape($client->getVille()) ?>">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                            <span class="visually-hidden">Modifier</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-delete-client"
                                            data-bs-toggle="modal" data-bs-target="#deleteClientModal"
                                            data-id="<?= (int) $client->getId() ?>"
                                            data-raison-social="<?= $escape($client->getRaisonSociale()) ?>">
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
            <?php if (empty($clients)): ?>
                <p class="text-muted text-center mb-0">Aucun client trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="clientDetailsModal" tabindex="-1" aria-labelledby="clientDetailsLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="clientDetailsLabel">Informations du client</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">ID</dt><dd class="col-sm-8" id="detailsClientId"></dd>
                    <dt class="col-sm-4">Raison sociale</dt><dd class="col-sm-8" id="detailsClientRaisonSociale"></dd>
                    <dt class="col-sm-4">Email</dt><dd class="col-sm-8" id="detailsClientEmail"></dd>
                    <dt class="col-sm-4">Téléphone</dt><dd class="col-sm-8" id="detailsClientTel"></dd>
                    <dt class="col-sm-4">Adresse</dt><dd class="col-sm-8" id="detailsClientAdresse"></dd>
                    <dt class="col-sm-4">Code postal</dt><dd class="col-sm-8" id="detailsClientCp"></dd>
                    <dt class="col-sm-4">Ville</dt><dd class="col-sm-8" id="detailsClientVille"></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="clientFormModal" tabindex="-1" aria-labelledby="clientFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="clientFormLabel">Nouveau client</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="/clients/nouveau" method="post" id="clientForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="formClientRaisonSociale" class="form-label">Raison sociale</label>
                            <input type="text" class="form-control" id="formClientRaisonSociale"
                                name="raison_social" maxlength="255" required>
                        </div>
                        <div class="col-md-6">
                            <label for="formClientEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="formClientEmail" name="email"
                                maxlength="255" required>
                        </div>
                        <div class="col-md-6">
                            <label for="formClientTel" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="formClientTel" name="tel" maxlength="30">
                        </div>
                        <div class="col-md-6">
                            <label for="formClientAdresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="formClientAdresse" name="adresse"
                                maxlength="255" required>
                        </div>
                        <div class="col-md-6">
                            <label for="formClientCp" class="form-label">Code postal</label>
                            <input type="text" class="form-control" id="formClientCp" name="cp"
                                maxlength="20" required>
                        </div>
                        <div class="col-md-6">
                            <label for="formClientVille" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="formClientVille" name="ville"
                                maxlength="100" required>
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

<div class="modal fade" id="deleteClientModal" tabindex="-1" aria-labelledby="deleteClientLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="deleteClientLabel">Confirmer la suppression</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimer <strong id="deleteClientName"></strong> ?
                <p class="text-muted small mb-0 mt-2">
                    Un client associé à un équipement ne pourra pas être supprimé.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <form action="#" method="post" id="deleteClientForm">
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
            jQuery('#clientsTable').DataTable({
                language: {
                    search: 'Rechercher :',
                    lengthMenu: '_MENU_ clients par page',
                    info: 'Affichage de _START_ à _END_ sur _TOTAL_ clients',
                    infoEmpty: 'Aucun client',
                    zeroRecords: 'Aucun résultat',
                    paginate: { first: 'Premier', last: 'Dernier', next: 'Suivant', previous: 'Précédent' }
                },
                pageLength: 10,
                order: [[1, 'asc']]
            });
        }

        document.querySelectorAll('.btn-details-client').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('detailsClientId').textContent = button.dataset.id;
                document.getElementById('detailsClientRaisonSociale').textContent = button.dataset.raisonSocial;
                document.getElementById('detailsClientEmail').textContent = button.dataset.email;
                document.getElementById('detailsClientTel').textContent = button.dataset.tel;
                document.getElementById('detailsClientAdresse').textContent = button.dataset.adresse;
                document.getElementById('detailsClientCp').textContent = button.dataset.cp;
                document.getElementById('detailsClientVille').textContent = button.dataset.ville;
            });
        });

        document.querySelectorAll('.btn-edit-client').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('clientFormLabel').textContent = 'Modifier le client';
                document.getElementById('clientForm').action = '/clients/' + button.dataset.id + '/modifier';
                document.getElementById('formClientRaisonSociale').value = button.dataset.raisonSocial;
                document.getElementById('formClientEmail').value = button.dataset.email;
                document.getElementById('formClientTel').value = button.dataset.tel;
                document.getElementById('formClientAdresse').value = button.dataset.adresse;
                document.getElementById('formClientCp').value = button.dataset.cp;
                document.getElementById('formClientVille').value = button.dataset.ville;
            });
        });

        document.getElementById('nouveauClientButton').addEventListener('click', function () {
            document.getElementById('clientForm').reset();
            document.getElementById('clientForm').action = '/clients/nouveau';
            document.getElementById('clientFormLabel').textContent = 'Nouveau client';
        });

        document.querySelectorAll('.btn-delete-client').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('deleteClientName').textContent = button.dataset.raisonSocial;
                document.getElementById('deleteClientForm').action =
                    '/clients/' + button.dataset.id + '/supprimer';
            });
        });
    });
</script>

<?php require_once Racine . '/../app/vue/layout/pied.php'; ?>