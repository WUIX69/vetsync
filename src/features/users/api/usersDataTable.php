<?php

include '../../../core/app.php';
apiHeaders();

// use VetSync\Models\Users;
use VetSync\Utils\Php\Formatters;
use VetSync\Services\DataTables;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response['message'] = 'Invalid usersDataTable request method';
    echo json_encode($response);
    exit;
}

try {

    // DB table to use
    $table = 'users';
    // Table's primary key
    $primaryKey = 'uuid';

    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes
    $columns = array(
        ['db' => 'firstname', 'dt' => 0],
        ['db' => 'email', 'dt' => 1],
        ['db' => null, 'dt' => 2],
        ['db' => 'location', 'dt' => 3],
        ['db' => 'telephone', 'dt' => 4],
        [
            'db' => 'dob',
            'dt' => 5,
            'formatter' => function ($d, $row) {
                return Formatters::dateToMDY($d);
            }
        ],
        [
            'db' => 'created_at',
            'dt' => 6,
            'formatter' => function ($d, $row) {
                return Formatters::timeAgo($d);
            }
        ],
        ['db' => 'uuid', 'dt' => 7], // Additional: This is used to get the user's uuid
    );

    // SQL server connection information // Already defined in core/conn.php
    // $sql_details = array(
    //     'user' => $_ENV['DB_USERNAME'],
    //     'pass' => $_ENV['DB_PASSWORD'],
    //     'db' => $_ENV['DB_DATABASE'],
    //     'host' => $_ENV['DB_HOST']
    // );


    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * If you just want to use the basic configuration for DataTables with PHP
     * server-side, there is no need to edit below this line.
     */

    // require('../../../services/ssp.class.php'); // Already using DataTables class

    $response = DataTables::simple($_GET, $conn, $table, $primaryKey, $columns);
    $response['data'] = array_map(function ($user) {
        return [
            'user_uuid' => $user[7],
            'name' => $user[0] . ' ' . $user[1],
            'email' => $user[1],
            'role' => 'User',
            'telephone' => $user[4] ?? '...',
            'dob' => Formatters::dateToMDY($user[5]),
            'location' => $user[3] ?? '...',
            'profile' => media($user[7]),
            'created_at' => Formatters::timeAgo($user[6]),
        ];
    }, $response['data'] ?? []);

    $response['success'] = true;
    $response['message'] = 'Users fetched successfully';

} catch (Exception $e) {
    error_log($e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;