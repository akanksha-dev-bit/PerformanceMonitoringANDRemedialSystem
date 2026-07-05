<?php
$host = 'ep-odd-sunset-adjqvh29.c-2.us-east-1.aws.neon.tech';
$port = '5432';
$database = 'neondb';
$user = 'neondb_owner';
$password = 'npg_f2oEKZR1MJGA';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname='$database';sslmode=require;options='endpoint=ep-odd-sunset-adjqvh29'";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Get search path
    $stmt = $pdo->query("SHOW search_path");
    echo "search_path: " . $stmt->fetchColumn() . "\n";
    
    // List tables in public schema
    $stmt = $pdo->query("SELECT table_name, table_schema FROM information_schema.tables WHERE table_schema NOT IN ('pg_catalog', 'information_schema')");
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "TABLES FOUND:\n";
    foreach ($tables as $t) {
        echo "- " . $t['table_schema'] . "." . $t['table_name'] . "\n";
    }
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
