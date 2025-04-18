<?php 

// Set timezone to Nairobi
date_default_timezone_set('Africa/Nairobi');

require "../app/core/config.php";
require "../app/core/database.php";
require "../app/core/functions.php";
require "../app/core/controller.php";
require "../app/core/model.php";

spl_autoload_register(function($class_name){
    require "../app/models/" . ucfirst($class_name) . ".php";
});