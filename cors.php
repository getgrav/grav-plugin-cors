<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;

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

        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

        foreach ($routes as $route) {
            if ($route === '*') {
                $this->active = true;
                break;
            }

            if (@preg_match('#' . $route . '#i', $uri)) {
                $this->active = true;
                break;
            }
        }

        if ($this->active) {
            if (in_array('*', $origins)) {
                $origin = '*';
            } else {
                $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : false;

                if (!$origin || !in_array($origin, $origins)) {
                    // Origin header doesn't match to the allowed origins: CORS not allowed.
                    return;
                }
            }

            header("Access-Control-Allow-Origin: ${origin}");

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
