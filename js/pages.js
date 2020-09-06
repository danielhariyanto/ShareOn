var scroll = window.scroll || {};
scroll.toScroll = [];
scroll.initiated = false;
$(document).on('click', "#header-sign-on", function () {
	window.location = "/signUp.php#go-to-sign-on";

});
$(document).on('click', "#header-sign-up", function () {
	window.location = "/signUp.php";
});
$(document).on("click", function () {
	if (!$("#header-dropdown-content").hasClass("invisible") && !$("#header-dropdown-content").hasClass("cooling"))
		$("#header-dropdown-content").addClass("invisible");
});
$(document).on('click', "#header-dropdown", function () {
	if ($("#header-dropdown-content").hasClass("invisible")) {
		$("#header-dropdown-content").addClass("cooling");
		$("#header-dropdown-content").removeClass("invisible");
		setTimeout(function () { $('#header-dropdown-content').removeClass('cooling'); }, 500);
	} else {
		$("#header-dropdown-content").addClass("invisible");
	}
});
$(document).on('click', '#main-notification-bell', function () {
	$('#main-notification-bell').attr('src', '/icon/bell-white.png');
	if (!$("#alert-box").hasClass("cooling")) {
		$("#alert-box").addClass("cooling");
		setTimeout(function () {
			$("#alert-box").removeClass("cooling")
		}, 300);
		if ($("#alert-box").hasClass("invisible")) {
			$("#alert-box").removeClass("invisible");
			$("#alert-box").empty();
			$("#alert-box").append("<div class='bar'> </div><div id='notifications-holder'></div>");
			$.get("/geters/notifications.php", function (data) {
				data = JSON.parse(data);
				// alert("101");

				for (var i = 0; i < data.length; i++) {
			// alert("102");
					//assume all the jason files contains the description and time
					addNotification("#notifications-holder",data[i]);

				}

			});

		}else{
			$("#alert-box").addClass("invisible");
		}
	}
});
$(document).on('click', "#sign-out", function () {
	window.location = "/logout.php";
});
$(document).on('click', "#delete-account", function () {
	$("body").append(`
        <div class= 'deletion_overlay'>
            <div id='add_popup'>
                <div style="text-align: center;">Are you sure you want to permanently delete your account? This cannot be reversed.</div>
                <br>
                <div style="text-align: center;">
                <input type='button' value='yes' id='confirmDelete' class='yes'>
                <input type='button' value='no' id="noDeletion">
                </div>
            </div>
        </div>`);
});

$(document).on("click", "#confirmDelete", function () {
	window.location = "/deleteAccount.php";
});

$(document).on("click", "#noDeletion", function () {
	$(".deletion_overlay").remove();
});
