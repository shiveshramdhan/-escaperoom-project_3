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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="win-lose-container">
        <div class="numbers">
            <?php foreach ($this->numbers as $num): ?>
                <span><?php echo htmlspecialchars($num); ?></span>
            <?php endforeach; ?>
        </div>
        <h1 class="result <?php echo $this->result; ?>"><?php echo strtoupper($this->result); ?></h1>
        <?php if ($this->time): ?>
            <div class="time">Tijd: <?php echo htmlspecialchars($this->time); ?></div>
        <?php endif; ?>
        <button id="play-again">Play again</button>
    </div>

    <script>
        document.getElementById('play-again').addEventListener('click', function() {
            window.location.href = './index.php';
        });
    </script>
</body>
</html><?php
    }
}

// instantiate and render using GET parameters
$page = new WinLosePage($_GET);
$page->render();
