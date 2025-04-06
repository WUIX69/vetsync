<?php include_once __DIR__ . '/../../utils/php/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?= shared('elements/meta') ?> <!-- rcs Meta Tags -->
	<title>VetSync - Sign In</title>
	<?= shared('elements/styles'); ?> <!-- rcs Styles -->
</head>

<body>

	<div class="shared-standalone-content">
		<?= shared('layouts/loader/window') ?> <!-- Window Spinner -->
	</div>

	<div class="container-body">
		<main class="container-main">
			<div class="section-container">
				<div class="section-wrapper auth-section">
					<?= featured('auth/components/header') ?> <!-- Header -->
					<div class="auth-wrapper box column">
						<div class="ui teal header section-in-header">
							Sign in
						</div>
						<form class="ui large form">
							<div class="field">
								<label for="email">Email</label>
								<div class="ui input">
									<input type="text" name="email" placeholder="E-mail address">
								</div>
							</div>
							<div class="field">
								<label for="password">Password</label>
								<div class="ui input">
									<input type="password" name="password" placeholder="Password">
								</div>
							</div>
							<div class="field clearing">
								<div class="ui checkbox remember-me">
									<input type="checkbox" name="remember-me">
									<label>Remember me</label>
								</div>
								<div class="ui text forgot-password">
									<a href="#">Forgot Password?</a>
								</div>
							</div>
							<button class="ui fluid large teal submit button" type="submit">Continue</button>
							<div class="ui error message"></div>
						</form>
						<div class="ui text text-center">
							Don't have an account? <a href="register.php">Sign Up</a>
						</div>
					</div>
				</div>
			</div>
		</main>
	</div>

	<!-- Scripts -->
	<?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
	<?= featured('auth/login/scripts') ?> <!-- Login Scripts -->
</body>

</html>