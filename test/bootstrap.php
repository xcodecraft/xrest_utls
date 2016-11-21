<?
require_once "pylon/pylon.php" ;

XSetting::$logMode   = XSetting::LOG_DEBUG_MODE ;
XSetting::$prjName   = "xrest_utls" ;
XSetting::$logTag    = "xrest_utls" ;
XSetting::$runPath   = XSetting::ensureEnv("RUN_PATH") ;
XSetting::$bootstrap = "pylonstrap.php" ;
XSetting::$bootstrap = XSetting::ensureEnv("PRJ_ROOT") . "/test/pylonstrap.php" ;
date_default_timezone_set('PRC');
//if(XEnv::get('DEBUG')==true)
//        XHttpCaller::failDebug(true) ;
XPylon::useEnv() ;
