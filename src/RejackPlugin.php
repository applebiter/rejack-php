<?php 
namespace Rejack;

use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Console\CommandCollection;
use Cake\Core\Exception\CakeException;
use Cake\Core\Exception\MissingPluginException;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use InvalidArgumentException;

/**
 * class RejackPlugin
 * @package Rejack
 */
class RejackPlugin extends BasePlugin
{
    
    /**
     * 
     * @param MiddlewareQueue $middleware 
     * @return MiddlewareQueue 
     * @access public
     */
    public function middleware(MiddlewareQueue $middleware): MiddlewareQueue
    {
        $middleware = parent::middleware($middleware);
        return $middleware;
    }

    /**
     * 
     * @param CommandCollection $commands 
     * @return CommandCollection 
     * @throws MissingPluginException 
     * @throws InvalidArgumentException 
     * @access public
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        $commands = parent::console($commands);
        return $commands;
    }

    /**
     * 
     * @param PluginApplicationInterface $app 
     * @return void 
     * @throws CakeException 
     * @access public
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        Configure::load('rejack-php.rejack', 'default', false);
    }

    /**
     * 
     * @param RouteBuilder $routes 
     * @return void 
     * @throws InvalidArgumentException 
     * @access public
     */
    public function routes($routes): void
    {
        parent::routes($routes);
        $routes->setExtensions(['json']);
        $routes->plugin(
            'Rejack',
            ['path' => '/rejack'],
            function ($routes) 
            {
                $routes->setRouteClass(DashedRoute::class);
                $routes->connect('/', ['controller' => 'Server', 'action' => 'index']);
                $routes->get('/theme/index/*', ['controller' => 'Theme', 'action' => 'index']);

                $routes->get('/server', ['controller' => 'Server', 'action' => 'index']);
                $routes->get('/server/buffersize', ['controller' => 'Server', 'action' => 'buffersize']);
                $routes->get('/server/client/*', ['controller' => 'Server', 'action' => 'client']);
                $routes->get('/server/clients', ['controller' => 'Server', 'action' => 'clients']);
                $routes->get('/server/samplerate', ['controller' => 'Server', 'action' => 'samplerate']);
                $routes->get('/server/status', ['controller' => 'Server', 'action' => 'status']);

                $routes->connect('/server/start', [
                    'controller' => 'Server', 'action' => 'start'
                ])->setMethods(["GET", "PATCH", "POST", "PUT"]);

                $routes->connect('/server/stop', [
                    'controller' => 'Server', 'action' => 'stop'
                ])->setMethods(["GET", "PATCH", "POST", "PUT"]);

                $routes->get('/server/uptime/*', ['controller' => 'Server', 'action' => 'uptime']);
                $routes->get('/patchbay', ['controller' => 'Patchbay', 'action' => 'index']);

                $routes->connect('/patchbay/connect', [
                    'controller' => 'Patchbay', 'action' => 'connect'
                ])->setMethods(["GET", "PATCH", "POST", "PUT"]);

                $routes->connect('/patchbay/disconnect', [
                    'controller' => 'Patchbay', 'action' => 'disconnect'
                ])->setMethods(["GET", "PATCH", "POST", "PUT"]);

                $routes->connect('/patchbay/disconnectall', [
                    'controller' => 'Patchbay', 'action' => 'disconnectall'
                ])->setMethods(["GET", "PATCH", "POST", "PUT"]);

                $routes->connect('/patchbay/export', [
                    'controller' => 'Patchbay', 'action' => 'export'
                ])->setMethods(["GET", "PATCH", "POST", "PUT"]);

                $routes->connect('/patchbay/import', [
                    'controller' => 'Patchbay', 'action' => 'import'
                ])->setMethods(["GET", "PATCH", "POST", "PUT"]);

                $routes->connect('/patchbay/port/*', [
                    'controller' => 'Patchbay', 'action' => 'port'
                ])->setMethods(["GET", "PATCH", "POST", "PUT"]);
            }
        );
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
        
    }
}
