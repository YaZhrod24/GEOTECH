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

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titre) ? $titre : 'Geotech Manager'; ?></title>
    <link rel="stylesheet" href="/CSS/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Titan+One&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    require_once Racine . '/../app/vue/vueLogin.php';
    ?>
</body>

</html>