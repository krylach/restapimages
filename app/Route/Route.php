<?
require_once($app->config->dir.'app/controllers.php');

class route {
	function __construct() {
		$this->controllers = new controllers();
		$this->notFound = 0;
	}

	public function get($var = []) {
		$this->URI_BOOL = false;
		if ($_SERVER['REQUEST_URI'] != $var['uri']) {
			if (is_numeric(strripos($_SERVER['REQUEST_URI'], '?')) || is_numeric(strripos($_SERVER['REQUEST_URI'], '&')))
				if (is_numeric(strripos($_SERVER['REQUEST_URI'], $var['uri']))) $this->URI_BOOL = true;
		} else $this->URI_BOOL = true;

		if ($this->URI_BOOL) {
			if ($_SERVER['REQUEST_METHOD'] != 'GET') {
				print json_encode((['error' => 1, 'message' => "Error GET request method, URI => {$var['uri']}"]));
				exit();
			}
			if (isset($var['uses'])) {
				$this->notFound = 1;
				if (method_exists($this->controllers, $var['uses'])) {
					if (isset($var['variables'])) {
						$this->controllers->$var['uses']($var['variables']);
						exit();
					} else {
						$this->controllers->$var['uses']();
						exit();
					}
				} else {
					print json_encode((['error' => 1, 'message' => "Undefined method {$var['uses']}()"]));
					exit();
				}
			}
		}
	}

	public function post($var = []) {
		$this->URI_BOOL = false;
		if ($_SERVER['REQUEST_URI'] != $var['uri']) {
			if (is_numeric(strripos($_SERVER['REQUEST_URI'], '?')) || is_numeric(strripos($_SERVER['REQUEST_URI'], '&')))
				if (is_numeric(strripos($_SERVER['REQUEST_URI'], $var['uri']))) $this->URI_BOOL = true;
		} else $this->URI_BOOL = true;

		if ($this->URI_BOOL) {
			if ($_SERVER['REQUEST_METHOD'] != 'POST') {
				print json_encode((['error' => 1, 'message' => "Error POST request method, URI => {$var['uri']}"]));
				exit();
			}
			if (isset($var['uses'])) {
				$this->notFound = 1;
				if (method_exists($this->controllers, $var['uses'])) {
					if (isset($var['variables'])) {
						$this->controllers->$var['uses']($var['variables']);
						exit();
					} else {
						$this->controllers->$var['uses']();
						exit();
					}
				} else {
					print json_encode((['error' => 1, 'message' => "Undefined method {$var['uses']}()"]));
					exit();
				}
			}
		}
	}

	function __destruct() {
		if (!$this->URI_BOOL) {
			header('HTTP/1.0 404 Not Found');
			die();
		}
	}
}

$route		= new route();

// routing
$route->get([
	'uri' => '/preview/',
	'uses' => 'previewPicture'
]);
$route->get([
	'uri' => '/',
	'uses' => 'index'
]);

$route->post([
	'uri' => '/push_picture/',
	'uses' => 'pushPicture'
]);