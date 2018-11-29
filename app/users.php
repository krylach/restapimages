<?
class users {
	function __construct() {
		global $app;
		$this->app = $app;
		$this->ssid = $this->getSsid()['rows'][0];
	}

	public function ssid($var = []) {
		$this->checkDataSsid($_REQUEST);

		$login   = htmlspecialchars($_REQUEST['login']);
		$pass 	 = hash('md5', htmlspecialchars($_REQUEST['password'])); 
		$visit	 = time();
		$newSsid = hash('md5', $login.$pass.$visit);

		if (!$this->app->fetch("SELECT `ssid` FROM `users` WHERE `login` = '$login' AND `pass` = '$pass'")['rows'][0]['ssid']) {
			print json_encode((['error' => 1, 'message' => 'Логин или пароль не верны!']));
			exit();
		}
		
		$this->app->query("UPDATE `users` SET `ssid` = '$newSsid', `visit` = '$visit' WHERE `login` = '$login' AND `pass` = '$pass'");
		$ssid = $this->app->fetch("SELECT `ssid` FROM `users` WHERE `login` = '$login' AND `pass` = '$pass' AND `ssid` = '$newSsid'")['rows'][0]['ssid'];

		if ($ssid) {
			setcookie('ssid', $ssid, strtotime('+30 days'), '/');
			print json_encode((['error' => 0, 'message' => 'Успешная авторизация!']));
			exit();
		}
	}
	public function getSsid()
	{
		if (isset($_COOKIE['ssid'])) {
			$ssid = htmlspecialchars($_COOKIE['ssid']);
			return $this->app->fetch("SELECT * FROM `users` WHERE `ssid` = '$ssid'");
		}
	}

	public function is_ssid() {
		if ($this->ssid) {
			return true;
		} else {
			return false;
		}
	}
	
	private function checkDataSsid($var = []) {
		if (!$var['login']) {
			print json_encode((['error' => 1, 'message' => 'Undefined entering login!']));
			exit();
		}
		if (!$var['password']) {
			print json_encode((['error' => 1, 'message' => 'Undefined entering password!']));
			exit();
		}
	}
	function __destruct() {
		unset($this->app);
	}
}