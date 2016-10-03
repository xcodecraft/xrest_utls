<?
namespace XCC\utls\v1 ;
class XSql
{
    static $order = "order" ;
    static $limit = "limit" ; 
    static public function where($dto)
    {
         $items  = get_object_vars($dto) ;
         $sql    = "" ;
         $andTag = "" ;
         foreach($items as $key => $val)
         {
             if (is_null($val)) continue ;
             $item    = static::parse($key,$val) ;
             $sql    .= "$andTag $item ";
             $andTag  = "and" ;


         }
         return trim($sql) ;

    }
    static public function parse($key,$line)
    {
        $tag  = array();
        $tag['['] = ">=" ;
        $tag['('] = ">" ;
        $tag[')'] = "<" ;
        $tag[']'] = "<=" ;

        $sql  = "" ;
        $rule = '/^([\(\[])([^,]){1,},([^,]){1,}([\)\]])$/';//(1,2) [2016-01-02,2017-09-08]
        if( preg_match($rule, $line, $matches))
        {

            $begin  = $matches[1] ;
            $first  = $matches[2] ;
            $second = $matches[3] ;
            $end    = $matches[4] ;
            $bTag   = $tag[$begin] ;
            $eTag   = $tag[$end] ;
            if (static::$limit == $key)
            {
                return   "limit $begin,$end" ;
            }
            return   "$key $bTag $first and $key $eTag $second" ;
        }

        $rule = '/^\{(.*)\}$/'; //in
        if( preg_match($rule, $line, $matches))
        {

            $data  = $matches[1] ;
            return   "$key in ($data)" ;
        }

        $rule = '/^([>=<]{1,2})\s+(\S{1,})$/'; //in
        if( preg_match($rule, $line, $matches))
        {

            $symbol = $matches[1] ;
            $value  = $matches[2] ;
            return   "$key $symbol $value"  ;
        }

        $rule = '/^like\((\S{1,})\)$/'; //in
        if( preg_match($rule, $line, $matches))
        {

            $value  = $matches[1] ;
            $value  = str_replace('*','%',$value) ;
            return   "$key like $value"  ;
        }

        $rule = '/^desc\((\S{1,})\)$/'; //in
        if( preg_match($rule, $line, $matches))
        {

            $value  = $matches[1] ;
            $value  = str_replace('*','%',$value) ;
            return   "order by $value DESC"  ;
        }
        
        $rule = '/^asc\((\S{1,})\)$/'; //in
        if( preg_match($rule, $line, $matches))
        {

            $value  = $matches[1] ;
            $value  = str_replace('*','%',$value) ;
            return   "order by $value ASC"  ;
        }
        
        return "$key = $line" ;

    }

}
