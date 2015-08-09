<?php

namespace Website\Controller;

class HomeController {

	public function homeAction() {
		return [
    		'html_view' => '/basic.structure.phtml',
    		'html_view_dynamic' => '/home.dynamic.phtml',
    		'page_author' => 'Antoine Demailly',
    		'page_description' => 'Connexion à l\'interface d\'administration de Trottilib.fr',
    		'page_title' => 'Trottilib - Interface d\'adminisration - Login' 
    	];
	}

}