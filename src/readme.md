#XInput 

```php 
    XInput::ruleSetting($dataRoot . "/input.json") ;
    XInput::failSetting("\XInputException");
    list($name,$age,$limit) = XInput::safeArr($data,"name,age,limit") ;

```
#XSql


``` php

    $qCont             = new QueryDTO;
    $qCont->id         = 100 ;
    $qCont->createtime = '[2016-2-1, 2016-3-1]';

    $sql               = XSql::where($qCont) ;
    $expect = "id = 100 and createtime >= 1 and createtime <= 1" ;
    $this->assertEquals($sql, $expect) ;

```