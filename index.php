<?php

$data = file_get_contents('php://input');
file_put_contents('log.txt',print_r(json_decode($data,true),true));