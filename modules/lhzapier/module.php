<?php

$Module = array( "name" => "Zapier",
    'variable_params' => true );

$ViewList = array();

$ViewList['settings'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$FunctionList['configure'] = array('explain' => 'Allow operator to configure Zapier');