
function like(elem) {
	var request = new XMLHttpRequest();
	request.open('POST', 'main/like', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	request.onreadystatechange = function() {
		if(request.readyState == 4 && request.status == 200) {
			var like = document.querySelector("span[scr_id='"+elem.id+"']");
			if(request.responseText != 'false') {
				like.innerHTML = request.responseText;
			}else {
				var answer = confirm("You must be registered. Click OK to continue.");
				if(answer) {
					document.location.href = "http://localhost:8100/user/index";
				}
			}
		}
	}
	request.send('id='+elem.id);
}