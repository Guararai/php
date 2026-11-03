<?php
/**
 * Configuração comum dos endpoints da API:
 * resposta em JSON, CORS liberado e funções de resposta.
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// O navegador manda um OPTIONS antes do POST (preflight); basta responder OK
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

/** Envia os dados como JSON com o código HTTP e encerra. */
function responder($dados, int $codigo = 200): void
{
    http_response_code($codigo);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function erro(string $mensagem, int $codigo, array $extra = []): void
{
    responder(['erro' => $mensagem] + $extra, $codigo);
}

/** Converte os tipos vindos do banco (o DECIMAL chega como texto). */
function formatarAnimal(array $a): array
{
    $a['peso'] = $a['peso'] !== null ? (float) $a['peso'] : null;
    return $a;
}

// Qualquer erro inesperado (ex.: banco desligado) vira um JSON 500
set_exception_handler(function (Throwable $e) {
    erro('Erro interno no servidor.', 500);
});
