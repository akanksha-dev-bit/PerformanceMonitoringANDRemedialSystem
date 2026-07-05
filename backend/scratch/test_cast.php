<?php
$host = 'ep-odd-sunset-adjqvh29-pooler.c-2.us-east-1.aws.neon.tech';
$port = '5432';
$database = 'neondb';
$user = 'neondb_owner';
$password = 'npg_f2oEKZR1MJGA';

// Extract first part of host name
$parts = explode('.', $host);
$endpoint = $parts[0];

try {
    $dsn = "pgsql:host=$host;port=$port;dbname='$database';sslmode=require;options='endpoint=$endpoint'";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "Attempting to create function and implicit cast...\n";
    
    $sql = "
    CREATE OR REPLACE FUNCTION public.cast_int_to_bool(integer) RETURNS boolean AS $$
        SELECT $1 != 0;
    $$ LANGUAGE SQL IMMUTABLE;
    ";
    $pdo->exec($sql);
    echo "1. Function created successfully.\n";
    
    // We try to create the cast. If it already exists, we handle the exception.
    try {
        $pdo->exec("CREATE CAST (integer AS boolean) WITH FUNCTION public.cast_int_to_bool(integer) AS IMPLICIT;");
        echo "2. Implicit cast created successfully!\n";
    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), 'already exists')) {
            echo "2. Implicit cast already exists.\n";
        } else {
            throw $e;
        }
    }
    
    // Test the cast with a simple query
    $stmt = $pdo->query("SELECT 1::integer::boolean AS val_true, 0::integer::boolean AS val_false");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "3. Query test: " . json_encode($result) . "\n";
    
} catch (PDOException $e) {
    echo "PDO EXCEPTION: " . $e->getMessage() . "\n";
}
