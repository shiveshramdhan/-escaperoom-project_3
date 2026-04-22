<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../dbcon.php');

// Log voor debugging
$logFile = __DIR__ . '/../save_answer.log';
file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] Request received' . PHP_EOL, FILE_APPEND);

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] Not a POST request' . PHP_EOL, FILE_APPEND);
        echo 'Error: Only POST requests allowed';
        exit;
    }

    // Get data from POST
    $riddle_id = $_POST['riddle_id'] ?? null;
    $user_answer = $_POST['user_answer'] ?? null;
    $team_name = $_POST['team_name'] ?? null;

    file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] POST data: riddle_id=' . $riddle_id . ', user_answer=' . $user_answer . ', team_name=' . $team_name . PHP_EOL, FILE_APPEND);

    if (!$riddle_id || !$user_answer || !$team_name) {
        file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] Missing fields' . PHP_EOL, FILE_APPEND);
        echo 'Error: Missing required fields';
        exit;
    }

    $riddle_id = (int)$riddle_id;

    file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] Saving: team=' . $team_name . ', riddle=' . $riddle_id . ', answer=' . $user_answer . PHP_EOL, FILE_APPEND);

    // Check if answer already exists for this team and riddle
    $checkStmt = $db_connection->prepare(
        "SELECT id FROM user_answers WHERE team_name = :team_name AND riddle_id = :riddle_id"
    );
    $checkStmt->execute([':team_name' => $team_name, ':riddle_id' => $riddle_id]);
    $existing = $checkStmt->fetch();

    if ($existing) {
        // Update existing answer
        file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] Updating existing answer' . PHP_EOL, FILE_APPEND);
        $stmt = $db_connection->prepare(
            "UPDATE user_answers SET user_answer = :user_answer, created_at = CURRENT_TIMESTAMP WHERE team_name = :team_name AND riddle_id = :riddle_id"
        );
    } else {
        // Insert new answer
        file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] Inserting new answer' . PHP_EOL, FILE_APPEND);
        $stmt = $db_connection->prepare(
            "INSERT INTO user_answers (team_name, riddle_id, user_answer) VALUES (:team_name, :riddle_id, :user_answer)"
        );
    }

    $stmt->execute([
        ':team_name' => $team_name,
        ':riddle_id' => $riddle_id,
        ':user_answer' => $user_answer
    ]);

    file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] Success!' . PHP_EOL, FILE_APPEND);
    
    // Check if this is an AJAX request
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    if ($isAjax) {
        // Return JSON for AJAX
        echo json_encode(['success' => true, 'message' => 'Answer saved']);
    } else {
        // Redirect for form submission
        $referer = $_SERVER['HTTP_REFERER'] ?? '../files/index.php';
        header('Location: ' . $referer);
    }
    exit;
    
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] Error: ' . $errorMsg . PHP_EOL, FILE_APPEND);
    echo 'Database error: ' . $errorMsg;
}
?>
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
