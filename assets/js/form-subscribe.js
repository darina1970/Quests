const form = document.querySelector("#subscribe-form");
const wrapper = document.querySelector("#form-wrapper");
const message = document.querySelector("#form-message");

if (form && wrapper && message) {
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    formData.append("action", "form_subscribe_ajax");

    try {
      const response = await fetch(wp_ajax_params.ajax_url, {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        const withQuest = result.data.with_quest === "yes";

        wrapper.querySelectorAll("form, .form__text, .form__image")
               .forEach((el) => el && (el.style.display = "none"));

        message.innerHTML = withQuest
          ? "Thanks for subscribing! 💌 A free quest will be sent to your email."
          : "Thanks for subscribing! 💌";

        message.style.display = "block";

        setTimeout(() => {
          wrapper.querySelectorAll("form, .form__text, .form__image")
                 .forEach((el) => el && (el.style.display = ""));
          message.style.display = "none";
          form.reset();
        }, 5000);
      } else {
        message.innerHTML = "An error has occurred. Try again.";
        message.style.display = "block";
      }
    } catch (err) {
      console.error("Ошибка отправки:", err);
      message.innerHTML = "Network error. Please try again later.";
      message.style.display = "block";
    }
  });
}