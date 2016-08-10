<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Common\Uri;
use RocketTheme\Toolbox\Event\Event;

class CorsPlugin extends Plugin
{

    /**
     * @var bool
     */
    protected $active = false;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * If the URI matches a CORS route, initialize the plugin
     * as active.
     */
    public function onPluginsInitialized()
    {
        // header("Access-Control-Allow-Origin: *");
        $routes = (array) $this->config->get('plugins.cors.routes');
        $origins = (array) $this->config->get('plugins.cors.origins');
        $methods = (array) $this->config->get('plugins.cors.methods');
        $expose = (array) $this->config->get('plugins.cors.expose');
        $credentials = $this->config->get('plugins.cors.credentials');

        if (!count($routes) || in_array('*', $routes)) {
            $this->active = true;
        }

        $uri = $this->grav['uri'];

        foreach ($routes as $route) {
            if ($route === '*') {
                $this->active = true;
                break;
            }

            $route = strtr(preg_quote($route, '#'), array('\*' => '.*', '\?' => '.'));
            if (preg_match('#^' . $route . '$#i', $uri->path())) {
                $this->active = true;
                break;
            }
        }

        if ($this->active) {
            $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : false;
            if (in_array('*', $origins)) { $origin = '*'; }

            if (in_array($origin, $origins)) {
                header("Access-Control-Allow-Origin: ${origin}");
            }

            if (count($methods)) {
                header("Access-Control-Allow-Methods: " . implode(', ', $methods));
            }

            if (count($expose)) {
                header("Access-Control-Expose-Headers: " . implode(', ', $expose));
            }

            if ($credentials) {
                header('Access-Control-Allow-Credentials: true');
            }
        }
    }
}
