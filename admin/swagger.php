<?php

require("../vendor/autoload.php");

$openapi = \OpenApi\scan(__DIR__.'/../public');

echo $openapi->toYaml();
