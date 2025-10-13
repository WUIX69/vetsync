<?php
// Add currently logged-in admin to users table
include_once __DIR__ . '/src/core/app.php'; // This loads the session properly

try {
    echo "<h2>🔍 Checking Current Admin User</h2>";

    // Check if session exists and user is admin
    if (!$session->has()) {
        echo "<p style='color: red;'>❌ No active session found!</p>";
        echo "<p><a href='/src/auth/index.php'>→ Go to Admin Login</a></p>";
        exit;
    }

    $sessionData = $session->get();
    $userType = $sessionData['type'] ?? '';

    if ($userType !== 'admin') {
        echo "<p style='color: red;'>❌ You are not logged in as admin!</p>";
        echo "<p>Current session type: <strong>$userType</strong></p>";
        echo "<p><a href='/src/auth/index.php'>→ Go to Admin Login</a></p>";
        exit;
    }

    $currentAdminEmail = $sessionData['email'] ?? '';
    echo "<p style='color: blue;'>👤 Currently logged in as admin: <strong>$currentAdminEmail</strong></p>";

    if (empty($currentAdminEmail)) {
        echo "<p style='color: red;'>❌ Admin email not found in session!</p>";
        echo "<p>Session data:</p>";
        echo "<pre>" . print_r($sessionData, true) . "</pre>";
        exit;
    }

    echo "<p style='color: green;'>✅ Database connection successful!</p>";

    // Check if current admin exists in users table
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$currentAdminEmail]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        echo "<p style='color: green;'>✅ Your admin account is already in the users table!</p>";
        echo "<h3>Your Admin Details:</h3>";
        echo "<ul>";
        foreach ($admin as $key => $value) {
            echo "<li><strong>$key:</strong> " . htmlspecialchars($value) . "</li>";
        }
        echo "</ul>";

        echo "<p style='color: green;'>✅ You should see your admin account in Users Management!</p>";
        echo "<p><a href='/src/app/admin/users.php' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>→ Go to Users Management</a></p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Your admin account is NOT in the users table yet.</p>";
        echo "<p>Email: <strong>$currentAdminEmail</strong></p>";

        echo "<h3>🔧 Add Your Admin Account to Users Table?</h3>";
        echo "<p>This will add your current admin login to the users management system.</p>";
        echo "<form method='post'>";
        echo "<button type='submit' name='add_current_admin' style='background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>✅ Add My Admin Account</button>";
        echo "</form>";
    }

    // Handle adding current admin to users table
    if (isset($_POST['add_current_admin'])) {
        echo "<hr><h3>🔧 Adding Your Admin Account...</h3>";

        // Generate UUID for admin
        function generateUUID()
        {
            return sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff)
            );
        }

        $adminUuid = generateUUID();

        // Get admin data from session or use defaults
        $adminFirstName = $sessionData['name'] ?? 'System';
        $adminLastName = 'Administrator';

        // Split name if it contains space
        if (isset($sessionData['name']) && strpos($sessionData['name'], ' ') !== false) {
            $nameParts = explode(' ', $sessionData['name'], 2);
            $adminFirstName = $nameParts[0];
            $adminLastName = $nameParts[1] ?? 'Administrator';
        }

        // Insert current admin into users table
        $createStmt = $conn->prepare("
            INSERT INTO users (
                uuid, firstname, lastname, email, telephone, location,
                password, verification_status, verified_at, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        // Use the same password hash as your login system
        $hashedPassword = $sessionData['password'] ?? '$2y$10$xKIlO8qhCbD5hxi466mlHupG5f8LkDakJia8T90kbwsBpS/RjNhg2';

        $result = $createStmt->execute([
            $adminUuid,
            $adminFirstName,
            $adminLastName,
            $currentAdminEmail,
            '+1-234-567-8900',
            'Admin Location',
            $hashedPassword,
            'verified',
            date('Y-m-d H:i:s')
        ]);

        if ($result) {
            echo "<p style='color: green;'>✅ Your admin account has been added to users table!</p>";
            echo "<h3>Added Details:</h3>";
            echo "<ul>";
            echo "<li><strong>UUID:</strong> $adminUuid</li>";
            echo "<li><strong>Name:</strong> $adminFirstName $adminLastName</li>";
            echo "<li><strong>Email:</strong> $currentAdminEmail</li>";
            echo "<li><strong>Status:</strong> Verified</li>";
            echo "</ul>";

            echo "<p style='color: green; font-weight: bold;'>🎉 You can now see your admin account in Users Management!</p>";
            echo "<p><a href='/src/app/admin/users.php' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>→ Go to Users Management</a></p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to add your admin account!</p>";
        }
    }

    // Show session debug info
    echo "<h3>Session Debug Info:</h3>";
    echo "<pre>" . print_r($sessionData, true) . "</pre>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 40px;
    }

    h2,
    h3 {
        color: #2c3e50;
    }

    ul {
        margin: 10px 0;
        padding-left: 20px;
    }

    li {
        margin: 5px 0;
    }

    button {
        font-size: 16px;
    }

    button:hover {
        opacity: 0.9;
    }

    pre {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        overflow-x: auto;
    }
</style>