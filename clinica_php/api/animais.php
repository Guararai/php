<?php
/**
 * GET  api/animais.php        -> lista todos os animais (com o nome do tutor)
 * GET  api/animais.php?id=1   -> retorna um animal
 * POST api/animais.php        -> cadastra um animal (JSON no corpo)
 */
require_once __DIR__ . '/base.php';
require_once __DIR__ . '/../models/Animal.php';

$model = new Animal();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $animal = $model->buscar((int) $_GET['id']);
            if (!$animal) {
                erro('Animal não encontrado.', 404);
            }
            responder(formatarAnimal($animal));
        }
        responder(array_map('formatarAnimal', $model->listar()));

    case 'POST':
        // Lê o JSON enviado pelo app
        $dados = json_decode(file_get_contents('php://input'), true);
        if (!is_array($dados)) {
            erro('Envie um JSON válido no corpo da requisição.', 400);
        }

        $erros = Animal::validar($dados);
        if ($erros) {
            erro('Dados inválidos.', 400, ['campos' => $erros]);
        }

        $id = $model->criar($dados);
        responder(formatarAnimal($model->buscar($id)), 201); // 201 = criado

    default:
        erro('Método não permitido.', 405);
}
