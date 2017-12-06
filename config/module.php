<?php

use Application\SampleEchoCommand;
use Aws\S3\S3Client;
use Framework\Base\Service\EmailService;
use Framework\Base\Service\FileUploadService;
use Framework\Mongo\MongoAdapter;
use Framework\S3FU\S3FileUpload;
use Framework\SendGrid\SendGrid;
use SendGrid as SendGridClient;

return [
    'modelAdapters' => [
        'users' => [
            MongoAdapter::class,
        ],
        'slackMessages' => [
            MongoAdapter::class,
        ],
        "logs" => [
            MongoAdapter::class,
        ],
        "uploads" => [
            MongoAdapter::class
        ]
    ],
    'primaryModelAdapter' => [
        'users' => MongoAdapter::class,
        'slackMessages' => MongoAdapter::class,
        'logs' => MongoAdapter::class,
        'uploads' => MongoAdapter::class,
    ],
    'services' => [
        EmailService::class => [
            'mailerInterface' => SendGrid::class,
            'mailerClient' => [
                'classPath' => SendGridClient::class,
                'constructorArguments' => [
                    getenv('SENDGRID_API_KEY'),
                ],
            ],
        ],
        FileUploadService::class => [
            'fileUploaderInterface' => S3FileUpload::class,
            'fileUploaderClient' => [
                'classPath' => S3Client::class,
                'constructorArguments' => [
                    'version' => 'latest',
                    'region' => getenv('S3_REGION'),
                    'credentials' => [
                        'key' => getenv('S3_KEY'),
                        'secret' => getenv('S3_SECRET'),
                    ],
                ],
            ],
        ],
    ],
    'commands' => [
        'sample:echo' => [
            'handler' => SampleEchoCommand::class,
            'requiredParams' => [],
            'optionalParams' => [],
        ],
    ],
];
