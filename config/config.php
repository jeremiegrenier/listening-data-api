<?php

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

return [
    Psr\Log\LoggerInterface::class => DI\factory(function () {
        $logger = new Logger('api');

        $fileHandler = new StreamHandler(__DIR__.'/../logs/'.getenv('APP_ENV').'.log', Logger::DEBUG);
        $fileHandler->setFormatter(new LineFormatter());
        $logger->pushHandler($fileHandler);

        return $logger;
    }),
];
