<?php
require_once __DIR__ . '/path.php';
require_once __DIR__ . '/../controllers.php';

$adminRoutes = [
    path_('', 'get', $profile, 'admin-get'),
    path_('/(albums|music|news|events|interviews|stream|orders|partners|books|services|settings)', 'get', $profile, 'profile-post'),
    path_('/(albums|music|news|events|interviews|stream|orders|partners|books|services|settings)/create', 'get', $profile, 'profile-post'),
    path_('/(albums|music|news|events|interviews|stream|orders|partners|books|services|settings)/:id/edit', 'get', $profile, 'profile-post'),
];
?>