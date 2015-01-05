<?php

// Startup and configure Silex application
$app = new Idealist\Application($config, __DIR__);

// Mount the controllers
$app->mount('', new Idealist\Controller\MainController());
$app->mount('', new Idealist\Controller\BlobController());
$app->mount('', new Idealist\Controller\CommitController());
$app->mount('', new Idealist\Controller\TreeController());
$app->mount('', new Idealist\Controller\NetworkController());

return $app;
