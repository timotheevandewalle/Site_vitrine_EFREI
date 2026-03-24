const searchInput = document.getElementById('search-input');
const filterTags = document.querySelectorAll('.filter-tag');
const teacherCards = document.querySelectorAll('.teacher-card');

function filterTeachers() {
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const activeFilter = document.querySelector('.filter-tag.active')?.dataset.specialite || 'all';

    teacherCards.forEach(card => {
        const nom = card.dataset.nom;
        const specialite = card.dataset.specialite;
        let matchesSearch = true;
        let matchesFilter = true;

        if (searchTerm) {
            matchesSearch = nom.includes(searchTerm);
        }
        if (activeFilter !== 'all') {
            matchesFilter = specialite === activeFilter;
        }

        if (matchesSearch && matchesFilter) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

if (searchInput) {
    searchInput.addEventListener('keyup', filterTeachers);
}

filterTags.forEach(tag => {
    tag.addEventListener('click', () => {
        filterTags.forEach(t => t.classList.remove('active'));
        tag.classList.add('active');
        filterTeachers();
    });
});