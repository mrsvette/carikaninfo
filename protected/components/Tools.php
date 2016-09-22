<?php

class Tools
{
    public static function file_put_contents($content, $target, $mode = 'wt')
    {
        $fp = @fopen($target, $mode);

        if ($fp) {
            fwrite($fp, $content);
            fclose($fp);
        } else {
            $error = error_get_last();

            throw new RuntimeException(
                sprintf(
                    'Could not write to %s: %s',
                    $target,
                    substr(
                        $error['message'],
                        strpos($error['message'], ':') + 2
                    )
                )
            );
        }
    }

    public static function get_url($url, $timeout = 10)
    {
        $ch = curl_init();
        $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        if (ini_get('open_basedir') == '' && ini_get('safe_mode') == 'Off'){
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->_followlocation);
        }

        $data = curl_exec($ch);
        if($data === false) {
            throw new Exception(curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);
        return $data;
    }
    
    /**
     * Get client IP
     * @return string
     */
    public static function getIpv4()
    {
        $ip = NULL;
        if (isset($_SERVER) ) {
            if (isset($_SERVER['REMOTE_ADDR']) ) {
                $ip = $_SERVER['REMOTE_ADDR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $iplist = explode(',', $ip);
                if(is_array($iplist)) {
                    $ip = trim(array_pop($iplist));
                }
            }
        }
        return $ip;
    }

    public static function checkPerms($path, $perm = '0777')
    {
        clearstatcache();
        $configmod = substr(sprintf('%o', fileperms($path)), -4);
        $int = (int)$configmod;
        if($configmod == $perm) {
            return true;
        }

        if((int)$configmod < (int)$perm) {
            return true;
        }
        return false;
    }

    /**
     * Returns referer url
     * @return string
     */
    public static function getReferer($default = '/')
    {
        $r = empty($_SERVER['HTTP_REFERER']) ? $default : $_SERVER['HTTP_REFERER'];
        return $r;
    }

    /**
     * Generates random password
     * @param int $length
     * @param int $strength
     * @return string
     */
    public static function generatePassword($length=8, $strength=3) {
    	$upper = 0;
    	$lower = 0;
    	$numeric = 0;
    	$other = 0;

    	$upper_letters = 'QWERTYUIOPASDFGHJKLZXCVBNM';
    	$lower_letters = 'qwertyuiopasdfghjklzxccvbnm';
    	$numbers = '1234567890';
    	$symbols = '!@#$%&?()+-_';

		switch ($strength) {
			//lowercase
			case 1:
				$lower = $length;
			break;
			//lowercase + numeric
			case 2:
				$lower = rand(1, $length - 1);
				$numeric = $length - $lower;
			break;
			//lowercase + uppsercase + numeric
			case 3:
				$lower = rand(1, $length - 2);
				$upper = rand(1, $length - $lower - 1);
				$numeric = $length - $lower - $upper;
			break;
			//lowercase + uppercase + numeric + symbols
            case 4:
			default:
				$lower = rand(1, $length - 3);
				$upper = rand(1, $length - $lower - 2);
				$numeric = rand(1, $length - $lower - $upper - 1);
				$other = $length - $lower - $upper - $numeric;
			break;
		}

		for ($i = 0; $i < $upper; $i++) {
        	$passOrder[] = $upper_letters[rand() % strlen($upper_letters)];
    	}
    	for ($i = 0; $i < $lower; $i++) {
        	$passOrder[] = $lower_letters[rand() % strlen($lower_letters)];
    	}
    	for ($i = 0; $i < $numeric; $i++) {
        	$passOrder[] = $numbers[rand() % strlen($numbers)];
    	}
    	for ($i = 0; $i < $other; $i++) {
        	$passOrder[] = $symbols[rand() % strlen($symbols)];
    	}

    	shuffle($passOrder);
    	$password = implode('', $passOrder);

        return $password;
    }

    public static function encrypt($text, $pass = '')
    {
        if (!extension_loaded('mcrypt')) {
            throw new Exception('php mcrypt extension must be enabled on your server to use BoxBilling');
        }
        
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $pass, $text, MCRYPT_MODE_ECB, $iv));
    }

    public static function decrypt($text, $pass = '')
    {
        if (!extension_loaded('mcrypt')) {
            throw new Exception('php mcrypt extension must be enabled on your server to use BoxBilling');
        }
        
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
          //I used trim to remove trailing spaces
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $pass, base64_decode($text), MCRYPT_MODE_ECB, $iv));
    }

    public static function autoLinkText($text)
    {
       $pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
       $callback = create_function('$matches', '
           $url       = array_shift($matches);
           $url_parts = parse_url($url);
           if(!isset($url_parts["scheme"])) {
              $url = "http://".$url;
           }
           return sprintf(\'<a target="_blank" href="%s">%s</a>\', $url, $url);
       ');

       return preg_replace_callback($pattern, $callback, $text);
    }

    public static function getResponseCode($theURL)
    {
        $headers = get_headers($theURL);
        return substr($headers[0], 9, 3);
    }

    public static function slug($str)
    {
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', "-", $str);
        $str = trim($str, '-');
        return $str;
    }

    public static function escape($string)
    {
    	$string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    	return stripslashes($string);
    }

    public static function get_mime_content_type($filename)
    {
        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }

    public static function to_camel_case($str, $capitalise_first_char = false) {
        if($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/-([a-z])/', $func, $str);
    }

    public static function from_camel_case($str) {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "-" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }
    
    public static function sortByOneKey(array $array, $key, $asc = true) {
        $result = array();

        $values = array();
        foreach ($array as $id => $value) {
            $values[$id] = isset($value[$key]) ? $value[$key] : '';
        }

        if ($asc) {
            asort($values);
        }
        else {
            arsort($values);
        }

        foreach ($values as $key => $value) {
            $result[$key] = $array[$key];
        }

        return $result;
    }
}
