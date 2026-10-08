<?php

require_once Racine . '/../app/modele/bd.php';
require_once Racine . '/../app/modele/InterventionsDAO.php';

$interventionDAO = new InterventionDAO();

if ($action === 'List') {
    $interventions = $interventionDAO->getAll();
    require_once Racine . '/../app/vue/layout/entete.php';
    require_once Racine . '/../app/vue/vueIntervention.php';
    require_once Racine . '/../app/vue/layout/pied.php';
} elseif ($action === 'View') {
    $intervention = $interventionDAO->getById((int) $id);

    if ($intervention === null) {
        http_response_code(404);
        require_once Racine . '/../app/vue/layout/entete.php';
        require_once Racine . '/../app/vue/erreur/404.php';
        require_once Racine . '/../app/vue/layout/pied.php';
    } else {
        require_once Racine . '/../app/vue/layout/entete.php';
        require_once Racine . '/../app/vue/vueInterventionDetail.php';
        require_once Racine . '/../app/vue/layout/pied.php';
    }
} else {
    http_response_code(404);
    require_once Racine . '/../app/vue/layout/entete.php';
    require_once Racine . '/../app/vue/erreur/404.php';
    require_once Racine . '/../app/vue/layout/pied.php';
}