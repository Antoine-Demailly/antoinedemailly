<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use Website\Kernel;
use Website\Router;

//$Request can by an object
$request['request'] = &$_POST;
$request['query'] = &$_GET;
$request['session'] = &$_SESSION;

$kernel = new Kernel($request);

// Get Config.yml And Routing.yml
$config = Yaml::parse(file_get_contents(__DIR__.'/../app/config/config.yml'));
$routing = Yaml::parse(file_get_contents(__DIR__.'/../app/config/routing.yml'));

// Init Router
$router = new Router($config['router']['host'],$config['router']['prefixe'],$routing);





// Get Controller And Action names
$current_route_config = $router->match($request);
if ($current_route_config === false) {
	throw new Exception("Not Found", 404);
}

$controller = new $current_route_config['controller']($router, $config);
//$response can be an object
$response = $controller->$current_route_config['action']($request);


/** do a redirection here if $response['redirect_to'] exists **/
if (is_array($response)) {
	if (!empty($response['redirect_to'])) {
	    header('Location: ' . $response['redirect_to']);
	} elseif (!empty($response['html_view'])) {

	    require __DIR__ . '/../src/' . $response['html_view'];

	} elseif (!empty($response['twig_view'])) {

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
	} else {
	    throw new Exception('response object is not complet');
	}
}