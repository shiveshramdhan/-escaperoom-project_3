<?php
require_once('../dbcon.php');

$errors = [];

try {
  $stmt = $db_connection->query('SELECT * FROM reviews ORDER BY created_at DESC');
  $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $errors[] = 'Kan reviews niet laden: ' . $e->getMessage();
  $reviews = [];
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Reviews overzicht</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body { background: #111; color: #fff; font-family: Arial, sans-serif; }
    .admin-page { max-width: 1000px; margin: 40px auto; padding: 20px; background: rgba(0,0,0,0.75); border-radius: 12px; }
    .admin-page h1 { margin-bottom: 20px; }
    .errors { margin-bottom: 20px; padding: 12px 16px; border-radius: 10px; background: #7e0000; color: #ffe5e5; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px 10px; border-bottom: 1px solid rgba(255,255,255,0.12); text-align: left; }
    th { background: rgba(255,255,255,0.08); }
    td { background: rgba(255,255,255,0.04); }
    .no-data { padding: 20px; background: rgba(255,255,255,0.05); border-radius: 10px; }
    .admin-actions { margin-top: 20px; }
    .admin-actions a { color: #fff; text-decoration: underline; }
  </style>
</head>
<body>
  <div class="admin-page">
    <h1>Admin - Reviews overzicht</h1>
    <?php if (!empty($errors)): ?>
      <div class="errors">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (empty($reviews)): ?>
      <div class="no-data">Er zijn nog geen reviews opgeslagen.</div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Naam</th>
            <th>Rating</th>
            <th>Moeilijkheid</th>
            <th>Feedback</th>
            <th>Datum</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($reviews as $review): ?>
            <tr>
              <td><?php echo htmlspecialchars($review['name']); ?></td>
              <td><?php echo htmlspecialchars($review['rating']); ?></td>
              <td><?php echo htmlspecialchars($review['difficulty']); ?></td>
              <td><?php echo nl2br(htmlspecialchars($review['comment'])); ?></td>
              <td><?php echo htmlspecialchars(date('d-m-Y H:i', strtotime($review['created_at']))); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <div class="admin-actions">
      <a href="add_review.php">Nieuwe review toevoegen</a>
    </div>
  </div>
</body>
</html>
