const canvas = document.getElementById("animated-bg");
const ctx = canvas.getContext("2d");
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let waveOffset = 0;

function drawWave() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  ctx.beginPath();
  ctx.moveTo(0, canvas.height / 2);

  for (let x = 0; x <= canvas.width; x += 10) {
    const y = canvas.height / 2 + Math.sin(x * 0.01 + waveOffset) * 20;
    ctx.lineTo(x, y);
  }

  ctx.lineTo(canvas.width, canvas.height);
  ctx.lineTo(0, canvas.height);
  ctx.closePath();

  ctx.fillStyle = "rgba(100, 150, 200, 0.2)"; // azul suave, semitransparente
  ctx.fill();

  waveOffset += 0.02;
  requestAnimationFrame(drawWave);
}

drawWave();

window.addEventListener("resize", () => {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
});
