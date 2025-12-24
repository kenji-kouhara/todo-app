<?php
// TODOアプリ API

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// OPTIONSリクエスト（CORS preflight）の処理
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config.php';

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    switch ($method) {
        case 'GET':
            // すべてのTODOを取得
            $stmt = $db->query('SELECT * FROM todos ORDER BY created_at DESC');
            $todos = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $todos]);
            break;

        case 'POST':
            // 新しいTODOを作成
            if (empty($input['title'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'タイトルは必須です']);
                exit();
            }

            $stmt = $db->prepare('INSERT INTO todos (title, completed) VALUES (?, ?)');
            $stmt->execute([trim($input['title']), 0]);

            $newId = $db->lastInsertId();
            $stmt = $db->prepare('SELECT * FROM todos WHERE id = ?');
            $stmt->execute([$newId]);
            $newTodo = $stmt->fetch();

            echo json_encode(['success' => true, 'data' => $newTodo]);
            break;

        case 'PUT':
            // TODOを更新（完了状態の切り替え）
            if (empty($input['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'IDは必須です']);
                exit();
            }

            $completed = isset($input['completed']) ? (int)(bool)$input['completed'] : null;

            if ($completed !== null) {
                $stmt = $db->prepare('UPDATE todos SET completed = ? WHERE id = ?');
                $stmt->execute([$completed, $input['id']]);
            }

            $stmt = $db->prepare('SELECT * FROM todos WHERE id = ?');
            $stmt->execute([$input['id']]);
            $updatedTodo = $stmt->fetch();

            if ($updatedTodo) {
                echo json_encode(['success' => true, 'data' => $updatedTodo]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'TODOが見つかりません']);
            }
            break;

        case 'DELETE':
            // TODOを削除
            if (empty($input['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'IDは必須です']);
                exit();
            }

            $stmt = $db->prepare('DELETE FROM todos WHERE id = ?');
            $stmt->execute([$input['id']]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => '削除しました']);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'TODOが見つかりません']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'メソッドが許可されていません']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
