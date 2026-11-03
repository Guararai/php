<?php
require_once __DIR__ . '/../config/Database.php';

/** Model dos tutores: todas as consultas usam prepared statements. */
class Tutor
{
    public function listar(): array
    {
        // Conta também quantos animais cada tutor tem
        $sql = 'SELECT t.*, COUNT(a.id) AS total_animais
                FROM tutores t
                LEFT JOIN animais a ON a.tutor_id = t.id
                GROUP BY t.id
                ORDER BY t.nome';
        return Database::conectar()->query($sql)->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $stmt = Database::conectar()->prepare('SELECT * FROM tutores WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function criar(array $d): int
    {
        $stmt = Database::conectar()->prepare(
            'INSERT INTO tutores (nome, telefone, email) VALUES (?, ?, ?)'
        );
        $stmt->execute([$d['nome'], $d['telefone'], $d['email'] ?: null]);
        return (int) Database::conectar()->lastInsertId();
    }

    public function atualizar(int $id, array $d): void
    {
        $stmt = Database::conectar()->prepare(
            'UPDATE tutores SET nome = ?, telefone = ?, email = ? WHERE id = ?'
        );
        $stmt->execute([$d['nome'], $d['telefone'], $d['email'] ?: null, $id]);
    }

    public function excluir(int $id): void
    {
        $stmt = Database::conectar()->prepare('DELETE FROM tutores WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function totalAnimais(int $id): int
    {
        $stmt = Database::conectar()->prepare('SELECT COUNT(*) FROM animais WHERE tutor_id = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    /** Valida os dados de um tutor. Retorna um array de erros (vazio = tudo certo). */
    public static function validar(array $d): array
    {
        $erros = [];
        if (trim($d['nome'] ?? '') === '')     $erros['nome'] = 'Informe o nome do tutor.';
        if (trim($d['telefone'] ?? '') === '') $erros['telefone'] = 'Informe o telefone.';
        if (($d['email'] ?? '') !== '' && !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            $erros['email'] = 'Informe um e-mail válido.';
        }
        return $erros;
    }
}
