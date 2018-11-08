var video, context, hasCam, state;

function CameraStuff()
{
    video = document.querySelector('#video');
    if (!hasCam)
    {
        document.querySelector("#canvas").style.display = "none";
        document.querySelector("#canvaspreview").style.display = "none";
        document.querySelector("#canvaspreview").style.display = "none";
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
		document.querySelector("#exampleView").addEventListener("click", examplePreview);
		document.querySelector("#image_upload").addEventListener("change", (e) => loadtocanvas(e.target.files));
        document.querySelector("#delete_snap").style.display = "none";
    }
}

function examplePreview()
{
	var flowy = document.getElementById("flogo");
	var grand = document.getElementById("grand");
	var corner = document.getElementById("corner");

	if (flowy.checked)
	{
		var flowyimg = new Image();
		flowyimg.src = "stickers/flogo.png";
		document.querySelector("#canvaspreview").getContext('2d').drawImage(flowyimg,100,480,630,100);
	}
	if (corner.checked)
	{
		var corner = new Image();
		corner.src = "stickers/corner.png";
		document.querySelector("#canvaspreview").getContext('2d').drawImage(corner,0,0,640,640);
	}
	if (grand.checked)
	{
		var grand = new Image();
		grand.src = "stickers/grand.png";
		document.querySelector("#canvaspreview").getContext('2d').drawImage(grand,320,0,400,100);
	}
}

function cancel_click(){
    document.querySelector("#canvas").style.display = "none";
    document.querySelector("#canvaspreview").style.display = "none";
    document.querySelector("#options").style.display = "none";
    document.querySelector("#video").style.display = "block";
    document.querySelector("#snap").style.display = "block";
    document.querySelector("#delete_snap").style.display = "none";
    document.querySelector("#image_upload").style.display = "block";
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
	img.style.display = "none";
	document.querySelector("#canvaspreview").style.display = "block";
	document.querySelector("#canvaspreview").width = video.offsetWidth;
	document.querySelector("#canvaspreview").height = video.offsetHeight;
	document.querySelector("#canvaspreview").getContext('2d').drawImage(video, 0, 0, video.offsetWidth, video.offsetHeight);
    document.querySelector("#video").style.display = "none";
    document.querySelector("#snap").style.display = "none";
    document.querySelector("#options").style.display = "block";
    document.querySelector("#delete_snap").style.display = "block";
    document.querySelector("#image_upload").style.display = "none";
    hasCam = true;
}

window.onload = function(){
	hasCam = false;
	this.document.querySelector("#snap").style.display = "none";
	document.querySelector("#canvaspreview").style.display = "none";
    changeTab("Feed");
	retrieveImage();
	setInterval("retrieveImage()", 10000);
	retrieveMyImage();
	setInterval("retrieveMyImage()", 10000);
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
	if (document.getElementById("myEdits").style.display === "block")
		retrieveMyImage();
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
	if (document.getElementById("user-control") != null)
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

function retrieveMyImage()
{
	if (document.querySelector("#myEdits").style.display == "block")
	{
		var XHR = new XMLHttpRequest();
		XHR.addEventListener('load', function(event) {
			if (this.response)
			{
				document.querySelector("#myGallery").innerHTML = this.response;
			}
			else
				document.querySelector("#myGallery").innerHTML = "<p>Upload some pictures guys, Im hungry for you pics</p>";
		});
		XHR.addEventListener('error', function(event) {
			alert('Oops! Something went wrong.');
		});
		XHR.open('GET', 'display_myimages.php');
		XHR.send();
	}
}

function loadtocanvas(e)
{
	var file = null;

	for (let i = 0; i < e.length; i++) {
		if (e[i].type.match(/^image\//)) {
			file = e[i];
		break;
		}
	}
	var newimg = new Image();
	if (file)
	{
		var canvas1 = document.querySelector("#canvas");
		var canvas2 = document.querySelector("#canvaspreview");
	
		if (!hasCam)
    	{
			document.querySelector("#canvas").style.display = "none";
			document.querySelector("#canvaspreview").style.display = "none";
			document.querySelector("#canvaspreview").style.display = "none";
			document.querySelector("#options").style.display = "none";
    	}

		newimg.onload = function()
		{
			canvas1.width = this.width;
			canvas1.height = this.height;
			canvas2.width = this.width;
			canvas2.height = this.height;

			console.log(this.width + ' ' + this.height)

			canvas1.getContext('2d').drawImage(newimg, 0, 0);
			canvas2.getContext('2d').drawImage(newimg, 0, 0);
		}
		newimg.src = URL.createObjectURL(file);
		var img = document.querySelector("#canvas");
		img.style.display = "none";
		document.querySelector("#canvaspreview").style.display = "block";
		document.querySelector("#video").style.display = "none";
		document.querySelector("#snap").style.display = "none";
		document.querySelector("#options").style.display = "block";
		document.querySelector("#delete_snap").style.display = "block";
		document.querySelector("#image_upload").style.display = "none";
		document.querySelector("#uploadImage").style.display = "none";
		hasCam = true;
	}
}

function sendData() 
{
    var XHR = new XMLHttpRequest();
	var img_data = document.querySelector("#canvas").toDataURL();
	var title = document.querySelector("#title").value;
	var flowy = document.getElementById("flogo");
	var grand = document.getElementById("grand");
	var corner = document.getElementById("corner");

    XHR.addEventListener('load', function(event) {
		cancel_click();
		changeTab("Feed");
    });
    XHR.addEventListener('error', function(event) {
		alert('Oops! Something went wrong.');
    });
    XHR.open('POST', 'add_pic.php');
    XHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    XHR.send("img=" + img_data + "&title=" + title + "&flowy=" + flowy.checked + "&grand=" + grand.checked + "&corner=" + corner.checked);
}

function delete_image(id)
{
    var XHR = new XMLHttpRequest();
    XHR.addEventListener('load', function(event) {
        if (this.response)
            alert(this.response);
		else
		{
			retrieveMyImage();
			retrieveImage();
		}
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