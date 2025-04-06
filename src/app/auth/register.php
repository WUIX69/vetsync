<?php include_once __DIR__ . '/../../utils/php/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta') ?> <!-- rcs Meta Tags -->
    <title>VetSync - Sign Up</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <style>
        main .section-container .section-wrapper {
            /* Disables default margin-top for register page */
            margin-top: 0;
        }
    </style>
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
                            Sign up
                        </div>
                        <form class="ui large form">
                            <div class="two fields">
                                <div class="field">
                                    <label for="firstname">First name</label>
                                    <div class="ui input">
                                        <input type="text" name="firstname" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="field">
                                    <label for="lastname">Last name</label>
                                    <div class="ui input">
                                        <input type="text" name="lastname" placeholder="Last Name">
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <label for="email">E-mail address</label>
                                <div class="ui input">
                                    <input type="email" name="email" placeholder="E-mail address">
                                </div>
                            </div>
                            <div class="field">
                                <label for="password">Password</label>
                                <div class="ui input">
                                    <input type="password" name="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="field">
                                <label for="confirm_password">Confirm Password</label>
                                <div class="ui input">
                                    <input type="password" name="confirm_password" placeholder="Confirm Password">
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui checkbox terms">
                                    <input type="checkbox" name="terms">
                                    <label for="terms">I agree to the Terms and Conditions</label>
                                </div>
                            </div>
                            <button class="ui fluid large teal submit button" type="submit">Register</button>
                            <div class="ui error message"></div>
                        </form>
                        <div class="ui text text-center">
                            Already have an account? <a href="index.php">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <?= shared('elements/scripts'); ?> <!-- rcs Scripts -->
    <?= featured('auth/register/scripts') ?> <!-- Register Scripts -->
</body>

</html>