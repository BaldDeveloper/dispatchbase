<?php
// Database.php — Reusable PDO-based Data Access Layer for MySQL

class Database {
    private PDO $pdo;

    public function __construct() {
        // Load environment variables
        $this->loadEnv(__DIR__ . '/../.env');
        $db_host = getenv('DB_HOST') ?: '127.0.0.1';
        $db_user = getenv('DB_USER') ?: 'root';
        $db_pass = getenv('DB_PASS') ?: '';
        $db_name = getenv('DB_NAME') ?: 'test';
        $log_file = __DIR__ . '/db_error.log';

        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, $db_user, $db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 5,
            ]);
        } catch (PDOException $e) {
            $error = "Database connection failed: " . $e->getMessage();
            file_put_contents($log_file, "[" . date('Y-m-d H:i:s') . "] $error\n", FILE_APPEND);
            throw new RuntimeException($error);
        }
    }

    private function loadEnv(string $envPath): void {
        if (!file_exists($envPath)) return;
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
            list($name, $value) = array_map('trim', explode('=', $line, 2));
            if (!getenv($name)) putenv("$name=$value");
        }
    }

    // Generic query executor
    public function query(string $sql, array $params = []): array {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Insert with auto-increment ID return
    public function insert(string $sql, array $params): int {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$this->pdo->lastInsertId();
    }

    // Update or delete
    public function execute(string $sql, array $params): int {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    // Transaction support
    public function beginTransaction(): void {
        $this->pdo->beginTransaction();
    }

    public function commit(): void {
        $this->pdo->commit();
    }

    public function rollback(): void {
        $this->pdo->rollBack();
    }
}