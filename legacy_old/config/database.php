<?php declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

class Database
{
    public PDO $connection;

    public function connect(): void
    {
        // Load the environment variables from the .env file.
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $db_dsn = $_ENV['DB_DSN'];
        $db_user = $_ENV['DB_USER'];
        $db_pass = $_ENV['DB_PASS'];

        echo 'Connecting to database: ' . $db_dsn . "\nWith credentials: " . $db_user . "\t" . $db_pass . "\n";

        try {
            $this->connection = new PDO($db_dsn, $db_user, $db_pass);
            $this->connection->exec('SET NAMES utf8');
        } catch (PDOException $exception) {
            throw new Exception('Could not connect to database');
        }
    }
}
?>