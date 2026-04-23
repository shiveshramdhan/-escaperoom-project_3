<?php
include '../dbcon.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $riddle = $_POST['riddle'];
    $answer = $_POST['answer'];
    $hint = $_POST['hint'];
    $roomId = $_POST['roomId'];

    if (!empty($riddle) && !empty($answer) && !empty($roomId)) {
        try {
            $stmt = $db_connection->prepare("INSERT INTO riddles (riddle, answer, hint, roomId) VALUES (?, ?, ?, ?)");
            $stmt->execute([$riddle, $answer, $hint, $roomId]);
            $message = 'Raadsel succesvol toegevoegd!';
        } catch (PDOException $e) {
            $message = 'Fout bij toevoegen: ' . $e->getMessage();
        }
    } else {
        $message = 'Vul alle verplichte velden in.';
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Raadsel Toevoegen</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="TEAMS">
    <h1>Raadsel Toevoegen</h1>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="riddle">Raadsel (vraag):</label><br>
        <textarea id="riddle" name="riddle" required></textarea><br><br>

        <label for="answer">Antwoord:</label><br>
        <input type="text" id="answer" name="answer" required><br><br>

        <label for="hint">Hint:</label><br>
        <input type="text" id="hint" name="hint"><br><br>

        <label for="roomId">Kamer ID:</label><br>
        <select id="roomId" name="roomId" required>
            <option value="1">Kamer 1</option>
            <option value="2">Kamer 2</option>
            <option value="3">Kamer 3</option>
        </select><br><br>

        <button type="submit">Toevoegen</button>
    </form>
</body>
</html>