<?php

namespace App\Models;

use PDO;

class Db
{
    private $host = 'localhost';
    private $user = 'slim-test';
    private $password = 'Knedl!k133540P0bed1t';
    private $dbname = 'customers_api';

    public function connect()
    {
        $conn_str = "mysql:host=$this->host;dbname=$this->dbname";
        $conn = new PDO($conn_str, $this->user, $this->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}