    // Modal para el perfil de usuario
    const userProfileButton = document.getElementById('userProfileButton');
    const userProfileModal = document.getElementById('userProfileModal');
    const closeProfile = document.getElementById('closeProfile');
    userProfileButton.onclick = function() {
        userProfileModal.style.display = "block";
    }
    closeProfile.onclick = function() {
        userProfileModal.style.display = "none";
    }

   
    window.onclick = function(event) {
        if (event.target == userProfileModal) {
            userProfileModal.style.display = "none";
        }
     
    }