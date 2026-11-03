<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Tutor.php';

class TutorController extends Controller
{
    private Tutor $model;

    public function __construct()
    {
        $this->exigirLogin(); // todas as ações daqui exigem login
        $this->model = new Tutor();
    }

    public function listar(): void
    {
        $this->view('tutores/listar', ['titulo' => 'Tutores', 'tutores' => $this->model->listar()]);
    }

    public function novo(): void
    {
        $this->view('tutores/form', ['titulo' => 'Novo tutor', 'tutor' => [], 'erros' => []]);
    }

    public function editar(): void
    {
        $tutor = $this->model->buscar((int) ($_GET['id'] ?? 0));
        if (!$tutor) {
            $this->mensagem('danger', 'Tutor não encontrado.');
            $this->redirecionar('index.php?controller=tutor&acao=listar');
        }
        $this->view('tutores/form', ['titulo' => 'Editar tutor', 'tutor' => $tutor, 'erros' => []]);
    }

    /** Recebe o formulário: se tiver id, atualiza; se não, cadastra. */
    public function salvar(): void
    {
        $this->exigirPost();
        $dados = [
            'id'       => (int) ($_POST['id'] ?? 0),
            'nome'     => trim($_POST['nome'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
        ];

        $erros = Tutor::validar($dados);
        if ($erros) {
            // Volta para o formulário mantendo o que foi digitado
            $titulo = $dados['id'] ? 'Editar tutor' : 'Novo tutor';
            $this->view('tutores/form', ['titulo' => $titulo, 'tutor' => $dados, 'erros' => $erros]);
            return;
        }

        if ($dados['id']) {
            $this->model->atualizar($dados['id'], $dados);
            $this->mensagem('success', 'Tutor atualizado.');
        } else {
            $this->model->criar($dados);
            $this->mensagem('success', 'Tutor cadastrado.');
        }
        $this->redirecionar('index.php?controller=tutor&acao=listar');
    }

    public function excluir(): void
    {
        $this->exigirPost();
        $id = (int) ($_POST['id'] ?? 0);

        // A chave estrangeira impede excluir tutor com animais; avisamos antes
        if ($this->model->totalAnimais($id) > 0) {
            $this->mensagem('danger', 'Este tutor tem animais cadastrados. Exclua ou transfira os animais primeiro.');
        } else {
            $this->model->excluir($id);
            $this->mensagem('success', 'Tutor excluído.');
        }
        $this->redirecionar('index.php?controller=tutor&acao=listar');
    }
}
