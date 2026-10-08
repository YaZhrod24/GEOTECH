<?php

require_once Racine . '/../app/modele/bd.php';
require_once Racine . '/../app/modele/Interventions.php';

$toutesLesInterventions = Intervention::getAll();
$fuseauUtc = new DateTimeZone('UTC');
$fuseauParis = new DateTimeZone('Europe/Paris');
$aujourdHui = (new DateTimeImmutable('now', $fuseauParis))->format('Y-m-d');
$interventionsDuJour = [];
$totalAujourdhui = 0;
$countEnAttente = 0;
$countEnCours = 0;
$countCloturees = 0;

foreach ($toutesLesInterventions as $intervention) {
    $dateInterventionParis = (new DateTimeImmutable($intervention->getDateIntervention(), $fuseauUtc))
        ->setTimezone($fuseauParis);
    if ($dateInterventionParis->format('Y-m-d') === $aujourdHui) {
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