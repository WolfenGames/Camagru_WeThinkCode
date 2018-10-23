var video, context;


function CameraStuff()
{
    video = document.querySelector('#video');
    document.querySelector("#canvas").style.display = "none";
    if (document.getElementById("Camera").style.display == "block")
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
        document.getElementById("snap").addEventListener("click", click);
    }
}
function click (){
    document.querySelector("#canvas").style.display = "block";    
    context = document.querySelector("#canvas").getContext('2d');
    context.drawImage(video, 0, 0, video.width, video.height);
    document.querySelector("#video").style.display = "none";
}
window.onload = function(){
   
    changeTab("Feed");
}

function changeTab(tabName) {
    var i;
    var x = document.getElementsByClassName("tab");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none"; 
    }
    document.getElementById(tabName).style.display = "block";
    CameraStuff();
}