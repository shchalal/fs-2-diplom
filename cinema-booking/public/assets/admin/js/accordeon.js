const headers = Array.from(document.querySelectorAll('.conf-step__header'));
headers.forEach(header => header.addEventListener('click', () => {
  header.classList.toggle('conf-step__header_closed');
  header.classList.toggle('conf-step__header_opened');
}));

document.querySelectorAll('[data-open]').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-open');
        document.getElementById(id).classList.add('active');
    });
});


document.querySelectorAll('.popup-close').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.popup').classList.remove('active');
    });
});


document.querySelectorAll('[data-open="popup-remove-hall"]').forEach(btn => {
    btn.addEventListener('click', () => {

        const popup = document.getElementById('popup-remove-hall');

        
        document.getElementById('delete-hall-id').value = btn.dataset.hall;
        document.getElementById('delete-hall-name').textContent = '"' + btn.dataset.name + '"';

       
        popup.classList.add('active');
    });
});


document.querySelectorAll('.popup-close').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.popup').classList.remove('active');
    });
});

