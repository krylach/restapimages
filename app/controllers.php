<?
require_once($app->config->dir.'app\models.php');
class controllers {
	function __construct() {
		global $app;
		$this->app = $app;
		$this->models = new models();
	}

	public function index($var = []) {
		//$this->getModels(['uses' => 'test', 'variables' => [
		//	'temp' => 1
		//]]);
		$this->getTemplates(['temp' => 'header']);
		$this->getTemplates(['temp' => 'index']);
		$this->getTemplates(['temp' => 'footer']);
	}

	public function pushPicture($var = []) {

		if (isset($_FILES['picture']))
			$this->getModels(['uses' => 'pushPictureFILES', 'variables' => [
				'picture' => $_FILES['picture']
			]]);
		elseif (isset($_POST['json']))
			$this->getModels(['uses' => 'pushPictureJSON', 'variables' => [
				'json' => $_POST['json']
			]]);
		elseif (isset($_POST['url'])) {
			$this->getModels(['uses' => 'pushPictureURL', 'variables' => [
				'url' => htmlspecialchars($_POST['url'])
			]]);
		}

	}

	public function previewPicture($var = []) {
		if (isset($_GET)) 
			if (strlen($_GET['im']) > 5) {
				$image = htmlspecialchars($_GET['im']);
				$this->getModels(['uses' => 'previewCreate', 'variables' => [
					'image' => $image, 
					'x_o' => 100, 
					'y_o' => 100, 
					'w_o' => 100, 
					'h_o' => 100
				]]);
			}
	}

	//private methods
	private function getModels($var = []) {
		if (method_exists($this->models, $var['uses'])) {
		if (isset($var['variables'])) {
				return $this->models->$var['uses']($var['variables']);
		} else 
				return $this->models->$var['uses']();
		} else {
			print json_encode((['error' => 1, 'message' => "Undefined method {$var['uses']}()"]));
			exit();
		}
	}
	private function getTemplates($var = []) {
		$file = $this->app->config->dir.'templates/blade.'.$var['temp'].'.php';
		if (file_exists($file)) {
			print file_get_contents($file);
		} else {
			print json_encode((['error' => 1, 'message' => "File $file - not found"]));
			exit();
		}
	}
	private function timestamp($time) {
    	return date("N", $time);
	}
	
}