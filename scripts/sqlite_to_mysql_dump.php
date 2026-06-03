<?php

$root = dirname(__DIR__);
$sqlitePath = $argv[1] ?? "{$root}/database/database.sqlite";
$outputPath = $argv[2] ?? "{$root}/storage/app/mysql-data-dump.sql";

if (! file_exists($sqlitePath)) {
    fwrite(STDERR, "SQLite database not found: {$sqlitePath}\n");
    exit(1);
}

$outputDir = dirname($outputPath);

if (! is_dir($outputDir)) {
    mkdir($outputDir, 0775, true);
}

$pdo = new PDO('sqlite:' . $sqlitePath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$tables = [
    'users',
    'collections',
    'localities',
    'lakous',
    'donations',
    'donation_audit_logs',
];

$quote = function ($value): string {
    if ($value === null) {
        return 'NULL';
    }

    return "'" . str_replace(["\\", "'"], ["\\\\", "\\'"], (string) $value) . "'";
};

$sql = [];
$sql[] = '-- Kote Kob La SQLite data export for MariaDB/MySQL';
$sql[] = '-- Run Laravel migrations first, then import this file.';
$sql[] = 'SET FOREIGN_KEY_CHECKS=0;';

foreach (array_reverse($tables) as $table) {
    $sql[] = "TRUNCATE TABLE `{$table}`;";
}

foreach ($tables as $table) {
    $rows = $pdo->query("SELECT * FROM {$table}")->fetchAll(PDO::FETCH_ASSOC);

    if ($rows === []) {
        continue;
    }

    $columns = array_keys($rows[0]);
    $columnList = implode(', ', array_map(fn ($column) => "`{$column}`", $columns));

    foreach ($rows as $row) {
        $values = implode(', ', array_map(fn ($column) => $quote($row[$column]), $columns));
        $sql[] = "INSERT INTO `{$table}` ({$columnList}) VALUES ({$values});";
    }
}

$sql[] = 'SET FOREIGN_KEY_CHECKS=1;';
$sql[] = '';

file_put_contents($outputPath, implode(PHP_EOL, $sql));

echo "Wrote MySQL data dump to {$outputPath}\n";
