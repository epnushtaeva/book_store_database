<?php
require_once 'vendor/autoload.php';
require_once 'error_handler.php';

use App\Fabrics\ServiceFabric;
$mainService = ServiceFabric::getMainService();
$mainService->start();
