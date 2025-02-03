<form action="/v2.0/sis/face/func/process/guest_register.php?path_loc=v1.0" id="myForm" method="post">
	<input type="hidden" name="firstname" value="guest">
	<input type="hidden" name="lastname" value="guest">
	<input type="hidden" name="gender" value="N/A">
	<input type="hidden" name="age_range" value="25">
</form>

<script>
    window.onload = function() {
        document.getElementById('myForm').submit();
    };
</script>