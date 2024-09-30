<?php

class BreakTime
{
    private $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->connection();
    }
}