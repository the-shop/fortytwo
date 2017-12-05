<?php

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
        SlackService::class => [
            'apiClient' => SlackApiClient::class,
            'httpClient' => Client::class,
        ],
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
    'cronJobs' => [
        SlackSendMessage::class => [
            'timer' => 'everyMinute',
            'args' => [],
        ],
    ],
];
