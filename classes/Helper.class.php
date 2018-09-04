<?

class Helper {
  static $css;
  static $js;
  
  static public function Add_css($file, $collection, $comment=''){
    self::$css[$collection][] = array($file, $comment);
  }
  static public function Add_js($file, $collection, $comment=''){
    self::$js[$collection][] = array($file, $comment);
  }
  static public function Get_css($collection){
    $return = '';
    foreach (self::$css[$collection] as $css){
      if ($css[1] <> '')
        $return .= "\r\n<!--  {$css[1]}  -->";
      $return .= "\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"{$css[0]}\">";
    }
    return $return."\r\n";
  }
  static function Get_js($collection){
    static $return;
    $return = '';
    foreach (self::$js[$collection] as $js){
      if ($js[1] <> '')
        $return .= "\r\n<!--  {$js[1]}  -->";
      $return .= "\r\n<script type=\"text/javascript\" src=\"{$js[0]}\"></script>";
    }
    return $return."\r\n";
  }
}
