// capture.js

function captureImage() {
    var video = document.createElement('video');
    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');
    var width = 320;
    var height = 240;
    canvas.width = width;
    canvas.height = height;
    var streaming = false;

    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(function (stream) {
            video.srcObject = stream;
            video.play();
        })
        .catch(function (err) {
            console.log("An error occurred: " + err);
        });

    video.addEventListener('canplay', function (ev) {
        if (!streaming) {
            video.setAttribute('width', width);
            video.setAttribute('height', height);
            streaming = true;
        }
    }, false);

    document.getElementById('camera').appendChild(video);

    video.addEventListener('click', function () {
        context.drawImage(video, 0, 0, width, height);
        var data = canvas.toDataURL('image/png');
        document.getElementById('captured_image_data').value = data;
        video.srcObject.getTracks().forEach(track => track.stop());
        video.remove();
        alert("Image captured successfully.");
    }, false);
}
