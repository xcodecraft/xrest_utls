
# namespace

```php
XCC\utls\v1
```
v1 版本号是为将来考虑兼容性
v2 版本号是修复sql注入问题


#XInput

```php
    XInput::ruleSetting($dataRoot . "/input.json") ;
    XInput::failSetting("\XInputException");
    list($name,$age,$limit) = XInput::safeArr($data,"name,age,limit") ;

```

### 规则文件

示例:
``` json
{
    "default" : {
        "regex" : "" ,
        "error" : ""
    },
    "name" : {
        "regex" : "" ,
        "error" : "姓名错误"
    },
    "age" : {
        "regex" : "/^\\d+/" ,
        "error" : "age error"
    }
}
```
加载规则:
ruleSetting() ;

设定失败
failSetting() ;

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
