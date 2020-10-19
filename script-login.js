function check_MailPass (event) {
	console.log("checking mail and password..")
	var email = document.querySelector("[name=email]");
	var pass = document.querySelector("[name=password]");

	if (email.value.indexOf("@") == -1) {
		event.preventDefault()
		email.classList.add("error-input")
		console.log("missing @")
	} 

	if (email.value == "") {
		event.preventDefault()
		email.classList.add("error-input")
		console.log("missing mail address")

	}

	if (pass.value == "") {
		event.preventDefault()
		pass.classList.add("error-input")
		console.log("missing password")		}
}


var form = document.querySelector("form")
form.addEventListener("submit", check_MailPass) 