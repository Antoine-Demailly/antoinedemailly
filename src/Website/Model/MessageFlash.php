<?php


namespace WebSite\Model;

class MessageFlash {
	
	public static function addMessageFlash($type, $message) {
	    // autorise que 4 types de messages flash
	    $types = ['success','error','alert','info'];
	    if (!in_array($type, $types)) {
	        return false;
	    }

	    // on vérifie que le type existe
	    if (!isset($_SESSION['flashBag'][$type])) {
	        //si non on le créé avec un Array vide
	        $_SESSION['flashBag'][$type] = [];
	    }

	    // on ajoute le message
	    $_SESSION['flashBag'][$type][] = $message;
	}
	
}