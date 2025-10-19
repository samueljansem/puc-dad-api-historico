<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

require_once 'AlunoValidator.php';
require_once 'HistoricoController.php';

$matricula = $_GET['matricula'] ?? null;

if (!$matricula) {
  http_response_code(400);
  echo json_encode(['error' => 'Matrícula é obrigatória']);
  exit;
}

$validator = new AlunoValidator();

if (!$validator->isValidFormat($matricula)) {
  http_response_code(400);
  echo json_encode(['error' => 'Formato de matrícula inválido', 'matricula' => $matricula]);
  exit;
}

if (!$validator->exists($matricula)) {
  http_response_code(404);
  echo json_encode(['error' => 'Aluno não encontrado', 'matricula' => $matricula]);
  exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$controller = new HistoricoController();

switch ($method) {
  case 'GET':
    $controller->getHistorico($matricula);
    break;
  case 'POST':
    $controller->createHistorico($matricula);
    break;
  case 'PUT':
    $controller->updateHistorico($matricula);
    break;
  case 'PATCH':
    $controller->updateHistorico($matricula);
    break;
  case 'DELETE':
    $controller->deleteHistorico($matricula);
    break;
  default:
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    break;
}

?>