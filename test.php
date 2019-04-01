<?php
require('vendor/autoload.php');

$c = new Headzoo\SlimerCapture\SlimerJS('/var/www/slimerjs-1.0.0/slimerjs', '/var/www/slimerjs-1.0.0/firefox/firefox');
$c->capture('https://headzoo.io', 'headzoo.png', 375);
// dump($c->getLastCommand());
$json = json_decode($c->getLastOutput()[0]);
dump($json);
