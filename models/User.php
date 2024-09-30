<?php

include 'models\Role.php';

class User
{
    private $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->connection();
    }

    public function create($name, $surname, $date, $email, $password)
    {
    $roleModel = new Role();
    $userRole = $roleModel->getUserRoleId();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $this->connection->prepare("INSERT INTO users (name, surname, date_of_birth, email, password, role_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $name, $surname, $date, $email, $hashedPassword, $userRole);

    if ($stmt->execute()) {
        return $stmt->insert_id;
    } else {
        throw new Exception("Error inserting user: " . $stmt->error);
    }
    }


    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        return mysqli_fetch_assoc($this->connection->query($sql));
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = $id";
        return mysqli_fetch_assoc($this->connection->query($sql));
    }

    public function delete($id)
    {
        $sql = "";
    }

    public function update($id, $params)
    {
        $sql = "";
    }

    public function getUsers()
    {
        $roleModel = new Role();
        $userRole = $roleModel->getUserRoleId()['id'];
        $sql = "SELECT * FROM users WHERE role_id=$userRole";
        $result = $this->connection->query($sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}