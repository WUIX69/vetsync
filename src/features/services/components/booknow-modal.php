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

    .date-availability-info {
        display: none;
        margin-top: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .date-availability-info i.icon {
        margin-right: 0.5rem;
    }

    .date-availability-info.available {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        display: flex;
        align-items: center;
    }

    .date-availability-info.fully-booked {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        display: flex;
        align-items: center;
    }

    .date-availability-info strong {
        font-weight: 600;
    }
</style>

<?php
// Get current user data for display purposes
$currentUser = userData();
$userFullName = $currentUser['name'] ?? '';
?>

<div class="ui large modal" id="bookNowModal">
    <div class="header">Book Appointment</div>
    <div class="image content">
        <div class="form-container">
            <!-- Display logged in user info -->
            <!-- <div class="ui info message">
                <div class="header">Booking for: <?= htmlspecialchars($userFullName) ?></div>
                <p>This appointment will be booked under your current account.</p>
            </div> -->

            <!-- Booking Policy Message -->
            <div class="ui warning message">
                <div class="header"><i class="info circle icon"></i> Booking Policy - First Come, First Served</div>
                <ul class="list">
                    <li><strong>Limited slots:</strong> We accept a maximum of <strong>10 appointments per day</strong>
                    </li>
                    <li><strong>First come, first served:</strong> Appointments are confirmed in the order they are
                        received</li>
                    <li><strong>Fully booked dates:</strong> Dates with 10 appointments are automatically disabled</li>
                    <li><strong>Book early:</strong> To secure your preferred date, please book as early as possible
                    </li>
                </ul>
                <p style="margin-top: 0.5rem; margin-bottom: 0;">
                    <i class="calendar check icon"></i> <strong>Tip:</strong> If your preferred date is unavailable, try
                    booking for the next available date.
                </p>
            </div>

            <form class="ui form" id="bookNowForm">
                <!-- Date Field - Full Width Row -->
                <div class="field">
                    <label for="date">Date <span style="color: red;">*</span></label>
                    <input type="date" name="date" id="appointmentDateInput" min="<?= date('Y-m-d') ?>" required />
                    <small class="text-muted">Select your preferred appointment date</small>
                    <div class="date-availability-info" id="dateAvailability">
                        <!-- Availability message will be shown here -->
                    </div>
                </div>

                <!-- Pet Dropdown - Full Width Row -->
                <div class="field">
                    <label for="pet_uuid">Select Pet <span style="color: red;">*</span></label>
                    <select name="pet_uuid" id="bookNowPetDropdown" class="ui dropdown" required>
                        <option value="">Select your pet</option>
                        <!-- Pets will be loaded here by JS -->
                    </select>
                </div>

                <div class="field">
                    <label for="service_uuids">Select Services <span style="color: red;">*</span></label>
                    <select name="service_uuids[]" id="bookNowServiceDropdown" class="ui fluid dropdown" multiple
                        required>
                        <!-- Services will be loaded here by JS -->
                    </select>
                    <small class="text-muted">You can select multiple services for this appointment</small>
                </div>

                <!-- Add custom service request field (hidden by default) -->
                <div class="field" id="customServiceField" style="display: none;">
                    <label for="custom_service_request">Custom Service Request</label>
                    <textarea rows="3" name="custom_service_request"
                        placeholder="Please describe the service you need in detail..."></textarea>
                    <small class="text-muted">Please provide detailed information about the service you require</small>
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