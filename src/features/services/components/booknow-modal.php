<style>
    #bookNowModal .ui.form .submit.button {
        background: #000;
        color: #fff;
        font-size: 1rem !important;
        padding: 1rem 2rem;
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

    .booking-policies {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    .booking-policies strong {
        color: #856404;
    }

    .booking-policies ul {
        margin: 0.5rem 0 0 0;
        padding-left: 1.2rem;
    }

    .booking-policies li {
        margin-bottom: 0.3rem;
        color: #856404;
    }
</style>

<?php
// Get current user data for display purposes
$currentUser = userData();
$userFullName = $currentUser['name'] ?? '';
?>

<div class="ui modal" id="bookNowModal">
    <i class="close icon"></i>
    <div class="header">
        <i class="calendar plus icon"></i> Book Appointment
    </div>
    <div class="content">
        <!-- Compact Booking Policies -->
        <div class="booking-policies">
            <strong><i class="info circle icon"></i> Important Booking Policies:</strong>
            <ul>
                <li><strong>10 appointments/day maximum</strong> - First come, first served basis</li>
                <li><strong>Time slots:</strong> 1-hour intervals, but actual service time may vary based on procedure
                </li>
                <li><strong>Lateness policy:</strong> Late arrivals will be placed at the end of the queue</li>
                <li><strong>Cannot cancel within 2 days</strong> of appointment date</li>
                <li><strong>No-show penalty:</strong> 20% health reduction affects booking priority</li>
                <li>For cancellations within 2 days, contact us directly</li>
            </ul>
        </div>

        <form class="ui form" id="bookNowForm">
            <!-- Date Field -->
            <div class="field">
                <label for="date">Date <span style="color: red;">*</span></label>
                <input type="date" name="date" id="appointmentDateInput" min="<?= date('Y-m-d') ?>" required />
                <small class="text-muted">Select your preferred appointment date</small>
                <div class="date-availability-info" id="dateAvailability">
                    <!-- Availability message will be shown here -->
                </div>
            </div>

            <!-- Time Slot Field -->
            <div class="field">
                <label for="time">Preferred Time <span style="color: red;">*</span></label>
                <select name="time" id="appointmentTimeSlot" class="ui dropdown" required disabled>
                    <option value="">Select a date first</option>
                </select>
                <small class="text-muted">Available time slots (clinic hours: 9 AM - 8 PM, lunch: 12 PM)</small>
            </div>

            <!-- Pet Dropdown -->
            <div class="field">
                <label for="pet_uuid">Select Pet <span style="color: red;">*</span></label>
                <select name="pet_uuid" id="bookNowPetDropdown" class="ui dropdown" required>
                    <option value="">Select your pet</option>
                    <!-- Pets will be loaded here by JS -->
                </select>
            </div>

            <!-- Services -->
            <div class="field">
                <label for="service_uuids">Select Services <span style="color: red;">*</span></label>
                <select name="service_uuids[]" id="bookNowServiceDropdown" class="ui fluid dropdown" multiple required>
                    <!-- Services will be loaded here by JS -->
                </select>
                <small class="text-muted">You can select multiple services for this appointment</small>
            </div>

            <!-- Custom service request field (hidden by default) -->
            <div class="field" id="customServiceField" style="display: none;">
                <label for="custom_service_request">Custom Service Request</label>
                <textarea rows="3" name="custom_service_request"
                    placeholder="Please describe the service you need in detail..."></textarea>
                <small class="text-muted">Please provide detailed information about the service you require</small>
            </div>

            <!-- Special Request -->
            <div class="field">
                <label for="special_request">Special Request</label>
                <textarea rows="3" name="special_request"
                    placeholder="Any special instructions or requests for your appointment..."></textarea>
            </div>

            <div class="actions" style="text-align:center; margin-top: 1.5rem;">
                <button class="ui black deny button" type="button">Cancel</button>
                <button class="ui positive submit button" type="submit">
                    <i class="calendar check icon"></i> Book Appointment
                </button>
            </div>
        </form>
    </div>
</div>