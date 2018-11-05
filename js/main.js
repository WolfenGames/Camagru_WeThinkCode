var video, context, hasCam, state;

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
				video.onloadeddata = function() {
					document.querySelector("#snap").style.display = "block";
				};
            });
        }else
            document.getElementById("Camera").innerHTML = "<p> NO CAMERA </p>";
        document.getElementById("snap").addEventListener("click", click);
        document.querySelector("#delete_snap").addEventListener("click", cancel_click);
        document.querySelector("#delete_snap").style.display = "none";
    }
}

function cancel_click(){
    document.querySelector("#canvas").style.display = "none";
    document.querySelector("#options").style.display = "none";
    document.querySelector("#video").style.display = "block";
    document.querySelector("#snap").style.display = "block";
    document.querySelector("#delete_snap").style.display = "none";
    hasCam = false;
}

function click (){
    var img = document.querySelector("#canvas");
    var video = document.querySelector("#video");
    img.style.display = "block";
    img.width = video.offsetWidth;
    img.height = video.offsetHeight;
    context = img.getContext('2d');
    context.drawImage(video, 0, 0, video.offsetWidth, video.offsetHeight);
    document.querySelector("#video").style.display = "none";
    document.querySelector("#snap").style.display = "none";
    document.querySelector("#options").style.display = "block";
    document.querySelector("#delete_snap").style.display = "block";
    hasCam = true;
}

window.onload = function(){
	hasCam = false;
	this.document.querySelector("#snap").style.display = "none";
    changeTab("Feed");
	retrieveImage();
	setInterval("retrieveImage()", 10000);
}

function changeTab(tabName) {
    var i;
    var x = document.getElementsByClassName("tab");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none"; 
    }
    document.getElementById(tabName).style.display = "block";
    if (document.getElementById("Feed").style.display === "block")
        retrieveImage();
    if (document.getElementById("Camera").style.display === "block")
		CameraStuff();
	if (document.getElementById("Profile").style.display === "block")
		setState(1);
}

function setState(num)
{
	state = num;
	profileStuff();
}

function profileStuff()
{
	switch(state)
	{
		case 1:
			document.getElementById("NewMember").style.display = "block";
			document.getElementById("AlreadyMember").style.display = "none";
			document.getElementById("ForgotPassword").style.display = "block";
			document.getElementById("Other").style.display = "block";
			document.getElementById("user-control").style.display = "none";
			document.getElementById("user-login").style.display = "block";
			document.getElementById("user-resubmit-email").style.display = "none";
			document.getElementById("user-resubmit").style.display = "none";
			break;
		case 2:
			document.getElementById("NewMember").style.display = "none";
			document.getElementById("AlreadyMember").style.display = "block";
			document.getElementById("ForgotPassword").style.display = "block";
			document.getElementById("Other").style.display = "block";
			document.getElementById("user-control").style.display = "block";
			document.getElementById("user-login").style.display = "none";
			document.getElementById("user-resubmit-email").style.display = "none";
			document.getElementById("user-resubmit").style.display = "none";
			break;
		case 3:
			document.getElementById("NewMember").style.display = "block";
			document.getElementById("AlreadyMember").style.display = "block";
			document.getElementById("ForgotPassword").style.display = "none";
			document.getElementById("Other").style.display = "block";
			document.getElementById("user-login").style.display = "none";
			document.getElementById("user-control").style.display = "none";
			document.getElementById("user-resubmit-email").style.display = "none";
			document.getElementById("user-resubmit").style.display = "block";
			break;
		case 4:
			document.getElementById("NewMember").style.display = "block";
			document.getElementById("AlreadyMember").style.display = "block";
			document.getElementById("ForgotPassword").style.display = "block";
			document.getElementById("Other").style.display = "none";
			document.getElementById("user-login").style.display = "none";
			document.getElementById("user-control").style.display = "none";
			document.getElementById("user-resubmit-email").style.display = "block";
			document.getElementById("user-resubmit").style.display = "none";
			break;
		case 5:
			document.getElementById("NewMember").style.display = "block";
			document.getElementById("AlreadyMember").style.display = "block";
			document.getElementById("ForgotPassword").style.display = "block";
			document.getElementById("Other").style.display = "block";
			document.getElementById("user-login").style.display = "block";
			document.getElementById("user-control").style.display = "block";
			document.getElementById("user-resubmit-email").style.display = "block";
			document.getElementById("user-resubmit").style.display = "block";
			break;
	}
}

function resize() {
    var height = window.innerHeight;
    var ratio = canvas.width / canvas.height;
    var width = height * ratio;
  
    canvas.style.width = width + 'px';
    canvas.style.height = height + 'px';
}

function retrieveImage()
{
	if (document.querySelector("#Feed").style.display == "block")
	{
		var XHR = new XMLHttpRequest();
		XHR.addEventListener('load', function(event) {
			if (this.response)
			{
				document.querySelector("#gallery").innerHTML = this.response;
			}
			else
				document.querySelector("#gallery").innerHTML = "<p>Upload some pictures guys, Im hungry for you pics</p>";
		});
		XHR.addEventListener('error', function(event) {
			alert('Oops! Something went wrong.');
		});
		XHR.open('GET', 'display_images.php');
		XHR.send();
	}
}

function sendData() 
{
    var XHR = new XMLHttpRequest();
    var img_data = document.querySelector("#canvas").toDataURL();

    XHR.addEventListener('load', function(event) {
        if (this.response)
        {
			alert(this.response);
        }
		else
		{
			cancel_click();
			changeTab("Feed");
		}
    });
    XHR.addEventListener('error', function(event) {
    alert('Oops! Something went wrong.');
    });
    XHR.open('POST', 'add_pic.php');
    XHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    XHR.send("img=" + img_data);
}

function delete_image(id)
{
    var XHR = new XMLHttpRequest();
    XHR.addEventListener('load', function(event) {
        if (this.response)
            alert(this.response);
        else
            retrieveImage();
    });
    XHR.addEventListener('error', function(event) {
      alert('Oops! Something went wrong.');
    });
    XHR.open('POST', 'delete_image.php');
    XHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    XHR.send("ID=" + id);
}

function like(id)
{
	var XHR = new XMLHttpRequest();
    XHR.addEventListener('load', function(event) {
        if (this.response)
            alert(this.response);
        else
            retrieveImage();
    });
    XHR.addEventListener('error', function(event) {
      alert('Oops! Something went wrong.');
    });
    XHR.open('POST', 'images/like.php');
    XHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    XHR.send("ID=" + id);
}

function dislike(id)
{
	var XHR = new XMLHttpRequest();
    XHR.addEventListener('load', function(event) {
        if (this.response)
            alert(this.response);
        else
            retrieveImage();
    });
    XHR.addEventListener('error', function(event) {
      alert('Oops! Something went wrong.');
    });
    XHR.open('POST', 'images/dislike.php');
    XHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    XHR.send("ID=" + id);
}

function validity(str, dom)
{
	if (isValid(str.value))
	{
		document.querySelector('#' + dom).style.background = "green";
	}
	else
	{
		document.querySelector('#' + dom).style.background = "red";
	}
}

function isValid(str)
{
	var pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/g;
	return (pattern.test(str));
}

function comment(id)
{
    
}