<?php

// require '../Core/Router.php';
// require '../App/Controllers/Posts.php';

// echo 'Requested URL ="'.$_SERVER['QUERY_STRING'].'"';

/**
 * Twig
 */

 require_once dirname(__DIR__) . '/vendor/autoload.php';
 


/**
 * Autoloader
 */
// spl_autoload_register(function($class){
//     $root = dirname(__DIR__); // get parent directory
//     $file = $root.'/'.str_replace('\\', '/', $class).'.php';
//     if (is_readable($file)){
//         require $file;
//     }
// });

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Sessions
 */
session_start();

/**
 * Routing
 */

$router = new Core\Router();

//echo get_class($router);

$router->add('',['controller'=>'Home', 'action' =>'index']);
$router->add('posts',['controller'=>'Posts', 'action' =>'index']);
$router->add('login',['controller'=>'Login', 'action' =>'new']);
$router->add('logout',['controller'=>'Login', 'action' =>'destroy']);
$router->add('menu',['controller'=>'Menu', 'action' =>'show']);
$router->add('incomeManager',['controller'=>'IncomeManager', 'action' =>'show']);
$router->add('expenseManager',['controller'=>'ExpenseManager', 'action' =>'show']);
$router->add('balanceManager',['controller'=>'BalanceManager', 'action' =>'show']);
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('signup/activate/{token:[\da-f]+}', ['controller' => 'Signup', 'action' => 'activate']);
// $router->add('posts/new',['controller'=>'Posts', 'action' =>'new']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}',['namespace' => 'Admin']);


//Display routing table
// echo '<pre>';
// var_dump($router->getRoutes());
// echo htmlspecialchars(print_r($router->getRoutes(), true));
// echo '</pre>';


// $url = $_SERVER['QUERY_STRING'];

$router->dispatch($_SERVER['QUERY_STRING']);

// if($router->dispatch($url)){
//     echo '<pre>';
//     var_dump($router->getParams());
//     echo '</pre>';
// } else{
//     echo "No route found for URL '$url'";
// }

//$router->dispatch($url);

?>