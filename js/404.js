document.addEventListener("DOMContentLoaded", () => {
  const reveal = document.getElementById("reveal");
  const beam = document.getElementById("beam");
  const flashlight = document.getElementById("flashlight");
  const sparkWrap = document.getElementById("sparkWrap");

  let mx = 50,
    my = 50;
  let sweep = 0;
  const speed = 0.6;

  function updatePos(xPerc, yPerc) {
    if (reveal) {
      reveal.style.setProperty("--mx", xPerc + "%");
      reveal.style.setProperty("--my", yPerc + "%");
    }
    if (beam) {
      beam.style.setProperty("--mx", xPerc + "%");
      beam.style.setProperty("--my", yPerc + "%");
    }
  }

  updatePos(mx, my);

  // Создаём искры
  const sparks = [];
  for (let i = 0; i < 10; i++) {
    const s = document.createElement("div");
    s.className = "spark";
    const left = 20 + Math.random() * 60;
    const top = 40 + Math.random() * 15;
    s.style.left = left + "%";
    s.style.top = top + "%";
    sparkWrap.appendChild(s);
    sparks.push({ el: s, left, top });
  }

  function loop() {
    sweep += 0.6 * speed;
    mx = 20 + 60 * (0.5 + 0.5 * Math.sin(sweep * 0.02));
    updatePos(mx, my);

    let fx = null,
      fy = null;
    if (flashlight && window.innerWidth <= 768) {
      const px = (mx / 100) * window.innerWidth;
      flashlight.style.left = px - flashlight.offsetWidth / 2 + "px";
      const rect = flashlight.getBoundingClientRect();
      fx = rect.left + rect.width / 2;
      fy = rect.top + rect.height / 2;
    }

    sparks.forEach((sp, i) => {
      let intensity;
      if (fx && fy) {
        const sparkRect = sp.el.getBoundingClientRect();
        const sx = sparkRect.left + sparkRect.width / 2;
        const sy = sparkRect.top + sparkRect.height / 2;
        const dx = fx - sx,
          dy = fy - sy;
        const d = Math.sqrt(dx * dx + dy * dy);
        intensity = Math.max(0, 1 - d / 120);
      } else {
        const dx = mx - sp.left;
        const dy = my - sp.top;
        const d = Math.sqrt(dx * dx + dy * dy);
        intensity = Math.max(0, 1 - d / 20);
      }
      const wobble = Math.sin(Date.now() / 200 + i) * 0.2;
      sp.el.style.opacity = intensity > 0.02 ? 0.2 + intensity * 0.8 : 0.05;
      sp.el.style.transform = `translate(-50%, -50%) scale(${
        0.8 + intensity + wobble
      })`;
    });

    requestAnimationFrame(loop);
  }
  requestAnimationFrame(loop);
});
