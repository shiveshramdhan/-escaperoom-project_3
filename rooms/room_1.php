<?php
require_once('../dbcon.php');

try {
  $stmt = $db_connection->query("SELECT * FROM riddles WHERE roomId = 1");
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
  <title>Escape Room 1</title>
  <link rel="stylesheet" href="../css/kadir.css">
</head>

<body class="room_1" data-next-room="room_2.php">

  <?php
  // Logische horror-achtige plekken in de kamer
  $positions = [
    ['top' => '30%', 'left' => '20%'], // bij het raam
    ['top' => '60%', 'left' => '55%'], // bij de oude kast
    ['top' => '75%', 'left' => '35%']  // bij de donkere hoek
  ];
  ?>

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

  

  <script src="../js/kadir.js"></script>

</body>
</html>