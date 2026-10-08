<?php

class ControleurPrincipal
{
    public function gererRequete()
    {
        // Récupère l'URL demandée, ex : "/utilisateur/15?test=1"
        $url = $_SERVER['REQUEST_URI'];

        // echo ($url);

        // Supprime les paramètres après "?", ex : "/utilisateur/15?test=1" -> "/utilisateur/15"
        $url = explode('?', $url)[0];

        // Supprime les "/" inutiles au début et à la fin
        // "/utilisateur/15/" -> "utilisateur/15"
        $url = trim($url, '/');

        // Tableau associant chaque URL à la méthode à appeler
        // 'url' => 'méthode'
        // {id} représente un integer
        $routes = [
            '' => 'dashboard',
            'dashboard' => 'dashboard',
            'interventions' => 'interventions',
            'intervention/{id}' => 'interventionView',
            'intervention/modifier/{id}' => 'interventionModify',
            'login' => 'login',
            'utilisateur/{id}' => 'afficherUtilisateur',
            'utilisateur/{id}/modifier' => 'modifierUtilisateur',
            'utilisateur/{id}/supprimer' => 'supprimerUtilisateur',
            'cgu' => 'cgu',
            'confidentialite' => 'confidentialite',
            'support' => 'support',
            'planning' => 'planning',
            'planning/events' => 'planningEvents',
            'techniciens' => 'techniciens',
            'techniciens/nouveau' => 'nouveauTechnicien',
            'techniciens/{id}/modifier' => 'modifierTechnicien',
            'techniciens/{id}/supprimer' => 'supprimerTechnicien',
            'equipements' => 'equipements',
            'equipements/nouveau' => 'nouvelEquipement',
            'equipements/{id}/modifier' => 'modifierEquipement',
            'equipements/{id}/supprimer' => 'supprimerEquipement'
        ];

        // Parcourt toutes les routes pour trouver celle qui correspond à l'URL
        foreach ($routes as $route => $action) {

            // Transforme {id} en règle acceptant un ou plusieurs chiffres
            // "utilisateur/{id}" -> "utilisateur/([0-9]+)"
            $routeAvecRegex = str_replace('{id}', '([0-9]+)', $route);

            // Vérifie si l'URL correspond exactement à la route
            // $correspondance récupère également les valeurs trouvées
            if (preg_match('#^' . $routeAvecRegex . '$#', $url, $correspondance)) {

                // Récupère l'id s'il existe, sinon vaut null
                $parametre = $correspondance[1] ?? null;

                /* 
                $correspondance = [
                    0 => 'utilisateur/25',
                    1 => '25'
                ];
                */

                // Appelle la méthode dans la classe correspondant à la route
                // Ex : "afficherUtilisateur" + 15 -> afficherUtilisateur(15)
                if ($action !== 'login' && empty($_SESSION['user_id'])) {
                    $_SESSION['url_apres_login'] = $_SERVER['REQUEST_URI'];
                    $this->login();
                    return;
                }

                $this->$action($parametre);

                // Route trouvée : inutile de continuer la boucle
                return;
            }
        }

        // Aucune route ne correspond à l'URL
        require_once Racine . '/../app/vue/layout/entete.php';
        require_once Racine . '/../app/vue/erreur/404.php';
        require_once Racine . '/../app/vue/layout/pied.php';


    }



    private function dashboard()
    {
        $titre = "Dashboard - Geotech";
        require_once Racine . '/../app/controleur/controleurDashboard.php';
    }

    private function interventions()
    {
        $titre = "Interventions - Geotech";
        $action = "List";
        require_once Racine . '/../app/controleur/controleurIntervention.php';
    }

    private function interventionView($id)
    {
        $titre = "Intervention - Geotech";
        $id = (int) $id; // Convertir l'ID en entier pour plus de sécurité
        $action = "View";
        require_once Racine . '/../app/controleur/controleurIntervention.php';
    }

    private function interventionModify($id)
    {
        $titre = "Modifier Intervention - Geotech";
        $id = (int) $id; // Convertir l'ID en entier pour plus de sécurité
        $action = "Modify";
        require_once Racine . '/../app/controleur/controleurIntervention.php';
    }

    private function login()
    {
        $titre = "Connexion - Geotech";

        require_once Racine . '/../app/modele/bd.php';

        require_once Racine . '/../app/controleur/controleurLogin.php';
    }


    private function afficherUtilisateur($id)
    {
        echo "Utilisateur numéro " . $id;
    }


    private function modifierUtilisateur($id)
    {
        echo "Modifier utilisateur numéro " . $id;
    }


    private function supprimerUtilisateur($id)
    {
        echo "Supprimer utilisateur numéro " . $id;
    }


    private function cgu()
    {
        require_once Racine . '/../app/vue/cgu.php';
    }

    private function confidentialite()
    {
        $titre = "Confidentialité - Geotech";
        require_once Racine . '/../app/vue/confidentialite.php';
    }

    private function support()
    {
        $titre = "Support technique - Geotech";
        require_once Racine . '/../app/vue/support.php';
    }

    private function planning()
    {
        $titre = "Planning - Geotech";
        require_once Racine . '/../app/vue/vuePlanning.php';
    }

    private function planningEvents()
    {
        require_once Racine . '/../app/modele/bd.php';

        $start = $this->datePourRequete($_GET['start'] ?? '');
        $end = $this->datePourRequete($_GET['end'] ?? '');

        if ($start === null || $end === null) {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Période de calendrier invalide.']);
            return;
        }

        $connexion = new PDO_Connexion();
        $db = $connexion->getConnection();
        $requete = $db->prepare(
            'SELECT i.id_intervention, i.desc_panne, i.date_intervention, i.date_cloture,
                    i.statut, e.nom AS equipement, c.raison_social AS client,
                    CONCAT(emp.prenom, " ", emp.nom) AS technicien
             FROM interventions i
             INNER JOIN equipements e ON e.id_equipement = i.id_equipement
             INNER JOIN clients c ON c.id_client = e.id_client
             INNER JOIN employes emp ON emp.id_employe = i.id_employe
             WHERE i.date_intervention >= :start
               AND i.date_intervention < :end
             ORDER BY i.date_intervention ASC'
        );
        $requete->execute([
            'start' => $start,
            'end' => $end
        ]);

        $events = [];
        foreach ($requete->fetchAll() as $intervention) {
            $color = $this->couleurStatut($intervention['statut']);
            $events[] = [
                'id' => (string) $intervention['id_intervention'],
                'title' => $intervention['equipement'],
                'start' => date(DATE_ATOM, strtotime($intervention['date_intervention'])),
                'end' => $intervention['date_cloture']
                    ? date(DATE_ATOM, strtotime($intervention['date_cloture']))
                    : null,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'client' => $intervention['client'],
                    'technicien' => $intervention['technicien'],
                    'statut' => $intervention['statut'],
                    'description' => $intervention['desc_panne']
                ]
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($events);
    }

    private function techniciens()
    {
        $titre = "Techniciens - Geotech";
        require_once Racine . '/../app/modele/bd.php';

        $connexion = new PDO_Connexion();
        $db = $connexion->getConnection();
        $requete = $db->query(
            "SELECT id_employe, nom, prenom, email, tel
             FROM employes
             WHERE role = 'TECHNICIEN'
             ORDER BY nom, prenom"
        );
        $techniciens = $requete->fetchAll();

        require_once Racine . '/../app/vue/vueTechnicien.php';
    }

    private function nouveauTechnicien()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /techniciens');
            return;
        }

        require_once Racine . '/../app/modele/bd.php';
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $tel = trim($_POST['tel'] ?? '');
        $mdp = $_POST['mdp'] ?? '';

        if ($nom === '' || $prenom === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $mdp === '') {
            $_SESSION['technicien_erreur'] = 'Nom, prénom, email et mot de passe sont obligatoires.';
            header('Location: /techniciens');
            return;
        }

        $connexion = new PDO_Connexion();
        $db = $connexion->getConnection();
        $requete = $db->prepare(
            'INSERT INTO employes (nom, prenom, email, mdp, tel, role)
             VALUES (:nom, :prenom, :email, :mdp, :tel, "TECHNICIEN")'
        );
        $requete->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mdp' => password_hash($mdp, PASSWORD_DEFAULT),
            'tel' => $tel !== '' ? $tel : null
        ]);

        header('Location: /techniciens');
    }

    private function modifierTechnicien($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /techniciens');
            return;
        }

        require_once Racine . '/../app/modele/bd.php';
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $tel = trim($_POST['tel'] ?? '');
        $mdp = $_POST['mdp'] ?? '';

        if ($nom === '' || $prenom === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['technicien_erreur'] = 'Nom, prénom et email valides sont obligatoires.';
            header('Location: /techniciens');
            return;
        }

        $connexion = new PDO_Connexion();
        $db = $connexion->getConnection();
        $champs = 'nom = :nom, prenom = :prenom, email = :email, tel = :tel';
        $parametres = [
            'id' => (int) $id,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'tel' => $tel !== '' ? $tel : null
        ];

        if ($mdp !== '') {
            $champs .= ', mdp = :mdp';
            $parametres['mdp'] = password_hash($mdp, PASSWORD_DEFAULT);
        }

        $requete = $db->prepare(
            "UPDATE employes SET $champs WHERE id_employe = :id AND role = 'TECHNICIEN'"
        );
        $requete->execute($parametres);

        header('Location: /techniciens');
    }

    private function supprimerTechnicien($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /techniciens');
            return;
        }

        require_once Racine . '/../app/modele/bd.php';
        $connexion = new PDO_Connexion();
        $db = $connexion->getConnection();
        $requete = $db->prepare(
            "DELETE FROM employes WHERE id_employe = :id AND role = 'TECHNICIEN'"
        );
        $requete->execute(['id' => (int) $id]);

        header('Location: /techniciens');
    }

    private function equipements()
    {
        $titre = "Équipements - Geotech";
        require_once Racine . '/../app/modele/bd.php';
        $db = (new PDO_Connexion())->getConnection();
        $equipements = $db->query(
            'SELECT e.id_equipement, e.nom, e.type, e.num_serie, e.id_client,
                    c.raison_social AS client
             FROM equipements e
             INNER JOIN clients c ON c.id_client = e.id_client
             ORDER BY c.raison_social, e.nom'
        )->fetchAll();
        $clients = $db->query(
            'SELECT id_client, raison_social FROM clients ORDER BY raison_social'
        )->fetchAll();

        require_once Racine . '/../app/vue/VueEquipements.php';
    }

    private function nouvelEquipement()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /equipements');
            return;
        }

        require_once Racine . '/../app/modele/bd.php';
        $nom = trim($_POST['nom'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $numSerie = trim($_POST['num_serie'] ?? '');
        $idClient = filter_input(INPUT_POST, 'id_client', FILTER_VALIDATE_INT);

        if ($nom === '' || $type === '' || $numSerie === '' || !$idClient) {
            $_SESSION['equipement_erreur'] = 'Tous les champs de l’équipement sont obligatoires.';
            header('Location: /equipements');
            return;
        }

        $db = (new PDO_Connexion())->getConnection();
        $requete = $db->prepare(
            'INSERT INTO equipements (nom, type, num_serie, id_client)
             VALUES (:nom, :type, :num_serie, :id_client)'
        );
        $requete->execute([
            'nom' => $nom,
            'type' => $type,
            'num_serie' => $numSerie,
            'id_client' => $idClient
        ]);

        header('Location: /equipements');
    }

    private function modifierEquipement($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /equipements');
            return;
        }

        require_once Racine . '/../app/modele/bd.php';
        $nom = trim($_POST['nom'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $numSerie = trim($_POST['num_serie'] ?? '');
        $idClient = filter_input(INPUT_POST, 'id_client', FILTER_VALIDATE_INT);

        if ($nom === '' || $type === '' || $numSerie === '' || !$idClient) {
            $_SESSION['equipement_erreur'] = 'Tous les champs de l’équipement sont obligatoires.';
            header('Location: /equipements');
            return;
        }

        $db = (new PDO_Connexion())->getConnection();
        $requete = $db->prepare(
            'UPDATE equipements
             SET nom = :nom, type = :type, num_serie = :num_serie, id_client = :id_client
             WHERE id_equipement = :id'
        );
        $requete->execute([
            'id' => (int) $id,
            'nom' => $nom,
            'type' => $type,
            'num_serie' => $numSerie,
            'id_client' => $idClient
        ]);

        header('Location: /equipements');
    }

    private function supprimerEquipement($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /equipements');
            return;
        }

        require_once Racine . '/../app/modele/bd.php';
        $db = (new PDO_Connexion())->getConnection();
        $requete = $db->prepare('DELETE FROM equipements WHERE id_equipement = :id');
        $requete->execute(['id' => (int) $id]);

        header('Location: /equipements');
    }

    private function datePourRequete(string $date): ?string
    {
        if ($date === '') {
            return null;
        }

        try {
            return (new DateTime($date))->format('Y-m-d H:i:s');
        } catch (Exception $exception) {
            return null;
        }
    }

    private function couleurStatut(string $statut): string
    {
        return match ($statut) {
            'CLOTUREE' => '#198754',
            'ANNULEE' => '#6c757d',
            'EN COURS' => '#0d6efd',
            default => '#00bf63'
        };
    }
}
