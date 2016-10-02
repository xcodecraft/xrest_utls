<?
namespace XCC\utls\v1 ;

class XInput
{
    static $preg  = "/[\',:;*?~`!#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/";
    static $inputSettings = array() ;

    static public function useSetting($jsonFile)
    {
        $content = file_get_contents($jsonFile);
        static::$inputSettings = json_decode($content) ;

    }
    public function error($key)
    {
        $msg = "$key 输入错误" ;
        if(isset(self::$messages[$key]))
        {
            $msg = self::$messages[$key];
        }
        throw new XUserInputException($msg);
    }
    public function safeGet($data,$maps) {
        $mapArr  = explode(',',$maps);
        $ret     = array();
        foreach($mapArr as $key){
            $value = trim($this->data[$key]) ;
            if(empty($value) || preg_match(self::$preg,$value)){
                $this->error($key);
            }
            $ret[$key] = $value;
        }
        return $ret ;
    }

    public function safeDict($maps) {
        $retval =  $this->safeGet($maps);
        return $retval ;
    }
    public function safeArr($maps) {
        $retval =  array_values($this->safeGet($maps));
        return $retval ;
    }
}
