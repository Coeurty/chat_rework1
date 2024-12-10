// handle Chat refresh button
document.getElementById("chatRefreshBtn")?.addEventListener("click", (e) => {
  e.preventDefault();
  window.location.reload();
});

// handle avatar input
document
  .getElementById("avatarFileInput")
  ?.addEventListener("change", function () {
    const file = this.files[0];

    if (file) {
      const errorElement = document.getElementById("fileError");
      const avatarSubmitButton = document.getElementById("avatarSubmitButton");
      const maxSize = 1_000_000;

      if (file.size > maxSize) {
        errorElement.style.display = "block";
        avatarSubmitButton.disabled = true;
        this.value = "";
      } else {
        errorElement.style.display = "none";
        avatarSubmitButton.disabled = false;
      }
    }
  });

// toggle deletion confirmation
document
  .getElementById("deletionConfirmationCheckbox")
  ?.addEventListener("change", function () {
    document.getElementById("deletionConfirmationInput").style.display = this
      .checked
      ? "block"
      : "none";
  });

// handle message textarea
const sendMessageTextarea = document.getElementById("message");
sendMessageTextarea?.addEventListener("keydown", (e) => {
  if (e.key === "Enter") {
    if (!e.shiftKey) {
      e.preventDefault();
      if (sendMessageTextarea.value.trim().length > 0) {
        sendMessageTextarea.form.submit();
      }
    }
  }
});
