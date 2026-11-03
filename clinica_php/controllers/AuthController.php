<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController extends Controller
{
    /** GET mostra o formulário; POST confere e-mail e senha. */
    public function login(): void
    {
        if (!empty($_SESSION['usuario_id'])) {
            $this->redirecionar('index.php');
        }

        $erro = null;
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';
            $usuario = (new Usuario())->buscarPorEmail($email);

            // password_verify compara a senha digitada com o hash salvo no banco
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                session_regenerate_id(true); // novo id de sessão por segurança
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $this->redirecionar('index.php');
            }
            $erro = 'E-mail ou senha incorretos.';
        }

        $this->view('auth/login', ['titulo' => 'Entrar', 'erro' => $erro, 'email' => $email]);
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirecionar('index.php?controller=auth&acao=login');
    }
}
