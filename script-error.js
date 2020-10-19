function check(event) {
	console.log("checking mail and password..")
	var name = document.querySelector("[name=error_name]");
	var text = document.querySelector("[name=error_text]");

	if(name == "") {
		event.preventDefault()
		name.classList.add("error-push")
		console.log("missing name of error")
	}

	if(text == "") {
		event.preventDefault()
		text.classList.add("error-push")
		console.log("missing text of error")
	}
}