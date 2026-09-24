<?php
namespace App\Controllers;

use App\Config\Database;
use App\Middleware\AuthMiddleware;
use Firebase\JWT\JWT;
use PDO;

class AuthController {
    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);

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
        $stmt = $db->prepare('SELECT id_employe, nom, prenom, email, mdp, role FROM Employe WHERE email = ?');
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
}