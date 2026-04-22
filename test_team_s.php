<?php
require_once('../dbcon.php');

try {
    // Test 1: Voeg een test antwoord toe
    $stmt = $db_connection->prepare("INSERT INTO user_answers (team_name, riddle_id, user_answer) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE user_answer = VALUES(user_answer)");
    $stmt->execute(['s', 1, 'Test antwoord voor team s']);
    
    echo "Test antwoord toegevoegd voor team 's'!<br>";
    
    // Test 2: Haal alle antwoorden op voor team 's'
    $stmt = $db_connection->prepare("SELECT * FROM user_answers WHERE team_name = ?");
    $stmt->execute(['s']);
    $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<br>Alle antwoorden voor team 's':<br><pre>";
    print_r($answers);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Fout: " . $e->getMessage();
}
?>