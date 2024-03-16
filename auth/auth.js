'use strict';
// BASE URL FOR FETCHING CONTENT
var baseURL = getBaseURL('/nsp-dbms-project'); // Define root folder name with forward slash (/) !importatant

$(function () {
	$("input[type='password'][data-eye]").each(function (i) {
		var $this = $(this),
			id = 'eye-password-' + i,
			el = $('#' + id);

		$this.wrap($("<div/>", {
			style: 'position:relative',
			id: id
		}));

		$this.css({
			paddingRight: 60
		});
		$this.after($("<div/>", {
			html: $("<img>",{
				src: baseURL + '/assets/img/eye-show.svg',
    			height: '24px',
    			width: '24px'
			}),
			id: 'passeye-toggle-' + i,
		}).css({
			position: 'absolute',
			right: 10,
			top: ($this.outerHeight() / 2) - 12,
			cursor: 'pointer',
		}));

		$this.after($("<input/>", {
			type: 'hidden',
			id: 'passeye-' + i
		}));

		var invalid_feedback = $this.parent().parent().find('.invalid-feedback');

		if (invalid_feedback.length) {
			$this.after(invalid_feedback.clone());
		}

		$this.on("keyup paste", function () {
			$("#passeye-" + i).val($(this).val());
		});
		$("#passeye-toggle-" + i).on("click", function () {
			if ($this.hasClass("show")) {
				$this.attr('type', 'password');
				$this.removeClass("show");
				$(this).html($("<img>",{
					src:  baseURL + '/assets/img/eye-show.svg',
					height: '24px',
					width: '24px'
				}));
			} else {
				$this.attr('type', 'text');
				$this.val($("#passeye-" + i).val());
				$this.addClass("show");
				$(this).html($("<img>",{
					src:  baseURL + '/assets/img/eye-hide.svg',
					height: '24px',
					width: '24px'
				}));
			}
		});
		$('<div id="cover-spin"></div>').appendTo('body'); // spinner
	});
});

document.addEventListener('DOMContentLoaded', () => {
	const form = document.querySelector('.auth-validation');
	const name = form.querySelector('#name');
	const username = form.querySelector('#username');
	const password = form.querySelector('#password');	

	const errMsg = {
		name: "Names must start with a capital letter and have capital letters after spaces. " +
			  "Only alphabetical characters are allowed.",

		username: "The username must start with a letter and can contain only " + 
				  "letters, digits, or underscores, with a length between 8 and 30 characters.",

		password: "Please use at least 8 characters with a combination of uppercase and " +
				  "lowercase letters, digits, and symbols."
	};

	const pattern = {
		name: "^[A-Z][a-z]*( [A-Z][a-z]*)*$",
		username: "^[A-Za-z][A-Za-z0-9_]{7,29}$",
		password: "(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}"
	}

	form.addEventListener('submit', (event) => {
		// Prevent Deafault form submission.
		event.preventDefault();

		validityCheck(name, pattern.name, errMsg.name);
		validityCheck(username, pattern.username, errMsg.username);
		validityCheck(password, pattern.password, errMsg.password);

		if (!form.reportValidity()) {
			event.stopPropagation();
			form.classList.add("was-validated"); //dont remove
		} else {
			document.getElementById('cover-spin').style.display = 'block';
			form.classList.add("was-validated"); //dont remove
			authenticate(form, form.getAttribute('execute'));
		}
	});
});

function validityCheck(element, pattern, errorMessage) {
	if (element === undefined || element === null) return;
	element.setAttribute('pattern', pattern);
	// Set custom validation message.
	if (element.validity.patternMismatch) {
		element.setCustomValidity(errorMessage);
	} else {
		element.setCustomValidity('');
	}
	// Check validity then set valid/invalid class appropriately.
	if (element.checkValidity()) {
		element.classList.remove('is-invalid');
		element.classList.add('is-valid');
	} else {
		element.classList.remove('is-valid');
		element.classList.add('is-invalid');
	}
}

function authenticate(form, filepath) {
	if(!filepath) return;
	var file_url = baseURL + filepath;
	fetch(file_url, { method:'POST', body: new FormData(form) })
	.then(response => {
		if (!response.ok)
			throw new Error('Network response was not ok');
		return response.json();
	})
	.then(result => {
		switch(result.status){
			case 200: if(result.message.length) alert(result.message);
				if(result.redirect) window.location.href = baseURL + result.redirect;
				break;
			case 500: alert(result.message); // Bad response
				window.location.reload();
				break;
			default: alert("Unexpected error occurred. Please try again later :-(");
				window.location.reload();
				break;
		}
	})
	.catch(error => {
		var err_msg = [
			`An error has occurred, most likely due to an attempt to execute`,
			`server-side scripts on a GitHub page, which is not permitted.`, 
			`Please run this project on a PHP-supported server for seamless functionality.`,
			`\nThank you for your understanding and cooperation.\n Ignore: ${error}`
		];
		alert(err_msg.join(" "));
		window.location.reload();
	});
}

function getBaseURL(rootFolderName) {
	if(window.location.pathname.includes(rootFolderName)){
	  return window.location.origin + rootFolderName;
	} else {
	  return window.location.origin;
	}
}