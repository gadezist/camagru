var modal = document.getElementById("modal");
var btn_cancel = document.getElementById("cancel");
var btn_save = document.getElementById("save");

var modal_comm = document.getElementById("modal-comments");
var cancel = document.getElementById("btn-cancel");
var btn_send_comment = document.querySelector("#btn-comment");


window.onload = function() {

	if(btn_snap) {
		btn_snap.onclick = function() {
			modal.style.display = "block";
		}
	}
	btn_cancel.onclick = function() {
		modal.style.display = "none";
	}

	window.onclick = function(event) {
		if(event.target == modal) {
			modal.style.display = "none";
		}
	}

	btn_save.onclick = function() {
		var cnv = document.getElementById("canvas");
		var ctx = cnv.getContext('2d');
		var img = cnv.toDataURL('image/png').replace('data:image/png;base64', '');
		var request = new XMLHttpRequest();
		request.open('POST', '/main/saveImg', true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		request.onreadystatechange = function() {
			if(request.readyState == 4 && request.status == 200) {
				document.getElementById("scr").innerHTML = request.responseText;
			}
		}
		request.send('img='+img);
		modal.style.display = "none";
	}

	window.onclick = function(event) {
		if(event.target == modal_comm) {
			modal_comm.style.display = "none";
		}
	}

	cancel.onclick = function() {
		modal_comm.style.display = "none";
	}



}


function comment(elem) {
	document.getElementById("image").src = elem.name;
	var request_comments = new XMLHttpRequest();
	var $url = 'main/comments/?screen_id=' + elem.id;
	request_comments.open('GET', $url, true);
	request_comments.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request_comments.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	request_comments.onreadystatechange = function() {
		if(request_comments.readyState == 4 && request_comments.status == 200) {
			var arr = request_comments.responseText;
			if(arr == 'false') {
				var answer = confirm("You must be registered. Click OK to continue.");
				if(answer) {
					document.location.href = "http://localhost:8100/user/index";
					return ;
				}
			}else {
				arr = JSON.parse(arr);
				var form = document.querySelector(".modal-comments");
				var now = document.querySelector(".all-comments");
				if(now) {
					now.parentNode.removeChild(now);
				}
					var comments = document.createElement('div');
					comments.className = "all-comments";
					form.appendChild(comments);
					arr.forEach(function(item, i, arr) {
						addComment(item);
					});
					modal_comm.style.display = "block";
				}
		}
	}
	request_comments.send();
	

	btn_send_comment.onclick = function() {
		var modal_text = document.querySelector("textarea");
		var params = 'text='+modal_text.value+'&'+'screen_id='+elem.id;
		var request = new XMLHttpRequest();
		request.open('POST', '/main/saveComment', true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

		request.onreadystatechange = function() {
			if(request.readyState == 4 && request.status == 200) {
				var comment = request.responseText;
				if(comment != 'false') {
					comment = JSON.parse(comment);
					addComment(comment);
				}else {
					alert('Sorry, some problem. Try again later!');
				}
			}
		}
		request.send(params);
	}

	function addComment(items) {
		var all = document.querySelector(".all-comments");
		var div = document.createElement('div');
		div.className = "single-comment";
		var div_date = document.createElement('div');
		var div_login = document.createElement('div');
		var p_text = document.createElement('p');
		var text = document.createTextNode(items["massage"]);
		var login = document.createTextNode(items["login"]);
		var date = document.createTextNode(items["date_massage"]);
		div_login.className = "login-comment";
		div_date.className = "date-comment";
		p_text.appendChild(text);
		div_login.appendChild(login);
		div_date.appendChild(date);
		div.appendChild(div_login);
		div.appendChild(div_date);
		div.appendChild(p_text);
		all.appendChild(div);
	}

}
