<?php

namespace vendor\core\base;

abstract class Controller {

	public $route = [];
	public $view;
	public $layout;
	public $vars = [];

	public function __construct($route) {
		$this->route = $route;
		$this->view = $route['action'];

	}
	public function getView() {
		$vObj = new View($this->route, $this->layout, $this->view);
		$vObj->render($this->vars);
	}

	public function set($vars) {
		$this->vars = $vars;
	}

	public function isAjax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	public function loadView($view, $vars = []) {
	    extract($vars);
	    require APP . "/views/{$this->route['controller']}/{$view}.php";
    }
}