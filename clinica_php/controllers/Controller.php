<?php
/** Escapa um texto antes de mostrar no HTML (evita XSS). */
function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/** Classe base com funções que todos os controllers usam. */
abstract class Controller
{
    /** Mostra uma view dentro do layout (cabeçalho + conteúdo + rodapé). */
    protected function view(string $arquivo, array $dados = []): void
    {
        extract($dados); // transforma ['titulo' => 'x'] na variável $titulo
        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/' . $arquivo . '.php';
        require __DIR__ . '/../views/layout/footer.php';
    }

    protected function redirecionar(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    /** Bloqueia quem não está logado e manda para a tela de login. */
    protected function exigirLogin(): void
    {
        if (empty($_SESSION['usuario_id'])) {
            $this->redirecionar('index.php?controller=auth&acao=login');
        }
    }

    /** Guarda uma mensagem para mostrar na próxima página (sucesso ou erro). */
    protected function mensagem(string $tipo, string $texto): void
    {
        $_SESSION['mensagem'] = ['tipo' => $tipo, 'texto' => $texto];
    }

    protected function exigirPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido.');
        }
    }
}
