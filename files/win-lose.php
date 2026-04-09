<?php
// win-lose.php
// Gebruik van een eenvoudige PHP-klasse om de pagina te renderen.

class WinLosePage {
    public $result;
    public $time;
    public $numbers = [];

    public function __construct(array $params) {
        $this->result = (isset($params['result']) && $params['result'] === 'win') ? 'win' : 'lose';
        $this->time = $params['time'] ?? null;
        $this->numbers = [$params['r1'] ?? 1, $params['r2'] ?? 2, $params['r3'] ?? 3];
    }

    public function render() {
        ?><!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($this->result); ?> scherm</title>
    <link rel="stylesheet" href="../css/loose,win.css">
</head>
<body class="win-lose-page">
    <div class="win-lose-card">
        <h1 class="result-title <?php echo $this->result; ?>">
            <?php echo $this->result === 'win' ? 'JE ONTSNAPT!' : 'JE BENT GEVONDEN...'; ?>
        </h1>
        <p class="result-description">
            <?php echo $this->result === 'win'
                ? 'Het kwaad is verslagen. Je hebt het overleefd.'
                : 'De deur sluit zich. Je kunt het nog proberen.'; ?>
        </p>
        <?php if ($this->time): ?>
            <div class="time">Tijd: <?php echo htmlspecialchars($this->time); ?></div>
        <?php endif; ?>
<<<<<<< Updated upstream
        <button id="play-again">Play again</button>
        <button id="leave-review">Review achterlaten</button>
=======
        <div class="button-row">
            <button type="button" class="btn secondary" onclick="window.location.href='index.php'">Terug naar start</button>
            <button type="button" class="btn primary" onclick="window.location.href='index.php'">Opnieuw proberen</button>
        </div>
>>>>>>> Stashed changes
    </div>

    <script>
        document.getElementById('play-again').addEventListener('click', function() {
            window.location.href = './index.php';
        });
        document.getElementById('leave-review').addEventListener('click', function() {
            window.location.href = '../admin/add_review.php';
        });
    </script>
</body>
</html><?php
    }
}

// instantiate and render using GET parameters
$page = new WinLosePage($_GET);
$page->render();
