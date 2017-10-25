<?php

require_once '../vendor/autoload.php';

\Config\Config::init();

\OLOG\Auth\RegisterRoutes::registerRoutes();
\OLOG\Storage\StorageRouting::register();

\OLOG\Router::action(\StorageDemo\DemoAction::class, 0);