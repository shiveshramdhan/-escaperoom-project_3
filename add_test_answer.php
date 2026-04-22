<?php
require_once('../dbcon.php');

try {
    // Voeg een test antwoord toe
    $stmt = $db_connection->prepare("INSERT INTO user_answers (team_name, riddle_id, user_answer) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE user_answer = VALUES(user_answer)");
    $stmt->execute(['TestTeam', 1, 'Mijn test antwoord']);
    
    echo "Test antwoord toegevoegd!";
    
    // Toon alle antwoorden
    $stmt = $db_connection->query("SELECT * FROM user_answers");
    $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<br><br>Alle antwoorden in database:<br><pre>";
    print_r($answers);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Fout: " . $e->getMessage();
}
?>