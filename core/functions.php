<?
class URI_Shem{
    private static $Securiry_Schem;
    private static $URI_Schem;
    static function set_access_data($arg){
        //var_dump($arg);
        $URI = $arg['url'];
        $access_array = $arg['access'];
        $file = $arg['file'];
        //var_dump($file);
        self::$Securiry_Schem[$URI] = $access_array;
        self::$URI_Schem[$URI] = $file;
    }
    static function set_access_data_manual($arg){
        foreach($arg as $rule){
            $URI = $rule['url'];
            $access_array = $rule['access'];
            $file = $rule['file'];
            //var_dump($file);
            self::$Securiry_Schem[$URI] = $access_array;
            self::$URI_Schem[$URI] = $file;
        }
    }
    static function get_Securiry_Schem(){
        return self::$Securiry_Schem;
    }
    static function get_URI_Schem(){
        return self::$URI_Schem;
    }
}
class HOOK { // позволяет назначить выполнение функций для событий (хуков), хранит список назначенных функций, запускает их выполнение при вызове хука
	public static $actions = array();
	public static $filters = array();
	
	public static function __callStatic($action, $args){
	    
	    foreach(self::$actions[$action] as $actions){
	        if(isset($actions['arg'])){
	            $result = call_user_func_array($actions['fn'], $actions['arg']);
	            //var_dump( $actions['arg']);
	        }else{
	             $result = call_user_func_array($actions['fn'], array());
	        }
	        print $result;
	    }
	    
		//print self::$actions[$action];
	}
	public static function add_action($action, callable  $function, $args=0){ //
    	if(is_array($args)){
    	    self::$actions[$action][] = ['fn'=>$function, 'arg'=>$args];
    	}else{
    	    self::$actions[$action][] = ['fn'=>$function];
    	}
		//
	    //print 'iss '.$result;
	}
	
	public static function add_filter($filter, $data){ //

    	    self::$filters[$filter] = $data;

	}
	public static function get_filter($filter){
	    return self::$filters[$filter];
	}
}
class NAV{ // предоставляет удобный программный интерфейс для создания навигационных пунктов меню, хранит все пункты меню, может запустить формирование меню в зависимости от текущей открываемой страницы
    private static $menu;
    
	function add($conf, $array=''){
		if(!is_array($array)){
			//$conf['children']=$array;
			$a = $conf;
		}else{
			$child_ids = [];
			foreach($array as $ind => $val){
				$ids[] = $ind;
			//	var_dump($val);
				if(isset($val['ids']) && is_array($val['ids'])){
				    //print 'array_merge';
					$child_ids = array_merge($child_ids, $val['ids']);
				}
				//$array1[$val[$newid[0]]['id']] = $val1;
			}
			if(count($child_ids)>0){
				$ids = array_merge($ids, $child_ids);
			}
			//foreach($array as $val1){
		//	    $array1[$val1['id']] = $val1;
		//	}
			$conf['children']=$array;
			$conf['ids']=$ids;
			$a = $conf;
		}
		return $a;
	}
	public static function start($array){
	    self::$menu[] = $array;
	//	var_dump($array);
	//	print gen_menu($array, 'id6', 1);
	}
	public function __invoke($active_id) {
	    global $act_id;
	    foreach(self::$menu as $menu){
	        print gen_menu($menu, $act_id, 1);
	    }
	    
       // return self::$menu;
    }
}
function print_human_date($date, $timestamp=0){
    if($timestamp){
        $basedate = $timestamp;
        $date = date('Y-m-d', $basedate);
    }else{
        $basedate = strtotime($date);
    }
     
     $monthes = array(
        1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
        5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
        9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
    );

    if(date('Y-m-d') == $date){
        echo "Сегодня";
    }elseif(date('Y-m-d', time()-86400) == $date){
        echo "Вчера";
    }else{
        echo(date('d ', $basedate) . $monthes[(date('n', $basedate))]); // . date(' Y', $basedate)
    }
}
function gen_menu($items, $active_id, $top=0){

    if(is_array($items)){
    foreach($items as $ind=>$val){
        if($ind == $active_id){
            $active = 'active ';
        }else{
            //var_dump($val['ids']);
           if(in_array ($active_id, $val['ids'])){
                $active = 'active ';
                $menu_open = ' menu-open';
           }
        }
        if($top){
            if(isset($val['children']) && is_array($val['children'])){
            $return .="
    <li class=\"{$active}treeview{$menu_open}\">
  <a href=\"/{$val['url']}/\">
	<i class=\"fa {$val['icon']}\"></i> <span>{$val['title']}</span>
	<span class=\"pull-right-container\">
	  <i class=\"fa fa-angle-left pull-right\"></i>
	</span>
  </a>
  <ul class=\"treeview-menu\" style=\"\">".
   gen_menu($val['children'], $active_id).
"</ul>
</li>";
                
            }else{
    $return .="
    <li class=\"{$active}treeview{$menu_open}\">
  <a href=\"/{$val['url']}/\">
	<i class=\"fa {$val['icon']}\"></i> <span>{$val['title']}</span>
	<span class=\"pull-right-container\">
	  <i class=\"fa fa-angle-left pull-right\"></i>
	</span>
  </a>
</li>";
}

        }else{
             
            if(isset($val['children']) && is_array($val['children'])){
              $return .= " <li class=\"$active\"><a href=\"/{$val['url']}/\"><i class=\"fa {$val['icon']}\"></i> <span>{$val['title']}</span>	<span class=\"pull-right-container\"> <i class=\"fa fa-angle-left pull-right\"></i>	</span></a><ul class=\"treeview-menu\" style=\"\">".  gen_menu($val['children'], $active_id).    "</ul></li>";
            }else{
                $return .= "<li class=\"$active\"><a href=\"/{$val['url']}/\"><i class=\"fa {$val['icon']}\"></i> {$val['title']}</a></li>";
            }
           
            
        } 
        $active = '';
        $menu_open = '';
    }
    }else{
        print 'error';
    }
    return $return ;
}
function generatePassword($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }

    return $result;
}
function InintControllers(){
    // Create recursive dir iterator which skips dot folders
    $dir = new RecursiveDirectoryIterator('./controllers',
        FilesystemIterator::SKIP_DOTS);
    
    // Flatten the recursive iterator, folders come before their files
    $it  = new RecursiveIteratorIterator($dir,
        RecursiveIteratorIterator::SELF_FIRST);
    
    // Maximum depth is 1 level deeper than the base folder
    $it->setMaxDepth(0);
    
    // Basic loop displaying different messages based on file or folder
    foreach ($it as $fileinfo) {
        if ($fileinfo->isDir()) {
            
            $dirname = $fileinfo->getFilename();
            if(file_exists(getcwd().'/controllers/'.$dirname.'/'.$dirname.'Init.php')){
               // print getcwd().'/controllers/'.$dirname.'/'.$dirname.'Init.php'."\r\n";
                include(getcwd().'/controllers/'.$dirname.'/'.$dirname.'Init.php');
                //call_user_func(array($dirname.'Init', 'init'));
            }
            //$dirs[] = $fileinfo->getFilename();
            //printf("Folder - %s\n", $fileinfo->getFilename());
        } elseif ($fileinfo->isFile()) {
            //printf("File From %s - %s\n", $it->getSubPath(), $fileinfo->getFilename());
        }
    }
    //var_dump($dirs);
}
function check_action_allow($access_config, $action){ // проверяем разрешено ли вообше этой роли запускать action
    if(is_array($access_config) && is_string($action)){
        if(isset($access_config[$action])){
            if(isset($access_config[$action]['allow_action'])){
                if(!in_array($_SESSION["access_level"], $access_config[$action]['allow_action']))
                    return false;
            }
            if(isset($access_config[$action]['deny_action'])){
                if(in_array($_SESSION["access_level"], $access_config[$action]['deny_action']))
                    return false;
            }
            
        }
    }
    return true;
}
function check_record_allow_update($access_config, $action, $where_fields){ // проверяем разрешено ли роли редактировать запись, а если разрешено только владельцу, то добавляем во WHERE условие, что запись принадлежит текущему пользователю
    if(isset($access_config[$action]['record_allow_update'])){                  // UPD: больше не добавляем ничего во where (если пользователь )
        if(!in_array($_SESSION["access_level"], $access_config[$action]['record_allow_update'])){
            if(isset($access_config[$action]['record_allow_update']['OWNER'])){
                foreach($access_config[$action]['record_allow_update']['OWNER'] as $id_fields){
                    if($where_fields[$id_fields] == $_SESSION["user_id"]){
                        return true;
                    }
                }
                return false;
            }else{
                return false;
            }
        }
        //return true;
    }
    return true;
}
function check_record_allow_view($access_config, $action, $where_fields){ // проверяем разрешено ли роли смотреть запись, а если разрешено только владельцу, то добавляем во WHERE условие, что запись принадлежит текущему пользователю
   // var_dump($access_config);
   // print "start check '$action' access\r\n";
    if(isset($access_config[$action]['record_allow_view'])){
        //print "record_allow_view is set \r\n";
        if(!in_array($_SESSION["access_level"], $access_config[$action]['record_allow_view'])){
          //  print "access level {$_SESSION["access_level"]} is not allowed\r\n";
            if(isset($access_config[$action]['record_allow_view']['OWNER'])){
               // print "OWNER parametr is set \r\n";
                foreach($access_config[$action]['record_allow_view']['OWNER'] as $id_fields){
                    $where_fields[$id_fields] = $_SESSION["user_id"];
                  //   print "$id_fields must be = {$_SESSION["user_id"]} \r\n";
                }
               // print "result: allowed for owner {$_SESSION["user_id"]}\r\n";
                return $where_fields;
            }
           // print "OWNER parametr is NOT set, return false \r\n";
            return false;
        }
      //  print "access level {$_SESSION["access_level"]} is allowed, return true\r\n";
        return true;
    }
}
function filter_field_disallow_update($access_config, $action, $updated_data){ // проверяем, разрешено ли этой роли редактировать эти поля. Если нет - убираем эти поля из запроса на редактирование
    if(is_array($access_config) && is_string($action)){
        if(isset($access_config[$action])){
            if(isset($access_config[$action]['field_disallow_update'])){
              foreach($access_config[$action]['field_disallow_update'][$_SESSION["access_level"]] as $field){
                unset($updated_data[$field]);
              }
              foreach($updated_data as $ind=>$val){
                  if($val === NULL ){
                      unset($updated_data[$ind]);
                  }
              }
              return $updated_data;
            }
        }else{
            return $updated_data;
        }
    }
    return $updated_data;
}
function filter_field_disallow_view($access_config, $action, $get_fields){ // проверяем, разрешено ли этой роли смотреть эти поля. Если нет - убираем эти поля из запроса select
    if(is_array($access_config) && is_string($action)){
        if(isset($access_config[$action])){
            if(isset($access_config[$action]['field_disallow_view'])){
                foreach($access_config[$action]['field_disallow_view'][$_SESSION["access_level"]] as $field){
                    $i = array_search($field, $get_fields);
                    if($i !== false){
                        unset($get_fields[$i]);
                    }
                }
				return $get_fields;
            }
        }else{
            return $get_fields;
        }
    }
    return $get_fields;
}