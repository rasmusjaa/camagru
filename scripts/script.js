(function() {

	var width = 1080;    // We will scale the photo width to this
	var height = 0;     // This will be computed based on the input stream
	
	var streaming = false;

	var video = null;
	var canvas = null;
	var photo = null;
	var snapbutton = null;
	var newbutton = null;
	var savebutton = null;
	var data = null;
	
	function startup() {
		canvas = document.getElementById('canvas');
		photo = document.getElementById('photo');
		video = document.getElementById('video');
		snapbutton = document.getElementById('snapbutton');
		newbutton = document.getElementById('newbutton');
		savebutton = document.getElementById('savebutton');

		
		if (video)
		{
			navigator.mediaDevices.getUserMedia({ video: true, audio: false })
			.then(function(stream) {
				video.srcObject = stream;
				video.play();
			})
			.catch(function(err) {
				console.log("An error occurred: " + err);
			});
			video.addEventListener('canplay', function(ev){
				if (!streaming) {
				  height = video.videoHeight / (video.videoWidth/width);
				  if (isNaN(height)) {
					height = width / (4/3);
				  }
	
				  video.setAttribute('width', width);
				  video.setAttribute('height', height);
				  canvas.setAttribute('width', width);
				  canvas.setAttribute('height', height);
				  streaming = true;
				}
			  }, false);
	
			snapbutton.addEventListener('click', function(ev){
				takepicture();
				ev.preventDefault();
				video.classList.add("hide");
				photo.classList.remove("hide");
				snapbutton.classList.add("hide");
				newbutton.classList.remove("hide");
				savebutton.classList.remove("hide");
			}, false);
	
			newbutton.addEventListener('click', function(ev){
				photo.classList.add("hide");
				video.classList.remove("hide");
				newbutton.classList.add("hide");
				savebutton.classList.add("hide");
				snapbutton.classList.remove("hide");
			}, false);
	
			savebutton.addEventListener('click', function(ev){
				var parameters = {
					"username" : username, 
					"image" : data
				};	
				AjaxPost("save_image.php", parameters, completedAJAX);
				photo.classList.add("hide");
				video.classList.remove("hide");
				newbutton.classList.add("hide");
				savebutton.classList.add("hide");
				snapbutton.classList.remove("hide");
			}, false);

			function clearphoto() {
				var context = canvas.getContext('2d');
				context.fillStyle = "#000";
				context.fillRect(0, 0, canvas.width, canvas.height);
		
				data = canvas.toDataURL('image/png');
				photo.setAttribute('src', data);
			}
		
			function takepicture() {
				var context = canvas.getContext('2d');
				if (width && height) {
				  canvas.width = width;
				  canvas.height = height;
				  context.drawImage(video, 0, 0, width, height);
		
				  data = canvas.toDataURL('image/png');
				  photo.setAttribute('src', data);
				} else {
				  clearphoto();
				}
			}

			clearphoto();
		}

		document.querySelectorAll('.heart').forEach(item => {
			item.addEventListener('click', function(ev) {
				item.classList.toggle("redheart");
				var parameters = {
					"username" : username, 
		//			"image" : id //taa jostain
				};	
		//		AjaxPost("like.php", parameters, completedAJAX);
			}, false)
		})
		
	}

	function createAjaxRequestObject() {
		var xmlhttp;
		if(window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		// Create the object
		return xmlhttp;
	}

	function AjaxPost(ajaxURL, parameters, onComplete) {
		var http3 = createAjaxRequestObject();
		http3.onreadystatechange = function() {
			if(http3.readyState == 4) {
				if(http3.status == 200) {
					if(onComplete) {
						onComplete(http3.responseText);
					}
				}
			}
		};
		// Create parameter string
		var parameterString = "";
		var isFirst = true;
		for(var index in parameters) {
			if(!isFirst) {
				parameterString += "&";
			} 
			parameterString += encodeURIComponent(index) + "=" + encodeURIComponent(parameters[index]);
			isFirst = false;
		}
		// Make request
		http3.open("POST", ajaxURL, true);
		http3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http3.send(parameterString);
	}

	function completedAJAX(response) {
		if (response)
			alert(response);
	}

	

	window.addEventListener('load', startup, false);
})();
