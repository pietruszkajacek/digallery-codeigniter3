
$(document).ready(function () {
	//usuwanie pojedyńczej wiadomości
	$("a.btn.btn-mini").click(function () {
		//odznacz wszystkie checkboxy z formularza
		$("[type=checkbox]", $("#posts-box")).attr('checked', false);

		//zaznacz checkboxa
		$("[type=checkbox]", $(this).parent().parent()).attr('checked', true);

		//wyślij formularz
		$("#posts-box").submit();
	} );

	//zaznaczanie / odznaczanie wszystkich wiadomości
	$("#select_all").click(function () {
		$("[type=checkbox]", $("#posts-box")).attr('checked', this.checked);
	});

	// sprawdza czy należy zaznaczyć bądź odznaczyć checkbox (select_all) zaznaczający / odznaczający wszystkie wiadomości
	// gdy wszystkie wiadomości zostają zaznaczone to checkbox (select_all) zostaje zaznaczony jeśli wszystkie
	// widomości nie są zaznaczone to checkbox (select_all) zostaje odznaczony
	$("[type=checkbox]:gt(0)", $("#posts-box")).click(function () {
		var checkbox_count = $("[type=checkbox]:gt(0)").length;
		var checkbox_checked = $("[type=checkbox]:gt(0):checked").length;

		if (checkbox_count == checkbox_checked)
		{
			$("#select_all").attr('checked', true);
		}
		else
		{
			$("#select_all").attr('checked', false);
		}
	});

	// sprawdzenie czy przynajmniej jedna widomość została zaznaczona do usunięcia
	// jeśli nie to nie wysyła formularza
	$("#posts-box").submit(function () {
		//alert('submit');
		if ($("[type=checkbox]:gt(0):checked").length != 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	});
	
	// walidacja formularza wysyłania wiadomości
	$("#posts-compose-message").submit(function() {
		if ($('input[name="recipient"]').val() != "" && $('input[name="subject"]').val() != "" && $('textarea[name="post_message"]').val() != "")
		{
			return true;
		}

		var msg_error = '';

		// recipient
		if ($('input[name="recipient"]').val() == "")
		{
			$("#ctrl-gr-recipient").attr("class", "control-group error");
			msg_error = msg_error + 'Musisz podać adresata.' + '<br />';
		}
		else
		{
			$("#ctrl-gr-recipient").attr("class", "control-group");
		}

		// subject
		if ($('input[name="subject"]').val() == "")
		{
			$("#ctrl-gr-subject").attr("class", "control-group error");
			msg_error = msg_error + 'Musisz podać temat wiadomości.' + '<br />';
		}
		else
		{
			$("#ctrl-gr-subject").attr("class", "control-group");
		}

		// message
		if ($('textarea[name="post_message"]').val() == "")
		{
			$("#ctrl-gr-message").attr("class", "control-group error");
			msg_error = msg_error + 'Wiaodmość nie może być pusta.' + '<br />';
		}
		else
		{
			$("#ctrl-gr-message").attr("class", "control-group");
		}

		$("div.alert").remove(); // usuń stare komunikaty

		// wygeneruj komunikat o błędzie...
		$("#compose-message").prepend('<div class="alert alert-error"></div>');
		$("div.alert").append('<a class="close" data-dismiss="alert" href="#">×</a>')
					  .append('<h4 class="alert-heading">Błąd...</h4>')
					  .append(msg_error);
		return false;
	});

	$('#recipient').typeahead({
		minLength: 3,
		source: function(query, process) {
			$.ajax({
				cache: false,
				url: '/posts/compose_autocomplete/',
				data: { typeahead: query },
				type: 'POST',
				success: function (data) {
					process(data);
				},
				error: function (data) {
					
				}
			});
		}
	});
});