function CameraStuff()
{
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
            try {
                video.srcObject = stream;
              } catch (error) {
                video.src = window.URL.createObjectURL(stream);
              }
            video.play();
        });
    }
}

var video, context;

window.onload = function(){
    context = this.document.getElementById("canvas").getContext('2d');
    video = document.querySelector('#video');

    CameraStuff();
    document.getElementById("snap").addEventListener("click", function(){
        context.drawImage(video, 0, 0, video.width, video.height);
    });
}

