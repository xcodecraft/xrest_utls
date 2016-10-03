<?php

use XCC\utls\v1\XSql ;

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

        $sql = XSql::parse("num","[1,3]");
        $this->assertEquals($sql,'num >= 1 and num <= 3');

        $sql = XSql::parse("num","(1,3)");
        $this->assertEquals($sql,'num > 1 and num < 3');

        $sql = XSql::parse("num","[1,3)");
        $this->assertEquals($sql,'num >= 1 and num < 3');

        $sql = XSql::parse("num","(1,3]");
        $this->assertEquals($sql,'num > 1 and num <= 3');


        $sql = XSql::parse("num","{1,2,3}");
        $this->assertEquals($sql,"num in (1,2,3)");
        $sql = XSql::parse("key","{'a','b','c'}");
        $this->assertEquals($sql,"key in ('a','b','c')");

        $sql = XSql::parse("num",">= 3");
        $this->assertEquals($sql,'num >= 3');

        $sql = XSql::parse("key","like('abc*')");
        $this->assertEquals($sql,"key like 'abc%'");

        $sql = XSql::parse("limit","[0,2]");
        $this->assertEquals($sql,"limit 0,2");


        $sql = XSql::parse("order","desc(name)");
        $this->assertEquals($sql,"order by name desc");
        
        $sql = XSql::parse("order","asc(name)");
        $this->assertEquals($sql,"order by name asc");
    }

    public function testA()
    {
        $qCont     = new QueryDTO;
        $qCont->id = 100 ;
        $sql       = XSql::where($qCont) ;
        $this->assertEquals($sql, "id = 100") ;


    }

    public function testB()
    {
        $qCont             = new QueryDTO;
        $qCont->id         = 100 ;
        $qCont->createtime = '[2016-2-1, 2016-3-1]';
        $sql               = XSql::where($qCont) ;

        $expect = "id = 100 and createtime >= 1 and createtime <= 1" ;
        $this->assertEquals($sql, $expect) ;

    }
}

