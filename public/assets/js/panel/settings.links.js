var items = [];


$(document).ready(function () {
    bindButtons();

    showItems();
});

function bindButtons() {
    $("#btnAdd").click(function () {
		addItem();
    });

    $("#btnSave").click(function () {
        saveItems();
    });
}

function saveItems() {
	let formData = new FormData();

    formData.append('_token', $("#CSRF_TOKEN").val());

    items.forEach(function (item, key) {
    	formData.append('items[]', JSON.stringify(item));
    });

    $.ajax({
        url: "/systemPanel/linksListUpdate",
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.SUCCESS, "Zapisano pomyślnie!");
            } else {
                showAlert(AlertType.ERROR, "Błąd podczas zapisywania!");
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, "Błąd podczas zapisywania!");
        }
    });
}

function showItems() {

	let m = "";

	items.forEach(function (item, key) {
		m += String.raw`
			<tr>
				<td><i class="` + item.icon + `" data-toggle="tooltip" title="` + item.icon + `"></i></td>
				<td>` + item.title + `</td>
				<td><a href="` + item.url + `" target="_blank"><i class="fas fa-link"></i></a></td>
				<td class="text-right"><button class="btn btn-danger" data-action="delete" data-id="` + item.id + `"><i class="fas fa-trash"></i></button></td>
			</tr>
		`;
	});

	$("#linksList").html(m);
    bindItems();
}

function bindItems() {
	$("[data-action='delete']").each(function() {
		$(this).click(function(){
			let id = parseInt($(this).attr('data-id'));
			removeItem(id);
		});
	});
}

function addItem() {
	let icon = $("#inp_icon").val();
	let title = $("#inp_title").val();
	let url = $("#inp_url").val();

	if(icon.includes("i class"))
		icon = icon.match(`="(.*)">`)[1];


	if(icon.length < 5) {
		showAlert(AlertType.ERROR, "Podaj ikonę!", "#alert01");
		return;
	}

	if(title.length < 5) {
		showAlert(AlertType.ERROR, "Podaj tytuł!", "#alert01");
		return;
	}

	if(url.length < 5) {
		showAlert(AlertType.ERROR, "Podaj url!", "#alert01");
		return;
	}

	let id = 0, tmpObj;

	do {
		id = randomInt(0, 9999999);

		tmpObj = getItemByID(id);

	} while(tmpObj != null);


	let obj = {
		id: id,
		icon: icon,
		title: title,
		url: url
	};

	items.push(obj);
	$("#inp_icon").val("");
	$("#inp_title").val("");
	$("#inp_url").val("");

	showItems();
	alertApplyChanges();
}

function removeItem(id) {
	if(!confirm("Na pewno usunąć ten link?"))
		return;

	showAlert(AlertType.NONE, '', '#alert02');

	let obj = getItemByID(id);

	if(obj == null) {
		showAlert(AlertType.ERROR, "Wystąpił błąd podczas usuwania!", '#alert02');
		return;
	}

	showAlert(AlertType.SUCCESS, "Usunięto pomyślnie!", '#alert02');

	items = arrayRemove(items, obj);

	showItems();
	alertApplyChanges();
}

function getItemByID(id) {
	let result = null;
	items.forEach(function (item, key) {
		if(item.id == id)
			result = item;
	});
	return result;
}

function alertApplyChanges() {
	showAlert(AlertType.INFO, "Zostały wykryte zmiany! Zapisz je!", '#alertInfo');
}