const form = document.getElementById('contact-form');
if (form) {
    form.addEventListener('submit', (e) => {
        let valid = true;
        const inputs = form.querySelectorAll('input[required], textarea[required]');
        inputs.forEach(input => {
            const errorSpan = input.nextElementSibling;
            if (!input.value.trim()) {
                errorSpan.textContent = 'Ce champ est requis.';
                valid = false;
            } else if (input.type === 'email' && !/^\S+@\S+\.\S+$/.test(input.value)) {
                errorSpan.textContent = 'Email invalide.';
                valid = false;
            } else {
                errorSpan.textContent = '';
            }
        });

        if (!form.querySelector('#rgpd').checked) {
            const errorSpan = form.querySelector('#rgpd').parentNode.nextElementSibling;
            errorSpan.textContent = 'Vous devez accepter.';
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}