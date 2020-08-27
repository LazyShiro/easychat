(function ($, window) {

	'use strict';

	let w = $(window),
		boxes = $('.box'),
		wrap = $('.wrap'),
		wrapInit = false,
		ww,
		wh,
		space = 20,
		wrapw,
		wraph,
		spop,
		breakPoint = 768,
		currentPause = null,
		checkEmail = function (em) {
			return (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/).test(em);
		},
		onWindowResize = function () {
			ww = w.width();
			wh = w.height();
			wraph = wrap.height();

			let calc = (wh - wraph) / 2;

			// hide addon in form
			if (ww <= breakPoint)
				$('.input-group-addon').hide();
			else
				$('.input-group-addon').show();

			wrap.stop(true, true).animate({
				'margin-top': calc < space ? space : calc
			}, {
				duration: !wrapInit ? 0 : 1000,
				complete: function () {
					if (!wrapInit) {
						wrapInit = true;
						wrap.hide();
						wrap.css('visibility', 'visible');
						wrap.fadeIn(1000);
					}
				}
			});

			boxes.popover('destroy');
		},
		stepFnc = function (n) {
			n = parseInt(n, 10);
			let b;

			if (n === 25 && spop !== 1) {
				b = $(boxes[0]);
				boxes.removeClass('active-box');
				boxes.popover('destroy');
				b.addClass('active-box');
				spop = 1;
				$.backstretch(bgImages[0], {fade: 300});
				$('.desc').hide();
				$('.desc1').show();
			} else if (n === 50 && spop !== 2) {
				b = $(boxes[1]);
				boxes.removeClass('active-box');
				boxes.popover('destroy');
				b.addClass('active-box');
				spop = 2;
				$.backstretch(bgImages[1], {fade: 300});
				$('.desc').hide();
				$('.desc2').show();
			} else if (n === 75 && spop !== 3) {
				b = $(boxes[2]);
				boxes.removeClass('active-box');
				boxes.popover('destroy');
				b.addClass('active-box');
				spop = 3;
				$.backstretch(bgImages[2], {fade: 300});
				$('.desc').hide();
				$('.desc3').show();
			}
		},
		redo = function (v) {
			$('.pb-item').width(v + '%');

			$('.pb-item').animate({
				width: '100%'
			}, {
				duration: sliderTime,
				easing: 'linear',
				complete: redo,
				step: stepFnc
			});
		};

	redo(25);

	$('.box').click(function (el) {
		let cmp = $(el.currentTarget),
			pbitem = $('.pb-item'),
			v = cmp.attr('data-per');

		if (v === currentPause) {
			redo(v);
			currentPause = null;
		} else {
			pbitem.stop(false, false);
			pbitem.width(v + '%');
			stepFnc(v);
			currentPause = v;
		}
	});

	w.resize(onWindowResize);
	onWindowResize();

	// submit form actions
	$('.semail').removeAttr('disabled');
	$('.sbtn').click(function () {

		let emEl = $('.semail'),
			email = emEl.val(),
			alert = $('.alert'),
			fadeTime = 500,
			showMessage = function (type, text, show) {
				alert.removeClass('alert-info alert-success alert-danger');

				if (type)
					alert.addClass('alert-' + type);

				alert.html(text);

				if (show)
					alert.fadeIn(fadeTime);
				else
					alert.hide();
			};

		showMessage(false, '', false);

		if (!checkEmail(email)) {
			showMessage('danger', 'Invalid email address!', true);

			return;
		}

		showMessage('info', '<i class="fa fa-spinner fa-spin"></i> Loading, please wait...', true);

		$.ajax({
			url: 'subscribe.php',
			type: 'GET',
			dataType: 'jsonp',
			timeout: 10000,
			data: {
				email: email
			},
			beforeSend: function () {
				emEl.attr('disabled', 'disabled');
				showMessage('info', '<i class="fa fa-spinner fa-spin"></i> Loading, please wait...', true);
			}
		}).done(function (response) {

			if (response.success === true) {
				showMessage('success', 'Thank you', true);

				$('.subscribe-form, .alert').delay(2000).fadeOut(500, function () {
					onWindowResize();
				});
			} else
				showMessage('danger', response.message, true);

		}).fail(function () {
			showMessage('danger', 'At the moment we dont ask for your personal data. When money will be low, we will ask for it and quickly resell it to fund our project. Thank you.', true);
			$('.alert').delay(2000).fadeOut(500, function () {
				onWindowResize();
			});
		}).always(function () {
			emEl.removeAttr('disabled');
		});

	});
	$('.input-group-btn').click(function () {
		let account = $("input[name='account']").val();
		let password = $("input[name='password']").val();
		if (account === null || account === undefined || account === "" || password === null || password === undefined || password === "") {
			alert("请填写完整");
			return false;
		}
		$.ajax({
			url: '/api/loginApi/login',
			type: 'post',
			data: JSON.stringify({
				"account": account,
				"password": password
			}),
			dataType: 'json',
			success: function (data) {
				if (data.code === 100000) {
					$.cookie('token', data.data.token, {path: "/"});
					let url = document.location.protocol + '//' + window.location.host;
					window.location.href = url
				} else if (data.code === 100001) {
					if (confirm(data.message + '，是否用此账号密码注册？')) {
						$('.input-group-addon').click();
					}
				} else if (data.code === 900003) {
					alert(data.message);
					window.location.reload();
				} else {
					alert('未知错误，请重试');
					window.location.reload();
				}
			},
			error: function () {
				alert('未知错误，请重试');
				window.location.reload();
			}
		});
	})
	$('.input-group-addon').click(function () {
		let account = $("input[name='account']").val();
		let password = $("input[name='password']").val();
		if (account === null || account === undefined || account === "" || password === null || password === undefined || password === "") {
			alert("请填写完整");
			return false;
		}
		$.ajax({
			url: '/api/loginApi/register',
			type: 'post',
			data: JSON.stringify({
				"account": account,
				"password": password
			}),
			dataType: 'json',
			success: function (data) {
				if (data.code === 100000) {
					$('.input-group-btn').click();
				} else {
					alert(data.message);
					window.location.reload();
				}
			}
		});
	})
})($, window);
