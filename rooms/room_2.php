<?php
require_once('../dbcon.php');

try {
  $stmt = $db_connection->query("SELECT * FROM riddles WHERE roomId = 2");
  $riddles = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $teamStmt = $db_connection->query(
    "SELECT team_name, GROUP_CONCAT(player_name SEPARATOR ', ') AS players
     FROM team_members
     GROUP BY team_name
     ORDER BY MAX(created_at) DESC
     LIMIT 1"
  );
  $activeTeam = $teamStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Databasefout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Escape Room 2</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .team-header {
      position: sticky;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1001;
      padding: 12px 18px;
      background: rgba(0, 0, 0, 0.82);
      color: #fff;
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      align-items: center;
      justify-content: flex-start;
      font-size: 0.95rem;
    }
    .team-header > div {
      margin-right: 18px;
    }
    .team-header strong {
      color: #ffd700;
    }
    .team-header a {
      color: #8ecae6;
      text-decoration: underline;
    }
    .team-header a:hover {
      text-decoration: none;
    }
    .timer {
      background-color: black !important;
      background-image: none !important;
      color: white;
    }
    #showBubblesHint {
      position: fixed;
      top: 120px;
      right: 20px;
      padding: 10px 15px;
      background-color: #cc6600;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      z-index: 1000;
    }
    #showBubblesHint:hover {
      background-color: #994d00;
    }
  </style>
</head>

<body class="room_2" data-next-room="room_3.php">
  <div class="team-header">
    <?php if ($activeTeam && $activeTeam['team_name']): ?>
      <div><span>Team:</span> <strong><?php echo htmlspecialchars($activeTeam['team_name']); ?></strong></div>
      <div><span>Spelers:</span> <strong><?php echo htmlspecialchars($activeTeam['players']); ?></strong></div>
      <div><a href="../files/teams.php">Wijzig</a></div>
    <?php else: ?>
      <div>Geen team gevonden. <a href="../files/teams.php">Maak een team</a></div>
    <?php endif; ?>
  </div>

  <?php
  $positions = [
    ['top' => '20%', 'left' => '15%'],
    ['top' => '45%', 'left' => '40%'],
    ['top' => '70%', 'left' => '65%']
  ];
  ?>

  <div class="container">
    <?php foreach ($riddles as $index => $riddle) : ?>
    <?php $pos = $positions[$index % count($positions)]; ?>
    <div class="box box<?php echo $index + 1; ?>" style="top: <?php echo $pos['top']; ?>; left: <?php echo $pos['left']; ?>;"
      onclick="openModal(<?php echo $index; ?>)"
      data-index="<?php echo $index; ?>" data-riddle="<?php echo htmlspecialchars($riddle['riddle']); ?>"
      data-answer="<?php echo htmlspecialchars($riddle['answer']); ?>"
      data-hint="<?php echo htmlspecialchars($riddle['hint']); ?>">
      Vraag <?php echo $index + 1; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="timer" id="timer">05:00</div>

  <button id="showBubblesHint">💡 Hint: Toon bolletjes</button>

  <section class="overlay" id="overlay" onclick="closeModal()"></section>

  <section class="modal" id="modal">
    <h2>Escape Room Vraag</h2>
    <p id="riddle"></p>
    <input type="text" id="answer" placeholder="Typ je antwoord">
    <button onclick="checkAnswer()">Verzenden</button>
    <button class="hint-btn" onclick="showHint()">💡 Hint aanvragen</button>
    <p id="feedback"></p>
    <p id="hintContent" style="display: none; color: #ffd700; margin-top: 10px; font-style: italic;"></p>
  </section>

  

  <script src="../js/team-info.js"></script>
  <script src="../js/app.js"></script>
  <script>
    document.getElementById('showBubblesHint').addEventListener('click', function() {
      const boxes = document.querySelectorAll('.box');
      boxes.forEach(box => {
        box.style.opacity = '1';
        box.style.borderColor = 'yellow';
      });
      setTimeout(() => {
        boxes.forEach(box => {
          box.style.opacity = '';
          box.style.borderColor = '';
        });
      }, 3000); // Show for 3 seconds
    });
  </script>

</body>
</html>