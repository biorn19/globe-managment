<?php

class Middleware
{
    public function handle($url)
    {
        $guest = [
            "/",
            "/login",
            "/register",
            "/login/submit",
            "/register/submit" 
        ];

        $user = [
            "/homepage",
            "/profile-page",
            "/logout"
        ];

        $admin = [
            "/admin/profile-page",
            "/admin/users-page",
            "/admin/homepage",
            "/admin/schedule-page",
            "/admin/calculate/user/payment",
            "/logout"
        ];

        if(isset($_SESSION['id'])){
            $role = $_SESSION['role'];
            if($role === 'admin' && !in_array($url, $admin)){
                $this->redirect('/globe-managment/admin/homepage');
            }else if($role === 'user' && !in_array($url, $user)){
                $this->redirect('/globe-managment/homepage');
            }
        }else{
            if(!in_array($url, $guest)){
                $this->redirect('/globe-managment');
            }
        }
    }

    private function redirect($url)
    {
        header("Location: " . $url);
        exit();
    }
}