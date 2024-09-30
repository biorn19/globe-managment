<?php

class Wage
{
    private $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->connection();
    }

    public function create($wage, $startDate, $endDate, $sundays, $festiveDays, $userId)
    {
        $startDate = $this->connection->real_escape_string($startDate);
        $endDate = $this->connection->real_escape_string($endDate);
        $wage = (float)$wage; 
        $sundays = (int)$sundays; 
        $festiveDays = (int)$festiveDays; 
        $userId = (int)$userId; 

        $sql = "INSERT INTO wages (wage, start_date, end_date, festive_days, sundays, user_id) 
        VALUES ($wage, '$startDate', '$endDate', $festiveDays, $sundays, $userId)";
        
        if ($this->connection->query($sql) === TRUE) {
            return $this->connection->insert_id; 
        } else {

            throw new Exception("Error: " . $this->connection->error);
        }
    }

    public function getLastWageDate($id)
    {
        $sql = "SELECT * FROM wages WHERE user_id = $id ORDER BY end_date DESC LIMIT 1";
        return mysqli_fetch_assoc($this->connection->query($sql));
    }
}