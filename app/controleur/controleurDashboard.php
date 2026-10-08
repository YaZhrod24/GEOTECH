<?php

// Appel des fichiers nécessaires 
require_once Racine . '/../app/modele/InterventionsDAO.php';

// Récupération des données via le DAO
$interventionsDAO = new InterventionsDAO();

// Correspondance exacte des variables attendues par la vueAccueil.php
$totalAujourdhui = $interventionsDAO->countAujourdhui();
$countEnAttente  = $interventionsDAO->countParStatut('En attente');
$countEnCours    = $interventionsDAO->countParStatut('En cours');
$countCloturees  = $interventionsDAO->countParStatut('Clôturée'); // Modifie si le texte exact en base est différent (ex: 'OUVERTE', etc.)

$interventionsDuJour = $interventionsDAO->getInterventionsDuJourComplet();

// Appel du script de vue
require_once Racine . '/../app/vue/layout/entete.php';
require_once Racine . '/../app/vue/vueAccueil.php';
require_once Racine . '/../app/vue/layout/pied.php';