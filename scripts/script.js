var width = 1080;    // Scale the photo width to this
var height = 810;     // Computed based on the input stream

// capture.php vars
var streaming = false;

var video = null;
var canvas = null;
var photo = null;
var snapbutton = null;
var warning = null;
var uploadbutton = null;
var uploadtext = null;
var snap_enabled = false;
var newbutton = null;
var savebutton = null;
var snap_data = null;
var sidebar = null;
var overlaid = null;
var uploaded = false;

var overlay_src = null;
var overlays = [];
var overlaycanvas;
var overlaylist;

// index.php vars
var img_feed = null;

// when page has loaded
function startup() {
	video = document.getElementById('video');
	canvas = document.getElementById('canvas');
	photo = document.getElementById('photo');
	snapbutton = document.getElementById('snapbutton');
	uploadbutton = document.getElementById('uploadbutton');
	uploadtext = document.getElementById('uploadtext');
	newbutton = document.getElementById('newbutton');
	savebutton = document.getElementById('savebutton');
	sidebar = document.getElementById('sidebar');
	overlaid = document.getElementById('overlaid');
	overlaycanvas = document.getElementById('overlaycanvas');
	warning = document.getElementById('warning')
	overlaylist = document.getElementById('overlays')

	img_feed = document.getElementById('img_feed');

	// only on capture.php
	if (video) {
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
		
		// take a new photo
		newbutton.addEventListener('click', function(ev){
			first_step_mode();
		}, false);
		
		// save photo to file and database
		savebutton.addEventListener('click', function(ev){
			var parameters = {
				"username" : username, 
				"image" : snap_data,
				"overlay" : overlaid.src,
				"token" : token
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

		clearphoto();
		reload_user_images();

		document.querySelectorAll('.overlay').forEach(item => {
			item.addEventListener('click', function(ev) {
				overlay_src = item.firstChild;
				if (overlay_src.classList.contains('chosen')) {
					overlay_src.classList.remove("chosen");
					var x = document.querySelector(".chosen");
					if (!x) {
						snap_enabled = false;
						snapbutton.classList.add('hide');
						if (!uploaded)
							warning.classList.remove("hide");
					}
				}
				else {
					overlay_src.classList.add("chosen");
					if (!snap_enabled)
					{
						snap_enabled = true;
						enable_snap_button();
						warning.classList.add("hide");
					}
				}
				index = overlays.indexOf(overlay_src.src);
				if (index > -1)
					overlays.splice(index, 1);
				else
					overlays.push(overlay_src.src);
				merge_overlays();
			}, false)
		})

	}

	// only on index.php
	if (img_feed) {
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
	first_step_mode();
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
			item.nextSibling.classList.add('greyed');
			item.parentNode.parentNode.nextSibling.classList.add('greyed');
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
				"image" : item.parentNode.id,
				"token" : token
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
				"image" : item.parentNode.parentNode.id,
				"token" : token
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
				"comment" : item.previousSibling.value,
				"image_owner" : item.parentNode.parentNode.parentNode.firstChild.innerHTML.substring(1),
				"token" : token
			};
			AjaxPost("comment.php", parameters, completedAJAX);
			reload_all_images();
		}, false)
	})
}

function enable_snap_button()
{
	if (!uploaded)
		snapbutton.classList.remove('hide');
	snapbutton.addEventListener('click', function(ev){
		takepicture();
		ev.preventDefault();
		second_step_mode();
	}, false);
}

// show snapped photo instead of video stream
function takepicture() {
	var context = canvas.getContext('2d');
	if (width && height) {
		canvas.width = width;
		canvas.height = height;
		context.drawImage(video, 0, 0, width, height);
		snap_data = canvas.toDataURL('image/png');
		photo.src = snap_data;
		overlaylist.classList.add('hide');
	} else {
		clearphoto();
	}
}

function uploadpicture(event) {
	photo.src = URL.createObjectURL(event.target.files[0]);
	photo.onload = function () {
		uploaded = true;
		var context = canvas.getContext('2d');
		if (width && height) {
			canvas.width = width;
			canvas.height = height;
			context.drawImage(photo, 0, 0, width, height);
			snap_data = canvas.toDataURL('image/png');
		} else {
			clearphoto();
		}
	}
	second_step_mode();
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

function first_step_mode() {
	uploaded = false;
	overlaylist.classList.remove('hide');
	photo.classList.add("hide");
	video.classList.remove("hide");
	newbutton.classList.add("hide");
	savebutton.classList.add("hide");
	if (snap_enabled)
		snapbutton.classList.remove("hide");
	else
		warning.classList.remove("hide");
	uploadbutton.classList.remove("hide");
	uploadbutton.value = '';
	uploadtext.classList.remove("hide");
}

function second_step_mode() {
	video.classList.add("hide");
	photo.classList.remove("hide");
	snapbutton.classList.add("hide");
	warning.classList.add("hide");
	uploadbutton.classList.add("hide");
	uploadtext.classList.add("hide");
	newbutton.classList.remove("hide");
	savebutton.classList.remove("hide");
}

function merge_overlays() {
	var ctx = overlaycanvas.getContext("2d");
	overlaycanvas.setAttribute('width', width);
	overlaycanvas.setAttribute('height', height);
	ctx.clearRect(0, 0, width, height);
	ctx.globalCompositeOperation = 'source-over';
	var imageObj = new Image();
	var len = overlays.length;
	if (len == 0) {
		overlaid.removeAttribute('src');
		overlaid.classList.add('hide');
	}
	else {
		overlaid.classList.remove('hide');
		i = 0;
		function recursive_overlay() {
			imageObj.src = overlays[i];
			imageObj.onload = function() {
				ctx.drawImage(imageObj, 0, 0, width, height);
				overlaid.src = overlaycanvas.toDataURL('image/png');
				i++;
				if (i < len)
					recursive_overlay();
			};
		}
		recursive_overlay();
	}
}

// After window has loaded
window.addEventListener('load', startup, false);
