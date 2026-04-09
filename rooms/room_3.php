<?php
require_once('../dbcon.php');

try {
  $stmt = $db_connection->query("SELECT * FROM riddles WHERE roomId = 3");
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
  <title>Escape Room 3</title>
  <link rel="stylesheet" href="../css/tygo.css">
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
  </style>
</head>

<body class="room3">
  <div class="team-header">
    <?php if ($activeTeam && $activeTeam['team_name']): ?>
      <div><span>Team:</span> <strong><?php echo htmlspecialchars($activeTeam['team_name']); ?></strong></div>
      <div><span>Spelers:</span> <strong><?php echo htmlspecialchars($activeTeam['players']); ?></strong></div>
      <div><a href="../files/teams.php">Wijzig</a></div>
    <?php else: ?>
      <div>Geen team gevonden. <a href="../files/teams.php">Maak een team</a></div>
    <?php endif; ?>
  </div>
  <div id="timer" class="timer"></div>
  <button id="hintBtn" class="hint-btn" onclick="openHintModal()">💡 Hint</button>

<div class="container">
    <?php foreach ($riddles as $index => $riddle) : ?>
    <div class="box box<?php echo $index + 1; ?>" onclick="openModal(<?php echo $index; ?>)"
      data-index="<?php echo $index; ?>" data-riddle="<?php echo htmlspecialchars($riddle['riddle']); ?>"
      data-answer="<?php echo htmlspecialchars($riddle['answer']); ?>" data-hint="<?php echo htmlspecialchars($riddle['hint']); ?>">
      Box <?php echo $index + 1; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <section class="overlay" id="overlay" onclick="closeModal()"></section>

  <section class="modal" id="modal">
    <h2>Escape Room Vraag</h2>
    <p id="riddle"></p>
    <input type="text" id="answer" placeholder="Typ je antwoord">
    <button onclick="checkAnswer()">Verzenden</button>
    <button onclick="toggleHint()" class="hint-reveal-btn">💡 Hint tonen</button>
    <p id="hintText" class="hint-text" style="display: none;"></p>
    <p id="feedback"></p>
  </section>

  <section class="hint-overlay" id="hintOverlay" onclick="closeHintModal()"></section>

  <section class="hint-modal" id="hintModal">
    <h2>Waar zijn de boxen?</h2>
    <div id="hintMap" class="hint-map"></div>
    <button onclick="closeHintModal()" class="close-hint-btn">Sluiten</button>
  </section>

  <script src="../js/team-info.js"></script>
  <script src="../js/tygo.js"></script>
 
</body>

</html>