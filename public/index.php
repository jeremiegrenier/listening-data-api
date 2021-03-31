<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));

require_once '../vendor/autoload.php';

/**
 * @return \App\Model\FormattedResponse
 */
function main(): \App\Model\FormattedResponse
{
    $appEnv = getenv('APP_ENV');

    $builder = new DI\ContainerBuilder();
    $builder->addDefinitions('../config/config.php');
    if ('prod' === $appEnv) {
        $builder->enableCompilation(__DIR__ . '/../var/cache');
        $builder->enableDefinitionCache();
    }
    $container = $builder->build();

    /** @var \Psr\Log\LoggerInterface $logger */
    $logger = $container->get(\Psr\Log\LoggerInterface::class);

    $uri = $_SERVER['REQUEST_URI'];
    $httpVerb = $_SERVER['REQUEST_METHOD'];
    $queryString = $_SERVER['QUERY_STRING'];
    $body = json_decode(file_get_contents('php://input'), true);
    parse_str($queryString, $parsedQueryString);

    $logger->info(
        'Enter on ...',
        [
            'uri' => $uri,
            'httpVerb' => $httpVerb,
            'queryString' => $queryString,
            'body' => $body,
        ]
    );

    $queryParamsMatches = [];
    try {
        if (preg_match('#^/manager(/|\?.*)$#', $uri)) {
            if ('GET' !== $httpVerb) {
                throw new \App\Exception\MethodNotAllowedException($httpVerb);
            }
            $controller = $container->get(\App\Controller\ManagerController::class);

            $logger->info(
                'Match ManagerController::getCompiledData',
                [
                    'uri' => $uri,
                    'httpVerb' => $httpVerb,
                    'queryString' => $queryString,
                    'body' => $body,
                ]
            );

            return $controller->getCompiledData($parsedQueryString);
        }

        if (preg_match('#^/artists/(?<artistId>[^?/]*)(/|\?.*)?$#', $uri, $queryParamsMatches)) {
            $controller = $container->get(\App\Controller\ArtistController::class);

            switch ($httpVerb)
            {
                case 'GET':
                    $logger->info(
                        'Match ArtistController::getListeningStatistics',
                        [
                            'uri' => $uri,
                            'httpVerb' => $httpVerb,
                            'queryString' => $queryString,
                            'body' => $body,
                        ]
                    );

                    $response = $controller->getListeningStatistics($queryParamsMatches['artistId'], $parsedQueryString);
                    break;
                case 'PATCH':
                    $logger->info(
                        'Match ArtistController::patchArtist',
                        [
                            'uri' => $uri,
                            'httpVerb' => $httpVerb,
                            'queryString' => $queryString,
                            'body' => $body,
                        ]
                    );

                    $response = $controller->patchArtist($queryParamsMatches['artistId'], $body);
                    break;
                default:
                    $logger->warning(
                        'Match ArtistController with invalid $httpVerb',
                        [
                            'uri' => $uri,
                            'httpVerb' => $httpVerb,
                            'queryString' => $queryString,
                            'body' => $body,
                        ]
                    );
                    throw new \App\Exception\MethodNotAllowedException($httpVerb);
            }

            return $response;
        }

        $logger->warning(
            'No route match',
            [
                'uri' => $uri,
                'httpVerb' => $httpVerb,
                'queryString' => $queryString,
                'body' => $body,
            ]
        );

        throw new \App\Exception\RouteNotFoundException($uri);
    } catch (\App\Exception\AbstractException $e) {

        $logger->warning(
            'Exception found',
            [
                'uri' => $uri,
                'httpVerb' => $httpVerb,
                'queryString' => $queryString,
                'body' => $body,
                'exception' => $e->getMessage(),
            ]
        );


        return new \App\Model\FormattedResponse(
            false,
            $e->getMessage(),
            [],
            $e->getStatusCode(),
            [
                new \App\Model\Error(
                    $e->getMessage(),
                    $e->getMessage()
                )
            ]
        );
    }
}

$response = main();

header('Content-Type: application/json');
http_response_code($response->getStatusCode());
echo json_encode($response);

