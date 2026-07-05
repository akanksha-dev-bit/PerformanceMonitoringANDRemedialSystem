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
    
    // List all schemas
    $stmt = $pdo->query("SELECT schema_name FROM information_schema.schemata");
    echo "SCHEMAS:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - {$row['schema_name']}\n";
    }
    
    // List all tables and their schema
    $stmt = $pdo->query("SELECT table_schema, table_name FROM information_schema.tables WHERE table_name = 'marks'");
    echo "SCHEMAS FOR TABLE 'marks':\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - Schema: {$row['table_schema']} | Table: {$row['table_name']}\n";
    }
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
