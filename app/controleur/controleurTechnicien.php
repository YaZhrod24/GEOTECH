<?php

require_once Racine . '/../app/modele/bd.php';
require_once Racine . '/../app/modele/Employe.php';

if ($action === 'List') {
    $titre = 'Techniciens - Geotech';
    $techniciens = Employe::getTechniciens();
    require_once Racine . '/../app/vue/vueTechnicien.php';
    return;
}

if ($action === 'Create' || $action === 'Update') {
    $id = $action === 'Update' ? (int) $id : 0;
    $nom = trim((string) ($_POST['nom'] ?? ''));
    $prenom = trim((string) ($_POST['prenom'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $telephone = trim((string) ($_POST['tel'] ?? ''));
    $motDePasse = (string) ($_POST['mdp'] ?? '');
    $creation = $action === 'Create';

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
        || $nom === ''
        || $prenom === ''
        || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || ($creation && $motDePasse === '')
    ) {
        $_SESSION['technicien_erreur'] = $creation
            ? 'Nom, prénom, email valide et mot de passe sont obligatoires.'
            : 'Nom, prénom et email valides sont obligatoires.';
        header('Location: /techniciens');
        return;
    }

    $technicien = new Employe(
        $id,
        $nom,
        $prenom,
        $email,
        '',
        $telephone === '' ? null : $telephone,
        'TECHNICIEN'
    );

    if ($creation) {
        $technicien->creerTechnicien($motDePasse);
    } else {
        $technicien->modifierTechnicien($motDePasse === '' ? null : $motDePasse);
    }

    header('Location: /techniciens');
    return;
}

if ($action === 'Delete') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /techniciens');
        return;
    }

    $technicien = new Employe((int) $id, '', '', '', '', null, 'TECHNICIEN');
    $technicien->supprimerTechnicien();
    header('Location: /techniciens');
    return;
}

http_response_code(404);
