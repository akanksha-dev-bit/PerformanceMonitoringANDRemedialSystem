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
    
    $stmt = $pdo->query("SELECT migration, batch FROM migrations ORDER BY id");
    $migrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "MIGRATIONS IN DB:\n";
    foreach ($migrations as $m) {
        echo "- " . $m['migration'] . " (Batch " . $m['batch'] . ")\n";
    }
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
