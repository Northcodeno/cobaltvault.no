<?php
define("MD5SALT","dhf489dvx0hwkl__30d9");
define("ALLOWED_TAGS","");
@session_start();

function isJson($str)
{
	return !preg_match('/[^,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/',
       preg_replace('/"(\\.|[^"\\])*"/g', '', $str));
}

function contains($str, array $arr)
{
    foreach($arr as $a) {
        if (strpos($str,$a) !== false) return true;
    }
    return false;
}

function shorten($text,$len,$replace)
{
	$str = substr($text,0,$len);
	if ($str != $text)
		$str .= $replace;
	return $str;
}

function check($var)
{
	return (isset($var) && $var != "");
}

function encrypt($string, $key = "msg")
{
	return @openssl_encrypt($string, "AES-256-OFB", $key);
}

function decrypt($string, $key = "msg")
{
	return @openssl_decrypt($string, "AES-256-OFB", $key);
}

function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function redirect($msg = null, $referer = null)
{
	if($referer == null)
	{
		if(isset($_SERVER['HTTP_REFERER']))
		{
			$referer = $_SERVER['HTTP_REFERER'];
			//if($referer = $_SERVER['PHP_SELF'])
			//	$referer = "/";
		}
	}

	if($msg == null)
	{
		header("Location: ".$referer);
	} else {
		?>
		Redirecting...
		<form action='<?php echo $referer; ?>' method='post' name='form'>
		<input type='hidden' name='m' value='<?php echo encrypt($msg); ?>'>
		</form>

		<script language="JavaScript">
			document.form.submit();
		</script>

		<?php
	}
	die("Redirect Failed");
}

function _error($msg)
{
	if($msg == "" || $msg == null)
		return;

	redirect("<div class='alert alert-danger'><b>Error!</b><br/>".$msg."</div>");
}

function alert($msg,$type = "info",$referer = null)
{
	if($referer == null)
	{
		redirect("<div class='alert alert-$type'>$msg</div>");
	}
	else
	{
		redirect("<div class='alert alert-$type'>$msg</div>",$referer);
	}
}

function FileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
        $arBytes = array(
            array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "." , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}