<?
namespace XCC\utls\v1 ;

class XInput
{
    static $preg  = "/[\',:;*?~`!#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/";
    static $inputSettings = array() ;
    static $exceptionCls  = "RuntimeException" ;

    static public function ruleSetting($jsonFile)
    {
        $content = file_get_contents($jsonFile);
        static::$inputSettings = json_decode($content) ;

    }
    static public function failSetting($exceptionCls = "RuntimeException") 
    {
        static::$exceptionCLS = $exceptionCls ;

    }
    static function error($key)
    {
        $msg = "$key 输入错误" ;
        if(isset(self::$inputSettings[$key]))
        {

            $msg = self::$messages[$key];
        }
        throw new static::$exceptionCLS($msg);
    }
    static public function safeGet($data,$maps) {
        $mapArr  = explode(',',$maps);
        $ret     = array();
        foreach($mapArr as $key){
            $value = trim($data[$key]) ;
            $setting =  self::$inputSettings['default'] ;
            if (isset(self::$inputSettings[$key] ))
            {
                $setting = self::$inputSettings[$key] ;
            }
            if(!preg_match($setting['regex'],$value))
            {
                if (null != static::$exceptionCls)
                {
                    throw new static::$exceptionCLS($setting['error']);

                }
                $value  = null ;
            }
            $ret[$key] = $value;
        }
        return $ret ;
    }

    static public function safeDict($maps) {
        $retval =  static::safeGet($maps);
        return $retval ;
    }
    static public function safeArr($maps) {
        $retval =  array_values(static::safeGet($maps));
        return $retval ;
    }
}
