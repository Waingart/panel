<?
class TPL1 {
	public $markers;
	public $my_tpl;
	public $vars;
	
	function TPL1($file) {
		$this->my_tpl = $file;
	}
	function __call($method, $args){
	    //print 'print testtplstatic'.$method;
		if(count($args)==0){
			if(count($this->markers) > 0){
			    if (is_array($this->markers[$method])){
			        foreach($this->markers[$method] as $tpl){
			            if( method_exists($tpl, 'run')){
        					$tpl->run();
        				}
			        }
			    }
				
			}
		}else{
			$this->markers[$method][] = new TPL1($args[0]);
			return $this->markers[$method][sizeof($this->markers[$method]) - 1];
		}
	}
	public static function __callStatic($method, $args){
	    print 'print testtplstatic';
	    return 'iss '.$method();
	}
	function __get($var){
		return $this->vars[$var];
	}
	function assign($name, $value){
		$this->vars[$name] = $value;
		return $this;
	}
	function get_tpl(){
		return $this->my_tpl;
	}
	function set_tpl($file){
		$this->my_tpl = $file;
	}
	function run(){
		if(!function_exists("__autoload")){
			function __autoload($class_name)
			{
			  if(@include_once("classes/" . $class_name . ".class.php")) return;
			}
		}
		include($this->my_tpl);
	}
}