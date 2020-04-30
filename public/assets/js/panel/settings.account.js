$(document).ready(function ($) {
    bindKeys();
});

var isChanging = false;

function bindKeys() {
    $('#btnChangeLogin').click(function () {
        changeLogin();
    });

    $("#inp_password").keyup(function (e) {
    	if(e.keyCode == 13) {
    		changeLogin();
    	}
    });

    $('#btnChangePass').click(function () {
        changePassword();
    });

    $("#inp_password_new2").keyup(function (e) {
    	if(e.keyCode == 13) {
    		changePassword();
    	}
    });
}

function changeLogin() {
	if(isChanging)
		return;

	let login = $("#inp_login").val();
	let password = $("#inp_password").val();

    let formData = new FormData();
    formData.append('_token', $("#CSRF_TOKEN").val());

    formData.append('login', login);
	formData.append('password', password);

    showAlert(AlertType.LOADING, "Zmiana danych...", '#alert01');
    isChanging = true;

    $.ajax({
        url: "/systemPanel/changeLogin",
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.SUCCESS, "Dane zostały zmienione!", '#alert01');

                setTimeout(function() {
                	location.reload();
                }, 500);
            } else {
            	if(data.error != null)
            		showAlert(AlertType.ERROR, data.error, '#alert01');
            	else
                	showAlert(AlertType.ERROR, "Błąd podczas zmiany danych!", '#alert01');
            }
            isChanging = false;
        },
        error: function () {
            showAlert(AlertType.ERROR, "Błąd podczas zmiany danych!", '#alert01');
            isChanging = false;
        }
    });
}

function changePassword() {
	if(isChanging)
		return;

	let password_old = $("#inp_password_old").val();
	let password_new1 = $("#inp_password_new1").val();
	let password_new2 = $("#inp_password_new2").val();

	if(password_new1 != password_new2) {
		showAlert(AlertType.ERROR, "Hasła nie są identyczne!", '#alert02');
		return;
	}

    let formData = new FormData();
    formData.append('_token', $("#CSRF_TOKEN").val());

    formData.append('password_old', password_old);
    formData.append('password_new', password_new1);

    showAlert(AlertType.LOADING, "Zmiana danych...", '#alert02');
    isChanging = true;
    $.ajax({
        url: "/systemPanel/changePassword",
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.SUCCESS, "Dane zostały zmienione!", '#alert02');

                setTimeout(function() {
                	location.reload();
                }, 500);
            } else {
            	if(data.error != null)
            		showAlert(AlertType.ERROR, data.error, '#alert02');
            	else
                	showAlert(AlertType.ERROR, "Błąd podczas zmiany danych!", '#alert02');
            }
            isChanging = false;
        },
        error: function () {
            showAlert(AlertType.ERROR, "Błąd podczas zmiany danych!", '#alert02');
            isChanging = false;
        }
    });
}