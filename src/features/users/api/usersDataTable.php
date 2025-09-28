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

    // ✅ SIMPLE: Use basic users table columns
    $columns = array(
        ['db' => 'firstname', 'dt' => 'firstname'],
        ['db' => 'lastname', 'dt' => 'lastname'],
        ['db' => 'email', 'dt' => 'email'],
        ['db' => 'telephone', 'dt' => 'telephone'],
        ['db' => 'location', 'dt' => 'location'],
        ['db' => 'verification_status', 'dt' => 'verification_status'],
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

    // ✅ ADD PROFILE IMAGES: Use the SAME method as "New Users" section
    foreach ($data['data'] as &$user) {
        // ✅ EXACT SAME LOGIC as recent-users-admin.php
        $avatarUrl = null;
        if (function_exists('media')) {
            $avatarUrl = media($user['user_uuid']);
        }

        // If no profile image or media function doesn't exist, use a nice placeholder
        if (!$avatarUrl || $avatarUrl === '/public/img/profiles/') {
            $fullName = ($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '');
            $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($fullName) .
                "&size=35&background=random&color=fff&font-size=0.6";
        }

        $user['profile_image'] = $avatarUrl;
        error_log("📸 Profile image for {$user['user_uuid']}: {$avatarUrl}");
    }

    // ✅ ADD CURRENT ADMIN TO RESULTS IF NEEDED
    if ($includeCurrentAdmin && $currentAdmin) {
        // Add current admin as first result
        array_unshift($data['data'], $currentAdmin);
        $data['recordsTotal']++;
        $data['recordsFiltered']++;
        error_log("✅ Added current admin session to results");
    }

    error_log("📊 Final result: " . count($data['data']) . " total users");

    echo json_encode($data);

} catch (Exception $e) {
    error_log("usersDataTable error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to load users',
        'error' => $e->getMessage(),
        'data' => [],
        'recordsTotal' => 0,
        'recordsFiltered' => 0
    ]);
}
?>