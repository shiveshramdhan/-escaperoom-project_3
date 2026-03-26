<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Teams</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="TEAMS">
    <h1>Teams</h1>

    <input type="text" id="name" placeholder="Typ hier je naam">
    <input type="text" id="team" placeholder="Typ hier je teamnaam">
    <button onclick="addTeam()">Opslaan</button>

    <h2>Overzicht</h2>
    <table>
        <thead>
            <tr>
                <th>Naam</th>
                <th>Team</th>
            </tr>
        </thead>
        <tbody id="tableBody"></tbody>
    </table>

   <a href="../rooms/room_1.php">
<button class="start-btn">Start game</button> <br>
</a>

    <!-- Script pas hier laden -->
    <script src="../js/app.js"></script>
</body>
</html>