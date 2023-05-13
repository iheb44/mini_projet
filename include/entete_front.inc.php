<?php
ini_set('display_errors', '1');

error_reporting(E_ALL & ~(E_NOTICE | E_STRICT | E_DEPRECATED));

function nsAutoLoad($classe)
{
if (file_exists('./class/'.$classe.'.class.php')) {
require './class/'.$classe.'.class.php';
} elseif (file_exists('../class/'.$classe.'.class.php')) {
require '../class/'.$classe.'.class.php';
} elseif (file_exists('../class/'.$classe.'.php')) {
require '../class/'.$classe.'.php';
}elseif (file_exists('class/'.$classe.'.php')) {
require 'class/'.$classe.'.php';
}
}
spl_autoload_register('nsAutoLoad');

session_start();