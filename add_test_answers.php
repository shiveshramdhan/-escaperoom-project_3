<?php
require_once('../dbcon.php');

try {
    // Voeg test antwoorden toe voor team 's'
    $testAnswers = [
        1 => 'de wind',
        2 => 'Schaduw',
        3 => 'Horloge',
        4 => 'Adem',
        5 => 'Piano',
        6 => 'Vuur'
    ];
    
    foreach ($testAnswers as $riddleId => $answer) {
        $stmt = $db_connection->prepare("INSERT INTO user_answers (team_name, riddle_id, user_answer) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE user_answer = VALUES(user_answer)");
        $stmt->execute(['s', $riddleId, $answer]);
    }
    
    echo "Test antwoorden toegevoegd voor team 's'!<br>";
    
    // Toon alle antwoorden
    $stmt = $db_connection->prepare("SELECT * FROM user_answers WHERE team_name = ? ORDER BY riddle_id");
    $stmt->execute(['s']);
    $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<br>Alle antwoorden:<br><pre>";
    print_r($answers);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Fout: " . $e->getMessage();
}
?>