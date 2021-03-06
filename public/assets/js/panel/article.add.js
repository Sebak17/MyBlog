var editor;

var titleImage = '';

$(document).ready(function () {

    loadEditor();

    bindButtons();
});

function loadEditor() {
    let options = {
        allowedContent: true,
        toolbar: {
            items: [
                'heading',
                '|',
                'bold',
                'italic',
                'underline',
                'link',
                'bulletedList',
                'numberedList',
                '|',
                'alignment',
                'indent',
                'outdent',
                '|',
                'fontFamily',
                'fontSize',
                'fontColor',
                'fontBackgroundColor',
                'highlight',
                '|',
                'horizontalLine',
                'removeFormat',
                '|',
                'imageUpload',
                'blockQuote',
                'insertTable',
                'mediaEmbed',
                'undo',
                'redo'
            ]
        },
        language: 'pl',
        image: {
            toolbar: [
                'imageTextAlternative',
                'imageStyle:full',
                'imageStyle:side'
            ]
        },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells'
            ]
        },
        simpleUpload: {
            uploadUrl: '/systemPanel/uploadPhoto',

            headers: {
                'X-CSRF-TOKEN': $("#CSRF_TOKEN").val(),
            }
        },
        licenseKey: '',
    };

    ClassicEditor
        .create(document.querySelector('#editorContext'), options)
        .then(newEditor => {
            editor = newEditor;
        })
        .catch(error => {
            console.error(error);
        });
}

function bindButtons() {
    $("#btnAddArticle").click(function () {
        uploadArticle();
    });

    $("#input_titleImage").change(function (event) {

        if (this.files && this.files.length == 1) {
            uploadImage(this);
        }
    });
}

function isFormValid() {
    let title = $("#inp_title").val();
    let tag = $("#inp_tag").val();
    let description = $("#inp_description").val();

    let status = $("#inp_status").val();

    let text = editor.getData();

    return true;
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
                titleImage = data.name;
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
    $("#titleImageBox").attr("src", "/uploads/images/" + titleImage);
    $("#titleImageBox").parent().removeClass('d-none');
}

function uploadArticle() {
    if (!isFormValid())
        return;
    let formData = new FormData();

    formData.append('_token', $("#CSRF_TOKEN").val());
    
    formData.append('status', $("#inp_status").val());

    formData.append('author', $("#inp_author").val());

    formData.append('title', $("#inp_title").val());
    formData.append('tag', $("#inp_tag").val());
    formData.append('description_short', $("#inp_description").val());

    formData.append('titleImage', titleImage);

    formData.append('text', editor.getData());

    $.ajax({
        url: "/systemPanel/articleAdd",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.SUCCESS, "Artykuł został dodany pomyślnie!");
                setTimeout(function(){
                    window.location.href = data.url;
                }, 500);
            } else {
                if(data.error != null)
                    showAlert(AlertType.ERROR, data.error);
                else
                    showAlert(AlertType.ERROR, "Błąd podczas dodawania artykułu!");
            }
        },
        error: function () {}
    });
}