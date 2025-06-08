<?php
// Ponto de entrada da API
header("Content-Type: application/json");
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Database.php';
require_once __DIR__ . '/controllers/VagaController.php';
require_once __DIR__ . '/controllers/PessoaController.php';
require_once __DIR__ . '/controllers/CandidaturaController.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Validação do JSON de entrada
$input = file_get_contents('php://input');
if ($input && $method !== 'GET') {
    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        exit;
    }
} else {
    $data = [];
}

// Roteamento
switch ($path) {
    case '/vagas':
        if ($method !== 'POST') {
            http_response_code(404);
            exit;
        }
        (new VagaController())->create($data);
        break;
        
    case '/pessoas':
        if ($method !== 'POST') {
            http_response_code(404);
            exit;
        }
        (new PessoaController())->create($data);
        break;
        
    case '/candidaturas':
        if ($method !== 'POST') {
            http_response_code(404);
            exit;
        }
        (new CandidaturaController())->create($data);
        break;
        
    default:
        if (preg_match('/\/vagas\/([^\/]+)\/candidaturas\/ranking$/', $path, $matches)) {
            if ($method === 'GET') {
                (new CandidaturaController())->getRanking($matches[1]);
            } else {
                http_response_code(404);
            }
        } else {
            http_response_code(404);
        }
        break;
}
exit;