<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('X-Powered-By: devsoft.com');
header('X-XSS-Protection: 1');
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Vary: Accept-Encoding');


if (isset($content))
{
    echo $content;
}
