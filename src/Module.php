<?php

namespace Application;

use Framework\Base\Module\BaseModule;

class Module extends BaseModule
{
    /**
     * @inheritdoc
     */
    public function bootstrap()
    {
        // Let's read all files from module config folder and set to Configuration
        $configDirPath = realpath(dirname(__DIR__)) . '/config/';
        $this->setModuleConfiguration($configDirPath);

        $application = $this->getApplication();
        $appConfig = $application->getConfiguration();
        $repositoryManager = $application->getRepositoryManager();

        // Register model adapters
        $modelAdapters = $appConfig->getPathValue('modelAdapters');
        foreach ($modelAdapters as $model => $adapters) {
            foreach ($adapters as $adapter) {
                $repositoryManager->addModelAdapter($model, new $adapter());
            }
        }

        // Register model primary adapters
        $primaryModelAdapter = $appConfig->getPathValue('primaryModelAdapter');
        foreach ($primaryModelAdapter as $model => $primaryAdapter) {
            $repositoryManager->setPrimaryAdapter($model, new $primaryAdapter());
        }

        //Register Services
        $services = $appConfig->getPathValue('services');
        foreach ($services as $serviceName => $config) {
            $application->registerService(new $serviceName($config));
        }

        // Add commands to dispatcher
        $application->getDispatcher()
                    ->addRoutes($appConfig->getPathValue('commands'));
    }
}
