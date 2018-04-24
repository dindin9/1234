<?php
// require configuration to connect to db.
require_once 'constants.php';

// Try to connect to db. If can not - show error.
// If Can - try to check the db. If it not exist - create one. If yes - that's ok - retugn db object to work with it.
try {
    $db = new PDO('mysql:host=' . DB_SERVER . '; charset=utf8mb4', DB_USERNAME, DB_PASSWORD);
    $stmt = $db->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".DB_DATABASE."'");
    if(!(bool) $stmt->fetchColumn()) {
        $sql = file_get_contents('ISNetworkDB.sql');
        $db->exec($sql);
    } else {
        $db->exec('use '.DB_DATABASE);
    }
} catch (PDOException $e) {
    echo 'Gor an error: `'.$e->getMessage().'`. Please check your configuration for the project.';
    exit();
}
