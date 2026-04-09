<?php
require_once('../dbcon.php');

try {
  $stmt = $db_connection->query("SELECT * FROM riddles WHERE roomId = 1");
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
  <title>Escape Room 1</title>
  <link rel="stylesheet" href="../css/kadir.css">
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

<body class="room_1" data-next-room="room_2.php">
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
  // Logische horror-achtige plekken in de kamer
  $positions = [
    ['top' => '30%', 'left' => '20%'], // bij het raam
    ['top' => '60%', 'left' => '55%'], // bij de oude kast
    ['top' => '75%', 'left' => '35%']  // bij de donkere hoek
  ];
  ?>

  <div class="timer" id="timer">05:00</div>

  <div class="popup" id="hintPopup">
    <div class="popup-content">
      <p>Je ziet iets in de kast</p>
      <button type="button" onclick="closeHintPopup()">Sluiten</button>
    </div>
  </div>

  <div class="popup" id="secondHintPopup">
    <div class="popup-content">
      <p>Er schijnt iets naar je oog aan de rechterkant van de kamer</p>
      <button type="button" onclick="closeSecondHintPopup()">Sluiten</button>
    </div>
  </div>

  <div class="popup" id="thirdHintPopup">
    <div class="popup-content">
      <p>Je voelt iets sterk van de tafel</p>
      <button type="button" onclick="closeThirdHintPopup()">Sluiten</button>
    </div>
  </div>

  <div class="popup" id="doorPopup">
    <div class="popup-content">
      <p>Een deur gaat open</p>
      <button type="button" onclick="window.location.href='room_2.php'">Ga naar kamer 2</button>
    </div>
  </div>

  <div class="scare-overlay" id="scareOverlay">
    <img src="../img/lg_ebb42c5c23d5-jump-scare-feat.jpg" alt="Jump scare">
  </div>

  <div class="container">
    <?php foreach ($riddles as $index => $riddle) : ?>
    <?php $pos = $positions[$index % count($positions)]; ?>
    <div class="box box<?php echo $index + 1; ?>" style="top: <?php echo $pos['top']; ?>; left: <?php echo $pos['left']; ?>;"
      onclick="openModal(<?php echo $index; ?>)"
      data-index="<?php echo $index; ?>" data-riddle="<?php echo htmlspecialchars($riddle['riddle']); ?>"
      data-answer="<?php echo htmlspecialchars($riddle['answer']); ?>">
      Vraag <?php echo $index + 1; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <section class="overlay" id="overlay" onclick="closeModal()"></section>

  <section class="modal" id="modal">
    <h2>Escape Room Vraag</h2>
    <p id="riddle"></p>
    <input type="text" id="answer" placeholder="Typ je antwoord">
    <button onclick="checkAnswer()">Verzenden</button>
    <p id="feedback"></p>
  </section>

  

  <script src="../js/team-info.js"></script>
  <script src="../js/kadir.js"></script>

</body>
</html>