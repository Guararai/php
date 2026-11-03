<?php
/**
 * Roteador simples. Exemplos de URL:
 *   index.php?controller=animal&acao=listar
 *   index.php?controller=tutor&acao=editar&id=2
 */
session_start();

// Controllers que existem no sistema (nome na URL => classe)
$controllers = [
    'auth'   => 'AuthController',
    'tutor'  => 'TutorController',
    'animal' => 'AnimalController',
];

$nome = $_GET['controller'] ?? 'animal';
$acao = $_GET['acao'] ?? 'listar';

if (!isset($controllers[$nome])) {
    http_response_code(404);
    exit('Página não encontrada.');
}

require_once __DIR__ . '/controllers/' . $controllers[$nome] . '.php';
$controller = new $controllers[$nome]();

// is_callable só aceita métodos públicos, então os auxiliares (protected) ficam bloqueados
if (!is_callable([$controller, $acao])) {
    http_response_code(404);
    exit('Ação não encontrada.');
}

$controller->$acao();
