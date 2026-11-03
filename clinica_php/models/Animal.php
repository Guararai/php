<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Tutor.php';

/** Model dos animais. As consultas trazem junto o nome do tutor (JOIN). */
class Animal
{
    private const SELECT_COM_TUTOR =
        'SELECT a.*, t.nome AS tutor_nome, t.telefone AS tutor_telefone
         FROM animais a
         INNER JOIN tutores t ON t.id = a.tutor_id';

    public function listar(): array
    {
        return Database::conectar()->query(self::SELECT_COM_TUTOR . ' ORDER BY a.nome')->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $stmt = Database::conectar()->prepare(self::SELECT_COM_TUTOR . ' WHERE a.id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function criar(array $d): int
    {
        $stmt = Database::conectar()->prepare(
            'INSERT INTO animais (nome, especie, raca, idade, peso, imagem_url, tutor_id)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute($this->valores($d));
        return (int) Database::conectar()->lastInsertId();
    }

    public function atualizar(int $id, array $d): void
    {
        $stmt = Database::conectar()->prepare(
            'UPDATE animais SET nome = ?, especie = ?, raca = ?, idade = ?, peso = ?,
                    imagem_url = ?, tutor_id = ?
             WHERE id = ?'
        );
        $stmt->execute(array_merge($this->valores($d), [$id]));
    }

    public function excluir(int $id): void
    {
        $stmt = Database::conectar()->prepare('DELETE FROM animais WHERE id = ?');
        $stmt->execute([$id]);
    }

    /** Monta os valores na ordem das colunas; campos vazios viram NULL. */
    private function valores(array $d): array
    {
        $vazio = function ($campo) use ($d) {
            return trim((string) ($d[$campo] ?? '')) === '';
        };
        return [
            trim($d['nome']),
            trim($d['especie']),
            $vazio('raca') ? null : trim($d['raca']),
            $vazio('idade') ? null : (int) $d['idade'],
            $vazio('peso') ? null : (float) $d['peso'],
            $vazio('imagem_url') ? null : trim($d['imagem_url']),
            (int) $d['tutor_id'],
        ];
    }

    /**
     * Valida os dados de um animal. Usado tanto no painel quanto na API.
     * Retorna um array de erros (vazio = tudo certo).
     */
    public static function validar(array $d): array
    {
        $erros = [];
        $texto = function ($campo) use ($d) {
            return trim((string) ($d[$campo] ?? ''));
        };

        if ($texto('nome') === '')    $erros['nome'] = 'Informe o nome do animal.';
        if ($texto('especie') === '') $erros['especie'] = 'Informe a espécie.';
        if ($texto('idade') !== '' && filter_var($texto('idade'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
            $erros['idade'] = 'A idade deve ser um número inteiro (0 ou mais).';
        }
        if ($texto('peso') !== '' && (!is_numeric($texto('peso')) || (float) $texto('peso') <= 0)) {
            $erros['peso'] = 'O peso deve ser um número maior que zero.';
        }
        if ($texto('imagem_url') !== '' && !filter_var($texto('imagem_url'), FILTER_VALIDATE_URL)) {
            $erros['imagem_url'] = 'Informe uma URL válida.';
        }
        if ((int) ($d['tutor_id'] ?? 0) <= 0 || (new Tutor())->buscar((int) $d['tutor_id']) === null) {
            $erros['tutor_id'] = 'Escolha um tutor válido.';
        }
        return $erros;
    }
}
