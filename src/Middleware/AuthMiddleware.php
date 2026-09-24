<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthMiddleware {
    require_once __DIR__ . '/../Config/config.php';
            return JWT::decode($matches[1], new Key(JWT_SECRET, 'HS256'));

    public function authenticate(): ?object {
        $headers = getallheaders();
        $authHeader = null;
        
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
        } elseif (isset($headers['authorization'])) {
            $authHeader = $headers['authorization'];
        }

        if (!$authHeader) {
            http_response_code(401);
            echo json_encode(['error' => 'Accès refusé : token manquant']);
            exit;
        }

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['error' => 'Accès refusé : format du token invalide']);
            exit;
        }

        try {
            return JWT::decode($matches[1], new Key(self::$secretKey, 'HS256'));
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token invalide ou expiré']);
            exit;
        }
    }
}