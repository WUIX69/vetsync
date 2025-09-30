<?php

include '../../../core/app.php';
apiHeaders();

use VetSync\Utils\Php\Formatters;
use VetSync\Services\DataTables;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response['message'] = 'Invalid usersDataTable request method';
    echo json_encode($response);
    exit;
}

try {
    // Get filter parameters
    $statusFilter = $_GET['status_filter'] ?? '';
    $roleFilter = $_GET['role_filter'] ?? '';

    error_log("=== USERS DATATABLE ===");
    error_log("Status filter: '$statusFilter'");
    error_log("Role filter: '$roleFilter'");

    // ✅ CHECK CURRENT ADMIN SESSION
    global $session;
    $currentAdmin = null;
    if ($session->has()) {
        $sessionData = $session->get();
        if (($sessionData['type'] ?? '') === 'admin') {
            $currentAdmin = [
                'firstname' => 'System',
                'lastname' => 'Administrator',
                'email' => $sessionData['email'] ?? 'admin@mail.com',
                'telephone' => '+1-234-567-8900',
                'location' => 'Admin Panel',
                'verification_status' => 'verified',
                'created_at' => date('Y-m-d H:i:s'),
                'user_uuid' => 'admin-session-' . substr(md5($sessionData['email'] ?? 'admin'), 0, 8),
                'profile_image' => asset('img/profiles/user-1.jpg'),
            ];
            error_log("🔍 Current admin session: " . $currentAdmin['email']);
        }
    }

    // ✅ COLUMNS CONFIGURATION - All columns searchable for proper search
    $columns = array(
        ['db' => 'firstname', 'dt' => 'firstname'],
        ['db' => 'lastname', 'dt' => 'lastname'],
        ['db' => 'email', 'dt' => 'email'],
        ['db' => 'telephone', 'dt' => 'telephone'],
        ['db' => 'location', 'dt' => 'location'],
        ['db' => 'verification_status', 'dt' => 'verification_status'],
        ['db' => 'user_health', 'dt' => 'user_health'], // Add user health
        ['db' => 'created_at', 'dt' => 'created_at'],
        ['db' => 'uuid', 'dt' => 'user_uuid'],
    );

    // ✅ FILTERING LOGIC
    $whereConditions = [];
    $whereBindings = [];
    $includeCurrentAdmin = false;

    // Handle status filter
    if (!empty($statusFilter)) {
        $whereConditions[] = "verification_status = :status";
        $whereBindings['status'] = $statusFilter;
        error_log("🔍 Applied status filter: {$statusFilter}");
    }

    // Handle role filter
    if (!empty($roleFilter)) {
        if ($roleFilter === 'admin') {
            $whereConditions[] = "email = :admin_email";
            $whereBindings['admin_email'] = $currentAdmin['email'] ?? 'admin@mail.com';
            error_log("🔍 Applied admin role filter");
        } elseif ($roleFilter === 'user') {
            $whereConditions[] = "email != :admin_email";
            $whereBindings['admin_email'] = $currentAdmin['email'] ?? 'admin@mail.com';
            error_log("🔍 Applied user role filter");
        }
    }

    // ✅ DETERMINE IF ADMIN SHOULD BE INCLUDED
    if ($currentAdmin) {
        if (empty($roleFilter) || $roleFilter === 'admin') {
            if (empty($statusFilter) || $currentAdmin['verification_status'] === $statusFilter) {
                $includeCurrentAdmin = true;
                error_log("✅ Admin will be included in results");
            } else {
                error_log("❌ Admin excluded - status doesn't match filter");
            }
        } else {
            error_log("❌ Admin excluded - role filter is 'user'");
        }
    }

    // Get database users
    if (!empty($whereConditions)) {
        $whereClause = [
            'condition' => implode(' AND ', $whereConditions),
            'bindings' => $whereBindings
        ];
        $data = DataTables::complex($_GET, $conn, 'users', 'uuid', $columns, $whereClause);
        error_log("Using filtered query: " . $whereClause['condition']);
    } else {
        $data = DataTables::simple($_GET, $conn, 'users', 'uuid', $columns);
        error_log("Using simple query - no filters");
    }

    error_log("📊 Retrieved " . count($data['data']) . " users from database");

    // ✅ ADD PROFILE IMAGES
    foreach ($data['data'] as &$user) {
        $avatarUrl = null;
        if (function_exists('media')) {
            $avatarUrl = media($user['user_uuid']);
        }

        if (!$avatarUrl || $avatarUrl === '/public/img/profiles/') {
            $fullName = ($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '');
            $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($fullName) .
                "&size=35&background=random&color=fff&font-size=0.6";
        }

        $user['profile_image'] = $avatarUrl;
    }

    // ✅ FIXED: ADD ADMIN ONLY TO FIRST PAGE AND IF MATCHES SEARCH
    if ($includeCurrentAdmin && $currentAdmin) {
        // Check if admin matches search term (if any)
        $searchTerm = $_GET['search']['value'] ?? '';
        $shouldIncludeAdmin = true;

        if (!empty($searchTerm)) {
            $searchTerm = strtolower($searchTerm);
            $adminData = strtolower(
                $currentAdmin['firstname'] . ' ' .
                $currentAdmin['lastname'] . ' ' .
                $currentAdmin['email'] . ' ' .
                $currentAdmin['location'] . ' ' .
                $currentAdmin['telephone']
            );
            $shouldIncludeAdmin = strpos($adminData, $searchTerm) !== false;
        }

        // Only add admin to first page (when start is 0)
        $start = intval($_GET['start'] ?? 0);
        $isFirstPage = ($start === 0);

        if ($shouldIncludeAdmin) {
            // Always count admin in totals
            $data['recordsTotal']++;
            $data['recordsFiltered']++;

            if ($isFirstPage) {
                // Add current admin as first result only on first page
                array_unshift($data['data'], $currentAdmin);
                error_log("✅ Added current admin session to first page");
            } else {
                error_log("ℹ️ Admin counted in totals but not shown (page > 1)");
            }
        } else {
            error_log("❌ Admin excluded - doesn't match search term: '$searchTerm'");
        }
    }

    error_log("📊 Final result: " . count($data['data']) . " users on this page");

    echo json_encode($data);

} catch (Exception $e) {
    error_log("Users DataTable Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error loading users data'
    ]);
}
?>