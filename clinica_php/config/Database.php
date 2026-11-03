<?php
/**
 * Classe responsável pela conexão com o MySQL usando PDO.
 * Usa o padrão "conexão única": a conexão é criada uma vez e reaproveitada.
 */
class Database
{
    // Dados padrão do XAMPP (usuário root sem senha)
    private const HOST    = 'localhost';
    private const BANCO   = 'clinica_veterinaria';
    private const USUARIO = 'root';
    private const SENHA   = '';

    private static $conexao = null; // guarda a conexão PDO

    public static function conectar(): PDO
    {
        if (self::$conexao === null) {
            $dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::BANCO . ';charset=utf8mb4';
            self::$conexao = new PDO($dsn, self::USUARIO, self::SENHA, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // erros viram exceções
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // resultados como array associativo
                PDO::ATTR_EMULATE_PREPARES   => false,                  // prepared statements reais
            ]);
        }
        return self::$conexao;
    }
}
