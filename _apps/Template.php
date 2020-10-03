<?php
/*
CREDITS::
https://www.daggerhart.com/simple-php-template-class/
*/
namespace Apps;
use \Apps\Session;

class Template extends Session{

	public $Core = NULL;
	public $Me = NULL;
	public $ME = NULL;
	public $Self = NULL;
	public $template = NULL;

	public $folder = "";
	public $root = templates_dir;
	public $templates_dir = templates_dir;
	public $public_dir = public_dir;
	public $assets = assets_dir;
	public $layouts = layouts_dir;
	public $store = store_dir;
	public $template_file = "";
	public $has_error = false;
	public $error = "";
	public $session = NULL;

	public $output = '';
	public $header_file = '';
	public $footer_file = '';
	public $robots = '';
	
	public $header_css_html = '';
	public $header_jss_html = '';

	public $header_jss_scripts = '';

	
	public $tempArr = array();
	public $template_extension = template_file_extension;

	public $UIX = "";
	public $uix = "";
	public $uix_ver = "";
	public $token = NULL;
	public $encrypt_salt = encrypt_salt;
	
	public $session_timout = session_timout;
	public $secure = false;
	public $auth = false;
	public $auth_url = auth_url;
	
	public $config = "";
	
	public function __construct($auth_url=NULL){
  	
		
		parent::__construct($auth_url);	
				
		if(!empty($auth_url)){
			
			$this->secure = true;
			$this->auth_url = $auth_url;
				
			if( isset($this->data['auth_time']) ){
				
				$s_now = strtotime(date('d-m-Y H:i:s'));
				$s_jvt = strtotime($this->data['auth_time']);
				$s_dif = $s_now - $s_jvt;

				//Check the session
				if( $s_dif <= ($this->session_timout * 60) ){
					$this->data['auth_time'] = date('d-m-Y H:i:s');
					$this->secure = true;
					$this->auth =  true;
					$this->save();
				}else{
					$this->secure = false;
					$this->auth = false;
					$expired = (int)$this->expire();
					if($expired){
						$this->redirect("{$auth_url}");
					}
				}
				
			}
		}			
		
		$this->set_folder( $this->templates_dir );
		$config = get_defined_constants(true);
		$config_class = array();
		foreach ($config as $key=>$value){
			$config_class[$key] = $value;
		}
		$this->token = $this->tokenize();
		$this->config = $config_class;
		$this->Core = new core;
		
		
	}
	
	public function auth(){
		$this->data['auth_time'] = date('d-m-Y H:i:s');
		$this->data['logged_in'] = true;
		$this->secure = true;
		$this->auth =  true;
		$this->save();
	}

	
	public function set_folder( $folder ){
		$this->folder = rtrim( $folder, '/' );
	}

	public function tokenize(){
		$encrypt_salt = $this->encrypt_salt;
		$rand = rand(1, 1000000);
		$tick = time();
		$all = ($encrypt_salt . $rand . $tick);
		$hash = md5(sha1($all));
		return $hash;
	}
	
	public function error( $error = "" ){
		$this->error = $error;
		$this->has_error = true;
	}

	/* use bootstrapp or materials */
	public function framework( $framework = "bootstrap",$framework_ver="0" ){
		$this->framework = $framework;
		$this->framework_ver = $framework_ver;
	}
	
	public function redirect( $url="/" ){
		header("Location: {$url}");
		exit();
	}
		
	public function render( $suggestions, $variables = array() ){
		$template = $this->search_template( $suggestions );
		$this->template_file = $suggestions;
		if ( $template ){
			$output = $this->render_template( $template,$variables );
		}
		return print($output);
	}
		
	public function SetupPage( $suggestions, $variables = array() ){
		
		$pagename = trim(strtolower($suggestions));
		$pagename = preg_replace('/\s+/', '', $pagename);
		$pagetitle = trim($suggestions);
		$pagetitle = str_replace('-', ' ', $pagetitle);
		$pagetitle = ucwords($pagetitle);
		$this->template_file = $suggestions;
		
		$template = $this->search_template( $suggestions );
		if ( $template ){
			$info = new \stdClass;
			$info->page = $pagename;
			$info->title = $pagetitle;
			return $info;
		}else{
			//Create dummy template file//
			$page_path = "{$this->templates_dir}";
			if( chmod($page_path, 0777) ){
				$page_save_path = "{$this->templates_dir}{$pagename}.{$this->template_extension}";
				$file = file_put_contents($page_save_path, "");
				$info = new \stdClass;
				$info->page = $pagename;
				$info->title = $pagetitle;
				return $info;
			}
		}
	}
	
	
	public function add( $template,$show=true ){
		
		$suggestions_arr = explode(".",$template);
		$suggestions_arr_count = (int)count($suggestions_arr);
		$suggestions_arr_count_dir = (int)($suggestions_arr_count - 2);
		$suggestions_arr_count_file = (int)($suggestions_arr_count - 1);
		$suggestions_dir = '';
		for( $i=0;$i<=$suggestions_arr_count_dir; ++$i){
			$suggestions_dir .= "{$suggestions_arr[$i]}/";
		}
		$suggestions_dir = rtrim( $suggestions_dir, '/' );
		$suggestions = $suggestions_arr[$suggestions_arr_count_file];
		if ( !is_array( $suggestions ) ) {
			$suggestions = array( $suggestions );
		}
		$suggestions = array_reverse( $suggestions );
		$found = false;
		foreach( $suggestions as $suggestion ){
			$file = "{$this->folder}/{$suggestions_dir}/{$suggestion}.{$this->template_extension}";
			if ( file_exists( $file ) ){
				if($show){
					require $file;
				}
				break ;
			}
		}
		
	}


	public function addheader( $header ){
		$header_arr = explode(".",$header);
		$header_arr_count = (int)count($header_arr);
		$header_arr_count_dir = (int)($header_arr_count - 2);
		$header_arr_count_file = (int)($header_arr_count - 1);
		$header_dir = '';
		for( $i=0;$i<=$header_arr_count_dir; ++$i){
			$header_dir .= "{$header_arr[$i]}/";
		}
		$header_dir = rtrim( $header_dir, '/' );
		$suggestions = $header_arr[$header_arr_count_file];
		if ( !is_array( $suggestions ) ) {
			$suggestions = array( $suggestions );
		}
		$suggestions = array_reverse( $suggestions );
		$found = false;
		foreach( $suggestions as $suggestion ){
			$file = "{$this->folder}/{$header_dir}/{$suggestion}.{$this->template_extension}";
			if ( file_exists( $file ) ){
				$this->header_file = $file;
				break ;
			}
		}
	}

	public function addfooter( $footer ){
		$footer_arr = explode(".",$footer);
		$footer_arr_count = (int)count($footer_arr);
		$footer_arr_count_dir = (int)($footer_arr_count - 2);
		$footer_arr_count_file = (int)($footer_arr_count - 1);
		$footer_dir = '';
		for( $i=0;$i<=$footer_arr_count_dir; ++$i){
			$footer_dir .= "{$footer_arr[$i]}/";
		}
		$header_dir = rtrim( $footer_dir, '/' );
		$suggestions = $footer_arr[$footer_arr_count_file];
		if ( !is_array( $suggestions ) ) {
			$suggestions = array( $suggestions );
		}
		$suggestions = array_reverse( $suggestions );
		$found = false;
		foreach( $suggestions as $suggestion ){
			$file = "{$this->folder}/{$footer_dir}/{$suggestion}.{$this->template_extension}";
			if ( file_exists( $file ) ){
				$this->footer_file = $file;
				break ;
			}
		}
	}


	public function addcss( $css_arr_data = array() ){
		$css_html = "";
		foreach( $css_arr_data as $css_file ){
			if ( file_exists( $css_file ) ){
				$css_html .= "<link rel=\"stylesheet\" href=\"{$css_file}\">\r\n";
			}
		}
		$this->header_css_html .= $css_html;
		return $this->header_css_html;
	}


	public function addjs( $jss_arr_data = array() ){
		$jss_html = "";
		foreach( $jss_arr_data as $jss_file ){
			if ( file_exists( $jss_file ) ){
				$jss_html .= "<script src=\"{$jss_file}\"></script>\r\n";
			}
		}
		$this->header_jss_html .= $jss_html;
		return $this->header_jss_html;
	}
	
	public function jQuery($selector,$scripts){
		$qjss_html = "<script type=\"text/javascript\" async=\"true\" runat=\"server\" language=\"javascript\">\r\n";
		$qjss_html += "(function( $ ){\r\n";
		$qjss_html += $scripts;
		$qjss_html += "});\r\n";
		$qjss_html += "</script>\r\n";
		$this->header_jss_scripts .= $qjss_html; 
		return $qjss_html;
	}


	public function robot($name,$content){
		$_name = $name;
		$_contents = explode(",",$content);
		foreach($_contents as $_item){
			$this->robots .= "<meta name=\"{$_name}\" content=\"{$_item}\" />\n";
		}
		return true;
	}
		

	public function assign( $arrKey, $arrVal ){
		$this->tempArr[$arrKey] = $arrVal;
	}

	public function find_template( $suggestions ){
		if ( !is_array( $suggestions ) ) {
			$suggestions = array( $suggestions );
		}
		$suggestions = array_reverse( $suggestions );
		$found = false;
		foreach( $suggestions as $suggestion ){
			$file = "{$this->folder}/{$suggestion}.{$this->template_extension}";
			if ( file_exists( $file ) ){
				$found = $file;
				break ;
			}
		}
		return $found;
	}


	public function search_template( $teplate ){
		
		$template_arr = explode(".",$teplate);
		$template_arr_count = (int)count($template_arr);
		$template_arr_count_dir = (int)($template_arr_count - 2);
		$template_arr_count_file = (int)($template_arr_count - 1);
		$template_dir = '';
		for( $i=0;$i<=$template_arr_count_dir; ++$i){
			$template_dir .= "{$template_arr[$i]}/";
		}
		$template_dir = rtrim( $template_dir, '/' );
		$suggestions = $template_arr[$template_arr_count_file];
		
		if ( !is_array( $suggestions ) ) {
			$suggestions = array( $suggestions );
		}
		$suggestions = array_reverse( $suggestions );
		$found = false;
		foreach( $suggestions as $suggestion ){
			$file = "{$this->folder}/{$template_dir}/{$suggestion}.{$this->template_extension}";
			if ( file_exists( $file ) ){
				$found = $file;
				break ;
			}
		}
		return $found;
	}



	public function render_template($template, $variables){
		ob_start();
		foreach(func_get_args()[1] as $key => $value) {
			${$key} = $value;
		}
		foreach($this->tempArr as $key => $value) {
			${$key} = $value;
		}
	
		$config = $this->config;
		
		$Me = $Template = $Self = $this;
		
		$error = $this->error;
		$has_error = $this->has_error;
		$root = $this->root;
		$assets = $this->assets;
		$token = $this->token;
		$layouts = $this->layouts;
		$store = $this->store;
		$template_file = $this->template_file;
		$public_dir = $this->public_dir;
		$templates_dir = $this->templates_dir;
		$header_files = "";
		$robots = $this->robots;
		$footer_files = "";
		$Core = $this->Core;
		//Load Config variables//
		$config = $this->config;
		//Load Config variables//

		//Wrap Header//
		if ( file_exists( $this->header_file ) ){
			include $this->header_file;
		}
		include func_get_args()[0];
		if ( file_exists( $this->footer_file ) ){
			include $this->footer_file;
		}
		//Wrap Footer//

		return ob_get_clean();
		
	}


	
	
	
}
	
	

	
	
?>