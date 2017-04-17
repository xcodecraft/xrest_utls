<?
namespace XCC\utls\v2 ;

use XCC\utls\v1\XSql;

class XSqlV2 extends XSql
{
    static public function where($data)
    {
        $items = static::getData($data) ;
        $sql   = "" ;
        $order = "" ;
        $limit = "" ;
        $values = array();
        foreach($items as $key => $val){
            if (is_null($val)) continue ;
            list($item,$value,$join)  = static::parse($key,$val) ;
            if (static::$limit == $key){
                $values   = array_merge($values,$value);
                $limit = $item ;
            }
            else if (static::$order == $key){
                $order = $item ;
            }else{
                $values   = array_merge($values,$value);
                if($join && !empty($sql)){
                    $sql .= " and $item";
                }else{
                    $sql .= " $item" ;
                }
            }
        }
        $sql =  "$sql $order $limit" ;
        return array(trim($sql),$values) ;
    }

    static public function parse($key,$line)
    {
        $tag  = array();
        $tag['['] = ">=" ;
        $tag['('] = ">" ;
        $tag[')'] = "<" ;
        $tag[']'] = "<=" ;

        $sql    = "" ;
        $values = array();
        $rule = '/^([\(\[])([^,]+)\,([^\,]+)([\)\]])$/';//(1,2) [2016-01-02,2017-09-08]
        if(preg_match($rule, $line, $matches)){
            $begin  = trim($matches[1]) ;
            $first  = trim($matches[2]) ;
            $second = trim($matches[3]) ;
            $end    = trim($matches[4]) ;
            $bTag   = $tag[$begin] ;
            $eTag   = $tag[$end] ;
            if (static::$limit == $key){
                $sql        = "limit ".intval($first).",".intval($second);
                $join       = false;
            }else{
                $sql    = "$key $bTag ? and $key $eTag ?";
                $values[] = $first;
                $values[] = $second;
                $join   = true;
            }
            return array($sql,$values,$join);
        }

        $rule = '/^\{(.*)\}$/'; //in
        if( preg_match($rule, $line, $matches)){
            $sql        = "$key in (?)";
            $values[]   = $matches[1] ;
            $join       = true;
            return array($sql,$values,$join);
        }
        $rule = '/^([>=<]{1,2})\s+(\S{1,})$/'; //in
        if( preg_match($rule, $line, $matches)){
            $symbol     = $matches[1] ;
            $sql        = "$key $symbol ?";
            $values[]   = $matches[2] ;
            $join       = true;
            return array($sql,$values,$join);
        }

        $rule = '/^like\((\S{1,})\)$/'; //in
        if( preg_match($rule, $line, $matches)){
            $sql        = "$key like ?";
            $values[]   = str_replace('*','%',$matches[1]) ;
            $join       = true;
            return array($sql,$values,$join);
        }

        $rule = '/^desc\((\S{1,})\)$/'; //in
        if( preg_match($rule, $line, $matches)){
            $value      = str_replace('*','%',$matches[1]) ;
            $sql        = "order by $value DESC";
            $join       = false;
            return array($sql,$values,$join);
        }

        $rule = '/^asc\((\S{1,})\)$/'; //in
        if( preg_match($rule, $line, $matches)){
            $value      = str_replace('*','%',$matches[1]) ;
            $sql        = "order by $value ASC";
            $join       = false;
            return array($sql,$values,$join);
        }
        $rule = '/^(is.*NULL)$/'; //in
        if( preg_match($rule, $line, $matches))
        {
            $sql        = "$key $matches[1]";
            $join       = true;
            return array($sql,$values,$join);
        }
        $sql        = "$key = ?";
        $values[]   = $line;
        $join       = true;
        return array($sql,$values,$join);
    }
}
