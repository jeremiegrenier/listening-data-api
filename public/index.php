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

    $uri = $_SERVER['REQUEST_URI'];
    $httpVerb = $_SERVER['REQUEST_METHOD'];
    $queryString = $_SERVER['QUERY_STRING'];
    $body = json_decode(file_get_contents('php://input'), true);
    parse_str($queryString, $parsedQueryString);

    $queryParamsMatches = [];
    try {
        if (preg_match('#^/manager(/|\?.*)$#', $uri)) {
            if ('GET' !== $httpVerb) {
                throw new \App\Exception\MethodNotAllowedException($httpVerb);
            }
            $controller = $container->get(\App\Controller\ManagerController::class);

            return $controller->getCompiledData($parsedQueryString);
        }

        if (preg_match('#^/artists/(?<artistId>[^?/]*)(/|\?.*)?$#', $uri, $queryParamsMatches)) {
            $controller = $container->get(\App\Controller\ArtistController::class);

            switch ($httpVerb)
            {
                case 'GET':
                    $response = $controller->getListeningStatistics($queryParamsMatches['artistId'], $parsedQueryString);
                    break;
                case 'PATCH':
                    $response = $controller->patchArtist($queryParamsMatches['artistId'], $body);
                    break;
                default:
                    throw new \App\Exception\MethodNotAllowedException($httpVerb);
            }

            return $response;
        }

        throw new \App\Exception\RouteNotFoundException($uri);
    } catch (\App\Exception\AbstractException $e) {
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

