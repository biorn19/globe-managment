<?php

include 'models\Database.php';
include "controllers\AdminController.php";
include "controllers\AuthenticationController.php";
include "controllers\UserController.php";
include "middlewares\Middleware.php";

session_start();

class Router
{
    private $middleware;

    public function __construct()
    {
        $this->middleware = new Middleware();
    }

    public function route($urlpath)
    {
        $this->middleware->handle($urlpath);
        $uri = $urlpath;
        $method = $_SERVER['REQUEST_METHOD'];

        $routes = [
            //guest routes
            ['uri' => '/', 'method' => 'GET', 'controller' => AuthenticationController::class, 'controllerMethod' => 'landingPage'],
            ['uri' => '/login', 'method' => 'GET', 'controller' => AuthenticationController::class, 'controllerMethod' => 'login'],
            ['uri' => '/login/submit', 'method' => 'POST', 'controller' => AuthenticationController::class, 'controllerMethod' => 'handleLogin'],
            ['uri' => '/register', 'method' => 'GET', 'controller' => AuthenticationController::class, 'controllerMethod' => 'register'],
            ['uri' => '/register/submit', 'method' => 'POST', 'controller' => AuthenticationController::class, 'controllerMethod' => 'handleRegister'],

            //uers routes
            ['uri' => '/homepage', 'method' => 'GET', 'controller' => UserController::class, 'controllerMethod' => 'showHomepage'],
            ['uri' => '/profile-page', 'method' => 'GET', 'controller' => UserController::class, 'controllerMethod' => 'showProfilePage'],

            //admin routes
            ['uri' => '/admin/homepage', 'method' => 'GET', 'controller' => AdminController::class, 'controllerMethod' => 'showHomepage'],
            ['uri' => '/admin/schedule-page', 'method' => 'GET', 'controller' => AdminController::class, 'controllerMethod' => 'showSchedulePage'],
            ['uri' => '/admin/profile-page', 'method' => 'GET', 'controller' => AdminController::class, 'controllerMethod' => 'showProfilePage'],
            ['uri' => '/admin/users-page', 'method' => 'GET', 'controller' => AdminController::class, 'controllerMethod' => 'showUsersPage'],
            ['uri' => '/admin/calculate/user/payment', 'method' => 'POST', 'controller' => AdminController::class, 'controllerMethod' => 'createWage'],


            // logout route
            ['uri' => '/logout', 'method' => 'POST', 'controller' => AuthenticationController::class, 'controllerMethod' => 'logout'],

        ];

        $matchedRoute = null;

        foreach($routes as $route){
            if($route['uri'] === $uri && $route['method'] === $method){
                $matchedRoute = $route;
                break;
            }
        }

        if($matchedRoute){
            $controllerName = $matchedRoute['controller'];
            $controllerMethod = $matchedRoute['controllerMethod'];
            $instance = new $controllerName();
            $instance->$controllerMethod();
        }
    }
}