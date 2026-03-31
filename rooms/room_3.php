<?php
require_once('../dbcon.php');

try {
  $stmt = $db_connection->query("SELECT * FROM riddles WHERE roomId = 3");
  $riddles = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
</head>

<body class="room3">
   
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

  <script src="../js/tygo.js"></script>
 
</body>

</html>