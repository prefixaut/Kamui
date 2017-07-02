<?php

require __DIR__ . '/vendor/autoload.php';

$api = new Kamui\API(trim(@file_get_contents('./client_id.txt')));

var_dump($api->channels->followers('439876893475689346789347689347689346gg'));
