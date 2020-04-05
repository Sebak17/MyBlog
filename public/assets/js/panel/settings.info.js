var bgImage;

$(document).ready(function () {

    bgImage = $("#bgImageBox").attr("src");
    bindButtons();
});

function bindButtons() {
    $("#btnSave").click(function () {
        uploadArticle();
    });

    $("#input_bgImage").change(function (event) {
        if (this.files && this.files.length == 1) {
            uploadImage(this);
        }
    });
}

function uploadImage(input) {
    let formData = new FormData();
    formData.append('upload', input.files[0]);

    $.ajax({
        url: "/systemPanel/uploadPhoto",
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.success == true) {
                bgImage = data.url;
                showAlert(AlertType.SUCCESS, "Zdjęcie zostało dodane pomyślnie!");
                showTitleImage();
            } else {
                showAlert(AlertType.ERROR, "Błąd podczas dodawania zdjęcia!");
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, "Błąd podczas dodawania zdjęcia!");
        }
    });
}

function showTitleImage() {
    $("#bgImageBox").attr("src", bgImage);
    $("#bgImageBox").parent().removeClass('d-none');
}

function uploadArticle() {
    let formData = new FormData();

    formData.append('_token', $("#CSRF_TOKEN").val());

    formData.append('title', $("#inp_title").val());
    formData.append('subtitle', $("#inp_subtitle").val());
    formData.append('text', $("#inp_text").val());

    formData.append('bgImage', bgImage);

    $.ajax({
        url: "/systemPanel/siteInfoUpdate",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.SUCCESS, "Zmiany zostały zapisane pomyślnie!");
            } else {
                if(data.error != null)
                    showAlert(AlertType.ERROR, data.error);
                else
                    showAlert(AlertType.ERROR, "Błąd podczas zapisywania zmian!");
            }
        },
        error: function () {}
    });
}