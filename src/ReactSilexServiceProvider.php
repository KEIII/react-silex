<?php

namespace KEIII\ReactSilex;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use React\EventLoop\Factory as ReactEventLoopFactory;
use React\Http\Server as ReactHttpServer;
use React\Socket\Server as ReactSocketServer;
use Silex\Application as SilexApplication;
use Symfony\Component\Console\Application as ConsoleApplication;

/**
 * Integrates Silex with React.
 */
class ReactSilexServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['react.loop'] = function () {
            return ReactEventLoopFactory::create();
        };

        $app['react.socket'] = function (SilexApplication $app) {
            return new ReactSocketServer($app['react.loop']);
        };

        $app['react.http'] = function (SilexApplication $app) {
            return new ReactHttpServer($app['react.socket']);
        };

        $app['react.request_bridge'] = function () {
            return new RequestBridge();
        };

        $app['react.response_bridge'] = function () {
            return new ResponseBridge();
        };

        $app['react.server'] = function (SilexApplication $app) {
            return new ReactServer($app);
        };

        $app['react.console'] = function (SilexApplication $app) {
            $console = new ConsoleApplication();
            $console->add(new ReactCommand($app['react.server']));

            return $console;
        };
    }
}
