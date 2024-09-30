<?php 

class UserController
{
    public function showHomepage()
    {
        require 'views\user\homepage.php';
    }

    public function showProfilePage()
    {
        require 'views/user/profile-page.php';
    }
}