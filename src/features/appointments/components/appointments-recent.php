<?php
// Simple fetch of 4 recent appointments
$recent_appointments = [];
$debug_info = '';

try {
    // Get database connection
    global $conn;
    include_once dirname(__FILE__) . '/../../../core/conn.php';

    if ($conn) {
        $debug_info .= "✅ Database connected. ";

        // First, let's just check if we have any appointments at all
        $count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM appointments");
        $count_stmt->execute();
        $count_result = $count_stmt->fetch(PDO::FETCH_ASSOC);
        $debug_info .= "Total appointments in DB: " . $count_result['total'] . ". ";

        // Now get the recent ones with CORRECT column names
        $stmt = $conn->prepare("
            SELECT 
                a.date,
                a.time,
                a.status,
                s.name AS service_name,
                p.name AS pet_name,
                p.breed AS pet_breed,
                a.created_at
            FROM appointments a
            LEFT JOIN services s ON a.service_uuid = s.uuid
            LEFT JOIN pets p ON a.pet_uuid = p.uuid
            ORDER BY a.created_at DESC
            LIMIT 4
        ");
        $stmt->execute();
        $recent_appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $debug_info .= "Recent appointments found: " . count($recent_appointments) . ". ";

    } else {
        $debug_info .= "❌ Database connection failed. ";
    }
} catch (Exception $e) {
    $debug_info .= "❌ Error: " . $e->getMessage() . ". ";
    $recent_appointments = [];
}

// Simple function to format date only (no time)
function formatDateOnly($date)
{
    if (empty($date)) {
        return 'No date';
    }

    $datetime = DateTime::createFromFormat('Y-m-d', $date);
    if (!$datetime) {
        return $date; // Return as-is if can't format
    }

    $now = new DateTime();
    $today = $now->format('Y-m-d');
    $yesterday = $now->modify('-1 day')->format('Y-m-d');
    $tomorrow = $now->modify('+2 days')->format('Y-m-d');

    if ($datetime->format('Y-m-d') === $today) {
        return 'Today';
    } elseif ($datetime->format('Y-m-d') === $yesterday) {
        return 'Yesterday';
    } elseif ($datetime->format('Y-m-d') === $tomorrow) {
        return 'Tomorrow';
    } else {
        return $datetime->format('M j, Y');
    }
}

// Simple function to get pet avatar
function getPetAvatar($pet_name)
{
    if (empty($pet_name)) {
        $pet_name = 'Pet';
    }
    $initial = strtoupper(substr($pet_name, 0, 1));
    return "https://ui-avatars.com/api/?name=" . urlencode($initial) . "&background=random&color=fff&bold=true&size=80";
}
?>

<style>
    /* Recent Appointments Section */
    main section.recent-appointments .appointment-card {
        background-color: var(--color-white);
        border-radius: 0.6rem;
        padding: 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--color-primary);
        box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease;
    }

    main section.recent-appointments .appointment-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.3rem 0.8rem rgba(0, 0, 0, 0.08);
    }

    main section.recent-appointments .appointment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    main section.recent-appointments .appointment-time {
        font-size: 0.8rem;
        color: var(--color-dark-variant);
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    main section.recent-appointments .appointment-status {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        border-radius: 1rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    /* Status Colors */
    main section.recent-appointments .status-confirmed {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    main section.recent-appointments .status-completed {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    main section.recent-appointments .status-cancelled {
        background-color: #ffebee;
        color: #c62828;
    }

    main section.recent-appointments .status-pending {
        background-color: #fff8e1;
        color: #f57c00;
    }

    /* NEW: Accepted status styling */
    main section.recent-appointments .status-accepted {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    main section.recent-appointments .appointment-patient {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    main section.recent-appointments .patient-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background-color: var(--color-light);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    main section.recent-appointments .patient-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    main section.recent-appointments .patient-info h4 {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
    }

    main section.recent-appointments .patient-info p {
        font-size: 0.8rem;
        color: var(--color-dark-variant);
    }

    main section.recent-appointments .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--color-dark);
    }

    .no-appointments {
        text-align: center;
        padding: 2rem 1rem;
        color: var(--color-dark-variant);
    }

    /* Debug info styling - commented out but kept for future use */
    /*
    .debug-info {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        color: #495057;
    }
    */
</style>

<section class="recent-appointments">
    <h3 class="section-title">Recent Appointments</h3>

    <!-- Debug Information - COMMENTED OUT -->
    <!-- 
    <div class="debug-info">
        🔍 Debug: <?= $debug_info ?>
    </div>
    -->

    <?php if (empty($recent_appointments)): ?>
        <div class="no-appointments">
            <i class="material-icons-sharp">event_busy</i>
            <p>No recent appointments found</p>
            <!-- <small>Check debug info above for details</small> -->
        </div>
    <?php else: ?>
        <?php foreach ($recent_appointments as $appointment): ?>
            <div class="appointment-card">
                <div class="appointment-header">
                    <div class="appointment-time">
                        <i class="material-icons-sharp">today</i>
                        <?= formatDateOnly($appointment['date']) ?>
                    </div>
                    <span class="appointment-status status-<?= htmlspecialchars($appointment['status']) ?>">
                        <?= ucfirst(htmlspecialchars($appointment['status'])) ?>
                    </span>
                </div>
                <div class="appointment-patient">
                    <div class="patient-avatar">
                        <img src="<?= getPetAvatar($appointment['pet_name']) ?>" alt="Pet Avatar">
                    </div>
                    <div class="patient-info">
                        <h4>
                            <?= htmlspecialchars($appointment['pet_name'] ?: 'Unknown Pet') ?>
                            <?php if (!empty($appointment['pet_breed'])): ?>
                                (<?= htmlspecialchars($appointment['pet_breed']) ?>)
                            <?php endif; ?>
                        </h4>
                        <p><?= htmlspecialchars($appointment['service_name'] ?: 'General Service') ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>