<?php
require 'bootstrap.php';

$statement = <<<EOS
    CREATE TABLE IF NOT EXISTS advertisement (
        id INT NOT NULL AUTO_INCREMENT,
        text VARCHAR(255) NOT NULL,
        price INT DEFAULT NULL,
        limit_of_impressions INT DEFAULT NULL,
        number_of_impressions INT DEFAULT 0,
        banner VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=INNODB;
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "Success!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}