<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));

require_once '../vendor/autoload.php';

/**
 * @return \App\Model\FormattedResponse
 */
function main(): \App\Model\FormattedResponse
{
    $uri = $_SERVER['REQUEST_URI'];
    $httpVerb = $_SERVER['REQUEST_METHOD'];
    $queryString = $_SERVER['QUERY_STRING'];
    parse_str($queryString, $parsedQueryString);

    $queryParamsMatches = [];
    try {
        if (preg_match('#^/manager$#', $uri)) {
            if ('GET' !== $httpVerb) {
                throw new \App\Exception\MethodNotAllowedException();
            }
            $controller = new \App\Controller\ManagerController();

            return $controller->getCompiledData();
        }

        if (preg_match('#^/artists/(?<artistId>[^?/]*)(/|\?.*)?$#', $uri, $queryParamsMatches)) {
            if ('GET' !== $httpVerb) {
                throw new \App\Exception\MethodNotAllowedException();
            }
            $controller = new \App\Controller\ArtistController();

            return $controller->get($queryParamsMatches['artistId'], $parsedQueryString);
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

