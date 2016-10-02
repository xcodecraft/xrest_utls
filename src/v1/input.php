<?
namespace XCC\utls\v1 ;

class XInput
{
    static $preg          = "/[\',:;*?~`!#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/";
    static $inputSettings = array() ;
    static $exceptionCls  = "\RuntimeException" ;

    static public function ruleSetting($jsonFile)
    {
        if(file_exists($jsonFile))
        {
            $content               = file_get_contents($jsonFile);
            static::$inputSettings = json_decode($content,true) ;
            if(static::$inputSettings )
            {
                return ;
            }
        }
        throw new \LogicException("$jsonFile is bad json!") ;
    }
    static public function failSetting($exceptionCls = "null")
    {
        static::$exceptionCls = $exceptionCls ;

    }
    static function getSetting($key)
    {
        $setting = self::$inputSettings['default'] ;
        if (isset(self::$inputSettings[$key] ))
        {
            $setting = self::$inputSettings[$key] ;
        }
        return $setting ;
    }
    static public function safeGet($data,$maps) {
        $mapArr  = explode(',',$maps);
        $ret     = array();
        foreach($mapArr as $key){
            $value   = trim($data[$key]) ;
            $setting = static::getSetting($key) ;
            $regex   = $setting['regex'] ;
            if(!empty($regex) && !preg_match($regex,$value))
            {
                if (null != static::$exceptionCls)
                {
                    throw new static::$exceptionCls($setting['error']);

                }
                $value  = null ;
            }
            $ret[$key] = $value;
        }
        return $ret ;
    }

    static public function safeDict($data,$maps) {
        $retval =  static::safeGet($data,$maps);
        return $retval ;
    }
    static public function safeArr($data,$maps) {
        $retval =  array_values(static::safeGet($data,$maps));
        return $retval ;
    }
}
