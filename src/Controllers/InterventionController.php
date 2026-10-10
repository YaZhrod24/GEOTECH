<?php
namespace App\Controllers;

use App\Config\Database;

class InterventionController {

    // GET /interventions : le technicien voit uniquement ses interventions
    public static function getAll($user) {
        if (!self::estTechnicien($user)) {
            return;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT i.*, c.raison_social AS client_nom, c.adresse, c.ville, c.tel AS client_tel,
                   e.nom AS equipement_nom, e.type AS equipement_type, e.num_serie
            FROM interventions i
            JOIN equipements e ON i.id_equipement = e.id_equipement
            JOIN clients c ON e.id_client = c.id_client
            WHERE i.id_employe = ?
            ORDER BY i.date_intervention ASC
        ");
        $stmt->execute([$user->id_employe]);

        echo json_encode($stmt->fetchAll());
    }

    // GET /interventions/jour : interventions du jour, selon le fuseau horaire français
    public static function getToday($user) {
        if (!self::estTechnicien($user)) {
            return;
        }

        $fuseauParis = new \DateTimeZone('Europe/Paris');
        $fuseauUtc = new \DateTimeZone('UTC');
        $debutJour = new \DateTimeImmutable('today', $fuseauParis);
        $debutUtc = $debutJour->setTimezone($fuseauUtc)->format('Y-m-d H:i:s');
        $finUtc = $debutJour->modify('+1 day')->setTimezone($fuseauUtc)->format('Y-m-d H:i:s');

        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT i.*, c.raison_social AS client_nom, c.adresse, c.ville, c.tel AS client_tel,
                   e.nom AS equipement_nom, e.type AS equipement_type, e.num_serie
            FROM interventions i
            JOIN equipements e ON i.id_equipement = e.id_equipement
            JOIN clients c ON e.id_client = c.id_client
            WHERE i.id_employe = ?
              AND i.date_intervention >= ?
              AND i.date_intervention < ?
            ORDER BY i.date_intervention ASC
        ");
        $stmt->execute([$user->id_employe, $debutUtc, $finUtc]);

        echo json_encode($stmt->fetchAll());
    }

    private static function estTechnicien($user): bool {
        if (($user->role ?? null) !== 'TECHNICIEN' || empty($user->id_employe)) {
            http_response_code(403);
            echo json_encode(['error' => 'Cette action est réservée aux techniciens']);
            return false;
        }

        return true;
    }

    // PUT /interventions/{id}/status : le technicien modifie le rapport et avance le statut
    public static function updateStatus($id, $user) {
        if (!self::estTechnicien($user)) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data) || (!isset($data['statut']) && !array_key_exists('rapport', $data))) {
            http_response_code(400);
            echo json_encode(['error' => 'Le corps JSON doit contenir un statut ou un rapport']);
            return;
        }

        $db = Database::getConnection();

        // Récupérer l'intervention
        $stmt = $db->prepare("SELECT * FROM interventions WHERE id_intervention = ?");
        $stmt->execute([$id]);
        $intervention = $stmt->fetch();

        if (!$intervention) {
            http_response_code(404);
            echo json_encode(['error' => 'Intervention non trouvée']);
            return;
        }

        if ((int) $intervention['id_employe'] !== (int) $user->id_employe) {
            http_response_code(403);
            echo json_encode(['error' => 'Cette intervention ne vous est pas assignée']);
            return;
        }

        if ($intervention['statut'] === 'CLOTUREE') {
            http_response_code(403);
            echo json_encode(['error' => 'Intervention clôturée : modification interdite']);
            return;
        }

        if (array_key_exists('rapport', $data) && $data['rapport'] !== null && !is_string($data['rapport'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Le rapport doit être un texte ou null']);
            return;
        }

        $statut = $intervention['statut'];
        if (isset($data['statut'])) {
            $statut = $data['statut'];
        }

        $rapport = $intervention['rapport'];
        if (isset($data['rapport'])) {
            $rapport = $data['rapport'];
        }

        $dateCloture = $intervention['date_cloture'];
        if (!in_array($statut, ['OUVERTE', 'EN_COURS', 'CLOTUREE'], true)) {
            http_response_code(400);
            echo json_encode(['error' => 'Statut invalide']);
            return;
        }

        $transitionsAutorisees = [
            'OUVERTE' => ['OUVERTE', 'EN_COURS'],
            'EN_COURS' => ['EN_COURS', 'CLOTUREE']
        ];
        if (!in_array($statut, $transitionsAutorisees[$intervention['statut']] ?? [], true)) {
            http_response_code(400);
            echo json_encode(['error' => 'Le statut ne peut pas revenir en arrière']);
            return;
        }

        if ($statut === 'CLOTUREE') {
            if (!$intervention['date_cloture']) {
                $dateCloture = date('Y-m-d H:i:s');
            }
        }

        $update = $db->prepare("UPDATE interventions SET statut = ?, rapport = ?, date_cloture = ? WHERE id_intervention = ?");
        $update->execute([$statut, $rapport, $dateCloture, $id]);

        echo json_encode(['message' => 'Statut mis à jour']);
    }
}