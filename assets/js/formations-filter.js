document.addEventListener('DOMContentLoaded', function() {
    console.log('🟢 Script formations-filter chargé');
    const btns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.course-card');
    console.log('📌 Boutons trouvés :', btns.length);
    console.log('📌 Cartes trouvées :', cards.length);

    if (btns.length === 0) return;

    function filterCourses(category) {
        cards.forEach(card => {
            const cats = card.getAttribute('data-categories');
            if (category === 'all' || (cats && cats.includes(category))) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    btns.forEach(btn => {
        btn.addEventListener('click', function() {
            btns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterCourses(this.getAttribute('data-filter'));
        });
    });

    filterCourses('all');
});