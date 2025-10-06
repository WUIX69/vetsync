<style>
    /* Clean, simple navigation styling */
    .card-header {
        padding: 1.5rem !important;
        background-color: #f8f9fa !important;
    }

    .nav-pills {
        gap: 0.5rem;
    }

    .nav-pills .nav-link {
        border-radius: 25px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .nav-pills .nav-link:hover {
        background-color: rgba(0, 123, 255, 0.1);
        color: #0d6efd;
    }

    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        color: white;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    .nav-pills .nav-link i {
        margin-right: 0.5rem;
    }

    /* Search section styling */
    .bg-light {
        padding: 1rem 1.5rem !important;
    }

    .input-group .input-group-text {
        background-color: white;
        border-right: none;
        color: #6c757d;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    /* Table styling */
    /* Add these styles to improve table column widths and readability */

    /* Revert and simplify - only target what needs to be centered */

    /* Basic table styling */
    .table {
        font-size: 0.95rem;
        table-layout: auto;
    }

    .table th,
    .table td {
        padding: 1rem 0.75rem;
        line-height: 1.5;
        vertical-align: top;
    }

    /* Specific column width adjustments */
    .table th:nth-child(1),
    /* Date */
    .table td:nth-child(1) {
        width: 12%;
        min-width: 120px;
    }

    .table th:nth-child(2),
    /* Pet */
    .table td:nth-child(2) {
        width: 15%;
        min-width: 140px;
    }

    .table th:nth-child(3),
    /* Owner */
    .table td:nth-child(3) {
        width: 18%;
        min-width: 160px;
    }

    /* Fix Service column alignment - update around line 95-110: */

    .table th:nth-child(4),
    /* Service header */
    .table td:nth-child(4) {
        /* Service content */
        width: 25%;
        min-width: 200px;
        max-width: 300px;
        text-align: left !important;
        /* Ensure left alignment */
        vertical-align: top !important;
        /* Align to top for better readability */
    }

    /* Improve service column text display */
    .table td:nth-child(4) .fw-bold {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.25rem;
        display: block;
    }

    .table td:nth-child(4) small {
        font-size: 0.85rem;
        color: #6c757d;
        line-height: 1.4;
        display: block;
        word-wrap: break-word;
        white-space: normal;
        margin-top: 0.25rem;
    }

    /* Make sure service content starts at the same position as header */
    .table td:nth-child(4) {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
    }

    .table th:nth-child(4) {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
        text-align: left !important;
    }

    .table th:nth-child(5),
    /* Status OR Actions in completed table */
    .table td:nth-child(5) {
        width: 12%;
        min-width: 100px;
    }

    .table th:nth-child(6),
    /* Actions in tables with Status column */
    .table td:nth-child(6) {
        width: 12%;
        min-width: 120px;
    }

    /* ONLY center align the Actions columns specifically */
    .table th:nth-child(5),
    /* Actions in completed table (no status column) */
    .table th:nth-child(6) {
        /* Actions in other tables */
        text-align: center !important;
    }

    .table td:nth-child(5),
    /* Actions in completed table */
    .table td:nth-child(6) {
        /* Actions in other tables */
        text-align: center !important;
        vertical-align: middle !important;
    }

    /* For cancelled table with reason column */
    .table th:nth-child(7),
    /* Actions in cancelled table */
    .table td:nth-child(7) {
        width: 10%;
        min-width: 100px;
        text-align: center !important;
        vertical-align: middle !important;
    }

    /* Improve small text readability throughout the table */
    .table td small.text-muted {
        font-size: 0.85rem;
        color: #6c757d;
        line-height: 1.3;
    }

    /* Make the table responsive but maintain readability */
    .table-responsive {
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }

    /* Ensure action buttons don't get cramped */
    .btn-group .btn {
        margin-right: 2px;
        margin-bottom: 2px;
        white-space: nowrap;
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {

        .table th:nth-child(4),
        .table td:nth-child(4) {
            min-width: 180px;
        }
    }

    @media (max-width: 992px) {
        .table {
            font-size: 0.9rem;
        }

        .table th,
        .table td {
            padding: 0.75rem 0.5rem;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
        }

        .btn-group .btn {
            margin-bottom: 2px;
            margin-right: 0;
            font-size: 0.8rem;
        }
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.5em 0.85em;
        font-weight: 600;
    }

    .btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .nav-pills {
            flex-wrap: wrap;
            justify-content: center;
        }

        .nav-pills .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
    }

    .table-controls {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }

    /* Replace the existing .btn-xs styles and add new button color variants around line 233-310: */

    .btn-xs {
        padding: 0.375rem 0.75rem !important;
        font-size: 0.75rem !important;
        line-height: 1.2 !important;
        border-radius: 0.25rem !important;
        display: inline-block !important;
        white-space: nowrap !important;
        font-weight: 500 !important;
        border: none !important;
        transition: all 0.2s ease !important;
        text-align: center !important;
        color: white !important;
        text-decoration: none !important;
    }

    /* Button color variants with solid backgrounds */
    .btn-xs.btn-primary {
        background-color: #0d6efd !important;
    }

    .btn-xs.btn-primary:hover {
        background-color: #0b5ed7 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
    }

    .btn-xs.btn-success {
        background-color: #198754 !important;
    }

    .btn-xs.btn-success:hover {
        background-color: #157347 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(25, 135, 84, 0.3);
    }

    .btn-xs.btn-warning {
        background-color: #fd7e14 !important;
    }

    .btn-xs.btn-warning:hover {
        background-color: #e85d04 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(253, 126, 20, 0.3);
    }

    .btn-xs.btn-danger {
        background-color: #dc3545 !important;
    }

    .btn-xs.btn-danger:hover {
        background-color: #bb2d3b !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
    }

    .btn-xs.btn-secondary {
        background-color: #6c757d !important;
    }

    .btn-xs.btn-secondary:hover {
        background-color: #5c636a !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
    }

    .btn-xs.btn-info {
        background-color: #0dcaf0 !important;
        color: #000 !important;
    }

    .btn-xs.btn-info:hover {
        background-color: #0aa5c2 !important;
        color: #000 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(13, 202, 240, 0.3);
    }

    /* Compact action buttons container */
    .action-buttons {
        display: flex;
        gap: 0.25rem;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    /* Single button centering */
    .action-buttons .btn-xs {
        margin: 0 !important;
        flex-shrink: 0;
    }

    /* Responsive adjustments for compact text buttons */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
            gap: 0.125rem;
            align-items: center !important;
        }

        .btn-xs {
            width: 90% !important;
            max-width: 100px;
            padding: 0.25rem 0.375rem !important;
            font-size: 0.7rem !important;
        }

        .table th:nth-child(6),
        .table td:nth-child(6),
        .table th:nth-child(7),
        .table td:nth-child(7) {
            min-width: 100px !important;
            padding: 0.5rem 0.25rem !important;
        }
    }

    @media (max-width: 576px) {
        .action-buttons {
            gap: 0.1rem;
        }

        .btn-xs {
            font-size: 0.65rem !important;
            padding: 0.2rem 0.3rem !important;
            min-width: 60px !important;
        }
    }

    /* Add this additional CSS to ensure perfect header alignment: */

    /* Force all table headers to center align */
    .table thead th {
        text-align: center !important;
        vertical-align: middle !important;
        font-weight: 600 !important;
        padding: 1rem 0.75rem !important;
        background-color: #f8f9fa !important;
        border-bottom: 2px solid #dee2e6 !important;
    }

    /* Specifically target the Actions header in completed table */
    #appointmentsTableCompleted thead th:nth-child(5),
    #appointmentsTableAll thead th:nth-child(6) {
        text-align: center !important;
        vertical-align: middle !important;
        font-weight: 600 !important;
    }

    /* Ensure all table cells in Actions column are perfectly centered */
    #appointmentsTableCompleted tbody td:nth-child(5),
    #appointmentsTableAll tbody td:nth-child(6) {
        text-align: center !important;
        vertical-align: middle !important;
        padding: 0.75rem 0.5rem !important;
    }

    /* Make sure action buttons container takes full cell height and centers content */
    .table td .action-buttons {
        min-height: 40px;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }

    /* Add this specific fix for Service column alignment: */

    /* Ensure Service header and content have consistent alignment */
    .table th:nth-child(4) {
        text-align: left !important;
        padding-left: 0.75rem !important;
        vertical-align: bottom !important;
    }

    .table td:nth-child(4) {
        text-align: left !important;
        padding-left: 0.75rem !important;
        vertical-align: top !important;
    }

    /* Make sure all table headers align consistently */
    .table th:nth-child(1),
    .table th:nth-child(2),
    .table th:nth-child(3),
    .table th:nth-child(4) {
        text-align: left !important;
        padding-left: 0.75rem !important;
    }

    /* Only center the Status and Actions columns */
    .table th:nth-child(5),
    /* Status */
    .table th:nth-child(6) {
        /* Actions */
        text-align: center !important;
    }

    .table td:nth-child(5),
    /* Status */
    .table td:nth-child(6) {
        /* Actions */
        text-align: center !important;
    }
</style>

<section class="appointments">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <!-- Navigation pills -->
                    <ul class="nav nav-pills justify-content-center" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#all">
                                <i class="bx bx-calendar"></i> All Appointments
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pending">
                                <i class="bx bx-time-five"></i> Pending
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#confirmed">
                                <i class="bx bx-check-circle"></i> Confirmed
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#completed">
                                <i class="bx bx-check-double"></i> Completed
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#cancelled">
                                <i class="bx bx-x-circle"></i> Cancelled
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <!-- All Appointments - SEARCH BACK TO ORIGINAL LOCATION -->
                        <div class="tab-pane fade show active" id="all">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="section-title mb-0">All Appointments</h3>
                                <div class="table-controls">
                                    <input type="text" id="searchTable" class="form-control"
                                        placeholder="Search appointments..." style="width: 250px;">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="appointmentsTableAll">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Pet</th>
                                            <th>Owner</th>
                                            <th>Service</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pending Appointments -->
                        <div class="tab-pane fade" id="pending">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="section-title mb-0">Pending Appointments</h3>
                                <small class="text-muted">Awaiting confirmation</small>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="appointmentsTablePending">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Pet</th>
                                            <th>Owner</th>
                                            <th>Service</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Confirmed Appointments -->
                        <div class="tab-pane fade" id="confirmed">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="section-title mb-0">Confirmed Appointments</h3>
                                <small class="text-muted">Scheduled appointments</small>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="appointmentsTableConfirmed">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Pet</th>
                                            <th>Owner</th>
                                            <th>Service</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Completed Appointments -->
                        <div class="tab-pane fade" id="completed">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="section-title mb-0">Completed Appointments</h3>
                                <small class="text-muted">Medical history & records</small>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="appointmentsTableCompleted">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Pet</th>
                                            <th>Owner</th>
                                            <th>Service</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Cancelled Appointments -->
                        <div class="tab-pane fade" id="cancelled">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="section-title mb-0">Cancelled Appointments</h3>
                                <small class="text-muted">Cancelled records</small>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="appointmentsTableCancelled">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Pet</th>
                                            <th>Owner</th>
                                            <th>Service</th>
                                            <th>Reason</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reschedule Modal -->
<div class="ui modal" id="rescheduleModal">
    <div class="header">Reschedule Appointment</div>
    <div class="content">
        <form class="ui form" id="rescheduleForm">
            <input type="hidden" id="rescheduleUuid" />
            <div class="field">
                <label>New Date</label>
                <input type="date" id="rescheduleDate" min="<?= date('Y-m-d') ?>" required />
            </div>
            <div class="field">
                <label>Reason for Rescheduling</label>
                <textarea id="rescheduleReason" rows="3"
                    placeholder="Optional: Explain why you're rescheduling this appointment..."></textarea>
            </div>
        </form>
    </div>
    <div class="actions">
        <div class="ui black deny button">Cancel</div>
        <div class="ui positive right labeled icon button" id="confirmReschedule">
            Reschedule
            <i class="checkmark icon"></i>
        </div>
    </div>
</div>

<!-- Cancellation Modal -->
<div class="ui modal" id="cancellationModal">
    <div class="header">Cancel Appointment</div>
    <div class="content">
        <form class="ui form">
            <input type="hidden" id="cancelUuid" />
            <div class="field">
                <label>Reason for Cancellation</label>
                <textarea id="cancelReason" rows="3" required
                    placeholder="Please explain why you're cancelling this appointment..."></textarea>
            </div>
        </form>
    </div>
    <div class="actions">
        <div class="ui black deny button">Keep Appointment</div>
        <div class="ui negative right labeled icon button" id="confirmCancel">
            Cancel Appointment
            <i class="remove icon"></i>
        </div>
    </div>
</div>

<!-- Report Modal -->
<div class="ui large modal" id="reportModal">
    <div class="header">
        <i class="file text outline icon"></i>
        Appointment Report
    </div>
    <div class="content">
        <div class="ui active centered inline loader" id="reportLoading" style="display: none;">
            Loading report...
        </div>

        <div id="reportContent" style="display: none;">
            <!-- Main Report Card -->
            <div class="ui fluid card">
                <div class="content">
                    <!-- Header Section -->
                    <div class="ui two column grid">
                        <div class="column">
                            <h3 class="ui header">
                                <i class="calendar alternate outline icon"></i>
                                <div class="content">
                                    Appointment Details
                                    <div class="sub header" id="reportAppointmentId"></div>
                                </div>
                            </h3>
                            <div class="ui relaxed list">
                                <div class="item">
                                    <i class="clock icon"></i>
                                    <div class="content">
                                        <strong>Date & Time:</strong> <span id="reportDate"></span>
                                    </div>
                                </div>
                                <div class="item">
                                    <i class="tag icon"></i>
                                    <div class="content">
                                        <strong>Status:</strong> <span id="reportStatus"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <h3 class="ui header">
                                <i class="paw icon"></i>
                                <div class="content">Pet Information</div>
                            </h3>
                            <div class="ui relaxed list">
                                <div class="item">
                                    <i class="heart icon"></i>
                                    <div class="content">
                                        <strong>Name:</strong> <span id="reportPetName"></span>
                                    </div>
                                </div>
                                <div class="item">
                                    <i class="dog icon"></i>
                                    <div class="content">
                                        <strong>Species:</strong> <span id="reportPetSpecies"></span>
                                    </div>
                                </div>
                                <div class="item">
                                    <i class="certificate icon"></i>
                                    <div class="content">
                                        <strong>Breed:</strong> <span id="reportPetBreed"></span>
                                    </div>
                                </div>
                                <!-- Pet Age - HIDDEN (DOB not used in system)
                                <div class="item">
                                    <i class="birthday cake icon"></i>
                                    <div class="content">
                                        <strong>Age:</strong> <span id="reportPetAge"></span>
                                    </div>
                                </div>
                                -->
                            </div>
                        </div>
                    </div>

                    <div class="ui divider"></div>

                    <!-- Owner and Service Section -->
                    <div class="ui two column grid">
                        <div class="column">
                            <h4 class="ui header">
                                <i class="user icon"></i>Owner Information
                            </h4>
                            <div class="ui relaxed list">
                                <div class="item">
                                    <i class="user outline icon"></i>
                                    <div class="content">
                                        <strong>Name:</strong> <span id="reportOwnerName"></span>
                                    </div>
                                </div>
                                <div class="item">
                                    <i class="mail icon"></i>
                                    <div class="content">
                                        <strong>Email:</strong> <span id="reportOwnerEmail"></span>
                                    </div>
                                </div>
                                <div class="item">
                                    <i class="phone icon"></i>
                                    <div class="content">
                                        <strong>Phone:</strong> <span id="reportOwnerPhone"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <h4 class="ui header">
                                <i class="medical icon"></i>Service Information
                            </h4>
                            <div class="ui relaxed list">
                                <div class="item">
                                    <i class="stethoscope icon"></i>
                                    <div class="content">
                                        <strong>Service:</strong> <span id="reportServiceName"></span>
                                    </div>
                                </div>
                                <div class="item" id="reportServiceDescriptionItem" style="display: none;">
                                    <i class="info circle icon"></i>
                                    <div class="content">
                                        <strong>Description:</strong> <span id="reportServiceDescription"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div id="reportNotesSection" style="display: none;">
                        <div class="ui divider"></div>
                        <h4 class="ui header">
                            <i class="sticky note outline icon"></i>Notes & Instructions
                        </h4>
                        <div class="ui message">
                            <p id="reportNotes"></p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="extra content">
                    <div class="ui right floated">
                        <small class="ui grey text">
                            <i class="time icon"></i>
                            Report generated: <span id="reportGeneratedDate"></span>
                        </small>
                    </div>
                    <div class="ui left floated">
                        <small class="ui grey text">
                            <i class="hospital icon"></i>
                            VetSync Appointment Management System
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div id="reportError" style="display: none;">
            <div class="ui negative message">
                <div class="header">
                    <i class="exclamation triangle icon"></i>
                    Error Loading Report
                </div>
                <p id="reportErrorMessage"></p>
            </div>
        </div>
    </div>

    <div class="actions">
        <div class="ui black deny button">
            <i class="close icon"></i>
            Close
        </div>
        <div class="ui primary button" id="printReport">
            <i class="print icon"></i>
            Print Report
        </div>
    </div>
</div>