<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\InterventionController;
use App\Controllers\ParcController;
use App\Middleware\AuthMiddleware;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$route = str_replace($scriptName, '', $uri);
if ($route === '') $route = '/';

// Route publique : Connexion
if ($route === '/login' && $method === 'POST') {
    AuthController::login();
    exit;
}

// Vérification du Token JWT pour toutes les routes suivantes
$decoded = AuthMiddleware::authenticate();
$currentUser = $decoded->user;

// ==========================================
// ROUTAGE PRINCIPAL
// ==========================================
switch ("$method $route") {
    // Interventions
    case 'GET /interventions':
        InterventionController::getAll($currentUser);
        break;
    case 'POST /interventions':
        InterventionController::create($currentUser);
        break;

    // Clients
    case 'GET /clients':
        ParcController::getClients($currentUser);
        break;
    case 'POST /clients':
        ParcController::createClient($currentUser);
        break;

    // Equipements
    case 'GET /equipements':
        ParcController::getEquipements($currentUser);
        break;
    case 'POST /equipements':
        ParcController::createEquipement($currentUser);
        break;

    // Techniciens
    case 'GET /techniciens':
        ParcController::getTechniciens($currentUser);
        break;

    default:
        // Gestion des routes dynamiques avec paramètres (Regex)
        if (preg_match('#^PUT /interventions/(\d+)/status$#', "$method $route", $matches)) {
            InterventionController::updateStatus($matches[1], $currentUser);
        } elseif (preg_match('#^PUT /clients/(\d+)$#', "$method $route", $matches)) {
            ParcController::updateClient($matches[1], $currentUser);
        } elseif (preg_match('#^DELETE /clients/(\d+)$#', "$method $route", $matches)) {
            ParcController::deleteClient($matches[1], $currentUser);
        } elseif (preg_match('#^PUT /equipements/(\d+)$#', "$method $route", $matches)) {
            ParcController::updateEquipement($matches[1], $currentUser);
        } elseif (preg_match('#^DELETE /equipements/(\d+)$#', "$method $route", $matches)) {
            ParcController::deleteEquipement($matches[1], $currentUser);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Route introuvable', 'uri' => $route]);
        }
        break;
}