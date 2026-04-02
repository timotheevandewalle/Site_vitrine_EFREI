// Attendre que le DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les données depuis window.agendaData
    const agendaData = window.agendaData || {};
    
    const tabs = document.querySelectorAll('.tab-btn');
    const agendaContent = document.getElementById('agenda-content');
    
    // Si la section agenda n'existe pas sur cette page, on ne fait rien
    if (!agendaContent) return;
    
    function displayAgenda(day) {
        const items = agendaData[day] || [];
        if (items.length === 0) {
            agendaContent.innerHTML = '<p>Aucune permanence pour ce jour.</p>';
            return;
        }
        
        let html = '<ul>';
        items.forEach(item => {
            html += `<li><strong>${item.horaire}</strong> - ${item.enseignant} (${item.specialite}) - Salle ${item.salle}</li>`;
        });
        html += '</ul>';
        agendaContent.innerHTML = html;
    }
    
    if (tabs.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                displayAgenda(this.dataset.day);
            });
        });
        
        // Afficher le lundi par défaut
        displayAgenda('lundi');
    }
});