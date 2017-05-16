<?php

//--------------------------------------------------------------------------------------------------
/**
 * @brief Test whether HTTP code is valid
 *
 * HTTP codes 200 and 302 are OK.
 *
 * For JSTOR we also accept 403
 *
 * @param HTTP code
 *
 * @result True if HTTP code is valid
 */
function HttpCodeValid($http_code)
{
	if ( ($http_code == '200') || ($http_code == '302') || ($http_code == '403'))
	{
		return true;
	}
	else{
		return false;
	}
}


//--------------------------------------------------------------------------------------------------
/**
 * @brief GET a resource
 *
 * Make the HTTP GET call to retrieve the record pointed to by the URL. 
 *
 * @param url URL of resource
 *
 * @result Contents of resource
 */
function get($url, $userAgent = '', $timeout = 0)
{
	global $config;
	
	$data = '';
	
	$ch = curl_init(); 
	curl_setopt ($ch, CURLOPT_URL, $url); 
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION,	1); 
	//curl_setopt ($ch, CURLOPT_HEADER,		  1);  

	curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
	
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: AccessSessionTimedSignature=e9f959d2f895bab86da067ba136d2be653eed3b624b4f86af95036ab34064bef; Path=/, UUID=ccdc227c-8aa6-4a96-abdc-514673c83229; expires=Fri, 15-May-2020 12:25:31 GMT; Max-Age=94593600; Path=/, AccessSession=H4sIAAAAAAAAAK1Uy27bMBC85ysMn0N3SS5JsTc7TdKilyKPAkVRBMuHUhWubVhyiyLIv5d6WIqtGughFwGaGS2Hs7t6OptMpkWYTt5OpqgzMI544FGgy5EMOK8BuYvBB2en57XYd-qCPCMiEC38o4O1xiuN7-Y4X3CujZFGCX6pjUS4UguzaNXbTi1AgfROkxJOBtTCWRsdYLQOhbehVdOu-l7Lq-0uNsAvWrYFOFq0ShrJVcYbqtg0hbmEmQA7Qz0TUnZlws9iVbM5Lcu2UFmuD4HKH77XR8-9r8oa_jp5StgQmFTIEXhTPaHL_aUwy8wejH24BDZkjkGea4bGcmbBcCZjdCq3OZHqv6n-bGLz0YdVWRXVrirWq_6QnizGpN_GcOB08CoybjLRCY_M2gFOye7iQYIwgzdcD4rB3OaGVo_xRcnBWkvdl3E70C-72GDPk29nzbNN-lTKHADkKOPjfG1MwxuiZ7k0jqE3ipHPBAPPM0vKOyAY5Xu9Xe8242QXVBa-5WqD52ND2h4b0qOO69wRpMxZGmpMHUfPXIDUduktj-RkFrLXczRMT-9olBFwob3XmqWsRHKkNCPUgdlce-sCxmDlKzmSBo2Qo5TSpmYgR8aEd17mxrAQZUzGqElJMW08jxxjtEaNjN3Ex3_uRYs3s3ciK9AjX8eOZCS0TjtmDQ8Mc5uiAuEZipgGSeTS5uN1_b-ounkvq-EHxu3wA1vSKWJ3gqCq2jab01304qI59vJ-b-X2rl3Yu_fzj_OHT7d7_KLFr5dUPq5_P3zu-3LTVrhe9Move6C-wdnzX8ikznc3BgAA; Path=/, AccessSessionSignature=4ffe5eb13f04787efa8799c570caf2fba523a19d5904d92a89cf13d40aa2c00f; Path=/, _pxCaptcha=; Expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/;"));

	
	if ($userAgent != '')
	{
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	}	
	
	if ($timeout != 0)
	{
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	}
	
	if ($config['proxy_name'] != '')
	{
		curl_setopt ($ch, CURLOPT_PROXY, $config['proxy_name'] . ':' . $config['proxy_port']);
	}
	
			
	$curl_result = curl_exec ($ch); 
	
	//echo $curl_result;
	
	if (curl_errno ($ch) != 0 )
	{
		echo "CURL error: ", curl_errno ($ch), " ", curl_error($ch);
		exit();
	}
	else
	{
		$info = curl_getinfo($ch);
		
		 //$header = substr($curl_result, 0, $info['header_size']);
		//echo $header;
		
		
		$http_code = $info['http_code'];
		
		//echo "<p><b>HTTP code=$http_code</b></p>";
		
		if (HttpCodeValid ($http_code))
		{
			$data = $curl_result;
		}
	}
	return $data;
}

$parameters = array(
	'si'		=> '1',
	'scope' 	=> 'plants',
	'Query' 	=> 'ps_repository:LPB AND resourceType:specimens',
	'filter' 	=> 'free_text'
);

$parameters = array(
	'so' 		=> 'ps_group_by_genus_species asc',
	'Query' 	=> 'Sticherus nervatus',
	'filter' 	=> 'free_text'
);
	

$url = 'http://plants.jstor.org/search?' . http_build_query($parameters);

$html = get($url, 'Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405');

echo $html;


?>
