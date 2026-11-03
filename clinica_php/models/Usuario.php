<?php
require_once __DIR__ . '/../config/Database.php';

class Usuario
{
    /** Busca um usuário pelo e-mail (usado no login). */
    public function buscarPorEmail(string $email): ?array
    {
        $stmt = Database::conectar()->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }
}
