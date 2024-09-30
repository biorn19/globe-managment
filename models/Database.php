<?php

class Database
{
    public function connection()
    {
        $servername = "localhost"; 
        $username = "root";
        $password = ""; 
        $dbname = "globe";


        $conn = new mysqli($servername, $username, $password, $dbname);
        return $conn;

    }

}