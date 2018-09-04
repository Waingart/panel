<?
class Templator {
	public $markers;
	public $my_tpl;
	public $vars;
	
	function Templator($file) {
		$this->my_tpl = $file;
	}
	function __call($method, $args){
		if(count($args)==0){
			if(count($this->markers) > 0){
				if( method_exists($this->markers[$method], 'run')){
					$this->markers[$method]->run();
				}
			}
		}else{
			if(isset($this->markers[$method]) && ($this->markers[$method]->get_tpl() != $args[0])){
				$this->markers[$method]->set_tpl($args[0]);
			}else{
				if(!isset($this->markers[$method])){
					$this->markers[$method] = new Templator($args[0]);
				}
			}
			return $this->markers[$method];
		}
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
		include("tpl/".$this->my_tpl);
	}
}