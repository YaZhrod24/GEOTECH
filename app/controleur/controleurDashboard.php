<?php

require_once Racine . '/../app/modele/bd.php';
require_once Racine . '/../app/modele/Interventions.php';

$toutesLesInterventions = Intervention::getAll();
$aujourdHui = date('Y-m-d');
$interventionsDuJour = [];
$totalAujourdhui = 0;
$countEnAttente = 0;
$countEnCours = 0;
$countCloturees = 0;

foreach ($toutesLesInterventions as $intervention) {
    if (date('Y-m-d', strtotime($intervention->getDateIntervention())) === $aujourdHui) {
        $interventionsDuJour[] = $intervention;
        $totalAujourdhui++;
    }

    if ($intervention->getStatut() === 'OUVERTE') {
        $countEnAttente++;
    } elseif ($intervention->getStatut() === 'EN_COURS') {
        $countEnCours++;
    } elseif ($intervention->getStatut() === 'CLOTUREE') {
        $countCloturees++;
    }
}

require_once Racine . '/../app/vue/layout/entete.php';
require_once Racine . '/../app/vue/vueAccueil.php';
require_once Racine . '/../app/vue/layout/pied.php';