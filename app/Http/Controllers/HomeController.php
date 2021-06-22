<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;

class HomeController extends Controller
{
    //
    public function index(Request $request){

    	$jobDesc = $request->input('job_desc');
    	$jobLoc = $request->input('job_location');
    	$fullTimeOnly = $request->input('job_type');
    	$page = $request->input('page');
    	if(empty($page) || !is_numeric($page)){
    		$page = 1;
    	}

    	$data = $this->getJobsData($page, $jobDesc, $jobLoc, $fullTimeOnly);
    	if($data){
	    	foreach($data as $key => $val){
	    		if($val){
		    		$data[$key]->created = '';
		    		if(!empty($val->created_at)){
		    			$data[$key]->created = $this->time_elapsed_string($val->created_at);
		    		}
	    		}
	    	}
    	}

    	$nextLink = $request->fullUrl();
    	$queryStr = str_replace($request->url(), '',$request->fullUrl());
    	$prevLink = '';
    	
    	if(empty($queryStr)){
    		$nextLink .= '?page='.($page + 1);
    	} else {
    		$queryStr = $request->query();
    		foreach($queryStr as $key => $str){
    			$queryStr['page'] = $page + 1;
    		}
    		$str = '?';
    		$i = 0;
    		foreach ($queryStr as $key => $value) {
    			if($i != 0){
    				$str .= '&';
    			}
    			$str .= $key . '='. $value;
    			$i++;
    		}
    		$nextLink = $request->url() . $str;

    		$queryStr = $request->query();
    		if(isset($queryStr['page']) && $queryStr['page'] > 1){	
	    		foreach($queryStr as $key => $str){
	    			$queryStr['page'] = $page - 1;
	    		}
	    		$str = '?';
	    		$i = 0;
	    		foreach ($queryStr as $key => $value) {
	    			if($i != 0){
	    				$str .= '&';
	    			}
	    			$str .= $key . '='. $value;
	    			$i++;
	    		}

	    		$prevLink = $request->url() . $str;
    		}
    	}

    	return view('home', ['data' => $data, 'job_desc' => $jobDesc, 'job_loc' => $jobLoc, 'job_type' => $fullTimeOnly, 'page' => $page, 'nextLink' => $nextLink, 'prevLink' => $prevLink]);
    }

    private function time_elapsed_string($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}


    private function getJobsData($page = 1, $jobDesc = '', $jobLoc = '', $fullTimeOnly = false){

    	$url = 'http://dev3.dansmultipro.co.id/api/recruitment/positions.json?page='.$page.'&description='.$jobDesc.'&location='.$jobLoc;

    	if(!empty($jobDesc)){
    		$url .= 'full_time=true';
    	}
    	//Initiate cURL.
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_COOKIE, $cookieString);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		$response = curl_exec($ch);
		$err     = curl_errno($ch);
        $errmsg  = curl_error($ch);
        $header  = curl_getinfo($ch);

		//Execute the cURL request.
		 
		//Check for errors.
		if(curl_errno($ch)){
		    //If an error occured, throw an Exception.
		    throw new Exception(curl_error($ch));
		}

		curl_close($ch);

		$response = json_decode($response);
		if(isset($response->status) && $response->status == 500){
			$response = array();
		}

		return $response;
    }

    public function jobDetail($id = 0){
    	if(empty($id)){
    		return redirect()->route('home');
    	}
    	
    	$jobDetail = $this->getJobsDetail($id);

    	if(empty($jobDetail)){
    		return redirect()->back();
    	}

    	$jobDetail['description'] = preg_replace('/"([a-zA-Z]+[a-zA-Z0-9_]*)":/','$1:',$jobDetail['description']);

    	return view('detail', ['data' => $jobDetail]);

    }

    private function getJobsDetail($id = ''){

    	$url = 'http://dev3.dansmultipro.co.id/api/recruitment/positions/'.$id;

    	//Initiate cURL.
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_COOKIE, $cookieString);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		$response = curl_exec($ch);
		$err     = curl_errno($ch);
        $errmsg  = curl_error($ch);
        $header  = curl_getinfo($ch);

		//Execute the cURL request.
		 
		//Check for errors.
		if(curl_errno($ch)){
		    //If an error occured, throw an Exception.
		    throw new Exception(curl_error($ch));
		}

		curl_close($ch);

		$response = json_decode($response, true);
		if(isset($response['status']) && $response['status'] == 500){
			$response = array();
		}

		return $response;
    }
}
