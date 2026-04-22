<?php
// Test bestand om database connectie te controleren
require_once('../dbcon.php');

try {
    // Test database connectie
    $stmt = $db_connection->query("SELECT COUNT(*) FROM user_answers");
    $count = $stmt->fetchColumn();
    
    echo "Database connectie OK. Aantal antwoorden in database: " . $count;
    
    // Test insert
    $stmt = $db_connection->prepare("INSERT INTO user_answers (team_name, riddle_id, user_answer) VALUES (?, ?, ?)");
    $stmt->execute(['TEST_TEAM', 999, 'test_answer_' . time()]);
    
    echo "<br>Test insert gelukt!";
    
} catch (Exception $e) {
    echo "Fout: " . $e->getMessage();
}
?>