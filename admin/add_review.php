<?php
require_once('../dbcon.php');

$message = '';
$errors = [];

try {
  $db_connection->exec("CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    rating TINYINT NOT NULL,
    difficulty ENUM('Eenvoudig','Gemiddeld','Moeilijk') NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (PDOException $e) {
  $errors[] = 'Databasefout bij het maken van de reviews-tabel: ' . $e->getMessage();
}

$name = '';
$rating = '';
$difficulty = '';
$comment = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $rating = trim($_POST['rating'] ?? '');
  $difficulty = trim($_POST['difficulty'] ?? '');
  $comment = trim($_POST['comment'] ?? '');

  if ($name === '') {
    $errors[] = 'Vul je naam in.';
  }

  if ($rating === '' || !in_array($rating, ['1', '2', '3', '4', '5'], true)) {
    $errors[] = 'Kies een score tussen 1 en 5.';
  }

  if (!in_array($difficulty, ['Eenvoudig', 'Gemiddeld', 'Moeilijk'], true)) {
    $errors[] = 'Selecteer de moeilijkheid.';
  }

  if (empty($errors)) {
    try {
      $stmt = $db_connection->prepare('INSERT INTO reviews (name, rating, difficulty, comment) VALUES (:name, :rating, :difficulty, :comment)');
      $stmt->execute([
        ':name' => $name,
        ':rating' => $rating,
        ':difficulty' => $difficulty,
        ':comment' => $comment,
      ]);

      $message = 'Review is opgeslagen.';
      $name = '';
      $rating = '';
      $difficulty = '';
      $comment = '';
    } catch (PDOException $e) {
      $errors[] = 'Kan de review niet opslaan: ' . $e->getMessage();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Review toevoegen</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body { background: #111; color: #fff; font-family: Arial, sans-serif; }
    .admin-page { max-width: 800px; margin: 40px auto; padding: 20px; background: rgba(0,0,0,0.75); border-radius: 12px; }
    .admin-page h1 { margin-bottom: 10px; }
    .admin-page p { margin-bottom: 20px; }
    .admin-form label { display: block; margin-bottom: 8px; font-weight: bold; }
    .admin-form input, .admin-form select, .admin-form textarea { width: 100%; padding: 10px; margin-bottom: 16px; border-radius: 8px; border: 1px solid #666; background: #222; color: #fff; }
    .admin-form button { padding: 10px 18px; background: #7e0000; color: #fff; border: none; border-radius: 8px; cursor: pointer; }
    .admin-form button:hover { background: #9d0000; }
    .message { margin-bottom: 20px; padding: 12px 16px; border-radius: 10px; background: #1e4f1e; color: #dff0d8; }
    .errors { margin-bottom: 20px; padding: 12px 16px; border-radius: 10px; background: #7e0000; color: #ffe5e5; }
    .admin-actions { margin-top: 20px; }
    .admin-actions a { color: #fff; text-decoration: underline; }
  </style>
</head>
<body>
  <div class="admin-page">
    <h1>Review toevoegen</h1>
    <p>Gebruik dit formulier om een review handmatig toe te voegen.</p>

    <?php if (!empty($message)): ?>
      <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="errors">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form class="admin-form" method="post" action="add_review.php">
      <label for="name">Naam</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

      <label for="rating">Beoordeling</label>
      <select id="rating" name="rating" required>
        <option value="">Kies een score</option>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <option value="<?php echo $i; ?>"<?php echo ($rating === (string)$i) ? ' selected' : ''; ?>><?php echo $i; ?></option>
        <?php endfor; ?>
      </select>

      <label for="difficulty">Moeilijkheid</label>
      <select id="difficulty" name="difficulty" required>
        <option value="">Kies een moeilijkheid</option>
        <?php foreach (['Eenvoudig', 'Gemiddeld', 'Moeilijk'] as $option): ?>
          <option value="<?php echo $option; ?>"<?php echo ($difficulty === $option) ? ' selected' : ''; ?>><?php echo $option; ?></option>
        <?php endforeach; ?>
      </select>

      <label for="comment">Feedback</label>
      <textarea id="comment" name="comment" placeholder="Schrijf hier de review..."><?php echo htmlspecialchars($comment); ?></textarea>

      <button type="submit">Opslaan</button>
    </form>

    <div class="admin-actions">
      <a href="show_all_reviews.php">Bekijk alle reviews</a>
    </div>
  </div>
</body>
</html>
