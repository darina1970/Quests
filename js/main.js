document.querySelectorAll(".custom-select").forEach((select) => {
  const value = select.querySelector(".custom-select-value");
  const options = select.querySelectorAll(".custom-select-options li");

  // Открытие/закрытие выпадающего меню при клике на поле
  select.addEventListener("click", (e) => {
    e.stopPropagation(); // чтобы клик не закрывал сразу меню
    select.classList.toggle("open");
  });

  // Выбор значения из списка
  options.forEach((option) => {
    option.addEventListener("click", (e) => {
      value.textContent = option.textContent;
      select.classList.remove("open");

      // Если нужно, здесь можно вызвать функцию фильтрации/сортировки
      // например: filterProducts();
    });
  });
});

// Закрытие всех селектов при клике вне
document.addEventListener("click", () => {
  document.querySelectorAll(".custom-select").forEach((select) => {
    select.classList.remove("open");
  });
});
