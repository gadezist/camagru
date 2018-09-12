
function toggle() {
	document.getElementById("form-in").classList.toggle("form-signin-left");
	document.getElementById("form-up").classList.toggle("form-signup-left");
	document.getElementById("frame-div").classList.toggle("frame-long");
	document.getElementById("btn-up").classList.toggle("signup-active");
	document.getElementById("btn-in").classList.toggle("signin-inactive");
	document.getElementById("btn-forgot").classList.toggle("forgot-left");
}

function del(elem) {
	var image = document.getElementById(elem.id);
	var request = new XMLHttpRequest();
	request.open('POST', '/user/removeImg', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	request.onreadystatechange = function() {
		if(request.readyState == 4 && request.status == 200) {
			if(request.responseText == 'ok') {
				image.remove();
			}
		}
	}
	request.send('id=' + elem.id);
}



