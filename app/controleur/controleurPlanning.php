<?php

require_once Racine . '/../app/modele/bd.php';
require_once Racine . '/../app/modele/Interventions.php';

if ($action === 'Page') {
    $titre = 'Planning - Geotech';
    require_once Racine . '/../app/vue/vuePlanning.php';
    return;
}

if ($action !== 'Events') {
    http_response_code(404);
    return;
}

$convertirEnUtc = static function ($date): ?string {
    if (!is_string($date) || $date === '') {
        return null;
    }

    try {
        return (new DateTimeImmutable($date, new DateTimeZone('Europe/Paris')))
            ->setTimezone(new DateTimeZone('UTC'))
            ->format('Y-m-d H:i:s');
    } catch (Exception $exception) {
        return null;
    }
};

$debutUtc = $convertirEnUtc($_GET['start'] ?? null);
$finUtc = $convertirEnUtc($_GET['end'] ?? null);

if ($debutUtc === null || $finUtc === null || $debutUtc >= $finUtc) {
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Période de calendrier invalide.']);
    return;
}

$interventions = Intervention::getPourPlanning($debutUtc, $finUtc);
$fuseauUtc = new DateTimeZone('UTC');
$fuseauParis = new DateTimeZone('Europe/Paris');
$couleursStatut = [
    'OUVERTE' => '#00cebd',
    'EN_COURS' => '#0d6efd',
    'CLOTUREE' => '#00BF63'
];
$evenements = [];

foreach ($interventions as $intervention) {
    $couleur = $couleursStatut[$intervention->getStatut()] ?? '#6c757d';
    $dateDebut = (new DateTimeImmutable($intervention->getDateIntervention(), $fuseauUtc))
        ->setTimezone($fuseauParis);
    $dateFin = $intervention->getDateCloture()
        ? (new DateTimeImmutable($intervention->getDateCloture(), $fuseauUtc))->setTimezone($fuseauParis)
        : null;

    $evenements[] = [
        'id' => (string) $intervention->getId(),
        'title' => $intervention->getNomEquipement(),
        'start' => $dateDebut->format(DATE_ATOM),
        'end' => $dateFin?->format(DATE_ATOM),
        'backgroundColor' => $couleur,
        'borderColor' => $couleur,
        'extendedProps' => [
            'client' => $intervention->getClient(),
            'technicien' => trim($intervention->getTechnicienPrenom() . ' ' . $intervention->getTechnicienNom()),
            'statut' => $intervention->getStatut(),
            'description' => $intervention->getDescPanne()
        ]
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($evenements, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
