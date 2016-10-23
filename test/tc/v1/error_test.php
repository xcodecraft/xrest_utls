<?php
use XCC\utls\v1\ExceptionLang ;

class USER_LOGIN_ERROR  extends XRuntimeException
{
    static public $enFmt  = "user %s login  failed ";
    static public $zhFmt  = "用户(%s) 录用 失败";
    static public $stCode = 400 ;
    use ExceptionLang ;
}

class USER_LOGIN_FAIL  extends XUserInputException
{
    static public $enFmt  = "user %s login  failed ";
    static public $zhFmt  = "用户(%s) 录用 失败";
    use ExceptionLang ;
}





ExceptionLang::$useLang = LANG_EN ;
class ErrTest extends PHPUnit_Framework_TestCase
{
    /**
     *       @expectedException  USER_LOGIN_ERROR
     **/
    public function testA() {
        throw new USER_LOGIN_ERROR("1380013800") ;
    }

    /**
     *       @expectedException  USER_LOGIN_FAIL
     **/
    public function testB() {
        throw new USER_LOGIN_FAIL("1380013800") ;
    }
}
