<?php

use XCC\utls\v1\XInput ;
use XCC\utls\v2\XSql ;

class CondTestV2 extends PHPUnit_Framework_TestCase
{
    public function testPase()
    {

        list($sql,$values,$join) = XSql::parse("num","[1,3]");
        $this->assertEquals($sql,'num >= ? and num <= ?');
        $this->assertEquals($values[0],'1');
        $this->assertEquals($values[1],'3');
        $this->assertEquals($join,true);

        list($sql,$values,$join) = XSql::parse("num","(1,3)");
        $this->assertEquals($sql,'num > ? and num < ?');
        $this->assertEquals($values[0],'1');
        $this->assertEquals($values[1],'3');
        $this->assertEquals($join,true);

        list($sql,$values,$join) = XSql::parse("num","[1,3)");
        $this->assertEquals($sql,'num >= ? and num < ?');
        $this->assertEquals($values[0],'1');
        $this->assertEquals($values[1],'3');
        $this->assertEquals($join,true);

        list($sql,$values,$join) = XSql::parse("num","(1,3]");
        $this->assertEquals($sql,'num > ? and num <= ?');
        $this->assertEquals($values[0],'1');
        $this->assertEquals($values[1],'3');
        $this->assertEquals($join,true);

        list($sql,$values,$join) = XSql::parse("num","{1,2,3}");
        $this->assertEquals($sql,"num in (?,?,?)");
        $this->assertEquals($values,[1,2,3]);
        $this->assertEquals($join,true);

        list($sql,$values,$join) = XSql::parse("key","{'a','b','c'}");
        $this->assertEquals($sql,"key in (?,?,?)");
        $this->assertEquals($values,["a","b","c"]);
        $this->assertEquals($join,true);

        list($sql,$values,$join) = XSql::parse("num",">= 3");
        $this->assertEquals($sql,'num >= ?');
        $this->assertEquals($values[0],'3');
        $this->assertEquals($join,true);

        list($sql,$values,$join) = XSql::parse("key","like('abc*')");
        $this->assertEquals($sql,"key like ?");
        $this->assertEquals($values[0],"abc%");
        $this->assertEquals($join,true);

        list($sql,$values,$join) = XSql::parse("limit","[0,2]");
        $this->assertEquals($sql,"limit 0,2");
        $this->assertEquals($join,false);

        list($sql,$values,$join) = XSql::parse("order","desc(name)");
        $this->assertEquals($sql,"order by name DESC");
        $this->assertEquals($values,array());
        $this->assertEquals($join,false);

        list($sql,$values,$join) = XSql::parse("order","desc(name,age)");
        $this->assertEquals($sql,"order by name,age DESC");
        $this->assertEquals($values,array());
        $this->assertEquals($join,false);

        list($sql,$values,$join) = XSql::parse("order","asc(name)");
        $this->assertEquals($sql,"order by name ASC");
        $this->assertEquals($values,array());
        $this->assertEquals($join,false);
    }

    public function testA()
    {
        $qCont     = new QueryDTO;
        $qCont->id = 100 ;
        list($sql,$values)  = XSql::where($qCont) ;
        $this->assertEquals($sql, "id = ?") ;
        $this->assertEquals($values[0], "100") ;

        $qCont     = new QueryDTO;
        $qCont->id = "abc" ;
        list($sql,$values)  = XSql::where($qCont) ;
        $this->assertEquals($sql, "id = ?") ;
        $this->assertEquals($values[0], "abc") ;
    }

    public function testB()
    {
        $qCont             = new QueryDTO;
        $qCont->id         = 100 ;
        $qCont->limit      = '[0, 20]';
        $qCont->createtime = '[2016-2-1, 2016-3-1]';
        $qCont->order      = 'desc(id)';
        $qCont->pID        = 'is not NULL';
        list($sql,$values) = XSql::where($qCont) ;

        $expect = "id = ? and createtime >= ? and createtime <= ? and pID is not NULL order by id DESC limit 0,20" ;
        $this->assertEquals($sql, $expect) ;
        $this->assertEquals($values[0], '100') ;
        $this->assertEquals($values[1], "2016-2-1") ;
        $this->assertEquals($values[2], "2016-3-1") ;
    }

    public function testOr()
    {
        $qCont             = new QueryDTO;
        $qCont->id         = 100 ;
        $qCont->limit      = '[0, 20]';
        $qCont->createtime = '[2016-2-1, 2016-3-1]|[2016-8-1, 2016-8-2]|[2016-9-1, 2016-9-2]';
        $qCont->order      = 'desc(id)';
        $qCont->pID        = 'is not NULL';
        list($sql,$values) = XSql::where($qCont) ;

        $expect = "id = ? and ((createtime >= ? and createtime <= ?) or (createtime >= ? and createtime <= ?) or (createtime >= ? and createtime <= ?)) and pID is not NULL order by id DESC limit 0,20" ;
        $this->assertEquals($sql, $expect) ;
        $this->assertEquals($values[0], '100') ;
        $this->assertEquals($values[1], "2016-2-1");
        $this->assertEquals($values[2], "2016-3-1");
        $this->assertEquals($values[3], "2016-8-1");
        $this->assertEquals($values[4], "2016-8-2");
        $this->assertEquals($values[5], "2016-9-1");
        $this->assertEquals($values[6], "2016-9-2");
    }

    public function testUse()
    {
        $_GET['id']         = 100 ;
        $_GET['limit']      = '[0,20] ';
        $_GET['createtime'] = '[2016-2-1, 2016-3-1]';
        $_GET['order']      = 'desc(id)' ;

        $data = XInput::safeDict($_GET,'id,limit,createtime,order') ;
        list($sql,$values)  = XSql::where($data);
        $sql  = "select * from user where "  . $sql . ";" ;
        echo "\n$sql" ;

    }
}
