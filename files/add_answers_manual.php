<!DOCTYPE html>
<html>
<head>
    <title>Handmatig antwoorden toevoegen</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        form { margin-bottom: 20px; }
        input, button { padding: 8px; margin: 5px; }
    </style>
</head>
<body>
    <h1>Handmatig antwoorden toevoegen voor team "s"</h1>
    
    <form method="post">
        <label>Riddle ID (1-9):</label>
        <input type="number" name="riddle_id" min="1" max="9" required>
        <br>
        <label>Jouw antwoord:</label>
        <input type="text" name="user_answer" required>
        <br>
        <button type="submit">Antwoord opslaan</button>
    </form>

    <?php
    require_once('../dbcon.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $riddle_id = (int)$_POST['riddle_id'];
        $user_answer = trim($_POST['user_answer']);
        
        if ($riddle_id >= 1 && $riddle_id <= 9 && !empty($user_answer)) {
            try {
                $stmt = $db_connection->prepare("INSERT INTO user_answers (team_name, riddle_id, user_answer) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE user_answer = VALUES(user_answer)");
                $stmt->execute(['s', $riddle_id, $user_answer]);
                echo "<p style='color: green;'>Antwoord opgeslagen!</p>";
            } catch (Exception $e) {
                echo "<p style='color: red;'>Fout: " . $e->getMessage() . "</p>";
            }
        }
    }

    // Toon huidige antwoorden
    try {
        $stmt = $db_connection->prepare("SELECT * FROM user_answers WHERE team_name = ? ORDER BY riddle_id");
        $stmt->execute(['s']);
        $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h2>Huidige antwoorden voor team 's':</h2>";
        if (count($answers) > 0) {
            echo "<ul>";
            foreach ($answers as $answer) {
                echo "<li>Riddle {$answer['riddle_id']}: {$answer['user_answer']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Geen antwoorden gevonden.</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Fout bij ophalen: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>