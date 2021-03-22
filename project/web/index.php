<?php

require __DIR__ . '/../vendor/autoload.php';

$application = new \Project\Application();
$application->setEnv(\Perfumer\Framework\Application\Application::HTTP);
$application->run();
