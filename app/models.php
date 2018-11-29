<? 
class models {
	function __construct() {
		global $app;
		$this->app = $app;
	}

	public function pushPictureURL($var = []) {
		$ext = explode('.', explode('/', $var['url'])[count(explode('/', $var['url']))-1])[1];
		$file = explode('.', explode('/', $var['url'])[count(explode('/', $var['url']))-1])[0].'.'.explode('.', explode('/', $var['url'])[count(explode('/', $var['url']))-1])[1];
		$file_md = md5($file.date('Y-m-d H:i:s')).'.'.$this->replaceNameFile($file);

		$ch = curl_init($var['url']);
		$fp = fopen($this->app->config->dir.'assets\upload\\'.$file_md, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);

		$returnArrayPictureToJSON = [
				'url' => $this->app->config->url."/assets/upload/".$file_md,
				'preview' => $this->app->config->url."/preview?im=".$file_md
		];
		$this->json_return($returnArrayPictureToJSON, 1);
	}

	public function pushPictureJSON($var = []) {
		if ($this->is_json($var['json'])) {
			$base64 = json_decode($var['json'])->base64;
			$ext = explode(';', explode('/', explode(',', $base64)[0])[1])[0];

			$file_name = md5(date('Y-m-d')).'.'.$ext;
			$output_file = $this->app->config->dir.'assets\upload\\'.$file_name;
			
			$ifp = fopen($output_file, "wb");

		    $data = explode(',', $base64)[1];

		    fwrite($ifp, base64_decode($data));
			fclose($ifp);


			$returnArrayPictureToJSON = [
				'url' => $this->app->config->url."/assets/upload/".$file_name,
				'preview' => $this->app->config->url."/preview?im=".$file_name
			];
			$this->json_return($returnArrayPictureToJSON, 1);
		}

	}

	public function pushPictureFILES($var = []) {
		$picturesArray = [];
		$validArray = [];
		$numPic = 0;
		foreach ($var['picture']['type'] as $type) {
			if ($this->validType(['valid' => $this->app->config->validation_image, 'type' => explode('/', $type)[1]])) {
				$validArray[$numPic] =  [
					'valid' => true, 
					'name' => md5($var['picture']['name'][$numPic].date('Y-m-d H:i:s')).'.'.$this->replaceNameFile($var['picture']['name'][$numPic]),
					'tmp' => $var['picture']['tmp_name'][$numPic]
				];
				$numPic++;
			} else {
				$validArray[$numPic] =  [
					'valid' => false, 
					'name' => md5($var['picture']['name'][$numPic].date('Y-m-d H:i:s')).'.'.$this->replaceNameFile($var['picture']['name'][$numPic]),
					'tmp' => $var['picture']['tmp_name'][$numPic]
				];
				$numPic++;
			}
		}

		$returnArrayPictureToJSON = [];
		foreach ($validArray as $picture) {
			if ($picture['valid'])
				if (move_uploaded_file($picture['tmp'], $this->app->config->dir.'assets\upload\\'.$picture['name'])) {
					$returnArrayPictureToJSON[] = [
						'url' => $this->app->config->url."/assets/upload/".$picture['name'],
						'preview' => $this->app->config->url."/preview?im=".$picture['name']
					];
				}
		}

		$this->json_return($returnArrayPictureToJSON, 1);
	}

	public function previewCreate($var = []) {
		$image  = $this->app->config->dir.'/assets/upload/'.$var['image'];

		$x_o = (int)$var['x_o'];
		$y_o = (int)$var['y_o']; 
		$w_o = (int)$var['w_o']; 
		$h_o = (int)$var['h_o'];

  	    if ($x_o > 0 || $y_o > 0 || $w_o > 0 || $h_o > 0) {
	   		list($w_i, $h_i, $type) = getimagesize($image); 
  	    } else { 
	    	exit(json_encode(['error' => 'Разрешение картинки меньше 100x100.']));
	    	return false;
	    }

	    $types = [1 => "gif", 2 => "jpeg", 3 => "png"]; 
	    $ext = $types[$type]; 

	    if ($ext) {
	      $func = 'imagecreatefrom'.$ext; 
	      $img_i = $func($image); 
	    } else { 
	    	exit(json_encode(['error' => 'Неизвестное изображение.']));
	    	return false;
	    }
	    if ($x_o + $w_o > $w_i) $w_o = $w_i - $x_o;
	    if ($y_o + $h_o > $h_i) $h_o = $h_i - $y_o;

	    $img_o = imagecreatetruecolor($w_o, $h_o);
	    imagecopy($img_o, $img_i, 0, 0, $x_o, $y_o, $w_o, $h_o);
	    
	    $func = 'image'.$ext;

	    header("Content-Type: image/$ext");
		header('Content-Length: ' . filesize($image));

		$func($img_o);
	    imagedestroy($img_o);
  }

	//other functions
	private function validType($var = []) {
		foreach ($var['valid'] as $valid) {
			if ($valid == $var['type']) {
				return true;
			}
		} return false;
	}
	private function replaceNameFile($title = '') {
		$title = mb_strtolower($title);
		$arraySymbol = 		 ['й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ', 'ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю', ' ', '	'];
		$arraySymbolReturn = ['y', 'c', 'u', 'k', 'e', 'n', 'g', 'sh', 'sh', 'z', 'h', '', 'f', 'y', 'v', 'a', 'p', 'r', 'o', 'l', 'd', 'g', 'e', 'ya', 'ch', 's', 'm', 'i', 't', '', 'b', 'yu', '-', '-']; 

		return str_replace($arraySymbol, $arraySymbolReturn, $title);
	}
	private function is_json($str = '') {
	 	json_decode($str);
	 	return (json_last_error() == JSON_ERROR_NONE);
	}
	private function json_return($var = [], $print = 0) {
		if ($print) print json_encode($var);
		else return json_encode($var);
	}


	//private methods
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