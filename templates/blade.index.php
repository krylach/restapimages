<style type="text/css">
	input, textarea {
		display: block;
		margin: 10px;
	}
	textarea {
		width: 200px;
	}

</style>
<h2>URL</h2>
<form class="" action="/push_picture/" method="post">
	<input type="input" name="url">
	<input type="submit" name="pushForm">
</form>
<h2>Base64 JSON({"base64": "*"})</h2>
<form class="" action="/push_picture/" method="post">
	<textarea rows="5" name="json"></textarea>
	<input type="submit" name="pushForm">
</form>
<h2>POST Form(*.jpg, *.jpeg)</h2>
<form class="picture_form" action="/push_picture/" method="post" enctype="multipart/form-data">
	<div class="pictures">
		<input type="file" name="picture[]">
		<input type="file" name="picture[]">
	</div>
	<input type="submit" name="pushForm">
</form>

<input type="button" name="add_input" value="Добавить форму">