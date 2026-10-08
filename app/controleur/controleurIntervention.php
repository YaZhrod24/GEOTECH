<?php

require_once Racine . '/../app/modele/bd.php';
require_once Racine . '/../app/modele/Interventions.php';
require_once Racine . '/../app/modele/Equipements.php';
require_once Racine . '/../app/modele/Employe.php';
$statutsAutorises = ['OUVERTE', 'EN_COURS', 'CLOTUREE'];
$lireDate = static function ($valeur): ?string {
    if (!is_string($valeur)) {
        return null;
    }

    $date = DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $valeur);
    $erreurs = DateTimeImmutable::getLastErrors();

    if (
        $date === false
        || (is_array($erreurs) && ($erreurs['warning_count'] > 0 || $erreurs['error_count'] > 0))
        || $date->format('Y-m-d\TH:i') !== $valeur
    ) {
        return null;
    }

    return $date->format('Y-m-d H:i:s');
};
$redirigerAvecMessage = static function (int $id, string $message): void {
    $_SESSION['intervention_message'] = $message;
    header('Location: /intervention/' . $id);
    exit;
};
$existeDansOptions = static function ($id, array $options): bool {
    return is_int($id)
        && $id > 0
        && in_array($id, array_map(static fn($option): int => (int) $option->getId(), $options), true);
};

if ($action === 'List') {
    $erreur = null;
    $valeursFormulaire = $_POST;
    $equipements = Equipement::getAll();
    $techniciens = Employe::getTechniciens();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formulaire'] ?? '') === 'creation') {
        $description = trim((string) ($_POST['desc_panne'] ?? ''));
        $dateIntervention = $lireDate($_POST['date_intervention'] ?? null);
        $idEquipement = filter_var($_POST['id_equipement'] ?? null, FILTER_VALIDATE_INT);
        $idTechnicien = filter_var($_POST['id_employe'] ?? null, FILTER_VALIDATE_INT);
        $equipementExiste = $existeDansOptions($idEquipement, $equipements);
        $technicienExiste = $existeDansOptions($idTechnicien, $techniciens);

        if ($description === '' || $dateIntervention === null || !$equipementExiste || !$technicienExiste) {
            $erreur = 'Veuillez renseigner une description, une date valide, un équipement et un technicien.';
        } else {
            $rapport = trim((string) ($_POST['rapport'] ?? ''));
            $nouvelleIntervention = new Intervention(
                0,
                $description,
                $dateIntervention,
                null,
                'OUVERTE',
                $rapport === '' ? null : $rapport,
                (int) $idEquipement,
                (int) $idTechnicien
            );
            $nouvelleIntervention->creer();

            $_SESSION['intervention_message'] = 'L’intervention a été créée.';
            header('Location: /interventions');
            exit;
        }
    }

    $message = $_SESSION['intervention_message'] ?? null;
    unset($_SESSION['intervention_message']);
    $interventions = Intervention::getAll();
    require_once Racine . '/../app/vue/layout/entete.php';
    require_once Racine . '/../app/vue/vueIntervention.php';
    require_once Racine . '/../app/vue/layout/pied.php';
} elseif ($action === 'View') {
    $intervention = Intervention::getById((int) $id);

    if ($intervention === null) {
        http_response_code(404);
        require_once Racine . '/../app/vue/layout/entete.php';
        require_once Racine . '/../app/vue/erreur/404.php';
        require_once Racine . '/../app/vue/layout/pied.php';
    } else {
        $erreur = null;
        $valeursModification = [];
        $equipements = Equipement::getAll();
        $techniciens = Employe::getTechniciens();
        $message = $_SESSION['intervention_message'] ?? null;
        unset($_SESSION['intervention_message']);
        require_once Racine . '/../app/vue/layout/entete.php';
        require_once Racine . '/../app/vue/vueInterventionDetail.php';
        require_once Racine . '/../app/vue/layout/pied.php';
    }
} elseif ($action === 'Modify') {
    $intervention = Intervention::getById((int) $id);

    if ($intervention === null) {
        http_response_code(404);
        require_once Racine . '/../app/vue/layout/entete.php';
        require_once Racine . '/../app/vue/erreur/404.php';
        require_once Racine . '/../app/vue/layout/pied.php';
    } elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /intervention/' . (int) $id);
        exit;
    } else {
        $formulaire = $_POST['formulaire'] ?? '';
        $erreur = null;
        $valeursModification = $_POST;

        if ($formulaire === 'statut') {
            $statut = $_POST['statut'] ?? '';
            if (!is_string($statut) || !in_array($statut, $statutsAutorises, true)) {
                $erreur = 'Le statut sélectionné est invalide.';
            } else {
                $intervention->setStatut($statut);
                if (!$intervention->enregistrerStatut()) {
                    http_response_code(404);
                    $intervention = null;
                    $erreur = 'Cette intervention n’existe plus.';
                } else {
                    $redirigerAvecMessage((int) $id, 'Le statut de l’intervention a été mis à jour.');
                }
            }
        } elseif ($formulaire === 'informations') {
            $description = trim((string) ($_POST['desc_panne'] ?? ''));
            $dateIntervention = $lireDate($_POST['date_intervention'] ?? null);
            $idEquipement = filter_var($_POST['id_equipement'] ?? null, FILTER_VALIDATE_INT);
            $idTechnicien = filter_var($_POST['id_employe'] ?? null, FILTER_VALIDATE_INT);
            $equipements = Equipement::getAll();
            $techniciens = Employe::getTechniciens();
            $equipementExiste = $existeDansOptions($idEquipement, $equipements);
            $technicienExiste = $existeDansOptions($idTechnicien, $techniciens);

            if ($intervention->getStatut() !== 'OUVERTE') {
                $erreur = 'Les informations ne peuvent être modifiées que si l’intervention est ouverte.';
            } elseif ($description === '' || $dateIntervention === null || !$equipementExiste || !$technicienExiste) {
                $erreur = 'Veuillez renseigner une description, une date valide, un équipement et un technicien.';
            } else {
                $rapport = trim((string) ($_POST['rapport'] ?? ''));
                $intervention->setDescPanne($description);
                $intervention->setDateIntervention($dateIntervention);
                $intervention->setRapport($rapport === '' ? null : $rapport);
                $intervention->setIdEquipement((int) $idEquipement);
                $intervention->setIdEmploye((int) $idTechnicien);
                $modifie = $intervention->enregistrerInformations();

                if ($modifie) {
                    $redirigerAvecMessage((int) $id, 'Les informations de l’intervention ont été mises à jour.');
                }

                $intervention = Intervention::getById((int) $id);
                $erreur = $intervention !== null && $intervention->getStatut() !== 'OUVERTE'
                    ? 'Les informations ne peuvent être modifiées que si l’intervention est ouverte.'
                    : 'Cette intervention n’existe plus.';
            }
        } else {
            $erreur = 'La demande de modification est invalide.';
        }

        if ($intervention === null) {
            http_response_code(404);
            require_once Racine . '/../app/vue/layout/entete.php';
            require_once Racine . '/../app/vue/erreur/404.php';
            require_once Racine . '/../app/vue/layout/pied.php';
        } else {
            $message = null;
            if (!isset($equipements)) {
                $equipements = Equipement::getAll();
            }
            if (!isset($techniciens)) {
                $techniciens = Employe::getTechniciens();
            }
            require_once Racine . '/../app/vue/layout/entete.php';
            require_once Racine . '/../app/vue/vueInterventionDetail.php';
            require_once Racine . '/../app/vue/layout/pied.php';
        }
    }
} else {
    http_response_code(404);
    require_once Racine . '/../app/vue/layout/entete.php';
    require_once Racine . '/../app/vue/erreur/404.php';
    require_once Racine . '/../app/vue/layout/pied.php';
}