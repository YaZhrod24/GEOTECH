<?php

// Appel des fichiers nécessaire 
require_once Racine . '/../app/modele/EmployeDAO.php';

// recuperation des donnees

// appel des fonctions permettant de recuperer les donnees utiles a l'affichage (modele)

// traitement si necessaire des donnees recuperees

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {

        $error = 'Veuillez remplir tous les champs.';

    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = 'Adresse email invalide.';

    } else {

        $employeDAO = new EmployeDAO();
        $result = $employeDAO->login($email, $password);

        if (!$result) {
            $error = 'Une erreur est survenue.';

        } elseif (is_string($result)) {
            $error = $result;

        } else {

            $employe = $result;

            // stockage des informations de l'employé dans la session
            session_regenerate_id(true);
            $_SESSION['user_id'] = $employe->getId();
            $_SESSION['email'] = $employe->getEmail();
            $_SESSION['role'] = $employe->getRole();

            $destination = $_SESSION['url_apres_login'] ?? '/';
            unset($_SESSION['url_apres_login']);

            if (!is_string($destination) || $destination === '' || $destination[0] !== '/' || substr($destination, 0, 2) === '//' || strpos($destination, '\\') !== false) {
                $destination = '/';
            }

            header('Location: ' . $destination);
            exit;
        }
    }
}

// appel du script de vue qui permet de gerer l'affichage des donnees

require_once Racine . '/../app/vue/layout/entete.php';
require_once Racine . '/../app/vue/vueLogin.php';
require_once Racine . '/../app/vue/layout/pied.php';