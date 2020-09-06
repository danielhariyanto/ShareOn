var signUpFormHTML = null;


$(document).on('click', "#big-signon-button", function () {
	$("#big-signup-button").remove();
	$("#big-signon-button").remove();
	$("body").css("background-image", "url('./icons/space-desaturated.svg')");
	$("body").append("<div id='login-box' style='opacity:0;'></div>");
	$.get("/geters/signon-form.php", function (data) {
		history.pushState({
			id: 'sign-up'
		}, 'Sign-On', '/register/sign-on');
		$("#login-box").append(data);

		$("#login-box").animate({
			opacity: 1
		}, 200);

	});
});
$(document).on('click', "#big-signup-button", function () {
	window.location.href = "/signUp.html";
});
$(document).on('click', "#register-button", function () {
	var fname = $("#name-register").val();
	var lname = $("#surname-register").val();
	var pass = $("#password-register").val();
	var username = $("#username-register").val();
	const date = new Date($("#date-register").val());
	var day = date.getDay();
	var month = date.getMonth();
	var year = date.getFullYear();
	$.post('/writers/new_user_checker.php', { fname: fname, lname: lname, username: username, pass: pass, day: day, month: month, year: year }, function (data) {
		window.location = "/";
	});
});

$(document).on("click", "#login-button", function () {
	var username = $('#username-login').val();
	var password = $('#password-login').val();
	$.post('/loger.php', { username: username, password: password }, function (data) {
		if (IsJsonString(data)) {
			window.location = "/";
		} else {
			alert("username or password may be wrong!");
		}
	});
});
$(document).on("keydown", ".log", function (e) {
	var code = e.which;
	if (code == 13) {
		var username = $('#username-login').val();
		var password = $('#password-login').val();
		$.post('/loger.php', { username: username, password: password }, function (data) {
			if (IsJsonString(data)) {
				window.location = "/";
			} else {
				alert("username or password may be wrong!");
			}
		});
	}
});

$(document).on("click", "#sign-on-button", function () {
	history.pushState({
		id: 'sign-up'
	}, 'Sign-On', '/signUp.php#go-to-sign-on');
	signUpFormHTML = `<form id="sign-up-form" action="" class="col-lg-11">` + $("#sign-up-form").html() + `</form>`;
	$("#sign-up-form").replaceWith(
		`<form action="" id="sign-on-form" class="col-lg-11">
        <h3 style="line-height: 3;" class="animate__animated animate__slideInUp">Sign on with your username</h3>
		<div id="inputs">
			<div class="form-group">
                <input id="username-login" class="log form-control inputs" placeholder="Username" type="text">
            </div>
            <div class="form-group">
            	<input id="password-login" class="log form-control inputs" placeholder="" type="password">
			</div>
		  <br>
		</div>
		<button type="button" class="btn btn-success animate__animated animate__bounceIn" id="login-button">SIGN
            ON</button>
		<br>
		<p class="animate__animated animate__zoomIn"> &copy ShareOn</p>
	</form>`);
	$("#sign-on-header").replaceWith(`<p id="sign-up-header">Not a member?    <a id="sign-up-button" class="btn btn-sm btn-primary">Sign
              Up</a></p>`);
})

$(document).on("click", "#sign-up-button", function () {
	$("#sign-on-form").replaceWith(signUpFormHTML);
	$("#sign-up-header").replaceWith(`<p id="sign-on-header">Already a member? <a id="sign-on-button" class="btn btn-sm btn-success">Sign
			  On</a></p>`);
	history.pushState({
		id: 'sign-up'
	}, 'Sign-On', '/signUp.php');
})
