import { baseURL, executePHP, spinner} from './inc-js/utils.inc.js';

const pattern = {
	name: "^[A-Z][a-z]*( [A-Z][a-z]*)*$",
	username: "^[A-Za-z][A-Za-z0-9_]{7,29}$",
	password: "(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,}"
};

'use strict';
$(function () {
	/* SHOW LOADING SCREEN */
	$(spinner).show();

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

		/* FORM ELEMENTS */
		const form = $('.auth-validation');
  	form.find('#name').attr('pattern', pattern.name);
  	form.find('#username').attr('pattern', pattern.username);
  	form.find('#password').attr('pattern', pattern.password);

		form.on('submit', function(event) {
			/* PREVENT DEFAULT FORM SUBMISSION */
			event.preventDefault();

			if (!this.checkValidity()) {
					event.stopPropagation();
			} else {
					/* OPEN SPINNER WINDOW */
					$(spinner).show();

					const phpPath = baseURL + $(this).attr('php-action');
					const requestInit = {
							method: 'POST',
							body: new FormData(this)
					};
					executePHP(phpPath, requestInit);
			}
			$(this).addClass('was-validated');
		});
	});
});

$(window).on("load", function() {
  /* HIDE LOADING SCREEN */
	setTimeout(()=>{
		$(spinner).hide();
	},300)
});