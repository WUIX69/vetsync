<style>
    #bookNowModal {
        padding: 0;
        margin: 0;
        overflow: hidden;
    }

    #bookNowModal .header {
        font-size: 1.8rem !important;
        font-weight: bold !important;
    }

    #bookNowModal .form-container {
        padding: 2.5rem;
        position: relative;
        width: calc(100% - 540px);
        height: 100%;
    }

    #bookNowModal .form-container .ui.form .submit.button {
        background: #000;
        color: #fff;
        font-size: 1rem !important;
        padding: 1rem 2rem;
    }

    #bookNowModal .image-container {
        width: calc(100% - 540px);
        height: 100%;
        background: #fafafa;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 0;
        right: 0;
    }

    #bookNowModal .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
</style>

<?php
// Get current user data for auto-population
$currentUser = userData();
$userFullName = $currentUser['name'] ?? '';
$userEmail = $currentUser['email'] ?? '';
$userPhone = $currentUser['telephone'] ?? '';
?>

<div class="ui large modal" id="bookNowModal">
    <div class="header">Book Appointment</div>
    <div class="image content">
        <div class="form-container">
            <form class="ui form" id="bookNowForm">
                <div class="two fields">
                    <div class="field">
                        <label for="full_name">Full Name</label>
                        <input type="text" name="full_name" placeholder="Your Name"
                            value="<?= htmlspecialchars($userFullName) ?>" readonly
                            style="background-color: #f8f9fa;" />
                        <small class="text-muted">Your registered name</small>
                    </div>
                    <div class="field">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" placeholder="your@email.com"
                            value="<?= htmlspecialchars($userEmail) ?>" readonly style="background-color: #f8f9fa;" />
                        <small class="text-muted">Your registered email</small>
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" placeholder="123-456-7890"
                            value="<?= htmlspecialchars($userPhone) ?>" />
                        <small class="text-muted">Enter your contact number</small>
                    </div>
                    <div class="field">
                        <label for="date">Date</label>
                        <input type="date" name="date" min="<?= date('Y-m-d') ?>" required />
                        <small class="text-muted">Select your preferred appointment date</small>
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label for="pet_uuid">Select Pet</label>
                        <select name="pet_uuid" id="bookNowPetDropdown" class="ui dropdown" required>
                            <option value="">Select your pet</option>
                            <!-- Pets will be loaded here by JS -->
                        </select>
                    </div>
                    <div class="field">
                        <label for="service_uuid">Select Service</label>
                        <select name="service_uuid" id="bookNowServiceDropdown" class="ui dropdown" required>
                            <!-- Services will be loaded here by JS -->
                        </select>
                    </div>
                </div>
                <div class="field">
                    <label for="special_request">Special Request</label>
                    <textarea rows="4" name="special_request"
                        placeholder="Any special instructions or requests for your appointment..."></textarea>
                </div>
                <div class="actions" style="text-align:center;">
                    <button class="ui submit button" type="submit">SUBMIT REQUEST</button>
                </div>
            </form>
        </div>
        <div class="image-container">
            <img src="<?= asset('img/contents/bookNow.jpg') ?>" alt="Elegant dining table with white background" />
        </div>
    </div>
</div>