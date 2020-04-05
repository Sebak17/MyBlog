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
	$("#btnSave").click(function(){
		updateDescription();
	});
}

function updateDescription() {
    let formData = new FormData();
    formData.append('_token', $("#CSRF_TOKEN").val());

    formData.append('text', editor.getData());

    $.ajax({
        url: "/systemPanel/aboutMeUpdate",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.SUCCESS, "Zakładka o mnie została zapisana pomyślnie!");
            } else {
                if(data.error != null)
                    showAlert(AlertType.ERROR, data.error);
                else
                    showAlert(AlertType.ERROR, "Błąd podczas aktuzalizowania zakładki o mnie!");
            }
        },
        error: function () {}
    });
}