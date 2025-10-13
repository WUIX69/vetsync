<style>
    main section.settings .avatar-upload {
        position: relative;
        max-width: 200px;
        margin: 2rem auto;
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

    main section.settings .avatar-upload .upload-button i {
        margin: 0;
    }

    main section.settings .avatar-upload .upload-button:hover {
        transform: scale(1.1);
    }

    main section.settings .readonly-field {
        background-color: #f8f9fa !important;
        color: #6c757d !important;
        cursor: not-allowed;
    }
</style>
<div class="tab-pane fade show active" id="profile">
    <div class="tab-header">
        <h3 class="ui header">Profile</h3>
        <p>This is how others will see you on the site.</p>
    </div>
    <div class="section-divider"></div>

    <!-- Profile Upload (Outside of form for separate handling) -->
    <div class="field" style="margin-bottom: 2rem;">
        <div class="avatar-upload">
            <input type="file" class="filepond profile-pond ignore" name="profile">
        </div>
    </div>

    <!-- Profile Form (Text fields only) -->
    <form class="ui form" id="profileForm">
        <div class="two fields">
            <!-- Firstname -->
            <div class="field">
                <label for="firstname">First Name *</label>
                <input type="text" placeholder="Enter your first name" name="firstname" value="">
                <div class="ui small text">
                    This is your first name. It will be visible to others on your profile.
                </div>
            </div>
            <!-- Lastname -->
            <div class="field">
                <label for="lastname">Last Name *</label>
                <input type="text" placeholder="Enter your last name" name="lastname" value="">
                <div class="ui small text">
                    This is your last name. It will be visible to others on your profile.
                </div>
            </div>
        </div>

        <!-- Email (Read-only) -->
        <div class="field">
            <label for="email">Email Address *</label>
            <input type="email" placeholder="Enter your email" name="email" value="" class="readonly-field" readonly>
            <div class="ui small text">
                <i class="lock icon"></i> Email address cannot be changed. Contact support if you need to update it.
            </div>
        </div>

        <!-- Phone -->
        <div class="field">
            <label for="telephone">Phone Number *</label>
            <input type="tel" placeholder="Enter your phone number" name="telephone" value="">
            <div class="ui small text">
                Your phone number for appointment confirmations.
            </div>
        </div>

        <!-- Address -->
        <div class="field">
            <label for="location">Address *</label>
            <input type="text" placeholder="Enter your address" name="location" value="">
            <div class="ui small text">
                Your address for service appointments and deliveries.
            </div>
        </div>

        <div class="actions mt-4">
            <button class="ui primary submit button" type="submit">Save Changes</button>
        </div>
    </form>
</div>