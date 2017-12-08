<?php

namespace Application;

use Framework\Base\Module\BaseModule;
use Framework\CrudApi\Repository\GenericRepository;

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

        // Format models configuration
        $modelsConfiguration = $this->generateModelsConfiguration(
            $appConfig->getPathValue('models')
        );

        // Register resources, repositories and model fields
        $repositoryManager->registerResources($modelsConfiguration['resources'])
                          ->registerModelFields($modelsConfiguration['modelFields']);
    }

    /**
     * @param $modelsConfig
     *
     * @return array
     */
    private function generateModelsConfiguration(array $modelsConfig)
    {
        $generatedConfiguration = [
            'resources' => [],
            'modelFields' => [],
        ];
        foreach ($modelsConfig as $modelName => $options) {
            $generatedConfiguration['resources'][$options['collection']] = GenericRepository::class;
            $generatedConfiguration['modelFields'][$options['collection']] = $options['fields'];
        }

        return $generatedConfiguration;
    }
}
