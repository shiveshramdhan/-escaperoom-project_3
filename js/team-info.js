function sanitizeHtml(text) {
  return String(text)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function renderTeamInfo() {
  const infoContainer = document.getElementById('teamInfoContainer');
  if (!infoContainer) return;

  const entries = JSON.parse(localStorage.getItem('teamEntries')) || [];
  if (entries.length === 0) {
    infoContainer.innerHTML = `
      <div class="team-info">
        Geen teamgegevens gevonden. <a href="../files/teams.php">Maak een team</a>
      </div>
    `;
    return;
  }

  const teamNames = [...new Set(entries.map(item => item.team))];
  const playerNames = [...new Set(entries.map(item => item.name))];

  const teamLabel = sanitizeHtml(teamNames.join(' / '));
  const players = sanitizeHtml(playerNames.join(', '));

  infoContainer.innerHTML = `
    <div class="team-info">
      <span>Team:</span> <strong>${teamLabel}</strong>
      <span>Spelers:</span> <strong>${players}</strong>
      <a href="../files/teams.php">Wijzig</a>
    </div>
  `;
}

function injectTeamHeaderStyles() {
  if (document.getElementById('team-info-styles')) return;

  const style = document.createElement('style');
  style.id = 'team-info-styles';
  style.textContent = `
    .team-header {
      padding: 12px 20px;
      background: rgba(0, 0, 0, 0.75);
      color: #fff;
      font-size: 0.95rem;
      text-align: center;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .team-header .team-info {
      display: inline-flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: center;
      align-items: center;
    }
    .team-header strong {
      color: #ffd700;
    }
    .team-header a {
      color: #8ecae6;
      text-decoration: underline;
    }
    .team-header a:hover {
      text-decoration: none;
    }
  `;
  document.head.appendChild(style);
}

window.addEventListener('DOMContentLoaded', () => {
  injectTeamHeaderStyles();
  renderTeamInfo();
});
