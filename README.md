#XInput

```php
    XInput::ruleSetting($dataRoot . "/input.json") ;
    XInput::failSetting("\XInputException");
    list($name,$age,$limit) = XInput::safeArr($data,"name,age,limit") ;

```
#XSql

```
区间:
    []
    ()
    (]

大于小于
    >=
    <>

函数
    like("abc*")
    desc(id)

limit:
    limit = [0,2]
```

``` php

    $qCont             = new QueryDTO;
    $qCont->id         = 100 ;
    $qCont->limit      = '[0, 20]';
    $qCont->createtime = '[2016-2-1, 2016-3-1]';
    $qCont->order      = 'desc(id)';
    $sql               = XSql::where($qCont) ;

    $expect = "id = 100 and createtime >= 2016-2-1 and createtime <= 2016-3-1 order by id DESC limit 0, 20" ;
    $this->assertEquals($sql, $expect) ;



```

``` php

    $_GET['id']         = 100 ;
    $_GET['limit']      = '[0,20] ';
    $_GET['createtime'] = '[2016-2-1, 2016-3-1]';
    $_GET['order']      = 'desc(id)' ;

    $data = XInput::safeDict($_GET,'id,limit,createtime,order') ;
    $sql  = "select * from user where "  . XSql::where($data) ;
    //select * from user where id = 100 and createtime >= 2016-2-1 and createtime <= 2016-3-1 order by id DESC limit 0, 20;

```
