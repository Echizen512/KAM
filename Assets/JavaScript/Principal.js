
const userProfileButton = document.getElementById('userProfileButton');
const userProfileModal = document.getElementById('userProfileModal');
const closeProfile = document.getElementById('closeProfile');
userProfileButton.onclick = function () {
    userProfileModal.style.display = "block";
}
closeProfile.onclick = function () {
    userProfileModal.style.display = "none";
}


const calendarButton = document.getElementById('calendarButton');
const calendarModal = document.getElementById('calendarModal');
const closeCalendar = document.getElementById('closeCalendar');
const addCommitment = document.getElementById('addCommitment');
calendarButton.onclick = function () {
    calendarModal.style.display = "block";
}
closeCalendar.onclick = function () {
    calendarModal.style.display = "none";
}
addCommitment.onclick = function () {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const commitment = document.getElementById('commitment').value;
    const today = new Date().toISOString().split('T')[0];
    if (!startDate || !commitment) {
        Swal.fire('Por favor, complete todos los campos.');
        return;
    }
    if (startDate < today) {
        Swal.fire('Fecha inválida', 'La fecha de inicio no puede ser anterior al día agendado.', 'error');
        return;
    }
    if (endDate && endDate < startDate) {
        Swal.fire('Fecha inválida', 'La fecha de fin no puede ser anterior a la fecha de inicio.', 'error');
        return;
    }
    const listItem = document.createElement('li');
    listItem.textContent = `Compromiso: ${commitment} - Fecha de inicio: ${startDate} ${endDate ? ' - Fecha de fin: ' + endDate : ''}`;
    document.getElementById('notificationList').appendChild(listItem);
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    document.getElementById('commitment').value = '';
    Swal.fire('Éxito', 'Su compromiso ha sido agendado correctamente', 'success');
    calendarModal.style.display = "none";
}

// Modal para las notificaciones
const notificationsButton = document.getElementById('notificationsButton');
const notifications = document.getElementById('notifications');
const closeNotifications = document.getElementById('closeNotifications');
notificationsButton.onclick = function () {
    notifications.style.display = "block";
}
closeNotifications.onclick = function () {
    notifications.style.display = "none";
}
window.onclick = function (event) {
    if (event.target == userProfileModal) {
        userProfileModal.style.display = "none";
    }
    if (event.target == calendarModal) {
        calendarModal.style.display = "none";
    }
    if (event.target == notifications) {
        notifications.style.display = "none";
    }
}

const carousel = document.querySelector('.carousel');
const images = carousel.querySelectorAll('img');
let currentIndex = 0;

function nextImage() {
    currentIndex = (currentIndex + 1) % images.length;
    carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
}

setInterval(nextImage, 3000);

function cerrarCarrusel() {
    const carousel = document.getElementById('carousel');
    const graficas = document.getElementById('graficas');
    carousel.style.display = 'none'; // Oculta el carrusel
    graficas.classList.remove('hidden'); // Muestra las gráficas
}

window.onload = function () {
    setTimeout(() => {
        document.getElementById('asistencias').style.height = '83.3%';
        document.getElementById('inasistencias').style.height = '50%';
    }, 500); // Retraso para que la animación de zoom sea visible
};

document.querySelectorAll('.logo-container').forEach(container => {
    const logo = container.querySelector('.logo');
    const hoverMessage = container.querySelector('.hover-message');

    logo.addEventListener('mouseenter', () => {
        hoverMessage.style.display = 'block';
    });

    logo.addEventListener('mouseleave', () => {
        hoverMessage.style.display = 'none';
    });
});
