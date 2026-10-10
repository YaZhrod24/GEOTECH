<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\InterventionController;
use App\Middleware\AuthMiddleware;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, PUT, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$route = $uri;
if ($basePath !== '' && str_starts_with($uri, $basePath . '/')) {
    $route = substr($uri, strlen($basePath));
} elseif ($basePath !== '' && $uri === $basePath) {
    $route = '/';
}
$route = '/' . trim($route, '/');

// Route publique : Connexion
if ($route === '/login' && $method === 'POST') {
    (new AuthController())->login();
    exit;
}

// Vérification du Token JWT pour toutes les routes suivantes
$decoded = (new AuthMiddleware())->authenticate();
$currentUser = $decoded->user;

// ==========================================
// ROUTAGE PRINCIPAL
// ==========================================
switch ("$method $route") {
    // Planning terrain du technicien
    case 'GET /interventions':
        InterventionController::getAll($currentUser);
        break;
    case 'GET /interventions/jour':
        InterventionController::getToday($currentUser);
        break;
    case 'GET /compte':
        (new AuthController())->getAccount($currentUser);
        break;
    case 'PUT /compte/mot-de-passe':
        (new AuthController())->updatePassword($currentUser);
        break;

    default:
        // Gestion des routes dynamiques avec paramètres (Regex)
        if (preg_match('#^PUT /interventions/(\d+)/status$#', "$method $route", $matches)) {
            InterventionController::updateStatus($matches[1], $currentUser);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Route introuvable', 'uri' => $route]);
        }
        break;
}