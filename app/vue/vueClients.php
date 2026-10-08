<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Gestion des Clients</h2>
            <p class="text-muted">Aperçu et gestion de la liste des clients</p>
        </div>
        <a href="clients/nouveau" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> + Ajouter un client
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($lesClients)) : ?>
                            <?php foreach ($lesClients as $client) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($client['nomClient'] ?? $client['nom'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($client['prenomClient'] ?? $client['prenom'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($client['telephoneClient'] ?? $client['telephone'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($client['emailClient'] ?? $client['email'] ?? '') ?></td>
                                    <td class="text-end">
                                        <a href="clients/modifier/<?= $client['idClient'] ?? $client['id'] ?? 0 ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                                        <a href="clients/supprimer/<?= $client['idClient'] ?? $client['id'] ?? 0 ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce client ?');">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucun client trouvé dans la base de données.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>