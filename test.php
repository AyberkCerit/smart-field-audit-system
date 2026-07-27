<?php
$s = microtime(true);
$pdo = new PDO('mysql:host=mysql;dbname=file_management_service', 'root', 'secret');
echo "MySQL: " . (microtime(true) - $s) . " seconds\n";

$s = microtime(true);
$redis = new Redis();
$redis->connect('redis', 6379);
echo "Redis: " . (microtime(true) - $s) . " seconds\n";
