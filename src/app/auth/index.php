<?php include_once __DIR__ . '/../../core/app.php'; ?>
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
			<section class="auth-section">
				<?= partial('components/header-logo') ?> <!-- Header -->
				<div class="auth-wrapper box column">
					<?= partial('components/header-in') ?> <!-- Section in header -->
					<form class="ui large form" id="loginForm">
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
								<input type="checkbox" name="remember">
								<label for="remember">Remember me</label>
							</div>
							<div class="ui text forgot-password">
								<a href="#" id="forgotPasswordLink">Forgot Password?</a>
							</div>
						</div>
						<div class="actions">
							<?= partial('components/ui/continue-btn'); ?>
						</div>
						<div class="ui error message"></div>
					</form>
					<div class="ui text text-center">
						Don't have an account? <a href="register.php">Sign Up</a>
					</div>
				</div>
				<?= partial('components/terms-privacy') ?> <!-- Terms & Privacy -->
			</section>
		</main>
	</div>

	<!-- Forgot Password Modal -->
	<div class="ui tiny modal" id="forgotPasswordModal">
		<i class="close icon"></i>
		<div class="header">
			<i class="key icon"></i> Reset Password
		</div>
		<div class="content">
			<p>Enter your email address to generate a temporary password.</p>
			<div class="ui info message" style="display: none;">
				<div class="header">Development Mode</div>
				<p>Email is disabled. A temporary password will be generated for you.</p>
			</div>
			<form class="ui form" id="forgotPasswordForm">
				<div class="field">
					<label>Email Address</label>
					<input type="email" name="email" placeholder="Enter your email">
				</div>
				<div class="ui error message"></div>
			</form>
			<!-- Temporary Password Display -->
			<div class="ui success message" id="tempPasswordDisplay" style="display: none;">
				<div class="header">Your Temporary Password</div>
				<p>Password: <strong id="tempPasswordValue" style="font-size: 1.2em; color: #2185d0;"></strong></p>
				<p><small>Copy this password and use it to login. Change it after logging in.</small></p>
			</div>
		</div>
		<div class="actions">
			<button class="ui black deny button">Cancel</button>
			<button class="ui positive right labeled icon button" id="sendResetLink">
				Generate Password
				<i class="key icon"></i>
			</button>
		</div>
	</div>

	<!-- Scripts -->
	<?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
	<script src="<?= featured('auth/js/login.js', true) ?>"></script>
</body>

</html>