<?php
/**
 * Bloubell
 *
 * Minimalist youtube PHP API.
 * Search youtube videos from keywords.
 * http://www.youtube.com/ does not sponsor this API.
 *
 * Copyright (c) 2013 - 92 Bond Street, Yassine Azzout
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 *	The above copyright notice and this permission notice shall be included in
 *	all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package Bloubell
 * @version 1.0
 * @copyright 2013 - 92 Bond Street, Yassine Azzout
 * @author Yassine Azzout
 * @link http://www.92bondstreet.com Bloubell
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

// SwissCode plugin
// Download on https://github.com/92bondstreet/swisscode
require_once('swisscode.php');	

define("YOUTUBE_URL",'http://www.youtube.com');
define("RESULTS_PER_PAGE",20);	
 
 //Report all PHP errors
error_reporting(E_ALL);
set_time_limit(0);



class Youtube { 
	public $url = "";
	public $title = "";
	public $description = "";
}


class Bloubell{
		
	// file dump to log
	private  $enable_log;
	private  $log_file_name = "bloubelle.log";
	private  $log_file;
	
	
	/**
	 * Constructor, used to input the data
	 *
	 * @param bool $log
	 */
	public function __construct($log=false){
					
		$this->enable_log = $log;
		if($this->enable_log)
			$this->log_file = fopen($this->log_file_name, "w");
		else
			$this->log_file = null;
			
	}
	
	/**
	 * Destructor, free datas
	 *
	 */
	public function __destruct(){
	
		// and now we're done; close it
		if(isset($this->log_file))
			fclose($this->log_file);
	}
	
	/**
	 * Write to log file
	 *
	 * @param $value string to write 
	 *
	 */
	function dump_to_log($value){
		fwrite($this->log_file, $value."\n");
	}

	/**
	 * Get videos with keyword
	 *
	 * @param 	$keyword 			to search
	 * @param 	$postresults		number of results to return 	
	 *
	 * @return array|null
	 */
	
	
	function search_videos($keyword, $postresults=30){
		

		$results = array();

		// Step 0. Get page number according to number results
		$nb_pages = ceil($postresults / RESULTS_PER_PAGE);
		
	
		// Step 1. parse pages: 20 results per pages
		for($page=0;$page<$nb_pages;$page++){
			
			$youtube_url = YOUTUBE_URL."/results?search_query=".urlencode($keyword)."&page=".$page;
			$youtube_results = $this->parse_youtube_url($youtube_url);
			$results = array_merge($results,$youtube_results);
		}
		
		return array_slice($results,0,$postresults);		// get only number of results defined by user
	}

	function parse_youtube_url($url){
	
		$results = array();

		//Step 0. Parse search url
		$html = MacG_connect_to($url);
		$html = str_get_html($html);
		
		// Step 1; get div with videos (without promoted videos)
		$videos = $html->find('#search-results',0);
		if(!isset($videos))
			return;
		$videos = $videos->find('li[data-context-item-type=video]');
			
		foreach($videos as $video){
			
			$youtubevideo = new Youtube;
			$url = $video->find('a',0);
			$youtubevideo->url = YOUTUBE_URL.$url->href;

			$title = $video->find('h3.yt-lockup2-title',0);
			$youtubevideo->title = $title->find('a',0)->plaintext;

			$description = $video->find('div.yt-lockup2-content',0);
			$youtubevideo->description = trim($description->find('p',0)->plaintext);
					
			$results[] = $youtubevideo;
		}
			
		unset($html);
		
		return $results;
	}
}

?>