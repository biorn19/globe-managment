<?php

class Role
{
    private $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->connection();
    }

    public function getUserRoleId()
    {
        $sql = "SELECT id FROM roles WHERE name = 'user'";
        return mysqli_fetch_assoc($this->connection->query($sql));
    }

    public function getAdminRoleId()
    {
        $sql = "SELECT id FROM roles WHERE name = 'admin'";
        return mysqli_fetch_assoc($this->connection->query($sql));
    }

    public function getRoleName($id)
    {
        $sql = "SELECT name FROM roles WHERE id = $id";
        return mysqli_fetch_assoc($this->connection->query($sql));
    }
}