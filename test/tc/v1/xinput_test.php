<?php
use XCC\utls\v1\XSql ;
use XCC\utls\v1\XInput ;
class XInputTC extends PHPUnit_Framework_TestCase
{
    public function testDemo() {

        $data = array(); 
        $data['name'] = "xcc" ;
        $data['age']  = 18 ;
        $data['limit'] = "[0,20]" ;
        $dataRoot = dirname(__FILE__) ;
        XInput::ruleSetting($dataRoot . "/input.json") ;
        XInput::failSetting(null);

        list($name,$age,$limit) = XInput::saftArr($data,"name,age,limit") ;

        $this->assertEquals($name,$data['name']) ;
        $this->assertEquals($age,$data['age']) ;


        $data['name'] = "xcc" ;
        $data['age']  = 11 ;
        $data['limit'] = "[0,20]" ;
        list($name,$age,$limit) = XInput::saftArr($data,"name,age,limit") ;
        
    }
}
    
