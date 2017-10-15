<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_btf2_projects extends CI_Model{

    function __construct(){
        parent::__construct();
    }

    function new_project ($project_id = 0)
    {
      $Project_Pages = $this->input->post('Available_Pages');
      if($Project_Pages == null)
      {
        $Project_Pages = array();
      }
    	$data = array(
    	    'FK_User_Id' => $this->pcs_utility->id_clean($this->input->post('FK_User_Id')),
    	    'Name' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Name'))),
    	    'Description' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Description'))),
    	    'Status' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Status'))),
          'Project_Pages' => serialize($Project_Pages)
    	);

    	if($project_id == 0)
    	{
        $user_info = $this->ion_auth->user()->row();
    		$data['Invite_Key'] = $this->pcs_utility->random_string();
  	    $this->db->insert('btf2_projects', $data);
  	    $new_project_id = $this->db->insert_id();
  	    //add creator as project memeber
  	    $data_member = array(
  	        'FK_Project_Id' => $new_project_id,
  	        'FK_User_Id' => $user_info->id,
  	        'Is_Admin' => 'yes',
  	    );

  	    $this->db->insert('btf2_project_members', $data_member);
  	    //create general channel
  	    $data_channel = array(
  	        'FK_Project_Id' => $new_project_id,
  	        'Channel_Name' => '#general',
  	        'Members' => 'a:1:{i:0;s:1:"'.$user_info->id.'";}'
  	    );
  	    $this->db->insert('btf2_channels', $data_channel);
        $intro_task = array(
          'FK_Project_Id' => $new_project_id,
          'FK_User_Id' => $user_info->id,
          'Assigned_To' => 'Unassigned',
          'Group_Name' => 'General',
          'Task_Name' => 'Intro to Task Management (Click me!)',
          'Status' => 'Complete',
          'Description' => '  Welcome to the task management page! From here you can create tasks and assign them to a team member, as well as track its progress and any due dates it may have. This task gives you an idea of the format for a task. New task groups can be added on the "Create Task" page, and groups will automtically be deleted if no tasks remain in a group (tasks can be reassigned to a different group.)

            If you are the admin of the project, you will have the ability to place tasks in the "Archived" group. This group is for tasks that have been completed or otherwise are no longer active and serves as an easy way to track the history of the project. Tasks should only be placed in the archive group if any work being done on them is completed as placing tasks in this group will strip any assigned users from the task, making further additions to the resource tracker for that task impossible unless it is done by an admin.'
        );
        $this->db->insert('btf2_tasks', $intro_task);
        $path = 'project_'.$new_project_id.'/';
        S3::putBucket('btf2_project_'.$new_project_id);
        $this->m_btf2_users->increment_project_count();
    	}

    	else {
    	    $this->db->where('PK_Project_Id', $project_id);
    	    $this->db->update('btf2_projects', $data);
    	}
    }


    function get_project_info($project_id)
    {
    	$project_info = array();
    	$query = $this->db->get_where('btf2_projects', array('PK_Project_Id' => $project_id));
    	if ($query->num_rows())
    	{
    		$results = $query->result_array();
    		foreach($results AS $row)
    		{
    			$project_info = $row;
    		}
    	}
    	return $project_info;
    }

    function get_project_menu($user_id)
    {
    	$project_menu = array();
    	$query = $this->db->query("SELECT PK_Project_Id, Name FROM btf2_projects LEFT JOIN btf2_project_members ON btf2_projects.PK_Project_Id = btf2_project_members.FK_Project_Id WHERE btf2_project_members.FK_User_Id = '".$user_id."'");
    	if ($query->num_rows())
    	{
    		$results = $query->result_array();
    		foreach($results AS $row)
    		{
    			$project_menu[] = array('Name'=>$row['Name'], 'project_id'=>$row['PK_Project_Id']);
    		}
    	}
    	return $project_menu;
    }

    function is_admin($user_id, $project_id)
    {
    	$admin = false;
    	$query = $this->db->get_where('btf2_project_members', array('FK_User_Id' => $user_id, 'FK_Project_Id' => $project_id));
    	if ($query->num_rows())
    	{
    		$results = $query->result_array();
    		foreach($results AS $row)
    		{
    			if ($row['Is_Admin'] == 'yes')
    			{
    				$admin = true;
    			}
    		}
    	}
    	return $admin;
    }

    function num_admin($project_id)
    {
    	$query = $this->db->get_where('btf2_project_members', array('Is_Admin' => 'yes', 'FK_Project_Id' => $project_id));
    	return $query->num_rows();
    }

    function is_project_member($project_id)
    {
      $user = $this->ion_auth->user()->row()->id;
  		$query = $this->db->get_where('btf2_project_members', array('FK_Project_Id' => $project_id, 'FK_User_Id' => $user));
      return $query->num_rows();
    }

    function is_project_member_id($project_id, $user_id)
    {
  		$query = $this->db->get_where('btf2_project_members', array('FK_Project_Id' => $project_id, 'FK_User_Id' => $user_id));
      return $query->num_rows();
    }

    function get_project_users($project_id)
    {
    	$users = array();
    	$query = $this->db->get_where('btf2_project_members', array('FK_Project_Id' => $project_id));
    	if ($query->num_rows())
    	{
    		$results = $query->result_array();
    		foreach($results AS $row)
    		{
    			$users[] = array('FK_User_Id'=>$row['FK_User_Id'], 'Join_Date'=>$row['Join_Date']);
    		}
    	}
    	return $users;
    }

    function project_invite($invite_code)
    {
    	$good_code = false;
    	$query = $this->db->get_where('btf2_projects', array('Invite_Key' => $invite_code));
    	if ($query->num_rows() == 1)
    	{
    		//a good code
    		$good_code = true;
    		$results = $query->result_array();
    		foreach($results AS $row)
    		{
    			$project_id = $row['PK_Project_Id'];
				  $user_info = $this->ion_auth->user()->row();
          $member = $this->db->get_where('btf2_project_members', array('FK_User_Id' => $user_info->id, 'FK_Project_Id' => $project_id));
          if($member->num_rows() == 0){
				      $data_member = array(
				            'FK_Project_Id' => $project_id,
				            'FK_User_Id' => $user_info->id,
				            'Is_Admin' => 'no',
				      );
				      $this->db->insert('btf2_project_members', $data_member);
              //add user to each public channel in this project
              $query = $this->db->query("SELECT Channel_Name, Members FROM btf2_channels WHERE FK_Project_Id = $project_id");
              if($query->num_rows())
              {
                $query = $query->result_array();
                foreach ($query as $channel)
                {
                  $members = unserialize($channel['Members']);
                  $members[] = $user_info->id;
                  $members = serialize($members);
                  $channel_name = $channel['Channel_Name'];
                  $this->db->query("UPDATE btf2_channels SET Members='$members' WHERE FK_Project_Id = $project_id AND Channel_Name = '$channel_name'");
                }
              }
    		  }
        }
    	}
    	return $good_code;
    }


    function delete_project($project_id)
    {
      $bucket = 'btf2_project_'.$project_id;
      $files = S3::getBucket($bucket);
      if ($files != Array())
      {
        foreach ($files as $object)
        {
          S3::deleteObject($bucket, $object['name']);
        }
        S3::deleteBucket($bucket);
      }
      else {
        S3::deleteBucket($bucket);
      }
		//delete the project
		$this->db->where('PK_Project_Id', $project_id);
		$this->db->delete('btf2_projects');
		//delete the members
		$this->db->where('FK_Project_Id', $project_id);
		$this->db->delete('btf2_project_members');
		//delete the channels
		$this->db->where('FK_Project_Id', $project_id);
		$this->db->delete('btf2_channels');
		//delete the chat messages
		$this->db->where('FK_Project_Id', $project_id);
		$this->db->delete('btf2_chat_messages');
		//delete the work records
		$this->db->where('FK_Project_Id', $project_id);
		$this->db->delete('btf2_work_records');
		//delete the tasks
		$this->db->where('FK_Project_Id', $project_id);
		$this->db->delete('btf2_tasks');

    //decrement user's project count
    $this->m_btf2_users->decrement_project_count();
    }

    function remove_team_member($project_id, $user_id)
    {
    	$this->db->where('FK_Project_Id', $project_id);
    	$this->db->where('FK_User_Id', $user_id);
    	$this->db->delete('btf2_project_members');
      //Remove this user from all channels
      $query = $this->db->query("SELECT Channel_Name, Members FROM btf2_channels WHERE FK_Project_Id = $project_id");
      if($query->num_rows())
      {
        $query = $query->result_array();
        foreach($query as $channel)
        {
          $members = unserialize($channel['Members']);
          $pos = array_search($user_id, $members);
          if($pos == 0 || $pos)//if found (at zero or otherwise)
          {
            unset($members[$pos]);
          }
          $members = serialize($members);
          $channel_name = $channel['Channel_Name'];
          $this->db->query("UPDATE btf2_channels SET Members='$members' WHERE FK_Project_Id = $project_id AND Channel_Name = '$channel_name'");
        }
      }
    }

    function change_admin_status($project_id, $user_id)
    {
    	if ($this->is_admin($user_id, $project_id))
    	{
	    	$data = array(
	    	    'Is_Admin' => 'no'
	    	);
    	} else {
    		$data = array(
    		    'Is_Admin' => 'yes'
    		);
    	}
    	$this->db->where('FK_Project_Id', $project_id);
    	$this->db->where('FK_User_Id', $user_id);
    	$this->db->update('btf2_project_members', $data);
    }

    function count_user_projects($user_id)
    {
      $query = $this->db->get_where('btf2_projects', array('FK_User_Id' => $user_id));
      return $query->num_rows();
    }

    function pull_user_projects($user_id)
    {
      $query = $this->db->get_where('btf2_project_members', array('FK_User_Id' => $user_id));

      if($query->num_rows())
      {
        $results = $query->result_array();
        foreach ($results as $row)
        {
          $projects[] = $row;
        }
      }
      return $projects;


    }

    function get_project_id_by_name($project_name)
    {
      $query = $this->db->query("SELECT PK_Project_Id FROM btf2_projects WHERE Name = '$project_name'");

      if($query->num_rows())
      {
        $results = $query->result_array();
        $project_info = $results[0];
      }
      return $project_info;
    }

    function projects_allowed($user_level)
    {
      switch($user_level)
      {
        case 0: return 1;
        case 1: return 3;
        case 2: return 10;
      }
    }

}
/* End of file m_btf2_projects.php */
/* Location: ./application/models/m_btf2_projects.php */
