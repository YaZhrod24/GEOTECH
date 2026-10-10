<?php
namespace App\Controllers;

use App\Config\Database;
use Firebase\JWT\JWT;

class AuthController
{
    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'Le corps de la requête doit être un JSON valide']);
            return;
        }

        if (empty($data['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email requis']);
            return;
        }

        if (empty($data['mdp'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Mot de passe requis']);
            return;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT id_employe, nom, prenom, email, mdp, role FROM employes WHERE email = ?');
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch();

        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'Identifiants invalides']);
            return;
        }

        if (!password_verify($data['mdp'], $user['mdp'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Identifiants invalides']);
            return;
        }

        if ($user['role'] !== 'TECHNICIEN') {
            http_response_code(403);
            echo json_encode(['error' => 'L’API mobile est réservée aux techniciens']);
            return;
        }

        $payload = [
            'iss' => 'geotech-api',
            'iat' => time(),
            'exp' => time() + (3600 * 8), // 8 heures
            'user' => [
                'id_employe' => $user['id_employe'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'role' => $user['role']
            ]
        ];

        require_once __DIR__ . '/../Config/config.php';
        $token = JWT::encode($payload, JWT_SECRET, 'HS256');

        echo json_encode([
            'token' => $token,
            'user' => $payload['user']
        ]);
    }

    public function getAccount($user)
    {
        if (!$this->estTechnicien($user)) {
            return;
        }

        $db = Database::getConnection();
        $statement = $db->prepare("
            SELECT id_employe, nom, prenom, email, tel, role
            FROM employes
            WHERE id_employe = ? AND role = 'TECHNICIEN'
        ");
        $statement->execute([$user->id_employe]);
        $compte = $statement->fetch();

        if (!$compte) {
            http_response_code(404);
            echo json_encode(['error' => 'Compte technicien introuvable']);
            return;
        }

        echo json_encode($compte);
    }

    public function updatePassword($user)
    {
        if (!$this->estTechnicien($user)) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (
            !is_array($data)
            || empty($data['ancien_mdp'])
            || empty($data['nouveau_mdp'])
            || !is_string($data['ancien_mdp'])
            || !is_string($data['nouveau_mdp'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => 'Les mots de passe actuel et nouveau sont obligatoires']);
            return;
        }

        $db = Database::getConnection();
        $statement = $db->prepare("
            SELECT mdp
            FROM employes
            WHERE id_employe = ? AND role = 'TECHNICIEN'
        ");
        $statement->execute([$user->id_employe]);
        $motDePasseHash = $statement->fetchColumn();

        if ($motDePasseHash === false) {
            http_response_code(404);
            echo json_encode(['error' => 'Compte technicien introuvable']);
            return;
        }

        if (!password_verify($data['ancien_mdp'], $motDePasseHash)) {
            http_response_code(400);
            echo json_encode(['error' => 'Le mot de passe actuel est incorrect']);
            return;
        }

        $nouveauHash = password_hash($data['nouveau_mdp'], PASSWORD_ARGON2ID);
        $statement = $db->prepare("
            UPDATE employes
            SET mdp = ?
            WHERE id_employe = ? AND role = 'TECHNICIEN'
        ");
        $statement->execute([$nouveauHash, $user->id_employe]);

        echo json_encode(['message' => 'Mot de passe modifié']);
    }

    private function estTechnicien($user): bool
    {
        if (($user->role ?? null) !== 'TECHNICIEN' || empty($user->id_employe)) {
            http_response_code(403);
            echo json_encode(['error' => 'Cette action est réservée aux techniciens']);
            return false;
        }

        return true;
    }
}