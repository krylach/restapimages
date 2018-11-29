<?

class app {
	function __construct($var = []) {
		//ini_set('error_reporting', E_ALL);
		//ini_set('display_errors', 0);
		//ini_set('display_startup_errors', 0);

		$this->usage_memory = memory_get_usage();

		$this->config = require_once('/../config.php');
		//$this->connectBase($this->config->dbase);
	}

	public function connectBase($var = [])
	{
		$dbase = new mysqli(
			$var->host,
			$var->user,
			$var->pass,
			$var->base
		);
		if ($dbase->connect_errno) {
			print json_encode(['error' => $dbase->connect_error]);
			exit();
		}

		$this->dbase = $dbase;
		unset($dbase);
	}
	public function closeBase($var = []) {
		$this->dbase->close();
	}
	public function fetch($query = '') {
		if ($this->dbase) {
				$fetch = $this->dbase->query($query);
				if ($this->dbase->connect_errno) {
					print json_encode(['error' => 1, 'message' => $dbase->connect_error]);
					exit();
				}
			$row = [];
			while ($rows = mysqli_fetch_assoc($fetch)) {
				$row[] = $rows;
			}
			$fetch->free();
			return [
				'rows' => $row,
				'count' => count($row)
			];
		}
 	}
 	public function query($query = '') {
 		if ($this->dbase) {
 			$this->dbase->query($query);
 			if ($this->dbase->connect_errno) {
				print json_encode(['error' => 1, 'message' => $dbase->connect_error]);
				exit();
			}
 			return $this->dbase->insert_id;
 		}
 	}

	function __destruct() {
		//$this->closeBase();
		$this->usage_memory = $this->usage_memory - memory_get_usage();
	}
}

$app 		= new app();
require_once($app->config->dir.'app/users.php');
require_once($app->config->dir.'app/Route/Route.php');
$route		= new route();