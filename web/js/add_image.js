
var vide_play = document.querySelector(".play");
var plus = document.querySelector("#plus");
var minus = document.querySelector("#minus");
var butt = document.getElementById("snap-user");
var i = -1;

function add_image(elem) {
	var img = elem.firstChild.cloneNode(true);
	img.className = "add_img";
	img.setAttribute("onMouseDown", "move(this)");
/*	img.setAttribute("onMouseUp", "clear(this)");*/
	vide_play.appendChild(img);
	i++;
	if(butt) {
		butt.removeAttribute('disabled');
	}
}

plus.onclick = function() {
	var picture = document.querySelectorAll(".add_img")[i];
	if(picture) {
		var width = picture.clientWidth;
	}
	if(width < 600) {
		picture.setAttribute("width", width + 20);
	}
}

minus.onclick = function() {
	var picture = document.querySelectorAll(".add_img")[i];
	if(picture) {
		var width = picture.clientWidth;
	}
	if(width > 50) {
		picture.setAttribute('width', width - 20);
	}
}

function move(elem) {
	/*var pic = document.querySelectorAll(".add_img")[i];*/
	delta_x = 0;
	delta_y = 0;
	document.onmousedown = function() {return false}
	document.onmousemove = function(e) {
		elem.style.cursor = "move";
		var x = e.clientX;
		var y = e.clientY;
		var x_pic = elem.offsetLeft;
		var y_pic = elem.offsetTop;
		var delta_x = x_pic - x;
		var delta_y = y_pic - y;
	
		document.onmousemove = function(e) {
			x = e.clientX;
			y = e.clientY;
			new_x = delta_x + x;
			new_y = delta_y + y;
			elem.style.left = new_x + "px";
			elem.style.top = new_y + "px";
		}
	}



	document.onmouseup = function() {
		elem.style.cursor = "auto";
		document.onmousedown = function() {};
		document.onmousemove = function() {};
	}
}