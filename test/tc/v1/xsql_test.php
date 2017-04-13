<?php

use XCC\utls\v1\XSql ;
use XCC\utls\v1\XInput ;

class QueryDTO
{
    public $id;
    public $phone;
    public $createtime;
    public $giftpackID;
    public $money;
}



class CondTest extends PHPUnit_Framework_TestCase
{
    public function testPase()
    {

        list($sql,$join) = XSql::parse("num","[1,3]");
        $this->assertEquals($sql,'num >= 1 and num <= 3');

        list($sql,$join) = XSql::parse("num","(1,3)");
        $this->assertEquals($sql,'num > 1 and num < 3');

        list($sql,$join) = XSql::parse("num","[1,3)");
        $this->assertEquals($sql,'num >= 1 and num < 3');

        list($sql,$join) = XSql::parse("num","(1,3]");
        $this->assertEquals($sql,'num > 1 and num <= 3');


        list($sql,$join) = XSql::parse("num","{1,2,3}");
        $this->assertEquals($sql,"num in (1,2,3)");
        list($sql,$join) = XSql::parse("key","{'a','b','c'}");
        $this->assertEquals($sql,"key in ('a','b','c')");

        list($sql,$join) = XSql::parse("num",">= 3");
        $this->assertEquals($sql,'num >= 3');

        list($sql,$join) = XSql::parse("key","like('abc*')");
        $this->assertEquals($sql,"key like 'abc%'");

        list($sql,$join) = XSql::parse("limit","[0,2]");
        $this->assertEquals($sql,"limit 0, 2");


        list($sql,$join) = XSql::parse("order","desc(name)");
        $this->assertEquals($sql,"order by name DESC");

        list($sql,$join) = XSql::parse("order","desc(name,age)");
        $this->assertEquals($sql,"order by name,age DESC");

        list($sql,$join) = XSql::parse("order","asc(name)");
        $this->assertEquals($sql,"order by name ASC");
    }

    public function testA()
    {
        $qCont     = new QueryDTO;
        $qCont->id = 100 ;
        $sql       = XSql::where($qCont) ;
        $this->assertEquals($sql, "id = '100'") ;

        $qCont     = new QueryDTO;
        $qCont->id = "abc" ;
        $sql       = XSql::where($qCont) ;
        $this->assertEquals($sql, "id = 'abc'") ;

    }

    public function testB()
    {
        $qCont             = new QueryDTO;
        $qCont->id         = 100 ;
        $qCont->limit      = '[0, 20]';
        $qCont->createtime = '[2016-2-1, 2016-3-1]';
        $qCont->order      = 'desc(id)';
        $qCont->pID        = 'is not NULL';
        $sql               = XSql::where($qCont) ;

        $expect = "id = '100' and createtime >= '2016-2-1' and createtime <= '2016-3-1' and pID is not NULL order by id DESC limit 0, 20" ;
        $this->assertEquals($sql, $expect) ;

    }
    public function testUse()
    {
        $_GET['id']         = 100 ;
        $_GET['limit']      = '[0,20] ';
        $_GET['createtime'] = '[2016-2-1, 2016-3-1]';
        $_GET['order']      = 'desc(id)' ;

        $data = XInput::safeDict($_GET,'id,limit,createtime,order') ;
        $sql  = "select * from user where "  . XSql::where($data) . ";" ;
        echo "\n$sql" ;

    }
}

class SQLTest extends PHPUnit_Framework_TestCase
{
    public function testDate()
    {
        $this->assertTrue(XSql::isDateTime('2009-02-15 15:16:17') ) ;
        $this->assertTrue(XSql::isDateTime("2016-01-01 10:59:59") ) ;
        $this->assertTrue(XSql::isDateTime("2016-1-1 10:59:59") ) ;
        $this->assertTrue(XSql::isDateTime("2016-01-01") ) ;
        $this->assertTrue(XSql::isDateTime("2016-1-1") ) ;

    }

}

