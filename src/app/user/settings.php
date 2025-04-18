<?php include_once __DIR__ . '/../../utils/php/functions.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= shared('elements/meta'); ?> <!-- rcs Meta -->
    <title>Settings - VetSync</title>
    <?= shared('elements/styles'); ?> <!-- rcs Styles -->
    <style>
        main section.header h1 {
            font-size: 2.5rem !important;
        }

        main section.settings .settings-nav {
            display: flex !important;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;

            padding: 1.6rem;
            margin: 0;
            border-radius: 1rem !important;
        }

        main section.settings .settings-nav .item {
            position: static !important;
            padding: 1rem 1.5rem;
            border-radius: 1rem !important;
            transition: all 0.3s ease;
            text-align: center;
            cursor: pointer;
            width: 100% !important;
        }

        main section.settings .settings-nav .item:hover {
            background-color: rgba(0, 0, 0, .03);
        }

        main section.settings .settings-nav .item.active {
            background-color: #21BA45;
            box-shadow: 0 4px 15px rgba(33, 186, 69, 0.2);
        }

        main section.settings .settings-nav .item i {
            margin-right: 10px;
        }

        main section.settings .ui.segment {
            padding: 1.8rem;
            margin: 0;
            border-radius: 1rem !important;
        }

        main section.settings .tab-header {
            border-bottom: 1px solid rgba(34, 36, 38, .15);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
        }

        main section.settings .tab-header p {
            font-size: 0.92rem;
            color: rgba(0, 0, 0, .6);
        }

        main section.settings .section-divider {
            height: 2px;
            background-color: var(--color-background);
            margin-top: -1.2rem;
            margin-bottom: 1.3rem;
        }

        main section.settings .avatar-upload {
            position: relative;
            max-width: 200px;
            margin: 0 auto 2rem;
        }

        main section.settings .avatar-upload img {
            width: 200px;
            height: 200px;
            border-radius: 50% !important;
            object-fit: cover !important;
            border: 3px solid #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        main section.settings .avatar-upload .upload-button {
            position: absolute;
            right: 10px;
            bottom: 10px;
            width: 40px;
            height: 40px;
            background: #21BA45;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        main section.settings .avatar-upload .upload-button:hover {
            transform: scale(1.1);
        }

        main section.settings .ui.form .field input,
        main section.settings .ui.form .field textarea,
        main section.settings .ui.form .field .ui.dropdown .text {
            font-size: 0.85rem;
        }

        main section.settings .ui.form .field .url-inputs {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.3rem;
        }

        main section.settings .ui.form .field .add-url-btn {
            background: var(--color-background) !important;
            color: var(--color-dark-variant) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 0.4rem;
            font-size: 0.7rem !important;
        }
    </style>
</head>

<body>
    <div class="shared-standalone-content">
        <?= shared('layouts/loader/window'); ?>
        <?= shared('layouts/top-redirect-btn'); ?>
    </div>

    <div class="container-body">
        <!-- Site Header -->
        <?= featured('user/shared/layouts/header'); ?>

        <main class="site-main">
            <section class="header py-5">
                <div class="container-xl">
                    <h1>Settings</h1>
                    <p>Manage your account settings and preferences.</p>
                </div>
            </section>
            <section class="settings pb-5">
                <div class="container-xl">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="ui vertical fluid menu nav-tab settings-nav">
                                <a class="item active" data-tab="profile">
                                    <i class="bx bx-user"></i> Profile
                                </a>
                                <a class="item" data-tab="account">
                                    <i class="bx bx-shield-alt"></i> Account
                                </a>
                                <a class="item" data-tab="notifications">
                                    <i class="bx bx-bell"></i> Notifications
                                </a>
                                <a class="item" data-tab="preferences">
                                    <i class="bx bx-cog"></i> Preferences
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="ui segment">
                                <div class="ui tab active" data-tab="profile">
                                    <div class="tab-header">
                                        <h3 class="ui header">Profile</h3>
                                        <p>This is how others will see you on the site.</p>
                                    </div>
                                    <div class="section-divider"></div>
                                    <form class="ui form">
                                        <!-- Profile Picture -->
                                        <div class="avatar-upload">
                                            <img src="<?= asset('img/profiles/profile.jpg'); ?>" alt="Profile Picture">
                                            <div class="upload-button">
                                                <i class="camera icon"></i>
                                            </div>
                                        </div>

                                        <!-- Username -->
                                        <div class="field">
                                            <label>Username</label>
                                            <input type="text" placeholder="Enter your username">
                                            <div class="ui small text">
                                                This is your public display name. It can be your real name or a
                                                pseudonym.
                                            </div>
                                        </div>

                                        <!-- Password -->
                                        <div class="field">
                                            <label>Password</label>
                                            <input type="password" placeholder="Enter new password">
                                            <div class="ui small text">
                                                Your password must be at least 8 characters long.
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="field">
                                            <label>Email Address</label>
                                            <input type="email" placeholder="Enter your email">
                                            <div class="ui small text">
                                                You can manage verified email addresses in your email settings.
                                            </div>
                                        </div>

                                        <!-- Bio -->
                                        <div class="field">
                                            <label>Bio</label>
                                            <textarea rows="4" placeholder="Enter your bio"></textarea>
                                            <div class="ui small text">
                                                Tell us about yourself. You can @mention other users.
                                            </div>
                                        </div>

                                        <!-- URLs -->
                                        <div class="field">
                                            <label>URLs</label>
                                            <div class="ui small text">Add links to your website or social media
                                                profiles.</div>
                                            <div class="fields mt-2">
                                                <div class="sixteen wide field url-inputs">
                                                    <input type="url" placeholder="Enter your URL">
                                                    <input type="url" placeholder="Enter your URL">
                                                    <input type="url" placeholder="Enter your URL">
                                                </div>
                                            </div>
                                            <button type="button" class="ui basic green icon button">
                                                <i class="plus icon"></i> Add URL
                                            </button>
                                        </div>
                                        <div class="actions mt-4">
                                            <button class="ui primary button" type="submit">Save Changes</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="ui tab" data-tab="account">
                                    <div class="tab-header">
                                        <h3 class="ui header">Account</h3>
                                        <p>Manage your account settings and preferences.</p>
                                    </div>
                                    <div class="section-divider"></div>
                                    <form class="ui form">
                                        <!-- Account Type -->
                                        <div class="field">
                                            <label>Account Type</label>
                                            <div class="ui selection dropdown">
                                                <input type="hidden" name="account_type">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">Select Account Type</div>
                                                <div class="menu">
                                                    <div class="item" data-value="personal">Personal</div>
                                                    <div class="item" data-value="business">Business</div>
                                                    <div class="item" data-value="professional">Professional</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Language -->
                                        <div class="field">
                                            <label>Language</label>
                                            <div class="ui selection dropdown">
                                                <input type="hidden" name="language">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">Select Language</div>
                                                <div class="menu">
                                                    <div class="item" data-value="en">English</div>
                                                    <div class="item" data-value="es">Spanish</div>
                                                    <div class="item" data-value="fr">French</div>
                                                    <div class="item" data-value="de">German</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Time Zone -->
                                        <div class="field">
                                            <label>Time Zone</label>
                                            <div class="ui selection dropdown">
                                                <input type="hidden" name="timezone">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">Select Time Zone</div>
                                                <div class="menu">
                                                    <div class="item" data-value="utc">UTC (GMT)</div>
                                                    <div class="item" data-value="est">Eastern Time (ET)</div>
                                                    <div class="item" data-value="cst">Central Time (CT)</div>
                                                    <div class="item" data-value="pst">Pacific Time (PT)</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dark Mode Toggle -->
                                        <div class="field">
                                            <label>Theme Preference</label>
                                            <div class="ui toggle checkbox">
                                                <input type="checkbox" name="dark_mode">
                                                <label>Enable Dark Mode</label>
                                            </div>
                                        </div>

                                        <!-- Delete Account -->
                                        <div class="field mt-5">
                                            <label>Danger Zone</label>
                                            <div class="ui segment"
                                                style="background-color: rgba(255, 0, 0, 0.05); border-color: rgba(255, 0, 0, 0.2);">
                                                <h4 class="ui header">Delete Account</h4>
                                                <p>Once you delete your account, there is no going back. Please be
                                                    certain.</p>
                                                <button type="button" class="ui red button">Delete Account</button>
                                            </div>
                                        </div>

                                        <div class="actions mt-4">
                                            <button class="ui primary button" type="submit">Save Account
                                                Settings</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="ui tab" data-tab="notifications">
                                    <div class="tab-header">
                                        <h3 class="ui header">Notifications</h3>
                                        <p>Control your notification preferences.</p>
                                    </div>
                                    <div class="section-divider"></div>
                                    <form class="ui form">
                                        <!-- Email Notifications -->
                                        <h4 class="ui header">Email Notifications</h4>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="email_appointments" checked>
                                                <label>Appointment reminders</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="email_updates" checked>
                                                <label>System updates and announcements</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="email_marketing">
                                                <label>Marketing and promotional emails</label>
                                            </div>
                                        </div>

                                        <!-- Push Notifications -->
                                        <h4 class="ui header mt-4">Push Notifications</h4>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="push_messages" checked>
                                                <label>New messages</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="push_appointments" checked>
                                                <label>Appointment alerts</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="push_updates">
                                                <label>System updates</label>
                                            </div>
                                        </div>

                                        <!-- Notification Frequency -->
                                        <h4 class="ui header mt-4">Notification Frequency</h4>
                                        <div class="field">
                                            <label>Email Digest Frequency</label>
                                            <div class="ui selection dropdown">
                                                <input type="hidden" name="email_frequency">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">Select Frequency</div>
                                                <div class="menu">
                                                    <div class="item" data-value="immediately">Immediately</div>
                                                    <div class="item" data-value="daily">Daily Digest</div>
                                                    <div class="item" data-value="weekly">Weekly Digest</div>
                                                    <div class="item" data-value="never">Never</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="actions mt-4">
                                            <button class="ui primary button" type="submit">Save Notification
                                                Settings</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="ui tab" data-tab="preferences">
                                    <div class="tab-header">
                                        <h3 class="ui header">Preferences</h3>
                                        <p>Customize your experience.</p>
                                    </div>
                                    <div class="section-divider"></div>
                                    <form class="ui form">
                                        <!-- Theme Preferences -->
                                        <h4 class="ui header">Theme Settings</h4>
                                        <div class="field">
                                            <label>Display Mode</label>
                                            <div class="ui selection dropdown">
                                                <input type="hidden" name="theme_mode">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">Select Mode</div>
                                                <div class="menu">
                                                    <div class="item" data-value="light">Light Mode</div>
                                                    <div class="item" data-value="dark">Dark Mode</div>
                                                    <div class="item" data-value="system">Use System Setting</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Language Preferences -->
                                        <h4 class="ui header mt-4">Language & Region</h4>
                                        <div class="field">
                                            <label>Language</label>
                                            <div class="ui selection dropdown">
                                                <input type="hidden" name="language">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">Select Language</div>
                                                <div class="menu">
                                                    <div class="item" data-value="en">English</div>
                                                    <div class="item" data-value="es">Spanish</div>
                                                    <div class="item" data-value="fr">French</div>
                                                    <div class="item" data-value="de">German</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="field">
                                            <label>Time Format</label>
                                            <div class="ui selection dropdown">
                                                <input type="hidden" name="time_format">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">Select Format</div>
                                                <div class="menu">
                                                    <div class="item" data-value="12h">12-hour (AM/PM)</div>
                                                    <div class="item" data-value="24h">24-hour</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dashboard Preferences -->
                                        <h4 class="ui header mt-4">Dashboard Settings</h4>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="show_welcome" checked>
                                                <label>Show welcome message</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="show_quick_actions" checked>
                                                <label>Show quick action buttons</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="show_recent_activity" checked>
                                                <label>Show recent activity</label>
                                            </div>
                                        </div>

                                        <div class="field">
                                            <label>Default Dashboard View</label>
                                            <div class="ui selection dropdown">
                                                <input type="hidden" name="default_view">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">Select View</div>
                                                <div class="menu">
                                                    <div class="item" data-value="summary">Summary</div>
                                                    <div class="item" data-value="detailed">Detailed</div>
                                                    <div class="item" data-value="compact">Compact</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="actions mt-4">
                                            <button class="ui primary button" type="submit">Save Preferences</button>
                                            <button class="ui button" type="reset">Reset to Defaults</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?= shared('elements/scripts'); ?>
    <script type="text/javascript">
        $(function () {
            // Initialize tabs with animation
            $('.nav-tab .item').tab();
        });
    </script>
</body>

</html>