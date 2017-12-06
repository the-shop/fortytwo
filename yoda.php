<?php

require_once realpath('./vendor/autoload.php');

$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(realpath('./.env'));

$appConfig = new \Framework\Base\Application\ApplicationConfiguration();
$appConfig->setRegisteredModules(
    [
        \Framework\Base\Module::class,
        \Framework\Terminal\Module::class,
        \Application\Module::class
    ]
);

$app = new \Framework\Terminal\TerminalApplication($appConfig);

//Add cron jobs
$cronJobs = $appConfig->getPathValue('cronJobs');
foreach ($cronJobs as $job => $params) {
    $cronJob = new $job($params);
    $app->registerCronJob($cronJob);
}

try {
    $app->run();
} catch (\Exception $e) {
    $app->getExceptionHandler()
        ->handle($e);

    $app->getRenderer()
        ->render($app->getResponse());
}
