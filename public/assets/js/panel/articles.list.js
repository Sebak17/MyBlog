$(document).ready(function () {

    bindButtons();
    
    loadArticles();
});

function bindButtons() {
    $("#btnSearch").click(function () {
        loadArticles();
    });
}

function loadArticles() {

	let id = $("#inp_id").val();
	let title = $("#inp_title").val();
	let tag = $("#inp_tag").val();

	let params = {
		id: id,
		title: title,
		tag: tag,
	};

    $.ajax({
        url: "/systemPanel/articlesList",
        method: "POST",
        data: params,
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.NONE);

                let m = "";


                if(data.articles == null || data.articles.length == 0) {
                	$("#articlesAmount").html("0");
                	$("#articlesList").html(String.raw`
						<tr>
							<td colspan="6"><i class="fas fa-exclamation"></i> Nie znaleziono artykułów!</td>
						</tr>`);
                	return;
                }

                $("#articlesAmount").html(data.articles.length);

                 Object.values(data.articles).forEach(function (item, key) {
                    m += String.raw`
						<tr>
							<td class="text-center">
								<h5>
									<span class="badge badge-primary">` + item.id + `</span>
								</h5>
							</td>
							<td>` + item.title + `</td>
							<td>` + item.statusName + `</td>
							<td>` + item.tag + `</td>
							<td>` + item.createdAt + `</td>
							<td>
                                <a href="` + item.statsURL + `">
                                    <button class="btn btn-info btn-sm w-100 mb-2"><i class="fas fa-chart-bar"></i> Statystyki</button>
                                </a>
								<a href="` + item.editURL + `">
									<button class="btn btn-success btn-sm w-100"><i class="fas fa-edit"></i> Edytuj</button>
								</a>
							</td>
						</tr>
                    `;
                });

                $("#articlesList").html(m);


            } else {
                if(data.error != null)
                    showAlert(AlertType.ERROR, data.error);
                else
                    showAlert(AlertType.ERROR, "Błąd podczas ładowania artykułów!");
            }
        },
        error: function () {}
    });
}