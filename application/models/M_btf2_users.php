<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_btf2_users extends CI_Model{

    function __construct(){
        parent::__construct();
    }

    function valid_user($key)
    {
        $valid = false;
        $query = $this->db->get_where('users',array('User_Key' => $key));
        if ($query->num_rows())
        {
        	$valid = true;
        }
        return $valid;
    }

    function get_user_info($key)
    {
    	$query = $this->db->get_where('users', array('User_Key' => $key));
    	return $query->result_array();
    }

    function get_user_info_from_username($username)
  	{
  		$query = $this->db->get_where('users', array('username' => $username));
  		if ($query->num_rows())
  		{
  			$user_array = $query->result_array();
  			return $user_array[0];
  		}
  		return false;
  	}

    function get_user_info_from_id($user_id)
    {
    	$query = $this->db->get_where('users', array('id' => $user_id));
    	$user_array = $query->result_array();
    	return $user_array[0];
    }

    function notify($user_id, $text, $subject)
    {
      $query = $this->db->get_where('users', array('id' => $user_id));
      $result = NULL;
      if($query->num_rows())
      {
        $result = $query->result_array();
      }
      if($result[0]['notification']=="phone")
      {
        if(strpos($text, "Click the link to view") !== false)
        {
          $text = substr($text, 0, strpos($text, "Click")-2);
        }
        $phone = $result[0]['phone'];
        $carrier = $result[0]['phone_carrier'];
        if($phone != "0" || $phone != NULL)
        {
          $email = $phone.'@'.$this->m_btf2_users->carrier_email($carrier);
          //$subject = "BTF2 Notifications";
          $from = 'BTF Notification';//."\r\n";
          mail($email, $subject, $text, $from);
        }
      } else if($result[0]['notification']=="email") {
        $email = $result[0]['email'];
        //$subject = "BTF2 Notifications";
        $from = 'Content-type: text/plain; charset=iso-8859-1'."\r\n".'From: BTF Notification'."\r\n";
        mail($email, $subject, $text, $from);
      }
    }

    function carrier_email($carrier)
    {
      /*
      Complete list on: http://www.emailtextmessages.com/
      T-Mobile: $phone@tmomail.net
      Boost: $phone@myboostmobile.com
      Virgin Mobile: $phone@vmobl.com
      Cingular: $phone@cingularme.com
      AT&T: $phone@txt.att.net
      Sprint: $phone@messaging.sprintpcs.com
      Verizon: $phone@vtext.com
      Nextel: $phone@messaging.nextel.com
      US Cellular: $phone@email.uscc.net
      */
      switch ($carrier)
      {
        case 'ATT':
          return 'txt.att.net';
          break;
        case 'Boost':
          return 'myboostmobile.com';
          break;
        case 'Cingular':
          return 'cingularme.com';
          break;
        case 'Nextel':
          return 'messaging.nextel.com';
          break;
        case 'Sprint':
          return 'messaging.sprintpcs.com';
          break;
        case 'T-Mobile':
          return 'tmomail.net';
          break;
        case 'US Cellular':
          return 'email.uscc.net';
          break;
        case 'Verizon':
          return 'vtext.com';
          break;
        case 'Virgin Mobile':
          return 'vmobl.com';
          break;
      }
    }

    function update_user_level($user_id)
    {
      $data = array(
        'user_level' => 1
      );

      $this->db->where('id', $user_id);
      $this->db->update('users', $data);
    }

    function user_level($user_id)
    {
      $query = $this->db->query("SELECT user_level FROM users WHERE id = $user_id");
      if($query->num_rows())
      {
        return $query->result_array()[0]['user_level'];
        /*switch ($query)
        {
          case 0:
            return 'Free';
            break;
          case 1:
            return 'Mid-level';
            break;
          case 2:
            return 'Premium';
            break;
        }*/
      }
      return 0;
    }

    function convert_level_to_string($user_level)
    {
      switch ($user_level)
      {
        case 0:
          return 'Free';
          break;
        case 1:
          return 'Mid-level';
          break;
        case 2:
          return 'Premium';
          break;
      }
    }

    function increment_project_count()
    {
      $user_info = $this->ion_auth->user()->row();
      $project_count = $user_info->projects_owned + 1;
      $this->db->query("UPDATE users SET projects_owned = $project_count WHERE id = $user_info->id");
    }

    function decrement_project_count()
    {
      $user_info = $this->ion_auth->user()->row();
      $project_count = $user_info->projects_owned - 1;
      $this->db->query("UPDATE users SET projects_owned = $project_count WHERE id = $user_info->id");
    }
}
/* End of file m_btf2_users.php */
/* Location: ./application/models/m_btf2_users.php */
