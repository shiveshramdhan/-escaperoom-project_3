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
  
  // Debug: toon alle teams
  $allTeamsStmt = $db_connection->query("SELECT team_name FROM team_members GROUP BY team_name");
  $allTeams = $allTeamsStmt->fetchAll(PDO::FETCH_ASSOC);
  
  // Save any answers from localStorage to database
  if ($teamName && isset($_POST['answers'])) {
    $answersData = json_decode($_POST['answers'], true);
    if (is_array($answersData)) {
      foreach ($answersData as $riddleId => $userAnswer) {
        $riddleId = (int)$riddleId;
        if ($riddleId > 0 && !empty($userAnswer)) {
          // Check if answer exists
          $checkStmt = $db_connection->prepare(
            "SELECT id FROM user_answers WHERE team_name = ? AND riddle_id = ?"
          );
          $checkStmt->execute([$teamName, $riddleId]);
          $existing = $checkStmt->fetch();
          
          if ($existing) {
            // Update
            $stmt = $db_connection->prepare(
              "UPDATE user_answers SET user_answer = ?, created_at = CURRENT_TIMESTAMP WHERE team_name = ? AND riddle_id = ?"
            );
            $stmt->execute([$userAnswer, $teamName, $riddleId]);
          } else {
            // Insert
            $stmt = $db_connection->prepare(
              "INSERT INTO user_answers (team_name, riddle_id, user_answer) VALUES (?, ?, ?)"
            );
            $stmt->execute([$teamName, $riddleId, $userAnswer]);
          }
        }
      }
    }
  }
  
  // Debug info
  $debugInfo = [
    'team_name' => $teamName,
    'all_teams' => array_column($allTeams, 'team_name'),
    'user_answers_count' => count($userAnswersData),
    'user_answers' => $userAnswers
  ];
  
} catch (PDOException $e) {
  die("Databasefout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overzichtspagina - Debug</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background-color: #35023f;
            color: white;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .overview {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
            background-color: #35023f;
            min-height: 100vh;
        }
        .overview h1 {
            color: #ffd700;
            font-size: 2.5em;
            margin-bottom: 20px;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }
        .debug-info {
            background-color: rgba(255, 0, 0, 0.3);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 2px solid red;
        }
        .team-info {
            background-color: rgba(75, 0, 110, 0.6);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #ffd700;
        }
        .room-section {
            margin-bottom: 40px;
        }
        .room-title {
            font-size: 1.8em;
            margin-bottom: 15px;
            color: #ffd700;
            border-bottom: 3px solid #ffd700;
            padding-bottom: 10px;
            text-shadow: 0 0 8px rgba(255, 215, 0, 0.3);
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
        .button-row {
            display: flex;
            gap: 15px;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        button {
            flex: 1;
            min-width: 200px;
            padding: 12px 20px;
            background-color: #7e0000;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1em;
            transition: background-color 0.2s;
        }
        button:hover {
            background-color: #5a0000;
        }
    </style>
</head>
<body>
    <div class="overview">
        <h1>Overzichtspagina - Debug Versie</h1>
        
        <div class="debug-info">
            <h3>Debug Informatie:</h3>
            <pre><?php echo json_encode($debugInfo, JSON_PRETTY_PRINT); ?></pre>
        </div>
        
        <?php if ($teamName): ?>
            <div class="team-info">
                <strong>Team:</strong> <?php echo htmlspecialchars($teamName); ?>
            </div>
        <?php else: ?>
            <div class="team-info" style="color: #ff6b6b;">
                <strong>Waarschuwing:</strong> Geen team gevonden. De antwoorden kunnen niet worden getoond.
            </div>
        <?php endif; ?>

        <?php
        $currentRoom = 0;
        foreach ($riddles as $riddle) {
            if ($riddle['roomId'] != $currentRoom) {
                if ($currentRoom != 0) echo '</div>';
                $currentRoom = $riddle['roomId'];
                echo '<div class="room-section">';
                echo '<h2 class="room-title">Kamer ' . $currentRoom . '</h2>';
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
        if ($currentRoom != 0) echo '</div>';
        ?>

        <div class="button-row">
            <button onclick="window.location.href='index.php'">← Terug naar start</button>
            <button onclick="window.location.href='../admin/add_review.php'">Review achterlaten →</button>
        </div>
    </div>

    <form id="saveAnswersForm" method="post" style="display: none;">
        <input type="hidden" name="answers" id="answersInput">
    </form>

    <script>
        // Save answers from localStorage to database
        const answers = JSON.parse(localStorage.getItem('userAnswers') || '{}');
        if (Object.keys(answers).length > 0) {
            document.getElementById('answersInput').value = JSON.stringify(answers);
            document.getElementById('saveAnswersForm').submit();
        }
    </script>
</body>
</html>
