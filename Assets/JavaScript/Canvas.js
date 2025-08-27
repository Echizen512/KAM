const canvas = document.getElementById("animated-bg");
const ctx = canvas.getContext("2d");
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let circles = [];

for (let i = 0; i < 50; i++) {
  circles.push({
    x: Math.random() * canvas.width,
    y: Math.random() * canvas.height,
    radius: Math.random() * 40 + 10,
    dx: (Math.random() - 0.5) * 1,
    dy: (Math.random() - 0.5) * 1,
    color: `rgba(35, 120, 182, ${Math.random() * 0.5 + 0.2})`,
  });
}

function animate() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  for (let c of circles) {
    ctx.beginPath();
    ctx.arc(c.x, c.y, c.radius, 0, Math.PI * 2);
    ctx.fillStyle = c.color;
    ctx.fill();
    c.x += c.dx;
    c.y += c.dy;

    if (c.x < 0 || c.x > canvas.width) c.dx *= -1;
    if (c.y < 0 || c.y > canvas.height) c.dy *= -1;
  }
  requestAnimationFrame(animate);
}

animate();
window.addEventListener("resize", () => {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
});
