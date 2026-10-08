<?php
// Appel du modèle des clients
require_once Racine . '/../app/modele/ClientsDAO.php';

$clientsDAO = new ClientsDAO();
$lesClients = $clientsDAO->getTousLesClients();

// Appel du layout et de la vue des clients
require_once Racine . '/../app/vue/layout/entete.php';
require_once Racine . '/../app/vue/vueClients.php';
require_once Racine . '/../app/vue/layout/pied.php';