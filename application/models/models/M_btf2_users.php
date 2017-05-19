<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_btf2_users extends CI_Model{
    
    function __construct(){
        parent::__construct();
    }
    
    function valid_user($key)
    {
        $valid = false;
        $results = $this->db->get_where('btf2_users',array('User_Key' => $key));
        if ($results->num_rows()) 
        {
        	$valid = true;
        }
        return $valid;
    }
    
    function get_user_info($key)
    {
    	$query = $this->db->get_where('btf2_users',array('User_Key' => $key));
    	return $query->result_array();
    }

    
        
}
/* End of file m_btf2_users.php */
/* Location: ./application/models/m_btf2_user.php */