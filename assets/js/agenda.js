// Données agenda (pourraient venir de PHP, mais ici on les met en dur pour l'exemple)
const agendaData = {
    lundi: [
        { horaire: '14h-16h', enseignant: 'Mme Martin', salle: 'A201', specialite: 'IA' },
        { horaire: '16h-18h', enseignant: 'M. Bernard', salle: 'B103', specialite: 'Cyber' }
    ],
    mardi: [
        { horaire: '10h-12h', enseignant: 'M. Dubois', salle: 'C101', specialite: 'Data' },
        { horaire: '14h-16h', enseignant: 'Mme Petit', salle: 'A201', specialite: 'Dev' }
    ],
    mercredi: [
        { horaire: '9h-11h', enseignant: 'M. Leroy', salle: 'B103', specialite: 'Cyber' },
        { horaire: '14h-16h', enseignant: 'Mme Fournier', salle: 'A201', specialite: 'Maths' }
    ],
    jeudi: [
        { horaire: '13h-15h', enseignant: 'M. Hamidi', salle: 'C101', specialite: 'IA' },
        { horaire: '15h-17h', enseignant: 'M. Guifo', salle: 'B103', specialite: 'Cyber' }
    ],
    vendredi: [
        { horaire: '11h-13h', enseignant: 'Mme Ben Khelil', salle: 'A201', specialite: 'Data' },
        { horaire: '14h-16h', enseignant: 'M. Ameur', salle: 'C101', specialite: 'Dev' }
    ]
};

const tabs = document.querySelectorAll('.tab-btn');
const agendaContent = document.getElementById('agenda-content');

function displayAgenda(day) {
    if (!agendaContent) return;
    const items = agendaData[day] || [];
    let html = '<ul>';
    items.forEach(item => {
        html += `<li><strong>${item.horaire}</strong> - ${item.enseignant} (${item.specialite}) - Salle ${item.salle}</li>`;
    });
    html += '</ul>';
    agendaContent.innerHTML = html;
}

if (tabs.length > 0) {
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            displayAgenda(tab.dataset.day);
        });
    });
    // Afficher le premier jour par défaut
    displayAgenda('lundi');
}