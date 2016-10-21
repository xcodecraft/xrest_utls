# ExceptionLang

## 约定
``` php
static  $enFmt   //英文
static  $zhFmt   //中文
static  $stCode  //状态码, XRuntimeException, XLogicException 需要

```
## useage

### 定义异常
``` php
use XCC\utls\v1\ExceptionLang ;
class USER_LOGIN_FAIL  extends XUserInputException
{
    static public $enFmt  = "user %s login  failed ";
    static public $zhFmt  = "用户(%s) 录用 失败";
    use ExceptionLang ;
}

class USER_LOGIN_FAIL  extends XUserInputException
{
    static public $enFmt  = "user %s login  failed ";
    static public $zhFmt  = "用户(%s) 录用 失败";
    use ExceptionLang ;
}

```
### 调用
``` php
        //setting
        use XCC\utls\v1\ExceptionLang ;
        ExceptionLang::$useLang = LANG_EN ;
        //call
        throw new USER_LOGIN_ERROR("1380013800") ;
```
