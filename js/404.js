document.addEventListener("DOMContentLoaded", () => {
  const reveal = document.getElementById("reveal");
  const beam = document.getElementById("beam");
  const sparkWrap = document.getElementById("sparkWrap");

  if (!reveal || !beam || !sparkWrap) {
    return;
  }

  let mx = 50,
    my = 40;
  let sweep = 0;
  const speed = 0.6;

  function updatePos(xPerc, yPerc) {
    reveal.style.setProperty("--mx", xPerc + "%");
    reveal.style.setProperty("--my", yPerc + "%");
    beam.style.setProperty("--mx", xPerc + "%");
    beam.style.setProperty("--my", yPerc + "%");
  }

  updatePos(mx, my);

  const sparks = [];
  for (let i = 0; i < 10; i++) {
    const s = document.createElement("div");
    s.className = "spark";
    const left = 20 + Math.random() * 60;
    const top = 20 + Math.random() * 55;
    s.style.left = left + "%";
    s.style.top = top + "%";
    sparkWrap.appendChild(s);
    sparks.push({ el: s, left, top });
  }

  function loop() {
    sweep += 0.8 * speed;
    mx = 20 + 60 * (0.5 + 0.5 * Math.sin(sweep * 0.018));
    my = 35 + 8 * Math.cos(sweep * 0.012);
    updatePos(mx, my);

    sparks.forEach((sp) => {
      const dx = mx - sp.left;
      const dy = my - sp.top;
      const d = Math.sqrt(dx * dx + dy * dy);
      const el = sp.el;
      const intensity = Math.max(0, 1 - d / 20);
      el.style.opacity = intensity > 0.02 ? 0.1 + intensity * 0.95 : 0;
      el.style.transform = `translate(-50%, -50%) scale(${
        0.6 + intensity * 1.4
      })`;
    });

    requestAnimationFrame(loop);
  }
  requestAnimationFrame(loop);

  document.addEventListener("mousemove", (e) => {
    const rect = document.body.getBoundingClientRect();
    const xp = (e.clientX / rect.width) * 100;
    const yp = (e.clientY / rect.height) * 100;
    mx = mx * 0.85 + xp * 0.15;
    my = my * 0.85 + yp * 0.15;
    updatePos(mx, my);
  });

  document.addEventListener(
    "touchmove",
    (e) => {
      const t = e.touches[0];
      if (!t) return;
      const rect = document.body.getBoundingClientRect();
      const xp = (t.clientX / rect.width) * 100;
      const yp = (t.clientY / rect.height) * 100;
      mx = mx * 0.85 + xp * 0.15;
      my = my * 0.85 + yp * 0.15;
      updatePos(mx, my);
    },
    { passive: true }
  );

  const btn = document.querySelector(".btn");
  if (btn) {
    btn.addEventListener("focus", () => {
      mx = 50;
      my = 50;
      updatePos(50, 50);
    });
  }
});
