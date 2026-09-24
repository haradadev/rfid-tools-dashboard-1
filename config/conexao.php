<?php
// ===== CONFIGURAÇÃO DO BANCO DE DADOS =====
// Troque os valores abaixo pelos dados reais do seu banco (XAMPP/phpMyAdmin, etc.)
// Todo o sistema usa PDO — não misture com mysqli em outros arquivos.

$host    = 'localhost';    // geralmente 'localhost' no XAMPP/WAMP
$porta   = 3306;         // porta padrão do MySQL
$banco   = 'rfid_ferramentas';   // nome do banco de dados (veja database/schema.sql)
$usuario = 'root';         // usuário do MySQL (padrão do XAMPP é 'root')
$senha   = '123456';              // senha do MySQL (padrão do XAMPP é vazio '')

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // lança erro em vez de falhar silenciosamente
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    // Em produção, não exiba o erro real na tela — apenas registre em log
    die('Erro ao conectar com o banco de dados: ' . $e->getMessage());
}
