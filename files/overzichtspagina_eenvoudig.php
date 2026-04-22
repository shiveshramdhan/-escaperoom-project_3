<?php
require_once('../dbcon.php');

try {
  $stmt = $db_connection->query("SELECT * FROM riddles ORDER BY roomId, id");
  $riddles = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  // Get the latest team
  $teamStmt = $db_connection->query(
    "SELECT team_name FROM team_members GROUP BY team_name ORDER BY MAX(created_at) DESC LIMIT 1"
  );
  $activeTeam = $teamStmt->fetch(PDO::FETCH_ASSOC);
  $teamName = $activeTeam ? $activeTeam['team_name'] : '';
  
  // Get all user answers for this team
  $answersStmt = $db_connection->prepare(
    "SELECT riddle_id, user_answer FROM user_answers WHERE team_name = :team_name"
  );
  $answersStmt->execute([':team_name' => $teamName]);
  $userAnswersData = $answersStmt->fetchAll(PDO::FETCH_ASSOC);
  
  // Convert to associative array for easy lookup
  $userAnswers = [];
  foreach ($userAnswersData as $answer) {
    $userAnswers[$answer['riddle_id']] = $answer['user_answer'];
  }
} catch (PDOException $e) {
  die("Databasefout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overzichtspagina - Eenvoudig</title>
    <style>
        body {
            background-color: #35023f;
            color: white;
            margin: 0;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .riddle-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 2px solid #4b006e;
            border-radius: 8px;
            background-color: rgba(75, 0, 110, 0.3);
        }
        .riddle-text {
            font-weight: bold;
            color: #fff;
            margin-bottom: 12px;
            font-size: 1.1em;
        }
        .answer {
            margin-top: 8px;
            padding: 8px;
            border-radius: 5px;
            border-left: 4px solid;
        }
        .user-answer {
            background-color: rgba(0, 255, 0, 0.1);
            color: #90ee90;
            border-left-color: #90ee90;
        }
        .correct-answer {
            background-color: rgba(30, 144, 255, 0.1);
            color: #87ceeb;
            border-left-color: #87ceeb;
        }
        .no-answer {
            background-color: rgba(255, 100, 100, 0.1);
            color: #ff6b6b;
            border-left-color: #ff6b6b;
        }
        .debug {
            background-color: rgba(255, 0, 0, 0.3);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 2px solid red;
        }
    </style>
</head>
<body>
    <h1>Overzichtspagina - Eenvoudige versie</h1>
    
    <div class="debug">
        <h3>Debug Informatie:</h3>
        <p><strong>Team naam:</strong> "<?php echo htmlspecialchars($teamName); ?>"</p>
        <p><strong>Aantal antwoorden in database:</strong> <?php echo count($userAnswersData); ?></p>
        <p><strong>Alle antwoorden:</strong></p>
        <pre><?php echo json_encode($userAnswers, JSON_PRETTY_PRINT); ?></pre>
    </div>
    
    <?php if ($teamName): ?>
        <div style="background-color: rgba(75, 0, 110, 0.6); padding: 15px; border-radius: 8px; margin-bottom: 30px;">
            <strong>Team:</strong> <?php echo htmlspecialchars($teamName); ?>
        </div>
    <?php else: ?>
        <div style="background-color: rgba(255, 100, 100, 0.3); padding: 15px; border-radius: 8px; margin-bottom: 30px;">
            <strong>Waarschuwing:</strong> Geen team gevonden!
        </div>
    <?php endif; ?>

    <h2>Alle vragen:</h2>
    <?php
    $currentRoom = 0;
    foreach ($riddles as $riddle) {
        if ($riddle['roomId'] != $currentRoom) {
            if ($currentRoom != 0) echo '</div>';
            $currentRoom = $riddle['roomId'];
            echo '<h3>Kamer ' . $currentRoom . '</h3>';
        }
        echo '<div class="riddle-item">';
        echo '<div class="riddle-text">Vraag: ' . htmlspecialchars($riddle['riddle']) . '</div>';
        echo '<div class="answer correct-answer">✓ Correct antwoord: ' . htmlspecialchars($riddle['answer']) . '</div>';
        
        if (isset($userAnswers[$riddle['id']])) {
            echo '<div class="answer user-answer">✓ Jouw antwoord: ' . htmlspecialchars($userAnswers[$riddle['id']]) . '</div>';
        } else {
            echo '<div class="answer no-answer">✗ Jouw antwoord: niet ingevuld</div>';
        }
        echo '</div>';
    }
    ?>

    <br><br>
    <button onclick="window.location.href='index.php'">Terug naar start</button>
</body>
</html>