var video, context;
var hasCam;

function CameraStuff()
{
    video = document.querySelector('#video');
    if (!hasCam)
    {
        document.querySelector("#canvas").style.display = "none";
        document.querySelector("#options").style.display = "none";
    }
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
        }else
            document.getElementById("Camera").innerHTML = "<p> NO CAMERA </p>";
        document.getElementById("snap").addEventListener("click", click);
        document.querySelector("#delete_snap").addEventListener("click", cancel_click);
    }
}

function cancel_click(){
    document.querySelector("#canvas").style.display = "none";
    document.querySelector("#options").style.display = "none";
    document.querySelector("#video").style.display = "block";
    document.querySelector("#snap").style.display = "block";
    hasCam = false;
}

function click (){
    document.querySelector("#canvas").style.display = "block";    
    var img = document.querySelector("#canvas");
    context = img.getContext('2d');
    context.drawImage(video, 0, 0);
    document.querySelector("#video").style.display = "none";
    document.querySelector("#snap").style.display = "none";
    document.querySelector(".addTree").addEventListener("click", add_tree);
    document.querySelector("#options").style.display = "block";
    hasCam = true;
}

window.onload = function(){
    hasCam = false;
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

function add_tree()
{
    base_image = new Image();
    base_image.src = 'img/tree.png';
    base_image.onload = function(){
    context.drawImage(base_image, Math.floor((Math.random() * 620)), 10,
                        Math.floor((Math.random() * 300) + 1),
                        Math.floor((Math.random() * 480) + 1));//300, 480);
    }
}

function sendData() {
    var XHR = new XMLHttpRequest();

    var img_data = document.querySelector("#canvas").toDataURL();

    XHR.addEventListener('load', function(event) {
      console.log("Success???");
      console.log(this.response);
    });
  
    XHR.addEventListener('error', function(event) {
      alert('Oops! Something went wrong.');
    });
  
    XHR.open('GET', 'add_pic.php?img='+img_data);
  
    XHR.send();
  }