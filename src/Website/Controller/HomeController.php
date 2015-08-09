<?php

namespace Website\Controller;

class HomeController {

	public function homeAction() {
		return [
    		'html_view' => '/basic/structure.phtml',
    		'html_view_dynamic' => '/home.dynamic.phtml',
    		'page_author' => 'Antoine Demailly',
    		'page_description' => '',
    		'page_title' => 'Antoine Demailly - Développeur Web' 
    	];
	}

}