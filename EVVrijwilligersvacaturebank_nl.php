<?php

class EVVrijwilligersvacaturebank_nl
{
	/**
	* authorize_keys
	*
	* Return token after autorizing key and sceret from server.
	*
	*/
	public function authorize_keys($target_url,$method,$user_agent,$headers,$consumerkey='',$consumersecret='')
	{
		if(empty($consumerkey))
			$consumerkey = get_option('consumerkey' );
		if(empty($consumersecret))
			$consumersecret = get_option('consumersecret');
		$curl = curl_init();
		$token = '';
		
		$curl_options = array(
		  CURLOPT_URL => $target_url.'/api/oauth/authorize',
		  CURLOPT_POST => $method == "POST",
		  CURLOPT_RETURNTRANSFER =>true,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_TIMEOUT => 5
		);
		$keys = 'consumerKey='.$consumerkey.'&consumerSecret='.$consumersecret;
		
		$curl_options[CURLOPT_POSTFIELDS] = $keys;
		curl_setopt_array($curl, $curl_options);
		$resp = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		if(!$resp)
		{
			echo JText::_( 'Error Getting Token. Please check your configuration.' );
			http_response_code($httpcode);
			exit(0);
		}
		elseif($httpcode == '200')
		{
			$token = $resp;
		}
		else
		{
			http_response_code($httpcode);
		}
		curl_close($curl);
		return $token;
	}
	/**
	* publish
	*
	* Get page from api and Publish them on front.
	*
	*/
	
	public function publish($target_url,$token, $route,$page,$user_agent,$headers)
	{
		$method = "POST";
		$queryparams = $_GET;
		$query_string = '';
		foreach($queryparams as $id=>$param)
		{
			$query_string .= '&'.$id.'='.$param;
		}
		$curl = curl_init();
		$curl_options = array(
		  CURLOPT_URL => $target_url.'/api/plugin/orgpage?token='.$token.$query_string,
		  CURLOPT_POST => $method == "POST",
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_RETURNTRANSFER =>true,
		  CURLOPT_TIMEOUT => 50
		);
		$keys = 'route='.$route.'&page='.$page;
		if($_SESSION['sess'])
			$keys .= '&session='.$_SESSION['sess'];
		
		$curl_options[CURLOPT_POSTFIELDS] = $keys;
		curl_setopt_array($curl, $curl_options);
		$resp = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if(!$resp)
		{
		  $message = 'Error proxying to "' . $target_url . ", " . $original_target_url
				   . '": "' . curl_error($curl) . '" - Code: ' . curl_errno($curl); die;
		  http_response_code($httpcode);
		}
		else
		{
			$result = json_decode($resp);	
			if (json_last_error() === JSON_ERROR_NONE && $result->ExceptionMessage) {
				echo $result->ExceptionMessage; 
				http_response_code($httpcode);exit(0);
			}
			else
			{				
				echo stripslashes(str_replace('\r\n','
				',trim($resp,'"')));
				exit(0);
			}
		}
		curl_close($curl);
	}
}
?>