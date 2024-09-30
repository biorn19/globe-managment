<?php

class Activity
{
    private $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->connection();
    }

    public function create($date, $turn, $startTime, $isSunday, $isFestive, $userId)
    {
        $date = $this->connection->real_escape_string($date);
        $turn = $turn ? 1 : 2;
        $startTime = $this->connection->real_escape_string($startTime);
        $isSunday = $isSunday ? 1 : 0; 
        $isFestive = $isFestive ? 1 : 0; 
        $userId = (int)$userId; 

        $sql = "INSERT INTO activities (date, turn, start_time, is_sunday, is_festive, user_id) 
        VALUES ('$date', '$turn', $startTime', $isSunday, $isFestive, $userId)";
        
        if ($this->connection->query($sql) === TRUE) {
            return $this->connection->insert_id; 
        } else {

            throw new Exception("Error: " . $this->connection->error);
        }
    }

    public function checkActivity($date, $userId)
    {
        $date = $this->connection->real_escape_string($date);
        $userId = (int)$userId; 
        $sql = "SELECT * FROM activities WHERE date = '$date' AND user_id = $userId";

        return mysqli_fetch_assoc($this->connection->query($sql));
    }


    public function addEndTime($date, $userId)
    {
        $endTime = $this->connection->real_escape_string(date('Y-m-d H:i:s'));
        $date = $this->connection->real_escape_string($date);
        $userId = (int)$userId; 
        $sql = "SELECT * FROM activities WHERE date = '$date' AND user_id = $userId";
        $response = mysqli_fetch_assoc($this->connection->query($sql));
        if(!empty($response)){
            $sqlUpdate = "UPDATE activities SET end_time = '$endTime' WHERE date = '$date' AND user_id = $userId";

            if ($this->connection->query($sqlUpdate) === TRUE) {
                return true;
            } else {
                throw new Exception("Error updating record: " . $this->connection->error);
            }
        }
    }

    public function getActivitiesById($id, $date)
    {
        $sql = "SELECT * FROM activities WHERE date >= '$date' AND user_id = $id ORDER BY date ASC";
    
        return mysqli_fetch_assoc($this->connection->query($sql));
    }

    public function getAllActivitiesById($id)
    {
        $sql = "SELECT * FROM activities WHERE user_id = $id ORDER BY date ASC";
    
        $result = $this->connection->query($sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}