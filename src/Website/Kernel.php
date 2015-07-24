<?php

namespace Website;

use Website\Router;
use Symfony\Component\Yaml\Yaml;

class Kernel {

    public $config;
    public $routing;

    public $request; 

	public function __construct() {

        $this->request['request'] = &$_POST;
        $this->request['query'] = &$_GET;
        $this->request['session'] = &$_SESSION;


        $this->config = Yaml::parse(file_get_contents(__DIR__.'/../../app/config/config.yml'));
        $this->routing = Yaml::parse(file_get_contents(__DIR__.'/../../app/config/routing.yml'));

        $this->router = new Router($this->config['router']['host'],$this->config['router']['prefixe'],$this->routing);

        // Get Controller And Action names
        $current_route_config = $this->router->match($this->request);
        if ($current_route_config === false) {
            throw new Exception("Not Found", 404);
        }

        $controller = new $current_route_config['controller']($router, $config);
        $this->response($controller, $current_route_config);
	}

    public function response($controller, $current_route_config) {

        $response = $controller->$current_route_config['action']($this->request);

        /** do a redirection here if $response['redirect_to'] exists **/
        if (is_array($response)) {

            if (!empty($response['redirect_to']))
                header('Location: ' . $response['redirect_to']);

            elseif (!empty($response['html_view'])) 
                require __DIR__  . '/View/' . $response['html_view'];

            elseif (!empty($response['twig_view'])) {

                $loader = new Twig_Loader_Filesystem(__DIR__.'/src/WebSite/View');
                $twig = new Twig_Environment($loader, array(
                    'cache' => __DIR__ . '/../app/cache',
                ));

                $template = $twig->loadTemplate($response['twig_view']);
                $response['router'] = $router;
                $template->render($response);

            } elseif (isset($response['json'])) {
                header('Content-type: application/json');
                echo(json_encode($response['json']));
            } else
                throw new Exception('response object is not complet');

        }

    }


} // End Kernel