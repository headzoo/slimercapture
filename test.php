<?php
require('vendor/autoload.php');

$c = new Headzoo\SlimerCapture\SlimerJS('/var/www/slimerjs-1.0.0/slimerjs', '/var/www/slimerjs-1.0.0/firefox/firefox');
$result = $c->capture('https://github.com', 'google.png', 375);
dump($c->getLastReturnCode());
