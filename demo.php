#!/php -q
<?php  
// Run from command prompt > php -q websocket.demo.php

// Basic WebSocket demo echoes msg back to client
include "server.php";
$master = new WebSocket("192.168.50.185",12345);

