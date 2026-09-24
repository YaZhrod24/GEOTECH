<?php

$racine = dirname(__FILE__);
define('Racine', dirname(__FILE__));

session_start();

require_once "$racine/../app/controleur/controleurPrincipal.php";

$controleur = new ControleurPrincipal();

$controleur->gererRequete();
