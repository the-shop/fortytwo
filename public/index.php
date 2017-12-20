<?php

/**
 * Require composer dependencies
 */
require_once realpath(dirname(__DIR__) . '/vendor/autoload.php');

$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(realpath(dirname(__DIR__) . '/.env'));

$appConfig = new \Framework\RestApi\RestApiConfiguration();
$appConfig->setRegisteredModules([
    \Framework\Base\Module::class,
    \Framework\S3FU\Module::class,
    \Framework\SendGrid\Module::class,
    \Framework\Http\Module::class,
    \Framework\RestApi\Module::class,
    \Application\Module::class,
    \Framework\CrudApi\Module::class
]);

$app = new \Framework\CrudApi\CrudApiApplication($appConfig);

try {
    $app->bootstrap()
        ->run();
} catch (\Exception $e) {
    $app->getExceptionHandler()
        ->handle($e);

    $app->getRenderer()
        ->render($app->getResponse());
}
