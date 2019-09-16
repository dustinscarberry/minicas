<?php

//load config settings
require('config.php');

//load casinfo class
require('src/class/MUCASInfo.class.php');

//get cas attributes for logged in user
MUCASInfo::preloadCAS(CAS_HOST, CAS_PORT, CAS_CONTEXTPATH, CAS_CERTPATH);
$user = MUCASInfo::fetchUser();
$attributes = MUCASInfo::fetchAttributes();

echo '<pre>';
echo '<p>User Information</p>';
var_dump($user);
echo '<p>Attribute Information</p>';
var_dump($attributes);
echo '</pre>';
exit;
