// Deze functie opent de modal en toont de vraag
function openModal(index) {
  // Zoek het element met de class 'box' en het bijbehorende data-index
  let box = document.querySelector(`.box[data-index='${index}']`);

  // Haal de vraag en het juiste antwoord uit de dataset van de box
  let riddleText = box.dataset.riddle;
  let correctAnswer = box.dataset.answer;

  // Zet de vraagtekst in het modalvenster
  document.getElementById('riddle').innerText = riddleText;

  // Zet het correcte antwoord in de modal, zodat we het later kunnen vergelijken
  let modal = document.getElementById('modal');
  modal.dataset.answer = correctAnswer;
  modal.dataset.index = index; // remember which box was opened

  // Maak het antwoordveld leeg
  document.getElementById('answer').value = '';

  // Toon de overlay en de modal door de display-stijl te veranderen naar 'block'
  document.getElementById('overlay').style.display = 'block';
  modal.style.display = 'block';
}

// Deze functie sluit de modal en de overlay
function closeModal() {
  // Zet de overlay en modal weer op 'none' zodat ze niet meer zichtbaar zijn
  document.getElementById('overlay').style.display = 'none';
  document.getElementById('modal').style.display = 'none';

  // Maak de feedback tekst leeg
  document.getElementById('feedback').innerText = '';
}

// Initialize start time and solved count
let startTime = Date.now();
let solvedCount = 0;
let totalBoxes = document.querySelectorAll('.box').length;

// Deze functie controleert of het ingevoerde antwoord correct is
function checkAnswer() {
  let userAnswer = document.getElementById('answer').value.trim();
  let correctAnswer = document.getElementById('modal').dataset.answer;
  let feedback = document.getElementById('feedback');

  if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
    feedback.innerText = 'Correct! Goed gedaan!';
    feedback.style.color = 'green';

    // mark the current box as solved so user can't reopen
    let idx = document.getElementById('modal').dataset.index;
    let box = document.querySelector(`.box[data-index='${idx}']`);
    if (box && !box.classList.contains('solved')) {
      box.classList.add('solved');
      solvedCount++;
    }

    // Sluit de modal na 1 seconde
    setTimeout(() => {
      closeModal();

      // if all boxes have been solved, redirect to win page
      if (solvedCount === totalBoxes) {
        let elapsed = Date.now() - startTime;
        // format mm:ss
        let minutes = Math.floor(elapsed / 60000);
        let seconds = Math.floor((elapsed % 60000) / 1000);
        let timeStr = `${minutes}:${seconds.toString().padStart(2,'0')}`;
        window.location.href = `/files/win-lose.php?result=win&time=${encodeURIComponent(timeStr)}`;
      }
    }, 1000);
  } else {
    feedback.innerText = 'Fout, probeer opnieuw!';
    feedback.style.color = 'red';
  }
}

localStorage.clear();
console.log("JS werkt!"); // Even checken of script geladen is

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