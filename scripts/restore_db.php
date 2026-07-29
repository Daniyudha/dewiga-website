<?php
/**
 * Restore database from phpMyAdmin SQL dump via Laravel's DB connection
 * Run with: php scripts/restore_db.php
 * Safe for multi-line INSERTs with VALUES containing semicolons
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$sql = file_get_contents(__DIR__ . '/../dewiga_db.sql');
if (!$sql) {
    die("Cannot read dewiga_db.sql\n");
}

echo "File size: " . strlen($sql) . " bytes\n";

// Clean up the SQL - remove comments and phpMyAdmin headers
$lines = explode("\n", $sql);
$clean_sql = '';
$in_insert = false;

foreach ($lines as $line) {
    $trimmed = trim($line);
    
    // Skip comments and phpMyAdmin directives
    if (empty($trimmed) || str_starts_with($trimmed, '--') || str_starts_with($trimmed, '/*') || 
        str_starts_with($trimmed, 'SET ') || str_starts_with($trimmed, 'START ') || 
        str_starts_with($trimmed, '/*!') || $trimmed === ';') {
        if ($in_insert && str_ends_with($trimmed, ';')) {
            $clean_sql .= $line . "\n";
            $in_insert = false;
        }
        continue;
    }
    
    $clean_sql .= $line . "\n";
    
    if (str_starts_with($trimmed, 'INSERT') && !str_ends_with($trimmed, ';')) {
        $in_insert = true;
    }
}

// Split by statement-ending semicolons
$statements = explode(";\n", $clean_sql);

try {
    DB::statement("DROP DATABASE IF EXISTS dewiga_db");
    DB::statement("CREATE DATABASE dewiga_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Reconnect to the new database
    config(['database.connections.mysql.database' => 'dewiga_db']);
    DB::purge('mysql');
    DB::reconnect('mysql');
    
    $count = 0;
    foreach ($statements as $stmt) {
        $stmt = trim($stmt);
        if (!empty($stmt)) {
            try {
                DB::statement($stmt);
                $count++;
            } catch (\Exception $e) {
                // Some DROP/CREATE statements may fail - that's okay
                echo "W: " . substr($stmt, 0, 60) . "... " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\n=== RESTORE COMPLETE ===\n";
    echo "Statements executed: $count\n";
    
    // Verify
    $tables = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA='dewiga_db'");
    echo "Tables (" . count($tables) . "):\n";
    foreach ($tables as $t) {
        try {
            $cnt = DB::table($t->TABLE_NAME)->count();
            echo "  {$t->TABLE_NAME}: $cnt rows\n";
        } catch (\Exception $e) {
            echo "  {$t->TABLE_NAME}: ?\n";
        }
    }
    
} catch (\Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}