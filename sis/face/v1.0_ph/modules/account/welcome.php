<style>

	body {
		background: url(images/store-banner.jpg);
		background-position: center top;
		background-repeat: no-repeat;
	}

	.welcome-overlay {
		position: fixed;
		height: 100%;
		width: 100%;
		left: 0;
		top: 0;
		background: rgba(0,0,0,0.55);
	}

	.header {
		background: #fff;
	}

	.front-page {
		position: relative;
		z-index: 10;
	}
	
	.front-page .welcome-message p {
		max-width: 600px;
		margin-top: 30px;
	}

	.front-page .welcome-message h3,
	.front-page .welcome-message p {
		color: #fff;
	}

	.front-page .account-card-holder {
		width: 100%;
		margin: 30px auto 0;
		max-width: 600px;
	}

	.front-page .account-card-holder > div {
		padding: 0 10px;
	}

	.front-page .account-card {
		padding: 80px 0;
		text-align: center;
		cursor: pointer;
		width: 100%;
		display: block;
		color: #000;
		text-decoration: none;
		-webkit-transition: .3s all ease;
		-moz-transition: .3s all ease;
		transition: .3s all ease;
	}

	.front-page .account-card:hover {
		background: #31b0d5;
		color: #fff;
	}

</style>

<div class="welcome-overlay"></div>

<div class="front-page">
	
	<div class="welcome-message text-center">
		<h3>Welcome Your Name!</h3>
		<p class="center-block">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi.</p>
	</div>
	
	<div class="row no-gutters account-card-holder">

		<!-- ?php if ( $account == 'doctor' ) : ? --> 

			<div class="col col-12 align-items-center justify-content-center" style="background: transparent; margin-bottom: 20px;">
				<a href="./?page=doctor" class="card account-card">
					<h5 class="font-weight-bold">Doctor</h5>
				</a>
			</div>

		<!-- ?php endif; ? -->

		<div class="col col-6 align-items-center justify-content-center" style="background: transparent;">
			<a href="./?page=cashier" class="card account-card">
				<h5 class="font-weight-bold">Cashier</h5>
			</a>
		</div>
		<div class="col col-6 align-items-center justify-content-center" style="background: transparent;">
			<a href="./?page=store-home" class="card account-card">
				<h5 class="font-weight-bold">Assist</h5>
			</a>
		</div>

	</div>

</div>