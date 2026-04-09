<?php
require_once('../dbcon.php');

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $team = trim($_POST['team'] ?? '');

    if ($name !== '' && $team !== '') {
        $stmt = $db_connection->prepare(
            'INSERT INTO team_members (team_name, player_name) VALUES (:team_name, :player_name)'
        );
        $stmt->execute([
            ':team_name' => $team,
            ':player_name' => $name,
        ]);
        $message = 'Teamlid opgeslagen.';
    } else {
        $message = 'Vul zowel je naam als je teamnaam in.';
    }
}

try {
    $stmt = $db_connection->query(
        "SELECT team_name, GROUP_CONCAT(player_name SEPARATOR ', ') AS players
         FROM team_members
         GROUP BY team_name
         ORDER BY MAX(created_at) DESC"
    );
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $teams = [];
    $message = 'Fout bij ophalen van teams: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Teams</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="TEAMS">
    <h1>Teams</h1>

    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <input type="text" name="name" id="name" placeholder="Typ hier je naam">
        <input type="text" name="team" id="team" placeholder="Typ hier je teamnaam">
        <button type="submit">Opslaan</button>
    </form>

    <h2>Overzicht</h2>
    <table>
        <thead>
            <tr>
                <th>Team</th>
                <th>Spelers</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($teams) === 0): ?>
                <tr><td colspan="2">Nog geen teams geregistreerd.</td></tr>
            <?php else: ?>
                <?php foreach ($teams as $teamItem): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($teamItem['team_name']); ?></td>
                        <td><?php echo htmlspecialchars($teamItem['players']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="../rooms/room_1.php">
        <button class="start-btn" type="button">Start game</button>
    </a>

</body>
</html>
