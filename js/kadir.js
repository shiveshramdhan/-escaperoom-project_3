
// Hint popup tonen bij laden
window.addEventListener('DOMContentLoaded', function() {
	const popup = document.getElementById('hintPopup');
	if (popup) {
		setTimeout(function() {
			popup.classList.add('active');
		}, 500);
	}
	startTimer();
});

function closeHintPopup() {
	document.getElementById('hintPopup').classList.remove('active');
}

function closeSecondHintPopup() {
	document.getElementById('secondHintPopup').classList.remove('active');
}

function closeThirdHintPopup() {
	document.getElementById('thirdHintPopup').classList.remove('active');
}

function showSecondHintPopup() {
	const popup = document.getElementById('secondHintPopup');
	if (popup) {
		popup.classList.add('active');
	}
}

function showThirdHintPopup() {
	const popup = document.getElementById('thirdHintPopup');
	if (popup) {
		popup.classList.add('active');
	}
}

function showDoorPopup() {
	const popup = document.getElementById('doorPopup');
	if (popup) {
		popup.classList.add('active');
	}
}

// Timer functionaliteit
let remainingSeconds = 5 * 60;
let timerInterval = null;

function startTimer() {
	const timerEl = document.getElementById('timer');
	if (!timerEl) {
		// Geen timer-element aanwezig op deze pagina, sla timer over.
		return;
	}

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

// Modal functionaliteit
let currentAnswer = '';
let currentIndex = null;
let secondHintShown = false;
let doorPopupShown = false;
let question3ScareShown = false;

function allQuestionsSolved() {
	const boxes = document.querySelectorAll('.box');
	return Array.from(boxes).every(function(box) {
		return box.dataset.solved === 'true';
	});
}

function openModal(index) {
	if (index === 2 && !question3ScareShown) {
		showScareOverlay(function() {
			question3ScareShown = true;
			openModal(index);
		});
		return;
	}

	const boxes = document.querySelectorAll('.box');
	const box = boxes[index];
	if (!box) return;
	
	const riddle = box.getAttribute('data-riddle');
	const answer = box.getAttribute('data-answer');
	
	document.getElementById('riddle').innerHTML = riddle;
	currentAnswer = answer.toLowerCase().trim();
	currentIndex = index;
	
	document.getElementById('modal').classList.add('active');
	document.getElementById('overlay').classList.add('active');
	document.getElementById('answer').value = '';
	document.getElementById('feedback').innerHTML = '';
}

function showScareOverlay(callback) {
	const overlay = document.getElementById('scareOverlay');
	if (!overlay) {
		callback();
		return;
	}
	overlay.classList.add('active');
	setTimeout(function() {
		overlay.classList.remove('active');
		callback();
	}, 1000);
}

function closeModal() {
	document.getElementById('modal').classList.remove('active');
	document.getElementById('overlay').classList.remove('active');
}

function checkAnswer() {
	const userAnswer = document.getElementById('answer').value.toLowerCase().trim();
	const feedback = document.getElementById('feedback');
	const boxes = document.querySelectorAll('.box');
	const box = boxes[currentIndex];
	
	if (userAnswer === currentAnswer) {
		if (box && box.dataset.solved === 'true') {
			feedback.innerHTML = 'Deze vraag is al opgelost.';
			feedback.style.color = '#ffd700';
			return;
		}
		if (box) {
			box.dataset.solved = 'true';
			box.classList.add('solved');
		}
		feedback.innerHTML = 'Correct! Goed gedaan.';
		feedback.style.color = '#00ff00';
		setTimeout(closeModal, 2000);
		setTimeout(function() {
			if (!secondHintShown) {
				secondHintShown = true;
				showSecondHintPopup();
			}
			if (currentIndex === 1) {
				showThirdHintPopup();
			}
			if (!doorPopupShown && allQuestionsSolved()) {
				doorPopupShown = true;
				showDoorPopup();
			}
		}, 10000);
	} else {
		feedback.innerHTML = 'Fout! Probeer opnieuw.';
		feedback.style.color = '#ff6666';
	}
}
