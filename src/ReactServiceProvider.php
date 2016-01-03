<?php namespace KEIII\ReactSilex;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use React\EventLoop\Factory as ReactEventLoopFactory;
use React\Http\Server as ReactHttpServer;
use React\Socket\Server as ReactSocketServer;
use Symfony\Component\Console\Application as ConsoleApplication;
use Silex\Application as SilexApplication;

/**
 * React service.
 */
class ReactServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Container $container)
    {
        /** @var SilexApplication $app */
        $app = $container;

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
            return new ReactRequestBridge();
        };

        $app['react.response_bridge'] = function () {
            return new ReactResponseBridge();
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
