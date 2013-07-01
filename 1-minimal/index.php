<?php

/**
 * A simple, clean and secure PHP Login Script
 * 
 * MINIMAL VERSION
 * (check the website / github / facebook for other versions)
 * 
 * A simple PHP Login Script without all the nerd bullshit.
 * Uses PHP SESSIONS, modern password-hashing and salting
 * and gives the basic functions a proper login system needs.
 * 
 * Please remember: this is just the minimal version of the login script, so if you need a more
 * advanced version, have a look on the github repo. there are / will be better versions, including
 * more functions and/or much more complex code / file structure. buzzwords: MVC, dependency injected,
 * one shared database connection, PDO, prepared statements, PSR-0/1/2 and documented in phpDocumentor style
 * 
 * @package php-login
 * @author Panique <panique@web.de>
 * @link https://github.com/panique/php-login/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
// (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
require "libraries/password_compatibility_library.php";

// include the configs / constants for the database connection
require "config/db.php";

// load the Auth class
require "classes/Auth.php";

// load the login class
require "classes/Login.php";

// by default the user is not logged in. you can do whatever you want here.
// for demonstration purposes, we simply show the "you are not logged in" view.
$view = "not_logged_in";

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process. in consequence, you can simply ...
$login = new Login();
if ($login->isUserLoggedIn()) {    
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.
    $view = "logged_in";
}
include 'views/'.$view.'.php';
