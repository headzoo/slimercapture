<?php
require('vendor/autoload.php');

$c = new Headzoo\SlimerCapture\SlimerJS('/var/www/slimerjs-1.0.0/slimerjs');
$result = $c->capture('https://github.com', 'google.png', 375);

