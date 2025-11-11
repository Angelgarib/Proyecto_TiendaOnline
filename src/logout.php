<?php
require_once __DIR__ . '/services/SessionServices.php';

use services\SessionServices;

$sessionService = SessionServices::getInstance();
$sessionService->logout();

header('Location: login.php');
exit;
?>