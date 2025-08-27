document.querySelector('.logo-container').addEventListener('mouseover', function () {
    document.querySelector('.hover-message').style.display = 'block';
});
document.querySelector('.logo-container').addEventListener('mouseout', function () {
    document.querySelector('.hover-message').style.display = 'none';
});

function cerrarModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}
