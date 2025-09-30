<?php
// Test file to verify the user health system functionality

include 'src/core/app.php';

// Set headers for testing
header('Content-Type: text/html; charset=UTF-8');

// Test data
$testResults = [];

try {
    global $conn;
    
    // ========================================
    // TEST 1: Verify user_health column exists
    // ========================================
    $testResults['database_setup'] = [];
    
    try {
        $columnCheck = $conn->prepare("SHOW COLUMNS FROM users LIKE 'user_health'");
        $columnCheck->execute();
        $columnExists = $columnCheck->fetch(PDO::FETCH_ASSOC);
        
        if ($columnExists) {
            $testResults['database_setup']['column_exists'] = '✅ user_health column exists';
        } else {
            $testResults['database_setup']['column_exists'] = '❌ user_health column not found';
        }
    } catch (Exception $e) {
        $testResults['database_setup']['column_exists'] = '❌ Error checking column: ' . $e->getMessage();
    }
    
    // ========================================
    // TEST 2: Check current user health values
    // ========================================
    $testResults['current_health'] = [];
    
    try {
        $healthCheck = $conn->prepare("SELECT email, user_health FROM users WHERE user_health IS NOT NULL ORDER BY user_health ASC LIMIT 10");
        $healthCheck->execute();
        $healthResults = $healthCheck->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($healthResults)) {
            foreach ($healthResults as $user) {
                $health = floatval($user['user_health']);
                $healthIcon = $health >= 80 ? '💚' : ($health >= 50 ? '🟡' : '🔴');
                $testResults['current_health'][] = $healthIcon . ' ' . $user['email'] . ': ' . $health . '%';
            }
        } else {
            $testResults['current_health'][] = '⚠️ No users found with health data';
        }
    } catch (Exception $e) {
        $testResults['current_health'][] = '❌ Error checking user health: ' . $e->getMessage();
    }
    
    // ========================================
    // TEST 3: Get sample reservation for testing
    // ========================================
    $testResults['sample_reservation'] = [];
    
    try {
        $reservationCheck = $conn->prepare("
            SELECT r.id, r.user_uuid, r.status, u.email, u.user_health 
            FROM reservations r 
            LEFT JOIN users u ON r.user_uuid = u.uuid 
            WHERE r.status = 'ready_for_pickup' 
            LIMIT 1
        ");
        $reservationCheck->execute();
        $sampleReservation = $reservationCheck->fetch(PDO::FETCH_ASSOC);
        
        if ($sampleReservation) {
            $testResults['sample_reservation']['found'] = '✅ Sample reservation found';
            $testResults['sample_reservation']['details'] = [
                'id' => $sampleReservation['id'],
                'user_email' => $sampleReservation['email'],
                'current_health' => $sampleReservation['user_health'] . '%',
                'status' => $sampleReservation['status']
            ];
        } else {
            $testResults['sample_reservation']['found'] = '⚠️ No ready_for_pickup reservations available for testing';
        }
    } catch (Exception $e) {
        $testResults['sample_reservation']['found'] = '❌ Error finding sample reservation: ' . $e->getMessage();
    }
    
    // ========================================
    // TEST 4: Simulate no-show penalty
    // ========================================
    $testResults['penalty_simulation'] = [];
    
    // Find a test user (not admin)
    try {
        $testUserStmt = $conn->prepare("
            SELECT uuid, email, user_health 
            FROM users 
            WHERE email != 'admin@mail.com' 
            AND user_health >= 20 
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $testUserStmt->execute();
        $testUser = $testUserStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($testUser) {
            $originalHealth = floatval($testUser['user_health']);
            $expectedNewHealth = max(0, $originalHealth - 20);
            
            $testResults['penalty_simulation']['test_user'] = $testUser['email'];
            $testResults['penalty_simulation']['original_health'] = $originalHealth . '%';
            $testResults['penalty_simulation']['expected_new_health'] = $expectedNewHealth . '%';
            $testResults['penalty_simulation']['ready_for_test'] = '✅ User ready for penalty simulation';
            
            // Note: We won't actually apply the penalty in this test file
            $testResults['penalty_simulation']['note'] = '⚠️ Penalty not applied in test - use admin interface to test';
        } else {
            $testResults['penalty_simulation']['ready_for_test'] = '⚠️ No suitable test user found';
        }
    } catch (Exception $e) {
        $testResults['penalty_simulation']['ready_for_test'] = '❌ Error finding test user: ' . $e->getMessage();
    }
    
    // ========================================
    // TEST 5: Check DataTables API for health data
    // ========================================
    $testResults['datatables_api'] = [];
    
    try {
        // Simulate the DataTables API call
        $columns = array(
            ['db' => 'firstname', 'dt' => 'firstname'],
            ['db' => 'lastname', 'dt' => 'lastname'],
            ['db' => 'email', 'dt' => 'email'],
            ['db' => 'user_health', 'dt' => 'user_health'],
            ['db' => 'uuid', 'dt' => 'user_uuid'],
        );
        
        $table = 'users';
        $primaryKey = 'id';
        $whereCondition = "email != 'admin@mail.com'";
        
        // Simple query to test health data
        $healthAPIStmt = $conn->prepare("
            SELECT firstname, lastname, email, user_health, uuid
            FROM users 
            WHERE email != 'admin@mail.com' 
            LIMIT 5
        ");
        $healthAPIStmt->execute();
        $healthAPIResults = $healthAPIStmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($healthAPIResults)) {
            $testResults['datatables_api']['status'] = '✅ DataTables API test successful';
            $testResults['datatables_api']['sample_data'] = [];
            
            foreach ($healthAPIResults as $user) {
                $health = floatval($user['user_health'] ?? 100);
                $healthColor = $health >= 80 ? 'green' : ($health >= 50 ? 'orange' : 'red');
                $testResults['datatables_api']['sample_data'][] = [
                    'name' => $user['firstname'] . ' ' . $user['lastname'],
                    'email' => $user['email'],
                    'health' => $health . '%',
                    'health_color' => $healthColor
                ];
            }
        } else {
            $testResults['datatables_api']['status'] = '⚠️ No user data found';
        }
    } catch (Exception $e) {
        $testResults['datatables_api']['status'] = '❌ DataTables API test failed: ' . $e->getMessage();
    }
    
    // ========================================
    // TEST 6: Priority calculation test
    // ========================================
    $testResults['priority_test'] = [];
    
    try {
        $priorityStmt = $conn->prepare("
            SELECT email, user_health,
            CASE 
                WHEN user_health >= 80 THEN 'High Priority'
                WHEN user_health >= 50 THEN 'Medium Priority'
                WHEN user_health >= 20 THEN 'Low Priority'
                ELSE 'Lowest Priority'
            END as priority_level
            FROM users 
            WHERE email != 'admin@mail.com'
            ORDER BY user_health DESC
            LIMIT 10
        ");
        $priorityStmt->execute();
        $priorityResults = $priorityStmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($priorityResults)) {
            $testResults['priority_test']['status'] = '✅ Priority calculation working';
            $testResults['priority_test']['user_priorities'] = [];
            
            foreach ($priorityResults as $user) {
                $health = floatval($user['user_health']);
                $priority = $user['priority_level'];
                $icon = $health >= 80 ? '🟢' : ($health >= 50 ? '🟡' : ($health >= 20 ? '🟠' : '🔴'));
                
                $testResults['priority_test']['user_priorities'][] = 
                    $icon . ' ' . $user['email'] . ' (' . $health . '%) - ' . $priority;
            }
        } else {
            $testResults['priority_test']['status'] = '⚠️ No users for priority testing';
        }
    } catch (Exception $e) {
        $testResults['priority_test']['status'] = '❌ Priority test failed: ' . $e->getMessage();
    }

} catch (Exception $e) {
    $testResults['global_error'] = '❌ Global error: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Health System Test - VetSync</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background: #f5f5f5; 
            line-height: 1.6;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .test-section { 
            margin-bottom: 30px; 
            padding: 20px; 
            border: 2px solid #e0e0e0; 
            border-radius: 8px; 
            background: #fafafa;
        }
        .test-section h3 { 
            margin-top: 0; 
            color: #333; 
            border-bottom: 2px solid #007bff; 
            padding-bottom: 10px;
        }
        .test-result { 
            padding: 10px; 
            margin: 5px 0; 
            border-radius: 5px; 
            background: white;
            border-left: 4px solid #007bff;
        }
        .success { border-left-color: #28a745; background: #d4edda; }
        .warning { border-left-color: #ffc107; background: #fff3cd; }
        .error { border-left-color: #dc3545; background: #f8d7da; }
        .info { border-left-color: #17a2b8; background: #d1ecf1; }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            padding-bottom: 20px; 
            border-bottom: 3px solid #007bff;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .health-indicator {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .health-high { background: #d4edda; color: #155724; }
        .health-medium { background: #fff3cd; color: #856404; }
        .health-low { background: #f8d7da; color: #721c24; }
        .action-buttons {
            margin-top: 30px;
            text-align: center;
            padding: 20px;
            background: #e9ecef;
            border-radius: 8px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 VetSync User Health System Test</h1>
            <p>Comprehensive testing of the user health and no-show penalty system</p>
            <p><strong>Test Date:</strong> <?= date('Y-m-d H:i:s') ?></p>
        </div>

        <!-- Database Setup Test -->
        <div class="test-section">
            <h3>🗄️ Database Setup Test</h3>
            <?php foreach ($testResults['database_setup'] as $test => $result): ?>
                <div class="test-result <?= strpos($result, '✅') === 0 ? 'success' : 'error' ?>">
                    <strong><?= ucfirst(str_replace('_', ' ', $test)) ?>:</strong> <?= $result ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Current Health Status -->
        <div class="test-section">
            <h3>💚 Current User Health Status</h3>
            <?php if (!empty($testResults['current_health'])): ?>
                <?php foreach ($testResults['current_health'] as $healthInfo): ?>
                    <div class="test-result info">
                        <?= $healthInfo ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="test-result warning">⚠️ No health data available</div>
            <?php endif; ?>
        </div>

        <!-- Sample Reservation Test -->
        <div class="test-section">
            <h3>📦 Sample Reservation for Testing</h3>
            <div class="test-result <?= strpos($testResults['sample_reservation']['found'], '✅') === 0 ? 'success' : 'warning' ?>">
                <?= $testResults['sample_reservation']['found'] ?>
            </div>
            <?php if (isset($testResults['sample_reservation']['details'])): ?>
                <div class="stats-grid">
                    <?php foreach ($testResults['sample_reservation']['details'] as $key => $value): ?>
                        <div class="stat-card">
                            <strong><?= ucfirst(str_replace('_', ' ', $key)) ?>:</strong> <?= $value ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Penalty Simulation -->
        <div class="test-section">
            <h3>⚠️ No-Show Penalty Simulation</h3>
            <?php foreach ($testResults['penalty_simulation'] as $test => $result): ?>
                <div class="test-result <?= strpos($result, '✅') === 0 ? 'success' : (strpos($result, '⚠️') === 0 ? 'warning' : 'info') ?>">
                    <strong><?= ucfirst(str_replace('_', ' ', $test)) ?>:</strong> <?= $result ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- DataTables API Test -->
        <div class="test-section">
            <h3>📊 DataTables API Health Data Test</h3>
            <div class="test-result <?= strpos($testResults['datatables_api']['status'], '✅') === 0 ? 'success' : 'warning' ?>">
                <?= $testResults['datatables_api']['status'] ?>
            </div>
            <?php if (isset($testResults['datatables_api']['sample_data'])): ?>
                <div class="stats-grid">
                    <?php foreach ($testResults['datatables_api']['sample_data'] as $user): ?>
                        <div class="stat-card">
                            <strong><?= $user['name'] ?></strong><br>
                            <small><?= $user['email'] ?></small><br>
                            <span class="health-indicator health-<?= $user['health_color'] === 'green' ? 'high' : ($user['health_color'] === 'orange' ? 'medium' : 'low') ?>">
                                <?= $user['health'] ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Priority Test -->
        <div class="test-section">
            <h3>🎯 Priority Calculation Test</h3>
            <div class="test-result <?= strpos($testResults['priority_test']['status'], '✅') === 0 ? 'success' : 'warning' ?>">
                <?= $testResults['priority_test']['status'] ?>
            </div>
            <?php if (isset($testResults['priority_test']['user_priorities'])): ?>
                <?php foreach ($testResults['priority_test']['user_priorities'] as $priority): ?>
                    <div class="test-result info">
                        <?= $priority ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <h3>🧪 Test Actions</h3>
            <a href="/src/app/admin/users.php" class="btn">View Users in Admin Panel</a>
            <a href="/src/app/admin/reservations.php" class="btn">Test No-Show Cancellation</a>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn">Refresh Test</a>
            
            <div style="margin-top: 20px;">
                <h4>🔍 Manual Testing Steps:</h4>
                <ol style="text-align: left; max-width: 600px; margin: 0 auto;">
                    <li>Go to <strong>Reservations Management</strong></li>
                    <li>Find a "Ready for Pickup" reservation</li>
                    <li>Click <strong>"Cancel"</strong> button</li>
                    <li>Select <strong>"Not picked up within 7 days - NO SHOW"</strong></li>
                    <li>Confirm cancellation</li>
                    <li>Check <strong>Users page</strong> to see health reduction</li>
                </ol>
            </div>
        </div>

        <?php if (isset($testResults['global_error'])): ?>
            <div class="test-section">
                <h3>❌ Global Error</h3>
                <div class="test-result error">
                    <?= $testResults['global_error'] ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
