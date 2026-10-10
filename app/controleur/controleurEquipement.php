<?php

require_once Racine . '/../app/modele/bd.php';
require_once Racine . '/../app/modele/Equipements.php';
require_once Racine . '/../app/modele/Clients.php';

if ($action === 'List') {
    $titre = 'Équipements - Geotech';
    $equipements = Equipement::getAll();
    $clients = Client::getAll();
    require_once Racine . '/../app/vue/VueEquipements.php';
    return;
}

if ($action === 'Create' || $action === 'Update') {
    $creation = $action === 'Create';
    $id = $creation ? 0 : (int) $id;
    $nom = trim((string) ($_POST['nom'] ?? ''));
    $type = trim((string) ($_POST['type'] ?? ''));
    $numSerie = trim((string) ($_POST['num_serie'] ?? ''));
    $idClient = filter_var($_POST['id_client'] ?? null, FILTER_VALIDATE_INT);

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
        || $nom === ''
        || $type === ''
        || $numSerie === ''
        || $idClient === false
        || $idClient === null
        || $idClient < 1
    ) {
        $_SESSION['equipement_erreur'] = 'Tous les champs de l’équipement sont obligatoires.';
        header('Location: /equipements');
        return;
    }

    $equipement = new Equipement($id, $nom, $type, $numSerie, $idClient);
    try {
        $enregistre = $creation ? $equipement->creer() : $equipement->modifier();
    } catch (DomainException $exception) {
        $_SESSION['equipement_erreur'] = $exception->getMessage();
        header('Location: /equipements');
        return;
    }

    if (!$enregistre) {
        $_SESSION['equipement_erreur'] = 'L’équipement demandé n’existe pas ou n’a pas pu être enregistré.';
    }

    header('Location: /equipements');
    return;
}

if ($action === 'Delete') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /equipements');
        return;
    }

    try {
        $supprime = (new Equipement((int) $id, '', '', '', 0))->supprimer();
    } catch (DomainException $exception) {
        $_SESSION['equipement_erreur'] = $exception->getMessage();
        header('Location: /equipements');
        return;
    }

    if (!$supprime) {
        $_SESSION['equipement_erreur'] = 'L’équipement demandé n’existe pas.';
    }

    header('Location: /equipements');
    return;
}

http_response_code(404);