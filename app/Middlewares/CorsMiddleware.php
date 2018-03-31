<?php

declare(strict_types = 1);

namespace HelloFresh\Middlewares;

use Petronetto\Config;
use Neomerx\Cors\Analyzer;
use Neomerx\Cors\Contracts\AnalysisResultInterface;
use Neomerx\Cors\Contracts\AnalyzerInterface;
use Neomerx\Cors\Strategies\Settings;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;

class CorsMiddleware implements MiddlewareInterface
{
    /** @var AnalyzerInterface */
    private $analyzer;

    /**
     * Process a request and return a response.
     *
     * @param  ServerRequestInterface  $request
     * @param  RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $cors = Analyzer::instance($this->getCorsSettings())->analyze($request);

        switch ($cors->getRequestType()) {
            case AnalysisResultInterface::ERR_NO_HOST_HEADER:
            case AnalysisResultInterface::ERR_ORIGIN_NOT_ALLOWED:
            case AnalysisResultInterface::ERR_METHOD_NOT_SUPPORTED:
            case AnalysisResultInterface::ERR_HEADERS_NOT_SUPPORTED:
                // TODO: Return 403
                die('403');
            case AnalysisResultInterface::TYPE_REQUEST_OUT_OF_CORS_SCOPE:
                return $handler->handle($request);
            case AnalysisResultInterface::TYPE_PRE_FLIGHT_REQUEST:
                // TODO: Check it...
                $response = new Response();

                return self::withCorsHeaders($response, $cors);
            default:
                die('default');
                $response = $handler->handle($request);

                return self::withCorsHeaders($response, $cors);
        }
    }

    /**
     * Adds cors headers to the response.
     */
    private static function withCorsHeaders(
        ResponseInterface $response,
        AnalysisResultInterface $cors
    ): ResponseInterface {
        foreach ($cors->getResponseHeaders() as $name => $value) {
            /* Diactoros errors on integer values. */
            if (false === is_array($value)) {
                $value = (string) $value;
            }
            $response = $response->withHeader($name, $value);
        }

        return $response;
    }

    /**
     * Get CORS Config.
     *
     * @return array
     */
    public function getCorsSettings(): Settings
    {
        $config   = Config::getInstance();
        $settings = new Settings();

        $settings->setServerOrigin(
            $config->get('cors')
        );

        return $settings;
    }
}
