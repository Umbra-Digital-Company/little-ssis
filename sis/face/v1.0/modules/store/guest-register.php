<form id="guest-register-form" method="post">
    <div class="d-flex form-group justify-content-center mt-4">
		<input type="text" name="firstname-guest" class="form-control" id="firstname-guest" autocomplete="off" autofocus />
		<label class="placeholder" for="firstname-guest" style="margin-left:15px;"><?= $arrTranslate['First Name'] ?></label>
	</div>
	<div class="d-flex form-group justify-content-center mt-4">
		<input type="text" name="lastname-guest" class="form-control" id="lastname-guest" autocomplete="off" />
		<label class="placeholder" for="lastname-guest" style="margin-left:15px;"><?= $arrTranslate['Last Name'] ?></label>
	</div>
	<div class="row mt-4">
		<div class="form-group gender col-12">
			<div class="d-flex no-gutters">
				<div class="col">
					<input class="sr-only" type="radio" id="gender-male-guest" name="gender-guest" value="Male" required>
					<label class="form-control col" for="gender-male-guest"><?= $arrTranslate['Male'] ?></label>
				</div>
				<div class="col">
					<input class="sr-only" type="radio" id="gender-female-guest" name="gender-guest" value="Female"required>
					<label class="form-control col" for="gender-female-guest"><?= $arrTranslate['Female'] ?></label>
				</div>
			</div>
		</div>
	</div>		
	<div class="form-group">
		<select class="text-left s-a mh-40 select form-control" name="age_range-guest" id="age_range-guest" required>
			<option value="" disabled selected>-</option>
			<!-- <option value="1">0-12</option>
			<option value="13">13-17</option> -->
			<option value="18">18-24</option>
			<option value="25">25-34</option>
			<option value="35">35-44</option>
			<option value="45">45-54</option>
			<!-- <option value="55">55-64</option> -->
			<!-- <option value="65">65+</option> -->
		</select>
		<label class="placeholder" for="age_range-guest">Age Group</label>
	</div>
	<input type="hidden" id="bdate2">
    <div class="text-center mt-4">
        <input type="submit" class="btn btn-black" value="Proceed">
    </div>
</form>

<script>
	$("#guest-register-form").submit(function(e){
            e.preventDefault();
            form = $(this).serialize();
            $.post("/sis/face/func/store/store-guest-register.php", form, function (d) {
                if (d == 'success') {
                    location.reload();
                }else{
                	alert(d);
                }
            });
        });
</script>