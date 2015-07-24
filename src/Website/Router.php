<?php

namespace WebSite;

class Router {

	private $host;
	private $prefixe;
	private $routing;
	private $current_route;

	public function __construct($host,$prefixe,$routing) {
		$this->host = $host;
		$this->prefixe = $prefixe;
		$this->routing = $routing;
	}

	public function generateUrl($route, $parameters = [], $absolute = false) {
		$url = '?p='.$route;
		if ($parameters) {
			$url .= '&'.http_build_query($parameters);
		}

		return $url;
		
	}
	
	public function match($request) {

		// Get $page
		if(!empty($request['query']['p'])) {
		    $path = $request['query']['p'];
		} else {
		    $path = 'home';
		}

		$current_route_config = false;

		foreach ($this->routing as $routes_name => $routes_config) {
			if ($routes_config['path'] == $path) {
				$current_route_config = $this->getRouteConfig($routes_name);
				$this->current_route_name = $routes_name;
				break;
			}
		}

		return $current_route_config;
	}

	public function getRouteConfig($route_name) {
		$current_route_config = explode(':', $this->routing[$route_name]['controller']);
		return ['controller' => $current_route_config[0], 'action' => $current_route_config[1]];
	}

	public function getCurrentRouteName(){
		return $this->current_route_name;
	}
	
}