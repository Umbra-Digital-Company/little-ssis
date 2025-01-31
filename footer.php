<?php

function get_footer() {

	$version = date('YmdHis'); ?>

		<script type="text/javascript" src="/assets/js/tether.min.js"></script>
		<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/assets/js/perfect-scrollbar.min.js"></script>
		<script type="text/javascript" src="/assets/js/main.js?v=<?= $version ?>"></script>

	</body>
	</html>

<?php } ?>