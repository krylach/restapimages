	<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="/assets/js/bootstrap.notify.min.js"></script>
	<script type="text/javascript">
		let length = 2;
		$('input[name=add_input]').click(function() {
			if (length < 5) {
				$('.pictures').append("<input type=\"file\" name=\"picture[]\">");
				length++;
		    }
		});
	</script>
</body>
</html>
