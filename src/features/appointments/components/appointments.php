<style>
    /**
     * Appointments Navigation START
     */
    main section.appointments .navigation {
        padding: 1.6rem !important;
    }

    main section.appointments .navigation .nav-pills {
        background: var(--color-white);
        display: flex !important;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin: 0;
    }

    main section.appointments .navigation .nav-pills .nav-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.7rem;

        padding: 1rem 1.5rem;
        border-radius: 1rem;
        transition: all 0.3s ease;
        text-align: center;
        cursor: pointer;
        width: 100%;
        color: var(--color-dark);
    }

    main section.appointments .navigation .nav-pills .nav-link:hover {
        background-color: rgba(0, 0, 0, .03);
    }

    main section.appointments .navigation .nav-pills .nav-link.active {
        background-color: var(--color-dark-variant);
        color: var(--color-white);
        box-shadow: 0 4px 15px rgba(33, 186, 69, 0.2);
    }

    main section.appointments .navigation .nav-pills .nav-link i {
        font-size: 1.4rem;
    }

    /**
     * Appointments Navigation END
     */

    /**
     * Upcoming Appointments Section START
     */
    main section.appointments .date-separator {
        font-size: 1.3rem;
        /* Much larger */
        font-weight: 700;
        color: var(--color-dark-variant);
        margin: 1.5rem 0 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    main section.appointments .date-separator::after {
        content: "";
        flex: 1;
        height: 1px;
        background-color: var(--color-light);
    }

    main section.appointments .appointment-card {
        background-color: var(--color-white);
        border-radius: 1rem;
        /* Larger border radius */
        padding: 2rem;
        /* Much larger padding */
        margin-bottom: 1.5rem;
        border-left: 6px solid var(--color-primary);
        /* Thicker accent border */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        /* Stronger shadow */
        transition: transform 0.2s ease;
    }

    main section.appointments .appointment-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.3rem 0.8rem rgba(0, 0, 0, 0.08);
    }

    main section.appointments .appointment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        /* Increased from 0.75rem */
    }

    main section.appointments .appointment-time {
        font-size: 1.2rem;
        /* Increased from 1rem - MUCH LARGER! */
        font-weight: 600;
        color: var(--color-dark-variant);
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    main section.appointments .appointment-time i {
        font-size: 1.4rem;
        /* Larger icon */
    }

    main section.appointments .appointment-status {
        font-size: 1rem;
        /* Increased from 0.85rem - MUCH LARGER! */
        padding: 0.5rem 1rem;
        border-radius: 1.2rem;
        font-weight: 700;
        /* Extra bold */
        text-transform: uppercase;
        /* Makes it stand out more */
        letter-spacing: 0.5px;
        /* Better spacing */
    }

    main section.appointments .status-confirmed {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    main section.appointments .status-completed {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    main section.appointments .status-cancelled {
        background-color: #ffebee;
        color: #c62828;
    }

    main section.appointments .status-pending {
        background-color: #fff8e1;
        color: #f57c00;
    }

    main section.appointments .appointment-patient {
        display: flex;
        align-items: center;
        gap: 1rem;
        /* Increased from 0.75rem */
        margin-bottom: 1rem;
        /* Increased from 0.75rem */
    }

    main section.appointments .patient-avatar {

        border-radius: 50%;
        background-color: var(--color-light);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 3px solid #e0e0e0;
        /* Thicker border */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        /* Added shadow */
    }

    main section.appointments .patient-avatar img {
        width: 4.5rem !important;
        /* Increased from 3.5rem (56px) to 4.5rem (72px) */
        height: 4.5rem !important;
        /* Much more visible! */
        border-radius: 12px !important;
        object-fit: cover;
        border: 3px solid var(--color-light);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* 🎯 PERFECTLY SIZED TEXT - READABLE BUT NOT OVERWHELMING */
    main section.appointments .patient-info h4 {
        font-size: 1.15rem;
        /* Sweet spot - readable but not huge */
        font-weight: 700;
        margin-bottom: 0.4rem;
        color: var(--color-dark);
        line-height: 1.3;
    }

    main section.appointments .patient-info p {
        font-size: 0.95rem;
        /* Perfect readable size */
        font-weight: 500;
        color: var(--color-dark-variant);
        line-height: 1.4;
        margin-bottom: 0.3rem;
    }

    main section.appointments .appointment-service {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--color-primary);
        margin-bottom: 0.5rem;
    }

    main section.appointments .appointment-service strong {
        font-weight: 700;
        color: var(--color-primary);
        font-size: 1.35rem;
        /* Extra large for "Service:" label */
    }

    main section.appointments .appointment-actions {
        display: flex;
        gap: 0.7rem;
        /* Increased from 0.5rem */
        flex-wrap: wrap;
        /* Allow wrapping on small screens */
    }

    main section.appointments .action-btn {
        border: none;
        background-color: var(--color-light);
        border-radius: 0.6rem;
        padding: 0.8rem 1.4rem;
        /* Much larger padding */
        font-size: 1.1rem;
        /* Increased from 0.9rem - MUCH LARGER! */
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 120px;
        /* Wider buttons */
        text-transform: capitalize;
        /* Better formatting */
    }

    main section.appointments .action-btn:hover {
        background-color: var(--color-primary-light);
        transform: translateY(-1px);
    }

    main section.appointments .action-btn.primary {
        background-color: var(--color-primary);
        color: white;
    }

    main section.appointments .action-btn.primary:hover {
        background-color: var(--color-primary-dark);
    }

    main section.appointments .section-title {
        font-size: 1.8rem;
        /* Much larger section titles */
        font-weight: 700;
        margin-bottom: 2rem;
        color: var(--color-dark);
    }

    /* 🖼️ BIGGER PET IMAGES - MORE VISIBLE! */
    main section.appointments .appointment-card img {
        width: 80px !important;
        /* Increased from 60px */
        height: 80px !important;
        /* Increased from 60px */
        border-radius: 12px !important;
        object-fit: cover;
        border: 3px solid var(--color-light);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* 📅 SERVICE INFO STYLING */
    main section.appointments .service-info {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--color-primary);
        margin-bottom: 0.5rem;
    }

    /* 🏷️ STATUS BADGES */
    main section.appointments .status-badge {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* 🎯 BUTTON IMPROVEMENTS */
    main section.appointments .btn {
        font-size: 1rem;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
    }

    /**
     * Upcoming Appointments Section END
     */

    #cancellationForm.error .field textarea {
        border-color: #e0b4b4 !important;
        background: #fff6f6 !important;
    }

    #cancellationForm.error .field label:after {
        content: " (Required)";
        color: #9f3a38;
    }

    /* Simple Yellow Banner Design */
    .reason-info {
        margin: 0.75rem 0;
    }

    .reason-info .alert {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        margin-bottom: 0;
        font-size: 0.85rem;
        color: #856404;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .reason-info .alert i {
        color: #f39c12;
        font-size: 1rem;
    }

    /* Remove different colors - all use same yellow design */
    .admin-reason .alert,
    .user-reason .alert,
    .reschedule-reason .alert {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
    }

    /* Admin Reason Info */
    .admin-reason-info,
    .user-reason-info,
    .reschedule-reason-info {
        margin: 0.5rem 0;
        padding: 0.5rem;
        border-radius: 0.25rem;
    }

    .admin-reason-info {
        background-color: #fff3cd;
        border-left: 3px solid #ffc107;
    }

    .user-reason-info {
        background-color: #d1ecf1;
        border-left: 3px solid #17a2b8;
    }

    .reschedule-reason-info {
        background-color: #d4edda;
        border-left: 3px solid #28a745;
    }

    /* Simple Yellow Banner for Admin Side */
    .admin-reason-info,
    .user-reason-info,
    .reschedule-reason-info {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        margin: 0.5rem 0;
        font-size: 0.85rem;
        color: #856404;
    }
</style>

<section class="appointments">
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card navigation">
                <div class="nav flex-column nav-pills card-body" role="tablist">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#all">
                        <i class="bx bx-calendar"></i>All Appointments
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pending">
                        <i class="bx bx-time-five"></i>Pending
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#confirmed">
                        <i class="bx bx-check-circle"></i>Confirmed
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#completed">
                        <i class="bx bx-check-double"></i>Completed
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#cancelled">
                        <i class="bx bx-x-circle"></i>Cancelled
                    </button>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="tab-content container">
                <!-- All Appointments -->
                <div class="tab-pane fade show active" id="all">
                    <h3 class="section-title">All Appointments</h3>
                    <div class="date-separator">Recent</div>
                    <div id="appointmentsCardsAll"></div>
                </div>

                <!-- Pending Appointments -->
                <div class="tab-pane fade" id="pending">
                    <h3 class="section-title">Pending Appointments</h3>
                    <div class="date-separator">Awaiting Confirmation</div>
                    <div id="appointmentsCardsPending"></div>
                </div>

                <!-- Confirmed Appointments -->
                <div class="tab-pane fade" id="confirmed">
                    <h3 class="section-title">Confirmed Appointments</h3>
                    <div class="date-separator">Scheduled</div>
                    <div id="appointmentsCardsConfirmed"></div>
                </div>

                <!-- Completed Appointments (NEW) -->
                <div class="tab-pane fade" id="completed">
                    <h3 class="section-title">Completed Appointments</h3>
                    <div class="date-separator">Medical History & Records</div>
                    <div id="appointmentsCardsCompleted"></div>
                </div>

                <!-- Cancelled Appointments -->
                <div class="tab-pane fade" id="cancelled">
                    <h3 class="section-title">Cancelled Appointments</h3>
                    <div class="date-separator">Cancelled Records</div>
                    <div id="appointmentsCardsCancelled"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pagination START -->
    <?= shared('components/pagination'); ?>
    <!-- Pagination END -->

    <!-- Reschedule Modal -->
    <div class="ui small modal" id="rescheduleModal">
        <div class="header">
            <i class="calendar icon"></i>
            Reschedule Appointment
        </div>
        <div class="content">
            <form class="ui form" id="rescheduleForm">
                <div class="field">
                    <label>New Date</label>
                    <input type="date" id="rescheduleDate" min="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="field">
                    <label>Reason for Rescheduling</label>
                    <textarea rows="3" id="rescheduleReason" placeholder="e.g., Doctor unavailable, equipment maintenance, emergency..."></textarea>
                </div>
            </form>
        </div>
        <div class="actions">
            <button class="ui cancel button">
                <i class="remove icon"></i>
                Keep Original Date
            </button>
            <button class="ui blue approve button" id="confirmReschedule">
                <i class="calendar icon"></i>
                Reschedule Appointment
            </button>
        </div>
    </div>

    <!-- Cancellation Reason Modal -->
    <div class="ui small modal" id="cancellationModal">
        <div class="header">
            <i class="times circle icon"></i>
            Cancel Appointment
        </div>
        <div class="content">
            <p>Please provide a reason for cancelling this appointment:</p>
            <form class="ui form" id="cancellationForm">
                <div class="field">
                    <label>Cancellation Reason</label>
                    <textarea rows="3" id="cancellationReason"
                        placeholder="e.g., Doctor emergency, equipment malfunction, patient request..."></textarea>
                </div>
            </form>
        </div>
        <div class="actions">
            <button class="ui cancel button">
                <i class="remove icon"></i>
                Keep Appointment
            </button>
            <button class="ui red approve button" id="confirmCancellation">
                <i class="checkmark icon"></i>
                Cancel Appointment
            </button>
        </div>
    </div>
</section>