<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pcs_utility
{

    function get_content_array($table, $key, $record_id)
    {
        $CI =& get_instance();
        $query = $CI->db->query("SELECT * FROM $table WHERE $key = '$record_id'");
        $data = null;
        foreach ($query->result_array() as $row)
        {
            $data = $row;
        }
        return $data;
    }
    

    
    
    function html_clean($web)
    {
        if (substr($web, 0, 7) == 'http://')
        {
            $cleaned_web = substr($web, 7);
        } else {
            $cleaned_web = $web;
        }
        return $cleaned_web;
    }
    
    function escape_quotes($str)
    {
    	//single quotes 
        $cleaned = str_replace("'","\'",$str);
        //double quotes 
        $cleaned = str_replace('"','\"',$cleaned);
        return $cleaned;
    }
    
    // decrypt
    function decrypt($data)
    {
        $charset = array_flip($this->scrambled_chars());
        $charset = array_reverse($charset, true);
        $data = strtr($data, $charset);
        unset($charset);
        return $data;
    }

    function scrambled_chars()
    {
        // 3 different symbols (or combinations) for obfuscation
        // these should not appear within the original text
        $sym = array('√Ç¬∂', '~#&', '^`k', '$%<|');
        foreach(range('a','z') as $key=>$val)
        $chars[$val] = str_repeat($sym[0],($key + 1)).$sym[1];
        $chars[' '] = $sym[2];
        $chars['"'] = $sym[3];
        unset($sym);
        return $chars;
    }

    // encrypt
    function encrypt($data)
    {
        $data = strtr(strtolower($data), $this->scrambled_chars());
        return $data;
    }
    
    function random_string()
    {
        $character_set_array = array();
        $character_set_array[] = array('count' => 5, 'characters' => 'abcdefghijkmnpqrstuvwxyz');
        $character_set_array[] = array('count' => 2, 'characters' => 'ABCDEFGHIJKMNQRSTUVWXYZ');
        $character_set_array[] = array('count' => 1, 'characters' => '23456789');
        $temp_array = array();
        foreach ($character_set_array as $character_set) {
            for ($i = 0; $i < $character_set['count']; $i++) {
                $temp_array[] = $character_set['characters'][rand(0, strlen($character_set['characters']) - 1)];
            }
        }
        shuffle($temp_array);
        return implode('', $temp_array);
    }
    
    /**
    * ID Clean
    * Determines if given ID is an integer and returns ID to a specific length.
    * Function will render any non-zero-length string into an integer.
    * In other words, it will convert hexadecmial and floating point numbers into integers.
    * Function helps prevent buffer overflow attacks by limiting size (ex: an id passed through the
    * URI has been hacked)
    *
    * For more info, see p.270 in Professional Codeigniter
    * @access	public
    * @param	int
    * @param	int
    * @return	int
    */
    function id_clean($id, $size = 10){
        return intval(substr($id,0,$size));
    }
    
    /**
    * DB Clean
    * Function will return a prepared string (cleansed and set to appropriate max length) for safe database insertion
    * Note: If size is NULL, prepared string will not have limitations regarding length. This will be useful for datatypes
    * such as vachar(max).
    * For more info, see p.270 in Professional Codeigniter
    * @access	public
    * @param	int
    * @param	int
    * @return	int
    */
    function db_clean($string, $size = NULL, $isNullable = TRUE){
        $CI =& get_instance();
        //if NULL values allowed, check for variable existence
        if($isNullable){
            $string = (!empty($string)) ? $string : NULL;
        }

        if(!empty($string)){
            if(!empty($size)){
                $string = substr($string,0,$size);
            }
            //load security library
            $string = $CI->security->xss_clean(trim($string));
        }
        return $string;
    }

}