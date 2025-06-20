<?php
date_default_timezone_set('Asia/Jakarta');
// Konfigurasi database
$host = 'localhost';
$dbname = 'database_gottawork';
$user = 'adm_backup';
$password = 'admin123';
$backupDir = __DIR__ . DIRECTORY_SEPARATOR . 'backup';
if (!is_dir($backupDir)) mkdir($backupDir, 0777, true);
$date = date('Y-m-d_H-i-s');
$backupFile = $backupDir . DIRECTORY_SEPARATOR . "gottawork_backup_$date.sql";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SET FOREIGN_KEY_CHECKS=0;\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        // Struktur tabel
        $create = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
        $sql .= "\n-- -----------------------------\n";
        $sql .= "-- Table structure for `$table`\n";
        $sql .= "-- -----------------------------\n";
        $sql .= "DROP TABLE IF EXISTS `$table`;\n";
        $sql .= $create['Create Table'] . ";\n\n";
        // Data tabel
        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        if ($rows) {
            $sql .= "-- -----------------------------\n";
            $sql .= "-- Data for table `$table`\n";
            $sql .= "-- -----------------------------\n";
            foreach ($rows as $row) {
                $vals = array_map(function($v) use ($pdo) {
                    if ($v === null) return 'NULL';
                    return $pdo->quote($v);
                }, array_values($row));
                $sql .= "INSERT INTO `$table` VALUES (" . implode(",", $vals) . ");\n";
            }
        }
        $sql .= "\n";
    }
    $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
    file_put_contents($backupFile, $sql);
    $size = number_format(filesize($backupFile)/1024, 2) . ' KB';
    echo "<b>Backup sukses!</b><br>File: <a href='backup/" . basename($backupFile) . "' target='_blank'>" . basename($backupFile) . "</a> ($size)";
} catch (Exception $e) {
    echo "<b>Backup gagal:</b> ", htmlspecialchars($e->getMessage());
}
?>