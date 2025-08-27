const canvas = document.getElementById("animated-bg");
const ctx = canvas.getContext("2d");
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let waveOffset1 = 0;
let waveOffset2 = 0;

function drawWave() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // Ola 1 - más suave y amplia
  ctx.beginPath();
  ctx.moveTo(0, canvas.height * 0.6);
  for (let x = 0; x <= canvas.width; x += 10) {
    const y = canvas.height * 0.6 + Math.sin(x * 0.008 + waveOffset1) * 30;
    ctx.lineTo(x, y);
  }
  ctx.lineTo(canvas.width, canvas.height);
  ctx.lineTo(0, canvas.height);
  ctx.closePath();
  ctx.fillStyle = "rgba(100, 150, 255, 0.3)";
  ctx.fill();

  // Ola 2 - más rápida y estrecha
  ctx.beginPath();
  ctx.moveTo(0, canvas.height * 0.65);
  for (let x = 0; x <= canvas.width; x += 10) {
    const y = canvas.height * 0.65 + Math.sin(x * 0.015 + waveOffset2) * 15;
    ctx.lineTo(x, y);
  }
  ctx.lineTo(canvas.width, canvas.height);
  ctx.lineTo(0, canvas.height);
  ctx.closePath();
  ctx.fillStyle = "rgba(80, 180, 220, 0.4)";
  ctx.fill();

  waveOffset1 += 0.015;
  waveOffset2 += 0.03;

  requestAnimationFrame(drawWave);
}

drawWave();

window.addEventListener("resize", () => {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
});
