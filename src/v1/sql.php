<?
namespace XCC\utls\v1 ;
class XSql
{
    static $order = "order" ;
    static $limit = "limit" ;

    static public function getData($data)
    {
        $ret    = array();
        if (is_object($data))
        {
            return  get_object_vars($data) ;
        }
        else if(is_array($data))
        {
            return  $data ;
        }
        throw new \RuntimeException("XSql not support " .  get_class($data));
    }

    static public function where($data)
    {
        $items = static::getData($data) ;
        $sql   = "" ;
        $order = "" ;
        $limit = "" ;
        foreach($items as $key => $val)
        {
            if (is_null($val)) continue ;
            list($item,$join)  = static::parse($key,$val) ;
            if (static::$limit == $key)
            {
                $limit = $item ;
            }
            else if (static::$order == $key)
            {
                $order = $item ;
            }
            else
            {
                if($join && !empty($sql))
                {
                    $sql .= " and $item";
                }
                else
                {
                    $sql .= " $item" ;
                }
            }
        }
        $sql =  "$sql $order $limit" ;
        return trim($sql) ;

    }
    static public function isDateTime($val)
    {
         $val = trim($val) ;
        $date = \DateTime::createFromFormat("Y-m-d H:i:s",$val) ;
        if($date != false) { return true; }
        $date = \DateTime::createFromFormat('Y-m-d',$val) ;
        if($date != false) { return true; }
        return false ;
    }
    static public function parse($key,$line)
    {
        $tag  = array();
        $tag['['] = ">=" ;
        $tag['('] = ">" ;
        $tag[')'] = "<" ;
        $tag[']'] = "<=" ;

        $sql  = "" ;
        $rule = '/^([\(\[])([^,]+)\,([^\,]+)([\)\]])$/';//(1,2) [2016-01-02,2017-09-08]
        if( preg_match($rule, $line, $matches))
        {

            $begin  = trim($matches[1]) ;
            $first  = trim($matches[2]) ;
            $second = trim($matches[3]) ;
            $end    = trim($matches[4]) ;
            $bTag   = $tag[$begin] ;
            $eTag   = $tag[$end] ;
            if (static::$limit == $key)
            {
                return   [ "limit $first, $second", false ] ;
            }
            if(static::isDateTime($first) &&  static::isDateTime($second))
            {
                return   [ "$key $bTag '$first' and $key $eTag '$second'" , true ] ;

            }
            return   [ "$key $bTag $first and $key $eTag $second" , true ] ;
        }

        $rule = '/^\{(.*)\}$/'; //in
        if( preg_match($rule, $line, $matches))
        {

            $data  = $matches[1] ;
            return   [ "$key in ($data)", true ] ;
        }

        $rule = '/^([>=<]{1,2})\s+(\S{1,})$/'; //in
        if( preg_match($rule, $line, $matches))
        {

            $symbol = $matches[1] ;
            $value  = $matches[2] ;
            return   ["$key $symbol $value" ,true] ;
        }

        $rule = '/^like\((\S{1,})\)$/'; //in
        if( preg_match($rule, $line, $matches))
        {

            $value  = $matches[1] ;
            $value  = str_replace('*','%',$value) ;
            return   ["$key like $value",true]  ;
        }

        $rule = '/^desc\((\S{1,})\)$/'; //in
        if( preg_match($rule, $line, $matches))
        {

            $value  = $matches[1] ;
            $value  = str_replace('*','%',$value) ;
            return   [ "order by $value DESC" ,false] ;
        }

        $rule = '/^asc\((\S{1,})\)$/'; //in
        if( preg_match($rule, $line, $matches))
        {

            $value  = $matches[1] ;
            $value  = str_replace('*','%',$value) ;
            return   [ "order by $value ASC" , false ] ;
        }
        $rule = '/^(is.*NULL)$/'; //in
        if( preg_match($rule, $line, $matches))
        {
            $value  = $matches[1] ;
            return   [ "$key $matches[1]", true ] ;
        }
       if (is_string($line))
        {
            return  [ "$key = '$line'" , true ] ;
        }
        else
        {
            return  [ "$key = $line" , true ] ;
        }


    }

}
