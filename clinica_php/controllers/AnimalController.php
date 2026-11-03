<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Animal.php';
require_once __DIR__ . '/../models/Tutor.php';

class AnimalController extends Controller
{
    /** @var Animal */
    private $model;

    public function __construct()
    {
        $this->exigirLogin();
        $this->model = new Animal();
    }

    public function listar(): void
    {
        $this->view('animais/listar', ['titulo' => 'Animais', 'animais' => $this->model->listar()]);
    }

    public function novo(): void
    {
        $this->formulario('Novo animal', [], []);
    }

    public function editar(): void
    {
        $animal = $this->model->buscar((int) ($_GET['id'] ?? 0));
        if (!$animal) {
            $this->mensagem('danger', 'Animal não encontrado.');
            $this->redirecionar('index.php?controller=animal&acao=listar');
        }
        $this->formulario('Editar animal', $animal, []);
    }

    public function salvar(): void
    {
        $this->exigirPost();
        $campos = ['nome', 'especie', 'raca', 'idade', 'peso', 'imagem_url', 'tutor_id'];
        $dados = ['id' => (int) ($_POST['id'] ?? 0)];
        foreach ($campos as $campo) {
            $dados[$campo] = trim($_POST[$campo] ?? '');
        }
        $dados['peso'] = str_replace(',', '.', $dados['peso']); // aceita 4,5 ou 4.5

        $erros = Animal::validar($dados);
        if ($erros) {
            $this->formulario($dados['id'] ? 'Editar animal' : 'Novo animal', $dados, $erros);
            return;
        }

        if ($dados['id']) {
            $this->model->atualizar($dados['id'], $dados);
            $this->mensagem('success', 'Animal atualizado.');
        } else {
            $this->model->criar($dados);
            $this->mensagem('success', 'Animal cadastrado.');
        }
        $this->redirecionar('index.php?controller=animal&acao=listar');
    }

    public function excluir(): void
    {
        $this->exigirPost();
        $this->model->excluir((int) ($_POST['id'] ?? 0));
        $this->mensagem('success', 'Animal excluído.');
        $this->redirecionar('index.php?controller=animal&acao=listar');
    }

    /** O formulário precisa da lista de tutores para montar o select. */
    private function formulario(string $titulo, array $animal, array $erros): void
    {
        $this->view('animais/form', [
            'titulo'  => $titulo,
            'animal'  => $animal,
            'erros'   => $erros,
            'tutores' => (new Tutor())->listar(),
        ]);
    }
}
