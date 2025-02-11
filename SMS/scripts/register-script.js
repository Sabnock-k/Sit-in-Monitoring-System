function triggerShake() {
    var form = document.getElementById("register-form");
    form.classList.add("shake");
    setTimeout(() => form.classList.remove("shake"), 300);
}

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
