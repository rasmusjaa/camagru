(function() {

	var width = 1080;    // Scale the photo width to this
	var height = 0;     // Computed based on the input stream
	
	// capture.php vars
	var streaming = false;

	var video = null;
	var canvas = null;
	var photo = null;
	var snapbutton = null;
	var newbutton = null;
	var savebutton = null;
	var snap_data = null;
	var sidebar = null;
	var overlaid = null;

	var overlay_src = null;

	// index.php vars
	var img_feed = null;
	
	// when page has loaded
	function startup() {
		video = document.getElementById('video');
		canvas = document.getElementById('canvas');
		photo = document.getElementById('photo');
		snapbutton = document.getElementById('snapbutton');
		newbutton = document.getElementById('newbutton');
		savebutton = document.getElementById('savebutton');
		sidebar = document.getElementById('sidebar');
		overlaid = document.getElementById('overlaid');

		img_feed = document.getElementById('img_feed');

		// only on capture.php
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
			
			// save photo to file and database
			savebutton.addEventListener('click', function(ev){
				var parameters = {
					"username" : username, 
					"image" : snap_data
				};
				AjaxPost("save_image.php", parameters, completedAJAX_save_image);
			}, false);

			// fill photo with black
			function clearphoto() {
				var context = canvas.getContext('2d');
				context.fillStyle = "#000";
				context.fillRect(0, 0, canvas.width, canvas.height);
		
				snap_data = canvas.toDataURL('image/png');
				photo.setAttribute('src', snap_data);
			}
			
			// show snapped photo instead of video stream
			function takepicture() {
				var context = canvas.getContext('2d');
				if (width && height) {
					canvas.width = width;
					canvas.height = height;
					context.drawImage(video, 0, 0, width, height);
					// var x = document.querySelector(".chosen");
					// if (x)
					// 	context.drawImage(x, 0, 0, width, height);
					// overlaid.src = '';
		
					snap_data = canvas.toDataURL('image/png');
					photo.setAttribute('src', snap_data);
				} else {
				  clearphoto();
				}
			}

			clearphoto();
			reload_user_images();

			document.querySelectorAll('.overlay').forEach(item => {
				item.addEventListener('click', function(ev) {
					var x = document.querySelector(".chosen");
					if (x)
						x.classList.remove("chosen");
					overlay_src = item.firstChild;
					overlay_src.classList.add("chosen");
					overlaid.src = overlay_src.src;
					console.log(overlay_src);
				}, false)
			})
		}

		// only on index.php
		if (img_feed)
		{
			reload_all_images();
		}

	}

	/*				*/
	/*	AJAX		*/
	/*				*/

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

	/*				*/
	/*	FUNCTIONS	*/
	/*				*/

	function reload_user_images()
	{
		var parameters = {
			"function" : 'reload_user_images',
			"value1" : username
		};
		AjaxPost("functions.php", parameters, completedAJAX_reload_user_images);
	}

	function reload_all_images()
	{
		var parameters = {
			"function" : 'reload_all_images'
		};
		AjaxPost("functions.php", parameters, completedAJAX_reload_all_images);
	}

	function completedAJAX(response) {
		if (response)
			alert(response);
	}

	function completedAJAX_save_image(response) {
		if (response)
			alert(response);
		photo.classList.add("hide");
		video.classList.remove("hide");
		newbutton.classList.add("hide");
		savebutton.classList.add("hide");
		snapbutton.classList.remove("hide");
		reload_user_images();
	}

	function completedAJAX_reload_user_images(response) {
		sidebar.innerHTML = response;
		add_delete_button_functions();
	}
	
	function completedAJAX_reload_all_images(response) {
		img_feed.innerHTML = response;
		if (typeof user_id == 'undefined' || !user_id)
		{
			document.querySelectorAll('.comment_field').forEach(item => {
				item.disabled = true;
				item.placeholder = "Log in to comment";
				item.classList.add("disabled");
			})
		}
		else
		{
			add_like_button_functions();
			add_comment_button_functions();
		}
	}

	function add_delete_button_functions()
	{
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
	}

	function add_like_button_functions()
	{
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
	}

	function add_comment_button_functions()
	{
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
				reload_all_images();
			}, false)
		})
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

	// After window has loaded
	window.addEventListener('load', startup, false);
})();
