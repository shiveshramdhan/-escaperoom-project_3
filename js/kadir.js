
// Boxen zichtbaar maken bij laden
window.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.box').forEach(function(box) {
		box.classList.add('visible');
	});
});

// Modal functionaliteit
let currentAnswer = '';

function openModal(index) {
	const boxes = document.querySelectorAll('.box');
	const box = boxes[index];
	if (!box) return;
	
	const riddle = box.getAttribute('data-riddle');
	const answer = box.getAttribute('data-answer');
	
	document.getElementById('riddle').innerHTML = riddle;
	currentAnswer = answer.toLowerCase().trim();
	
	document.getElementById('modal').classList.add('active');
	document.getElementById('overlay').classList.add('active');
	document.getElementById('answer').value = '';
	document.getElementById('feedback').innerHTML = '';
}

function closeModal() {
	document.getElementById('modal').classList.remove('active');
	document.getElementById('overlay').classList.remove('active');
}

function checkAnswer() {
	const userAnswer = document.getElementById('answer').value.toLowerCase().trim();
	const feedback = document.getElementById('feedback');
	
	if (userAnswer === currentAnswer) {
		feedback.innerHTML = 'Correct! Goed gedaan.';
		feedback.style.color = '#00ff00';
		// Optioneel: sluit modal na correct antwoord
		setTimeout(closeModal, 2000);
	} else {
		feedback.innerHTML = 'Fout! Probeer opnieuw.';
		feedback.style.color = '#ff6666';
	}
}
