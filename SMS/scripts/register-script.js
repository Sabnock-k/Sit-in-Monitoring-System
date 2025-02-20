window.addEventListener('DOMContentLoaded', () => {
    VANTA.GLOBE({
    el: "#globe",
    mouseControls: true,
    touchControls: true,
    gyroControls: false,
    minHeight: 200.00,
    minWidth: 200.00,
    scale: 1.00,
    scaleMobile: 1.00,
    color: 0xD29C00,
    backgroundColor: 0x0e2f60
    })
});

function triggerShake() {
    var form = document.getElementById("register-form-card");
    if (form) {
        form.classList.add("shake");
        setTimeout(() => form.classList.remove("shake"), 300);
    } else {
        console.error("Element not found: #register-form-card");
    }
}

function openModal() {
    document.getElementById("successModal").style.display = "block";
}

function closeModal() {
    document.getElementById("successModal").style.display = "none";
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById("successModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

