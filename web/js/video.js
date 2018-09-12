
var video = document.getElementById("video");
var canvas = document.getElementById("canvas");
var context = canvas.getContext('2d');
var file = document.getElementById("file");
var delete_img = document.getElementById("delete_img");
var delete_all = document.getElementById("delete_all");
var left = document.querySelector(".left-side");
var side = document.querySelector(".side");
var btn_snap = document.getElementById("snap-user");

if(window.innerWidth < 1275) {
	
	left.style.marginRight = "0px";
	side.style.minWidth = "695px";
}else{
	left.style.marginRight = "10px";
	side.style.minWidth = "";
}

window.addEventListener("resize", function(){
	if(window.innerWidth < 1275) {
		left.style.marginRight = "0px";
		side.style.minWidth = "695px";
	}else{
		left.style.marginRight = "10px";
		side.style.minWidth = "";
	}
});


function enter() {
	

// Get access to the camera!
if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    // Not adding `{ audio: true }` since we only want video now
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
    	video.srcObject = stream;
    	video.play();
    });
} else if(navigator.getUserMedia) { // Standard
	navigator.getUserMedia({ video: true }, function(stream) {
		video.src = stream;
		video.play();
	}, errBack);
} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
	navigator.webkitGetUserMedia({ video: true }, function(stream){
		video.src = window.webkitURL.createObjectURL(stream);
		video.play();
	}, errBack);
} else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
	navigator.mozGetUserMedia({ video: true }, function(stream){
		video.src = window.URL.createObjectURL(stream);
		video.play();
	}, errBack);
}
}

// Trigger photo take
if(btn_snap) {
	btn_snap.addEventListener("click", function() {
		var all_images = document.querySelectorAll(".add_img");
		if(video.poster) {
			var image = new Image(640, 480);
			image.src = video.poster;
			context.drawImage(image, 0, 0, 640, 480);
		} else {
			context.drawImage(video, 0, 0, 640, 480);
		}
		if(all_images) {
			all_images.forEach(function(item, i, arr) {
				var width = item.clientWidth;
				var heigth = item.clientHeight;
				var style = getComputedStyle(item);
				var x = parseInt(style.left, 10);
				var y = parseInt(style.top, 10);
				console.log(Number(x));
				console.log(y);
				context.drawImage(item, x, y, width, heigth);
			});
		}
	});
}

file.onchange = function() {
	var xhr = new XMLHttpRequest();
	var form = new FormData();
	var upload_file = file.files[0];

	form.append('file', upload_file);
	xhr.open("POST", "/main/uploadFile", true);
	xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	xhr.onreadystatechange = function() {
		if(xhr.readyState == 4 && xhr.status == 200) {
			document.querySelector('video').poster = xhr.responseText;
		}
	}
	xhr.send(form);
}

if(delete_img) {
	delete_img.onclick = function() {
		var img = document.querySelectorAll(".add_img");
		if(img[img.length - 1]){
			img[img.length - 1].remove();
			i--;
		}
		if(i < 0) {
			butt.setAttribute('disabled', 'disabled');
		}
	}
}

if(delete_all) {
	delete_all.onclick = function() {
		var img = document.querySelectorAll(".add_img");
		if(img) {
			img.forEach(function(item, i, arr) {
				item.remove();
			});
			i = -1;
			butt.setAttribute('disabled', 'disabled');
		}
	}
}