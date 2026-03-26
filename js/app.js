let solvedCount = 0;
let totalBoxes = document.querySelectorAll('.box').length;
let gamePhase = 'riddles'; // 'riddles' of 'final'

const finalQuestion = 'Wat heeft één sleutel maar kan geen enkele deur openen?';
const finalAnswer = 'Piano';

let remainingSeconds = 5 * 60;
let timerInterval = null;

function startTimer() {
  const timerEl = document.getElementById('timer');

  function updateTimer() {
    const minutes = Math.floor(remainingSeconds / 60);
    const seconds = remainingSeconds % 60;
    timerEl.innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

    if (remainingSeconds <= 0) {
      clearInterval(timerInterval);
      alert('Tijd is om!');
      window.location.href = '/files/win-lose.php?result=lose&time=00:00';
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

  const modal = document.getElementById('modal');
  document.getElementById('riddle').innerText = riddleText;
  modal.dataset.answer = correctAnswer;
  modal.dataset.index = index;
  modal.dataset.phase = 'riddles';

  document.getElementById('answer').value = '';
  document.getElementById('feedback').innerText = '';

  document.getElementById('overlay').style.display = 'block';
  modal.style.display = 'block';
}

function closeModal() {
  document.getElementById('overlay').style.display = 'none';
  document.getElementById('modal').style.display = 'none';
  document.getElementById('feedback').innerText = '';
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
        showFinalQuestion();
      }
    }, 800);
  } else {
    feedback.innerText = 'Fout, probeer opnieuw!';
    feedback.style.color = 'red';
  }
}

window.addEventListener('load', () => {
  startTimer();
});
