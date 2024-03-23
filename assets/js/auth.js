import { baseURL, formSubmitHandler } from './inc-js/utils.inc.js';

'use strict';
$(function () {
	$('<div id="cover-spin"></div>').appendTo('body'); // spinner
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
		$this.after(
			$("<div/>", {
				html: [
					$("<img>", {
						id: 'show',
						src: baseURL + '/assets/img/eye-show.svg',
						height: '24px',
						width: '24px',
						hidden: 'true'
					}),
					$("<img>", {
						id: 'hide',
						src: baseURL + '/assets/img/eye-hide.svg',
						height: '24px',
						width: '24px'
					})],
				id: 'passeye-toggle-' + i
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

		$("#passeye-toggle-" + i).on("click", function () {
			if ($this.hasClass("show")) {
				$this.attr('type', 'password');
				$this.removeClass("show");
				$(this).find('#show').attr('hidden','true');
				$(this).find('#hide').removeAttr('hidden');
			} else {
				$this.attr('type', 'text');
				$this.addClass("show");
				$(this).find('#show').removeAttr('hidden');
				$(this).find('#hide').attr('hidden','true');
			}
		});
	});
});

document.addEventListener('DOMContentLoaded', () => {
	const form = document.querySelector('.auth-validation');
	const name = form.querySelector('#name');
	const username = form.querySelector('#username');
	const password = form.querySelector('#password');	

	const pattern = {
		name: "^[A-Z][a-z]*( [A-Z][a-z]*)*$",
		username: "^[A-Za-z][A-Za-z0-9_]{7,29}$",
		password: "(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}"
	}

	form.addEventListener('submit', (event) => {
		// Prevent Deafault form submission.
		event.preventDefault();

		if(name) name.setAttribute('pattern', pattern.name);
		if(username) username.setAttribute('pattern', pattern.username);
		if(password) password.setAttribute('pattern', pattern.password);

		if (!form.checkValidity()) {
			event.stopPropagation();
		} else {
			document.getElementById('cover-spin').style.display = 'block';
			const phpPath = baseURL + form.getAttribute('php-execute');
			formSubmitHandler(new FormData(form), phpPath);
		}
		form.classList.add('was-validated');
	});
});