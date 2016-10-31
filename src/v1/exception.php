<?php
namespace XCC\utls\v1 ;

define('LANG_ZH','zh');
define('LANG_EN','en');
trait ExceptionLang
{
    static $useLang = "" ;
    static public function getTPL()
    {
        if (ExceptionLang::$useLang == LANG_EN)
        {
            return static::$enFmt ;
        }
        return static::$zhFmt;
    }
    static public function getMsg($args)
    {
        return vsprintf(static::getTPL(), $args) ;
    }
    public function assemble($args)
    {
        $basecls = get_parent_class($this);
        $clsname = get_class($this) ;
        $message = static::getMsg($args) ;
        if($basecls == 'RuntimeException' || $basecls == 'LogicException'  )
        {
            parent::__construct($message,static::$stCode) ;
        }
        else if($basecls == 'XRuntimeException' || $basecls == 'XLogicException'  )
        {
            parent::__construct(static::$stCode,$message,$clsname) ;
        }
        else
        {
            parent::__construct($message,$clsname) ;
        }
    }
    public function __construct(...$args )
    {
        $this->assemble($args) ;
    }

}

