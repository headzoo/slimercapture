<?php
require('vendor/autoload.php');

$c = new Headzoo\SlimerCapture\SlimerJS('/var/www/slimerjs-1.0.0/slimerjs', '/var/www/slimerjs-1.0.0/firefox/firefox');
$c->capture('http://dev.blocksedit.com/screenshot-take-frame?file#@#screenshotmGQcyk.html', 'headzoo.png', 375);
// dump($c->getLastOutput());die();
$json = json_decode($c->getLastOutput()[0]);
dump($json);
