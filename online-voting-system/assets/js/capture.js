document.addEventListener("DOMContentLoaded", function() {
    const video = document.createElement("video");
    const canvas = document.createElement("canvas");
    const context = canvas.getContext("2d");
    canvas.width = 320;
    canvas.height = 240;

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
            video.play();
            document.getElementById("camera").appendChild(video);
            video.width = 320;
            video.height = 240;
        })
        .catch(err => console.error("Error accessing camera: " + err));

    window.captureImage = function() {
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataURL = canvas.toDataURL("image/png");
        document.getElementById("captured_image_data").value = dataURL;

        // Show capture successful message
        alert("Capture Successful");
    };
});
