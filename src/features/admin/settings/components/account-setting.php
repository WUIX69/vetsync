<style>
    main .account-settings .section-wrapper {
        margin-top: 1.3rem !important;
    }

    main .account-settings .section-wrapper .section-informative-header {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        flex-direction: column;
        gap: 0.4rem;
    }

    main .account-settings .section-wrapper .section-divider {
        height: 2px;
        background-color: var(--color-background);
        margin-top: 1.2rem;
        margin-bottom: 1.3rem;
    }

    main .account-settings .section-wrapper .section-informative-header .title {
        font-size: 1.45rem;
        font-weight: 600;
    }

    main .account-settings .section-wrapper .section-informative-header .description {
        color: var(--color-dark-variant) !important;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .ui.form .field .add-url-btn {
        background: var(--color-background) !important;
        color: var(--color-dark-variant) !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 0.4rem;
        font-size: 0.7rem !important;
    }
</style>
<!-- Account Settings -->
<div class="section-container account-settings">
    <div class="section-wrapper box">
        <div class="section-informative-header">
            <div class="title">Profile</div>
            <div class="description">This is how others will see you on the site.</div>
        </div>
        <div class="section-divider"></div>
        <form class="ui form">
            <div class="field">
                <label>Username</label>
                <input class="ui input" type="text" placeholder="Enter your username">
                <div class="description">This is your public display name. It can be your
                    real name
                    or a pseudonym. You
                    can only change this once every 30 days.</div>
            </div>
            <div class="field">
                <label>Password</label>
                <input class="ui input" type="password" placeholder="Enter new password">
                <div class="description">Your password must be at least 8 characters long and
                    contain
                    at least one
                    uppercase letter, one lowercase letter, and one number.</div>
            </div>
            <div class="field">
                <label>Email Address</label>
                <input class="ui input" type="email" placeholder="Enter your email">
                <div class="description">You can manage verified email addresses in your email
                    settings.</div>
            </div>
            <div class="field">
                <label>Bio</label>
                <textarea class="ui input" placeholder="Enter your bio"></textarea>
                <div class="description">Tell us about yourself. You can @mention other users and
                    organizations to link to them.</div>
            </div>
            <div class="field">
                <label>URLs</label>
                <div class="description">Add links to your website, blog, or social media profiles.
                </div>
                <div class="url-inputs">
                    <input class="ui input" type="text" placeholder="Enter your URLs">
                    <input class="ui input" type="text" placeholder="Enter your URLs">
                    <button type="button" class="ui mini basic button add-url-btn"
                        style="margin: 0 !important; width: 100px !important;"><i class="add icon"></i>
                        Add
                        URL</button>
                </div>
            </div>
            <div class="actions mt-4">
                <button class="ui primary button" type="submit">Save Changes</button>
            </div>
        </form>
    </div>
</div>