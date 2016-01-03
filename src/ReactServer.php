<?php namespace KEIII\ReactSilex;

use React\EventLoop\LoopInterface as ReactLoopInterface;
use Silex\Application;
use React\Http\Request;
use React\Http\Response;
use React\Http\ServerInterface as ReactHttpServerInterface;
use React\Socket\ServerInterface as ReactSocketServerInterface;

/**
 * React server.
 */
class ReactServer
{
    /**
     * @var ReactHttpServerInterface $http
     */
    private $http;

    /**
     * @var ReactSocketServerInterface $socket
     */
    private $socket;

    /**
     * @var ReactLoopInterface $loop
     */
    private $loop;

    /**
     * @var ReactRequestBridge
     */
    private $request_bridge;

    /**
     * @var ReactResponseBridge
     */
    private $response_bridge;

    /**
     * @var Application
     */
    private $app;

    /**
     * Constructor.
     * @param Application $app
     */
    public function __construct(Application $app) {
        $this->http = $app['react.http'];
        $this->socket = $app['react.socket'];
        $this->loop = $app['react.loop'];
        $this->request_bridge = $app['react.request_bridge'];
        $this->response_bridge = $app['react.response_bridge'];
        $this->app = $app;
    }

    /**
     * Run react.
     * @param int $port
     * @param string $host
     */
    public function run($port, $host)
    {
        $request_handler = function (Request $request, Response $response) {
            echo $request->getMethod().' '.$request->getPath().PHP_EOL;

            $sf_request = $this->request_bridge->convertRequest($request);
            $sf_response = $this->app->handle($sf_request);
            $this->app->terminate($sf_request, $sf_response);
            $this->response_bridge->send($response, $sf_response);
        };

        $this->http->on('request', $request_handler);
        $this->socket->listen($port, $host);
        $this->loop->run();
    }
}
