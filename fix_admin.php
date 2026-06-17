<?php
require 'vendor/autoload.php';
$db = \Config\Database::connect();

$users = [1, 2];
foreach ($users as $userId) {
    // Check if already in admin group
    $exists = $db->table('auth_groups_users')
                ->where('user_id', $userId)
                ->where('group', 'admin')
                ->get()->getRow();
    
    if (!$exists) {
        echo "Adding admin group to user ID $userId...\n";
        $db->table('auth_groups_users')->insert([
            'user_id' => $userId,
            'group' => 'admin',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    } else {
        echo "User ID $userId is already an admin.\n";
    }
}
