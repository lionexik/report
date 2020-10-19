function check_MailPass (event) {
	console.log("checking mail and password..")
	var email = document.querySelector("[name=email]");
	var pass = document.querySelector("[name=password]");
	var pass_again = document.querySelector("[name=password_again]");

	if (email.value.indexOf("@") == -1) {
		event.preventDefault()
		email.classList.add("error-registration")
		console.log("missing @")
	} 

	if (email.value == "") {
		event.preventDefault()
		email.classList.add("error-registration")
		console.log("missing mail address")

	}

	if (pass.value == "") {
		event.preventDefault()
		pass.classList.add("error-registration")
		console.log("missing password")	

	}

	if (pass_again.value == "") {
		event.preventDefault()
		pass_again.classList.add("error-registration")
		console.log("missing password again")	
	}

	if (pass.value != pass_again.value) {
		event.preventDefault()
		pass_again.classList.add("error-registration")
		console.log("different passwords")
	}
}

var form = document.querySelector("form")
form.addEventListener("submit", check_MailPass)
