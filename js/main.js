const magnifier = document.querySelector(".magnifier");
const contentClone = document.body.cloneNode(true);
contentClone.classList.add("magnifier-content");
magnifier.appendChild(contentClone);

let mouseX = 0;
let mouseY = 0;

document.addEventListener("mousemove", (e) => {
  mouseX = e.clientX;
  mouseY = e.clientY;
});

function animate() {
  // двигаем лупу за мышью
  magnifier.style.left = `${mouseX - 50}px`;
  magnifier.style.top = `${mouseY - 50}px`;

  // двигаем клонированное содержимое так, чтобы под лупой совпадала область
  contentClone.style.left = `${-mouseX * 1.05 + 50}px`;
  contentClone.style.top = `${-mouseY * 1.05 + 50}px`;

  requestAnimationFrame(animate);
}

requestAnimationFrame(animate);
