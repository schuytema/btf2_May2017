<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_btf2_chat extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    // return a list of the channels in this project
    function get_existing_channels ($project_id)
    {
      $channel = array();
      $query = $this->db->query("SELECT DISTINCT Channel_Name FROM btf2_channels WHERE FK_Project_Id = $project_id ORDER BY Channel_Name");
      if ($query->num_rows())
      {
        $results = $query->result_array();
        foreach ($results as $row)
        {
          $channel[] = $row;
        }
      }
      return $channel;
    }
    // return a list of channels in this project by messages table. Empty channels will not be selected.
    // ** This function is not currently being used **
    function get_existing_channels_by_messages ($project_id)
    {
      $channel = array();
      $query = $this->db->query("SELECT DISTINCT Channel FROM btf2_chat_messages WHERE FK_Project_Id = $project_id ORDER BY Channel");
      if ($query->num_rows())
      {
        $results = $query->result_array();
        foreach ($results as $row)
        {
          $channel[] = $row;
        }
      }
      return $channel;
    }
    // return a list of project users in this channel.
    // ** This function is not currently being used since all users are members of each channel.
    function get_users_by_channel ($project_id, $channel)
    {
      $users = array();
      $query = $this->db->query("SELECT DISTINCT first_name FROM users JOIN btf2_chat_messages ON users.id = btf2_chat_messages.FK_User_Id WHERE btf2_chat_messages.FK_Project_Id = $project_id AND btf2_chat_messages.Channel = '$channel' ORDER BY first_name");
      if ($query->num_rows())
      {
        $results = $query->result_array();
        foreach ($results as $row)
        {
          $users[] = $row;
        }
      }
      return $users;
    }
    // return all stored messages in this channel
    function get_existing_chat ($project_id, $channel)
    {
    	$message = array();
      // Query to pull only messages in this project in the specified channel and sort them chronologically
		  $query = $this->db->order_by('PK_Chat_Message_Id')->get_where('btf2_chat_messages', array('FK_Project_Id' => $project_id, 'Channel' => "#" . $channel));
      // As long as messages are found, take the reulting array and place each message and its info into a new array where each element is one message/info pair.
      if ($query->num_rows())
    	{
    		$results = $query->result_array();
    		foreach($results AS $row)
    		{
    			$message[] = $row;
    		}
    	}
    	return $message;
    }
    // return all stored messages in this project in the private chat between $user_id1 and $user_id2
    function get_existing_private_chat($project_id, $user_id1, $user_id2)
    {
      $message = array();
      $user_name1 = '#-' . $user_id1;
      $user_name2 = '#-' . $user_id2;
      // Query to pull messages in this project sent from $user_id1 to $user_name2 and from $user_id2 to $user_name1
		  $query = $this->db->query("SELECT * FROM btf2_chat_messages WHERE (FK_User_Id = $user_id1 AND Channel = '$user_name2') OR (FK_User_Id = $user_id2 AND Channel = '$user_name1') ORDER BY PK_Chat_Message_Id");
      // As long as messages are found, take the reulting array and place each message and its info into a new array where each element is one message/info pair.
      if ($query->num_rows())
    	{
    		$results = $query->result_array();
    		foreach($results AS $row)
    		{
    			$message[] = $row;
    		}
    	}
    	return $message;
    }
    // add a newly submitted message to the database
    function new_message($message_id)
    {
      $input_message = $this->pcs_utility->db_clean(strip_tags($this->input->post('Message')));
      // This array provides all of the necessary variables for the database to accept the new message.
      // FK_User_Id, FK_Project_Id, and Channel are hidden variables from the form that the user cannot interact with.
      // Message, of course, is the message the user wishes to send.
      // Create_Date needs to be $CURRENT_TIMESTAMP in order for MySQL to properly insert the date. Doing this, however, will create a php error as $CURRENT_TIMESTAMP was never defined.
      // To get around this the @ is inserted before the array definition to ignore the php error reporting for this array.
      @$data = array(
            'FK_User_Id' => $this->pcs_utility->id_clean($this->input->post('FK_User_Id')),
            'FK_Project_Id' => $this->pcs_utility->id_clean($this->input->post('FK_Project_Id')),
            'Create_Date' => $CURRENT_TIMESTAMP,
            'Channel' => '#' . $this->pcs_utility->db_clean($this->input->post('Channel')),
            'Message' => $input_message);
      if ($input_message != NULL)
      { // insert the new message into the database.
        $this->db->insert('btf2_chat_messages', $data);
      }
    }

    function new_pm($user_id)
    {
      $pm = $this->pcs_utility->db_clean(strip_tags($this->input->post('Message')));

      $data = array(
        'FK_User_Id' => $this->pcs_utility->id_clean($this->input->post('FK_User_Id')),
        'Title' => $this->pcs_utility->id_clean($this->input->post('Title')),
			  'Recipient_Id' => $user_id,
        'Message' => $pm,
        'Is_Read' => 0);

        if ($pm != NULL)
        { // insert the new message into the database.
          $this->db->insert('btf2_private_messages', $data);
        }
    }
    // add a newly submitted channel to the database
    function new_channel($project_id)
    {
      $temp = $this->m_btf2_projects->get_project_users($project_id);
      foreach ($temp as $user)
      {
        $members[] = $user['FK_User_Id'];
      }
      // This array provides all of the necessary variables for the database to accept the new channel.
      // FK_User_Id is a hidden variable from the form that the user cannot interact with.
      // Channel_name is the name of the new channel.
      // Members is a list of channel members which is the same as all project members by default
      $data1 = array(
            'FK_Project_Id' => $this->pcs_utility->id_clean($this->input->post('FK_Project_Id')),
            'Channel_Name' => '#' . $this->pcs_utility->db_clean($this->input->post('Channel_Name')),
            'Members' => serialize($members)
      );
      // insert the description in the database as the first message on this channel
      @$data2 = array(
            'FK_User_Id' => $this->pcs_utility->id_clean($this->input->post('FK_User_Id')),
            'FK_Project_Id' => $this->pcs_utility->id_clean($this->input->post('FK_Project_Id')),
            'Create_Date' => $CURRENT_TIMESTAMP,
            'Channel' => '#' . $this->pcs_utility->db_clean($this->input->post('Channel_Name')),
            'Message' => $this->pcs_utility->db_clean($this->input->post('Description'))
      );
      $this->db->insert('btf2_channels', $data1);
      $this->db->insert('btf2_chat_messages', $data2);
      /*foreach ($members as $id)
      {
        notify each user (incomplete yet)
        $this->M_btf2_users->notify()
      }*/
      redirect(base_url().'main/chat_list/'.$project_id.'/-'.substr($data1['Channel_Name'], 1));
    }
    // convert the month from number form to the name of the month
    function convert_date($timestamp)
  	{
  		switch (substr($timestamp, 5, 2))
  		{
  			case '01':
  				return "January";
  				break;
  			case '02':
  				return "February";
  				break;
  			case '03':
  				return "March";
  				break;
  			case '04':
  				return "April";
  				break;
  			case '05':
  				return "May";
  				break;
  			case '06':
  				return "June";
  				break;
  			case '07':
  				return "July";
  				break;
  			case '08':
  				return "August";
  				break;
  			case '09':
  				return "September";
  				break;
  			case '10':
  				return "October";
  				break;
  			case '11':
  				return "November";
  				break;
  			case '12':
  				return "December";
  				break;
  		}
  	}
    function home_new_messages($user_ll, $name)
    {
      $target_user = '#-' . $name;
      $user_info = $this->ion_auth->user()->row();
      $query = $this->db->query("SELECT DISTINCT Channel, FK_Project_Id, FK_User_Id FROM btf2_chat_messages WHERE Create_Date >= '$user_ll' AND FK_User_Id != $user_info->id AND (Channel = '$target_user' OR Channel NOT LIKE '#-%')");
      $message = array();
      if ($query->num_rows())
    	{
    		$results = $query->result_array();
    		foreach($results AS $row)
    		{
          if ($this->m_btf2_projects->is_project_member($row['FK_Project_Id']))
          {
            $message[] = $row;
          }
    		}
        return $message;
    	}
    }

    function notify_chatters($project_id, $channel, $sender)
    {
      $project = $this->db->query("SELECT Name FROM btf2_projects WHERE PK_Project_Id = $project_id");
      $project = $project->result_array();
      //print_r($project);
      $project = $project[0]['Name'];
      if(substr($channel,0,1)=='-')//if private chat
      {
        $channel = substr($channel,1);// notify the other end
        $user = $this->m_btf2_users->get_user_info_from_id($channel);
        $user_first_name = $user['first_name'];
        $user_id = $this->db->query("SELECT DISTINCT id FROM users WHERE first_name = '$user_first_name'");
        $user_id = $user_id->result_array();
        $user_id = $user_id[0]['id'];
        $sender_name = $this->db->query("SELECT first_name FROM users WHERE id = $sender");
        $sender_name = $sender_name->result_array();
        $sender_name = $sender_name[0]['first_name'];
        $message = 'From '.$sender_name.' in '.$project.' project'."\r\n";
        $message = $message.'Click the link to view:'."\r\n";
        $message = $message.base_url().'main/chat_list/'.$project_id.'/--'.$sender;
        $subject = "New Private Message";
        $this->m_btf2_users->notify($user_id, $message, $subject);
      }
      else { // if a channel
        $channel_name = "#".$channel;
        //echo $channel." ".$channel_name." ";
        $query = $this->db->query("SELECT Members FROM btf2_channels WHERE Channel_Name = '$channel_name' AND FK_Project_Id = $project_id");
        $members = $query->result_array(); // notify all members of the channel
        //print_r($members[0]['Members']); a:6:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"6";}
        // s is the length of the string...
        $members = unserialize($members[0]['Members']);
        $message = 'In '.$channel.' channel in '.$project.' project'."\r\n";
        $message = $message.'Click the link to view:'."\r\n";
        $message = $message.base_url().'main/chat_list/'.$project_id.'/-'.$channel;
        $subject = "New Message";
        foreach ($members as $member)
        {
          if($sender!= $member)
          {
            //echo $member."  ";
            $this->m_btf2_users->notify($member, $message, $subject);
          }
        }
      }

    }

    function get_global_pms($user_id)
    {
      $query = $this->db->query("SELECT * FROM btf2_private_messages WHERE Recipient_Id = '$user_id' ORDER BY Create_Date DESC");

      if ($query->num_rows())
    	{
    		$results = $query->result_array();
    		foreach($results AS $row)
    		{
            $pm[] = $row;
    		}
        return $pm;
    	}
    }

    function get_global_pm_info($message_id)
    {
      $query = $this->db->query("SELECT * FROM btf2_private_messages WHERE PK_Private_Message_Id = '$message_id'");

      if ($query->num_rows())
    	{
    		$results = $query->result_array();
    		foreach($results AS $row)
    		{
            $minfo = $row;
    		}
        return $minfo;
    	}
    }
}
/* End of file m_btf2_users.php */
/* Location: ./application/models/m_btf2_users.php */
?>
