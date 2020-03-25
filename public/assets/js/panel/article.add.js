var editor;

$(document).ready(function () {

    let options = {
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
            // The URL that the images are uploaded to.
            uploadUrl: '/systemPanel/uploadPhoto',

            // Headers sent along with the XMLHttpRequest to the upload server.
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
        });;
});