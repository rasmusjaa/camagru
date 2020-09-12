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
	var my_photos = null;
	
	// when page has loaded
	function startup() {
		canvas = document.getElementById('canvas');
		photo = document.getElementById('photo');
		video = document.getElementById('video');
		snapbutton = document.getElementById('snapbutton');
		newbutton = document.getElementById('newbutton');
		savebutton = document.getElementById('savebutton');
		my_photos = document.getElementById('my_photos');

		// only on page with video feed
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
	
			// take a photo
			snapbutton.addEventListener('click', function(ev){
				takepicture();
				ev.preventDefault();
				video.classList.add("hide");
				photo.classList.remove("hide");
				snapbutton.classList.add("hide");
				newbutton.classList.remove("hide");
				savebutton.classList.remove("hide");
			}, false);
			
			// take a new photo
			newbutton.addEventListener('click', function(ev){
				photo.classList.add("hide");
				video.classList.remove("hide");
				newbutton.classList.add("hide");
				savebutton.classList.add("hide");
				snapbutton.classList.remove("hide");
			}, false);
			
			// save photo to database and reset buttons
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
				// add photo to sidebar
				var parameters = {
					"function" : 'reload_user_images',
					"value1" : username
				};
				AjaxPost("functions.php", parameters, completedAJAX);
				// var new_div = document.createElement("div");
				// new_div.classList.add("single_photo");
				// new_div.innerHTML = '<img src="' + data + '"><br>';
				// my_photos.insertBefore(new_div, my_photos.firstChild);
			}, false);

			// fill photo with black
			function clearphoto() {
				var context = canvas.getContext('2d');
				context.fillStyle = "#000";
				context.fillRect(0, 0, canvas.width, canvas.height);
		
				data = canvas.toDataURL('image/png');
				photo.setAttribute('src', data);
			}
			
			// show snapped photo instead of video stream
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

		// Add like button functions
		document.querySelectorAll('.heart').forEach(item => {
			item.addEventListener('click', function(ev) {
				if (!user_id)
					return ;
				var like_count = item.nextElementSibling;
				item.classList.toggle('redheart');
				if (item.classList.contains('redheart'))
					like_count.innerHTML = parseInt(like_count.innerHTML, 10) + 1;
				else
					like_count.innerHTML = parseInt(like_count.innerHTML, 10) - 1;
				var parameters = {
					"user_id" : user_id, 
					"image" : item.parentNode.parentNode.id
				};
				AjaxPost("like.php", parameters, completedAJAX);
			}, false)
		})

		// Add comment button functions
		document.querySelectorAll('.comment_button').forEach(item => {
			item.addEventListener('click', function(ev) {
				if (!user_id)
					return ;
				var parameters = {
					"user_id" : user_id, 
					"image" : item.parentNode.parentNode.parentNode.id,
					"comment" : item.previousSibling.value
				};
				AjaxPost("comment.php", parameters, completedAJAX);
				// Add comment before previous
				var comment_form = item.parentNode.parentNode.nextElementSibling.nextElementSibling;
				var new_comment = document.createElement("p");
				new_comment.classList.add("comment");
				var new_span = document.createElement("span");
				new_span.classList.add("comment_user");
				new_span.innerHTML = username;
				new_comment.appendChild(new_span);
				new_span = document.createElement("span");
				new_span.classList.add("comment_date");
				new_span.innerHTML = formatDate(Date.now());
				new_comment.appendChild(new_span);
				new_span = document.createElement("span");
				new_span.classList.add("comment_text");
				new_span.innerHTML = item.previousSibling.value;
				new_comment.appendChild(new_span);
				comment_form.insertBefore(new_comment, comment_form.firstChild);
				item.previousSibling.value = '';
			}, false)
		})

		// Add delete button functions
		document.querySelectorAll('.delete_image').forEach(item => {
			item.addEventListener('click', function(ev) {
				if (confirm("Delete this image?") == false)
					return ;
				var parameters = {
					"image" : item.parentNode.id
				};
				AjaxPost("delete_image.php", parameters, completedAJAX);
				item.parentNode.style.display = 'none';
			}, false)
		})
		
		if (typeof user_id !== 'undefined')
		{
			document.querySelectorAll('.comment_field').forEach(item => {
				item.disabled = true;
				item.classList.add("disabled");
				item.placeholder = "Log in to comment";
			})
		}
		
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

	function completedAJAX_save_image(response) {
		if (response)
			alert(response);
	}

	function formatDate(date) {
		var d = new Date(date),
			month = '' + (d.getMonth() + 1),
			day = '' + d.getDate(),
			year = d.getFullYear(),
			hour = '' + d.getHours(),
			minute = '' + d.getMinutes(),
			second = '' + d.getSeconds();
	
		if (month.length < 2) 
			month = '0' + month;
		if (day.length < 2) 
			day = '0' + day;
		console.log(month.length);
		if (hour.length < 2) 
			hour = '0' + hour;
		if (minute.length < 2) 
			minute = '0' + minute;
		if (second.length < 2) 
			second = '0' + second;
		var formated = [year, month, day].join('-');
		formated = formated + ' ' + [hour, minute, second].join(':');
		return (formated);
	}

	window.addEventListener('load', startup, false);
})();
