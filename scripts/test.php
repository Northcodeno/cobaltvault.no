<?php

$array = range(1,10);

function manipulate(&$arr)
{
	$arr[5] = 50;
}

manipulate($array);

echo '<pre>';
print_r($array);
echo '</pre>';

/**
 * Generate authorization headers
 *
 * @param string $username
 * @param string $password
 * @param string $prefix
 * @param string $hashAlgorithm
 *
 * @return array(
 *          'X-gr-AuthDate': uncrypted date
 *         'Authorization': encrypted token
 * )
 */
/*
function generateHeaders($username, $password, $prefix = "gr001", $hashAlgorithm = 'sha1')
{
    $rfc_1123_date = gmdate('D, d M Y H:i:s T', time());
    $xgrdate = utf8_encode($rfc_1123_date);
    $userPasswd = base64_encode(hash($hashAlgorithm, $password, true));

    $signature = base64_encode(hash_hmac($hashAlgorithm, $userPasswd, $xgrdate));
    $auth = $prefix . " " . base64_encode($username) . ":" . $signature;
    $headers = array(
                   'X-gr-AuthDate' => $xgrdate,
                   'Authorization' => $auth
    );

    return $headers;
}

function post_call($url,$data) {
	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'  => "Accept:application/json\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data),
			),
		);

	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	return $result;
}

$headers = generateHeaders("rest-example","topsecret");

$res = post_call("https://api.tam.ch/kho/example", $headers);

echo $res;*/
?>