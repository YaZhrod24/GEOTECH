<?php

require_once Racine . '/../app/modele/bd.php';
require_once Racine . '/../app/modele/Clients.php';

if ($action === 'List') {
    $titre = 'Clients - Geotech';
    $clients = Client::getAll();
    require_once Racine . '/../app/vue/vueClient.php';
    return;
}

if ($action === 'Create' || $action === 'Update') {
    $creation = $action === 'Create';
    $id = $creation ? 0 : (int) $id;
    $raisonSociale = trim((string) ($_POST['raison_social'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $telephone = trim((string) ($_POST['tel'] ?? ''));
    $adresse = trim((string) ($_POST['adresse'] ?? ''));
    $codePostal = trim((string) ($_POST['cp'] ?? ''));
    $ville = trim((string) ($_POST['ville'] ?? ''));

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
        || (!$creation && $id < 1)
        || $raisonSociale === ''
        || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || $adresse === ''
        || $codePostal === ''
        || $ville === ''
    ) {
        $_SESSION['client_erreur'] =
            'La raison sociale, un email valide, l’adresse, le code postal et la ville sont obligatoires.';
        header('Location: /clients');
        return;
    }

    $client = new Client(
        $creation ? 0 : $id,
        $raisonSociale,
        $email,
        $telephone,
        $adresse,
        $codePostal,
        $ville
    );

    try {
        $enregistre = $creation ? $client->creer() : $client->modifier();
    } catch (DomainException $exception) {
        $_SESSION['client_erreur'] = $exception->getMessage();
        header('Location: /clients');
        return;
    }

    if (!$enregistre) {
        $_SESSION['client_erreur'] = 'Le client demandé n’existe pas ou n’a pas pu être enregistré.';
    } else {
        $_SESSION['client_succes'] = $creation
            ? 'Le client a été créé.'
            : 'Les informations du client ont été mises à jour.';
    }

    header('Location: /clients');
    return;
}

if ($action === 'Delete') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || (int) $id < 1) {
        header('Location: /clients');
        return;
    }

    $client = new Client((int) $id, '', '', '', '', '', '');
    try {
        $supprime = $client->supprimer();
    } catch (DomainException $exception) {
        $_SESSION['client_erreur'] = $exception->getMessage();
        header('Location: /clients');
        return;
    }

    if ($supprime) {
        $_SESSION['client_succes'] = 'Le client a été supprimé.';
    } else {
        $_SESSION['client_erreur'] = 'Le client demandé n’existe pas.';
    }

    header('Location: /clients');
    return;
}

http_response_code(404);