<?php
// データベース接続設定

// 環境変数から読み取り、なければデフォルト値を使用
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'todo_app');
define('DB_USER', getenv('DB_USER') ?: 'todoapp');
define('DB_PASS', getenv('DB_PASS') ?: 'todoapp123');
define('DB_CHARSET', 'utf8mb4');

// データベース接続関数
function getDB() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'データベース接続エラー: ' . $e->getMessage()]);
        exit();
    }
}
?>
