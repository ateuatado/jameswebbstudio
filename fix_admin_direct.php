<?php
$mysqli = new mysqli("localhost", "root", "", "hero");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$users = [1, 2];
foreach ($users as $userId) {
    $res = $mysqli->query("SELECT id FROM auth_groups_users WHERE user_id = $userId AND `group` = 'admin'");
    if ($res->num_rows == 0) {
        if ($mysqli->query("INSERT INTO auth_groups_users (user_id, `group`, created_at) VALUES ($userId, 'admin', NOW())")) {
            echo "Successfully added admin group to user $userId\n";
        } else {
            echo "Error adding admin group to user $userId: " . $mysqli->error . "\n";
        }
    } else {
        echo "User $userId already is admin\n";
    }
}

$mysqli->close();
