let solvedCount = 0;
let totalBoxes = document.querySelectorAll('.box').length;
let gamePhase = 'riddles'; // 'riddles' of 'final'

const finalQuestion = 'Wat heeft één sleutel maar kan geen enkele deur openen?';
const finalAnswer = 'Piano';

let initialSeconds = 5 * 60;
let remainingSeconds = 5 * 60;
let timerInterval = null;

function getElapsedTime() {
  const elapsed = initialSeconds - remainingSeconds;
  const minutes = Math.floor(elapsed / 60);
  const seconds = elapsed % 60;
  return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

function startTimer() {
  const timerEl = document.getElementById('timer');

  function updateTimer() {
    const minutes = Math.floor(remainingSeconds / 60);
    const seconds = remainingSeconds % 60;
    timerEl.innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

    if (remainingSeconds <= 0) {
      clearInterval(timerInterval);
      alert('Tijd is om!');
      window.location.href = '../files/win-lose.php?result=lose&time=00:00';
      return;
    }

    remainingSeconds -= 1;
  }

  updateTimer();
  timerInterval = setInterval(updateTimer, 1000);
}

function openModal(index) {
  const box = document.querySelector(`.box[data-index='${index}']`);
  if (!box || box.classList.contains('solved')) {
    return;
  }

  const riddleText = box.dataset.riddle;
  const correctAnswer = box.dataset.answer;
  const hintText = box.dataset.hint;

  const modal = document.getElementById('modal');
  document.getElementById('riddle').innerText = riddleText;
  modal.dataset.answer = correctAnswer;
  modal.dataset.index = index;
  modal.dataset.phase = 'riddles';
  modal.dataset.hint = hintText;

  document.getElementById('answer').value = '';
  document.getElementById('feedback').innerText = '';
  document.getElementById('hintText').innerText = hintText;
  document.getElementById('hintText').style.display = 'none';

  document.getElementById('overlay').style.display = 'block';
  modal.style.display = 'block';
}

function toggleHint() {
  const hintText = document.getElementById('hintText');
  const hintBtn = document.querySelector('.hint-reveal-btn');
  
  if (hintText.style.display === 'none' || hintText.style.display === '') {
    hintText.style.display = 'block';
    hintBtn.innerText = '🔒 Hint verbergen';
  } else {
    hintText.style.display = 'none';
    hintBtn.innerText = '💡 Hint tonen';
  }
}

function closeModal() {
  document.getElementById('overlay').style.display = 'none';
  document.getElementById('modal').style.display = 'none';
  document.getElementById('feedback').innerText = '';
  document.getElementById('hintText').style.display = 'none';
  document.querySelector('.hint-reveal-btn').innerText = '💡 Hint tonen';
}

function showFinalQuestion() {
  gamePhase = 'final';
  const modal = document.getElementById('modal');
  modal.dataset.phase = 'final';
  modal.dataset.answer = finalAnswer;
  modal.dataset.index = '';

  document.getElementById('riddle').innerText = `Laatste vraag: ${finalQuestion}`;
  document.getElementById('answer').value = '';
  document.getElementById('feedback').innerText = 'Beantwoord de laatste vraag om naar kamer 2 te gaan.';
  document.getElementById('feedback').style.color = '#000';

  document.getElementById('overlay').style.display = 'block';
  modal.style.display = 'block';
}

function checkAnswer() {
  const userAnswer = document.getElementById('answer').value.trim();
  const modal = document.getElementById('modal');
  const correctAnswer = modal.dataset.answer || '';
  const feedback = document.getElementById('feedback');

  if (!userAnswer) {
    feedback.innerText = 'Vul eerst een antwoord in.';
    feedback.style.color = 'red';
    return;
  }

  if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
    if (modal.dataset.phase === 'final') {
      window.location.href = '/rooms/room_2.php';
      return;
    }

    feedback.innerText = 'Correct! Goed gedaan!';
    feedback.style.color = 'green';

    const idx = modal.dataset.index;
    const box = document.querySelector(`.box[data-index='${idx}']`);
    if (box && !box.classList.contains('solved')) {
      box.classList.add('solved');
      solvedCount += 1;
    }

    setTimeout(() => {
      closeModal();

      if (solvedCount === totalBoxes) {
        const elapsedTime = getElapsedTime();
        window.location.href = `../files/win-lose.php?result=win&time=${elapsedTime}`;
      }
    }, 800);
  } else {
    feedback.innerText = 'Fout, probeer opnieuw!';
    feedback.style.color = 'red';
  }
}

function positionBoxes() {
  const boxes = document.querySelectorAll('.box');
  const container = document.querySelector('.container');
  const containerWidth = container.offsetWidth;
  const containerHeight = container.offsetHeight;
  const boxWidth = 70;
  const boxHeight = 70;
  
  // Nieuwe posities
  const positions = [
    { x: containerWidth * 0.5, y: containerHeight * 0.15 },      // top-center
    { x: containerWidth * 0.15, y: containerHeight * 0.35 },     // left-upper
    { x: containerWidth * 0.85, y: containerHeight * 0.35 },     // right-upper
    { x: containerWidth * 0.5, y: containerHeight * 0.65 },      // center
    { x: containerWidth * 0.15, y: containerHeight * 0.8 },      // left-lower
    { x: containerWidth * 0.85, y: containerHeight * 0.8 }       // right-lower
  ];

  boxes.forEach((box, index) => {
    if (index < positions.length) {
      const pos = positions[index];
      // Zorg dat boxen niet buiten de container vallen
      const x = Math.min(pos.x, containerWidth - boxWidth);
      const y = Math.min(pos.y, containerHeight - boxHeight);
      box.style.left = x + 'px';
      box.style.top = y + 'px';
    }
  });
}

function openHintModal() {
  const hintOverlay = document.getElementById('hintOverlay');
  const hintModal = document.getElementById('hintModal');
  const hintMap = document.getElementById('hintMap');
  
  hintOverlay.style.display = 'block';
  hintModal.style.display = 'block';
  
  // Genereer hint kaart met box posities
  hintMap.innerHTML = '';
  const boxes = document.querySelectorAll('.box');
  const container = document.querySelector('.container');
  const containerWidth = container.offsetWidth;
  const containerHeight = container.offsetHeight;
  const mapWidth = hintMap.offsetWidth;
  const mapHeight = hintMap.offsetHeight;
  
  const scaleX = mapWidth / containerWidth;
  const scaleY = mapHeight / containerHeight;
  
  boxes.forEach((box, index) => {
    const rectBox = box.getBoundingClientRect();
    const containerRect = container.getBoundingClientRect();
    const relX = rectBox.left - containerRect.left;
    const relY = rectBox.top - containerRect.top;
    
    const hintBox = document.createElement('div');
    hintBox.className = 'hint-box';
    hintBox.innerHTML = box.classList.contains('solved') ? '✓' : (index + 1);
    hintBox.style.left = (relX * scaleX) + 'px';
    hintBox.style.top = (relY * scaleY) + 'px';
    hintBox.style.opacity = box.classList.contains('solved') ? '0.5' : '1';
    
    hintMap.appendChild(hintBox);
  });
}

function closeHintModal() {
  document.getElementById('hintOverlay').style.display = 'none';
  document.getElementById('hintModal').style.display = 'none';
}
console.log("JS werkt!"); // Even checken of script geladen is

window.addEventListener('load', () => {
  startTimer();
  positionBoxes();
});

// Haal bestaande data op of maak lege array
let teams = JSON.parse(localStorage.getItem("teams")) || [];

// Toon tabel bij laden
document.addEventListener("DOMContentLoaded", function() {
    displayTeams();
});

function addTeam() {

  console.log("addTeam"); // Even checken of script geladen is

    let name = document.getElementById("name").value.trim();
    let team = document.getElementById("team").value.trim();

    if (name === "" || team === "") {
        alert("Vul beide velden in!");
        return;
    }

    // Voeg toe aan array
    teams.push({ name: name, team: team });

    // Opslaan in localStorage
    localStorage.setItem("teams", JSON.stringify(teams));

    // Leeg maken input
    document.getElementById("name").value = "";
    document.getElementById("team").value = "";

    // Update tabel
    displayTeams();
}

function displayTeams() {
    let tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";

    teams.forEach((item, index) => {
        let row = `<tr>
            <td>${item.name}</td>
            <td>${item.team}</td>
        </tr>`;
        tableBody.innerHTML += row;
    });
}
