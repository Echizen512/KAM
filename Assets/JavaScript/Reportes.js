   const userProfileButton = document.getElementById('userProfileButton');
    const userProfileModal = document.getElementById('userProfileModal');
    const closeProfile = document.getElementById('closeProfile');
    
    userProfileButton.onclick = function() {
        userProfileModal.style.display = "block";
    };
    
    closeProfile.onclick = function() {
        userProfileModal.style.display = "none";
    };
    

    window.onclick = function(event) {
        if (event.target === userProfileModal) {
            userProfileModal.style.display = "none";
        }
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