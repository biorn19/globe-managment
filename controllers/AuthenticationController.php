<?php

include "models\User.php";
include "models\Activity.php";

class AuthenticationController
{
    public function landingPage()
    {
        require "views\guest\landing-page.php";
    }

    public function register()
    {
        require 'views\guest\register.php';
    }

    public function login()
    {
        require "views\guest\login.php";
    }

    public function handleRegister()
    {
        $errors = array();
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $date_of_birth = $_POST['date_of_birth'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confim_password = $_POST['confirm_password'];

        if(empty($name)) {
            $errors["Name"] = "Name is required";
            header("Location: /globe-managment/register");
        }elseif (!preg_match("/^[a-zA-Z]+$/", $name)) {
            $errors["Name"] = "Symbols not allowed in Name";
            header("Location: /globe-managment/register");
        }
        if(empty($surname)) {
            $errors["Surame"] = "Surname is required";
            header("Location: /globe-managment/register");
        }elseif (!preg_match("/^[a-zA-Z]+$/", $surname)) {
            $errors["Surname"] = "Symbols not allowed in Surname";
            header("Location: /globe-managment/register");
        }
        if(empty($date_of_birth)) {
            $errors["Date_of_birth"] = "Date of birth is required";
            header("Location: /globe-managment/register");
        }elseif (strtotime($date_of_birth) > time()) {
            $errors["Date_of_birth"] = "Date of birth can not be in the future";
            header("Location: /globe-managment/register");
        }
        if (empty($email)) {
            $errors["Email"] = "Email is required";
            header("Location: /globe-managment/register");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["Email"] = "Invalid email format";
            header("Location: /globe-managment/register");
        } elseif (!preg_match('/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,}$/', $email)) {
            $errors["Email"] = "Email must contain a valid domain";
            header("Location: /globe-managment/register");
        }
        if(empty($password)) {
            $errors["Password"] = "Password is required";
            header("Location: /globe-managment/register");
        }
        if(empty($confim_password)) {
            $errors["Confirm_password"] = "Please confirm your password";
            header("Location: /globe-managment/register");
        }
        if (!empty($password) && !empty($confim_password) && $password !== $confim_password) {
            $errors["Password_does_not_match"] = "Passwords do not match";
            header("Location: /globe-managment/register");
        }
        if(count($errors) > 0){
            $_SESSION["errors"] = $errors;
        } else {
            $userModel = new User();
            $userModel->create($name, $surname, $date_of_birth, $email, $password);
            $success['Account'] = "Account created successfully";
            $_SESSION['success'] = $success;

            $this->handleLogin();
        }
    }

    public function handleLogin()
{
    $email = $_POST['email'];
    $password = $_POST['password'];
    $errors = array();
    $userModel = new User();
    $roleModel = new Role();
    $activityModel = new Activity();

    $user = $userModel->getUserByEmail($email);
    if (!empty($user) && password_verify($password, $user['password'])) {
        $_SESSION['name'] = $user['name'];
        $_SESSION['surname'] = $user['surname'];
        $_SESSION['date_of_birth'] = $user['date_of_birth'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        
        $role = $roleModel->getRoleName($user['role_id']);
        $_SESSION['role'] = $role['name'];

        
        if ($role['name'] === 'admin') {
            $redirectUrl = '/globe-managment/admin/homepage';
        } else if ($role['name'] === 'user') {
            $currentDate = date('Y-m-d'); 
            $existingActivity = $activityModel->checkActivity($currentDate, $_SESSION['id']);
            
            if (empty($existingActivity)) {
                $vacationDates = [
                    '01-01', // New Year's Day
                    '01-02', // New Year's Day
                    '03-14', // Summer Day
                    '04-07', // Health Day
                    '05-01', // Labor Day
                    '05-24', // Eid al-Fitr (example date)
                    '06-28', // Eid al-Adha (example date)
                    '11-28', // Independence Day
                    '11-29', // Liberation Day
                    '12-25'  // Christmas Day
                ];

                $startTime = date('Y-m-d H:i:s');
                $isSunday = (date('N') == 7);
                $currentMonthDay = date('m-d'); 
                $isFestive = in_array($currentMonthDay, $vacationDates);

                $activityModel->create($currentDate, $turn, $startTime, $isSunday, $isFestive, $_SESSION['id']);
            }

            $redirectUrl = '/globe-managment/homepage';
        } else {
            $errors['Log'] = 'Invalid user!';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $redirectUrl = '/globe-managment/login';
        }
        if (isset($redirectUrl)) {
            header("Location: " . $redirectUrl);
            exit();
        }
    } else {
        $_SESSION['login_error'] = true;
        header("Location: /globe-managment/login");
        exit();
    }
}


    public function logout()
    {
        if($_SESSION['role'] === 'user'){
            $activityModel = new Activity();
            $activityModel->addEndTime(date('Y-m-d'), $_SESSION['id']);
        }
        session_unset();
        session_destroy();
        header("Location: /globe-managment/login");
        exit();
    }
}