<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));

require_once '../vendor/autoload.php';

/**
 * @return \App\Model\FormattedResponse
 */
function main(): \App\Model\FormattedResponse
{
    $container = new DI\Container();

    $uri = $_SERVER['REQUEST_URI'];
    $httpVerb = $_SERVER['REQUEST_METHOD'];
    $queryString = $_SERVER['QUERY_STRING'];
    $body = json_decode(file_get_contents('php://input'), true);
    parse_str($queryString, $parsedQueryString);

    $queryParamsMatches = [];
    try {
        if (preg_match('#^/manager$#', $uri)) {
            if ('GET' !== $httpVerb) {
                throw new \App\Exception\MethodNotAllowedException($httpVerb);
            }
            $controller = $container->get(\App\Controller\ManagerController::class);

            return $controller->getCompiledData();
        }

        if (preg_match('#^/artists/(?<artistId>[^?/]*)(/|\?.*)?$#', $uri, $queryParamsMatches)) {
            $controller = $container->get(\App\Controller\ArtistController::class);

            switch ($httpVerb)
            {
                case 'GET':
                    return $controller->getListeningStatistics($queryParamsMatches['artistId'], $parsedQueryString);
                case 'PATCH':
                    return $controller->patchArtist($queryParamsMatches['artistId'], $body);
                default:
                    throw new \App\Exception\MethodNotAllowedException($httpVerb);
            }
        }

        throw new \App\Exception\RouteNotFoundException();
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

