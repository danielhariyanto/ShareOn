<?php
$t=new triagens\ArangoDb\users();
$t=$t->usernameCheck($username);
echo $t;
