
$(document).ready(function () {
	$("#del-btn-show-post-in").click(function () {
		//wyślij formularz
		$("#posts-message-inbox-delete").submit();
	} );

	// walidacja formularza
	$("#posts-message-inbox").submit(function() {
		if ($('input[name="title"]').val() != "" && $('textarea[name="post_message"]').val() != "")
		{
			return true;
		}

		// title
		if ($('input[name="title"]').val() == "")
		{
			$("#ctrl-gr-title").attr("class", "control-group error");
		}
		else
		{
			$("#ctrl-gr-title").attr("class", "control-group");
		}

		// message
		if ($('textarea[name="post_message"]').val() == "")
		{
			$("#ctrl-gr-message").attr("class", "control-group error");
		}
		else
		{
			$("#ctrl-gr-message").attr("class", "control-group");
		}

		return false;
	} );
});