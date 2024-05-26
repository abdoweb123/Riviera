var toggleButtons = document.querySelectorAll(".toggle-password");

toggleButtons.forEach(function (button) {
  button.addEventListener("click", function () {
    if (this.classList.contains("fa-eye")) {
      this.classList.remove("fa-eye");
      this.classList.add("fa-eye-slash");
    } else {
      this.classList.remove("fa-eye-slash");
      this.classList.add("fa-eye");
    }

    var input = document.querySelector(this.getAttribute("toggle"));

    if (input.getAttribute("type") === "password") {
      input.setAttribute("type", "text");
    } else {
      input.setAttribute("type", "password");
    }
  });
});
