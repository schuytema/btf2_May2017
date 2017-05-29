<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_btf2_interest_groups extends CI_Model{

    function __construct(){
        parent::__construct();
    }

    function add_interest_group()
    {
      $user_id = $this->pcs_utility->id_clean($this->input->post('FK_User_Id'));
      $name = $this->pcs_utility->db_clean(strip_tags($this->input->post('Name')));
      $invite_key = $this->pcs_utility->random_string();
    	$data = array(
    	    'FK_User_Id' => $user_id,
    	    'Name' => $name,
    	    'Description' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Description'))),
          'Invite_Key' => $invite_key,
    	);
    	$this->db->insert('btf2_interest_group', $data);
      $query = $this->db->query("SELECT DISTINCT PK_Interest_Group_Id FROM btf2_interest_group WHERE (Name = '$name' AND FK_User_Id = $user_id)");
      $group_id = $query->result_array()[0]['PK_Interest_Group_Id'];
      //print_r($group_id);
      $data = array(
          'FK_Interest_Group_Id' => $group_id,
          'FK_User_Id' => $user_id,
          'Is_Admin' =>'yes',
      );
      $this->db->insert('btf2_interest_group_members', $data);
    }

    function get_default_interest_group($group_id, $user_id)
    {
      if($group_id == 0)
      {
        $query = $this->db->query("SELECT btf2_interest_group.* FROM btf2_interest_group LEFT JOIN btf2_interest_group_members ON btf2_interest_group.PK_Interest_Group_Id = btf2_interest_group_members.FK_Interest_Group_Id WHERE btf2_interest_group_members.FK_User_Id = $user_id");
      } else {
        $query = $this->db->query("SELECT * FROM btf2_interest_group WHERE PK_Interest_Group_Id = $group_id");
      }
      if ($query->num_rows())
      {
        return $query->result_array()[0];
      }
    }

    function get_interest_group($group_id)
    {
      $query = $this->db->query("SELECT * FROM btf2_interest_group WHERE PK_Interest_Group_Id = $group_id");
      if ($query->num_rows())
      {
        return $query->result_array()[0];
      }
    }

    function group_invite($invite_code)
    {
    	$good_code = false;
    	$query = $this->db->get_where('btf2_interest_group', array('Invite_Key' => $invite_code));
    	if ($query->num_rows() == 1)
    	{
    		//a good code
    		$good_code = true;
    		$results = $query->result_array();
    		foreach($results AS $row)
    		{
    			$group_id = $row['PK_Interest_Group_Id'];
				  $user_info = $this->ion_auth->user()->row();
          $member = $this->db->get_where('btf2_interest_group_members', array('FK_User_Id' => $user_info->id, 'FK_Interest_Group_Id' => $group_id));
          if($member->num_rows() == 0){
				      $data_member = array(
				            'FK_Interest_Group_Id' => $group_id,
				            'FK_User_Id' => $user_info->id,
				      );
				      $this->db->insert('btf2_interest_group_members', $data_member);
    		  }
        }
    	}
    	return $good_code;
    }

    function pull_group_members($group_id)
    {
      $query = $this->db->query("SELECT DISTINCT FK_User_Id FROM btf2_interest_group_members WHERE FK_Interest_Group_Id = '$group_id' AND FK_Project_Id = 0");
      if ($query->num_rows())
      {
        $results = $query->result_array();
        foreach ($results as $row)
        {
          $members[] = $row;
        }
      }
      return $members;
    }

    function get_group_feed($group_id)
    {
      $query = $this->db->query("SELECT * FROM btf2_interest_group_feed WHERE FK_Interest_Group_Id = '$group_id' AND Published != 'hidden' ORDER BY Post_Date DESC");
      $feed = array();
      if($query->num_rows())
      {
        $results = $query->result_array();
        foreach ($results as $row)
        {
          $feed[] = $row;
        }
      }
      return $feed;
    }

    function get_post_by_id($post_id)
    {
      $query = $this->db->query("SELECT * FROM btf2_interest_group_feed WHERE PK_Interest_Group_Feed_Id = '$post_id'");
      $feed = array();
      if($query->num_rows())
      {
        $results = $query->result_array();
        foreach ($results as $row)
        {
          $feed[] = $row;
        }
      }
      return $feed[0];
    }

    function make_admin($group_id, $user_id)
    {
      $this->db->query("UPDATE btf2_interest_group_members SET Is_Admin = 'yes' WHERE FK_Interest_Group_Id = $group_id AND FK_User_Id = $user_id");
    }

    function add_content_to_group($group_id)
    {
      $user_id = $this->pcs_utility->id_clean($this->input->post('FK_User_Id'));
      $query = $this->db->query("SELECT Name FROM btf2_interest_group WHERE PK_Interest_Group_Id = $group_id AND FK_User_Id = $user_id");
      if($query->num_rows())
      {
        $publish = 'yes';
      } else {
        $publish = 'no';
      }
      $content = $this->pcs_utility->db_clean(strip_tags($this->input->post('Content')));
      $title = $this->pcs_utility->db_clean(strip_tags($this->input->post('Title')));
      $image = $this->pcs_utility->db_clean(strip_tags($this->input->post('Image')));
      $data = array(
          'FK_User_Id' => $user_id,
          'FK_Interest_Group_Id' => $group_id,
          'Content' => $content,
          'Image' => $image,
          'Title' => $title,
          'Published' => $publish,
      );
      $this->db->insert('btf2_interest_group_feed', $data);
      if($publish == 'yes')
      {
        $name = $query->result_array();
        $name = $name[0]['Name'];
        $this->m_btf2_interest_groups->notify_members($group_id, $user_id, $name);
      }
    }

    function publish_feed($feed_id, $group_id)
    {
      $this->db->query("UPDATE btf2_interest_group_feed SET Published = 'yes' WHERE PK_Interest_Group_Feed_Id = $feed_id");
      $query = $this->db->query("SELECT FK_User_Id FROM btf2_interest_group_feed WHERE PK_Interest_Group_Feed_Id = $feed_id");
      $user_id = $query->result_array();
      $user_id = $user_id[0]['FK_User_Id'];
      $query = $this->db->query("SELECT Name FROM btf2_interest_group WHERE PK_Interest_Group_Id = $group_id");
      $group = $query->result_array();
      $group = $group[0]['Name'];
      $this->m_btf2_interest_groups->notify_members($group_id, $user_id, $group);
    }

    function get_feed_comments_count($feed_id)
    {
      $comments = $this->m_btf2_interest_groups->get_feed_comments($feed_id);
      if($comments == null)
      {
        return 0;
      }
      return count($comments);
    }

    function is_default_group($group_id, $user_id)
    {
      $query = $this->db->query("SELECT id FROM users WHERE id = $user_id AND default_interest_group = $group_id");
      return $query->num_rows();
    }

    function make_default_group($group_id, $user_id)
    {
      $this->db->query("UPDATE users SET default_interest_group = $group_id WHERE id = $user_id");
    }

    function get_feed_comments($feed_id)
    {
      $comments = $this->db->query("SELECT * FROM btf2_interest_group_comments WHERE FK_Feed_Id = '$feed_id'");
      if($comments->num_rows())
      {
        return $comments->result_array();
      }
      return null;
    }

    function add_feed_comment($feed_id)
    {
      $content = $this->pcs_utility->db_clean(strip_tags($this->input->post('Comment')));
      $user_id = $this->ion_auth->user()->row()->id;
      $data = array(
        'FK_User_Id' => $user_id,
        'Content' => $content,
        'FK_Feed_Id' => $feed_id,
      );
      $this->db->insert('btf2_interest_group_comments', $data);
    }

    function add_feed_like($user_id, $feed_id)
    {
      $likes = $this->db->query("SELECT Feed_Likes FROM btf2_interest_group_feed WHERE PK_Interest_Group_Feed_Id = '$feed_id'");
      $likes = unserialize($likes->result_array()[0]['Feed_Likes']);
      $likes[] = $user_id;
      $likes = serialize($likes);
      $this->db->query("UPDATE btf2_interest_group_feed SET Feed_Likes = '$likes' WHERE PK_Interest_Group_Feed_Id = '$feed_id'");
    }

    function notify_members($group_id, $poster_id, $group_name)
    {
      $query = $this->db->query("SELECT DISTINCT FK_User_Id FROM btf2_interest_group_members WHERE FK_Interest_Group_Id = $group_id AND FK_User_Id != $poster_id AND FK_Project_Id = 0");
      if($query->num_rows())
      {
        $results = $query->result_array();
        foreach ($results as $user_id)
        {
          $id = $user_id['FK_User_Id'];
          $groups = $this->db->query("SELECT interest_group_name, notification FROM users WHERE id = $id");
          $groups = $groups->result_array();
          $notification = $groups[0]['notification'];
          $groups = $groups[0]['interest_group_name'];
          $subject = "New ".$groups." Feed";
          $message = "There is a new post in ".$group_name;
          if($notification == 'email')
          {
            $message = $message.". Click the link below to view the post:\r\n";
            $message = $message.base_url().'main/interest_groups/'.$group_id;
          }
          $this->m_btf2_users->notify($id, $message, $subject);
        }
      }
    }

    function increase_feed_views($feed_id)
    {
      $views = $this->db->query("SELECT Feed_Views FROM btf2_interest_group_feed WHERE PK_Interest_Group_Feed_Id = $feed_id");
      $views = $views->result_array()[0]['Feed_Views'] + 1;
      $this->db->query("UPDATE btf2_interest_group_feed SET Feed_Views = $views WHERE PK_Interest_Group_Feed_Id = $feed_id");
    }

    function get_group_id_from_post($feed_id)
    {
      $query = $this->db->query("SELECT FK_Interest_Group_Id FROM btf2_interest_group_feed WHERE PK_Interest_Group_Feed_Id = $feed_id");
      return $query->result_array()[0]['FK_Interest_Group_Id'];
    }

    function get_all_interest_groups($user_id)
    {
      $query = $this->db->query("SELECT DISTINCT btf2_interest_group.* FROM btf2_interest_group LEFT JOIN btf2_interest_group_members ON btf2_interest_group.PK_Interest_Group_Id = btf2_interest_group_members.FK_Interest_Group_Id AND btf2_interest_group_members.FK_User_Id = '$user_id' WHERE btf2_interest_group.Visibility = 'public' OR (btf2_interest_group.Visibility = 'private' AND btf2_interest_group_members.FK_User_Id = '$user_id') ORDER BY Name");
      $groups = array();
      if($query->num_rows())
      {
        $results = $query->result_array();
        foreach ($results as $row)
        {
          $groups[] = $row;
        }
      }
      return $groups;
    }

    function is_group_member($group_id, $user_id)
    {
      $query1 = $this->db->query("SELECT PK_Interest_Group_Member_Id FROM btf2_interest_group_members WHERE FK_Interest_Group_Id = $group_id AND FK_User_Id = $user_id");
      return $query1->num_rows();
    }

    function is_group_admin($group_id, $user_id)
    {
      $query = $this->db->query("SELECT PK_Interest_Group_Member_Id FROM btf2_interest_group_members WHERE FK_Interest_Group_Id = $group_id AND FK_User_Id = $user_id AND Is_Admin = 'yes'");
      return $query->num_rows();
    }

    function pull_group_projects($group_id)
    {
      $query = $this->db->query("SELECT DISTINCT FK_Project_Id FROM btf2_interest_group_members WHERE FK_Interest_Group_Id = '$group_id'");
      if ($query->num_rows())
      {
        $results = $query->result_array();
        foreach ($results as $row)
        {
          $projects[] = $row['FK_Project_Id'];
        }
      }
      return $projects;
    }

    function make_clickable($text)
    {
      $regex = '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#';
      return preg_replace_callback($regex, function ($matches) {
        return '<a href="'.$matches[0].'" target="_blank">'.$matches[0].'</a>';
      }, $text);
    }

    function connect_project_to_group($group_id)
    {
      $project_id = $this->pcs_utility->db_clean($this->input->post('Chosen_Project'));
      $project_members = $this->m_btf2_projects->get_project_users($project_id);
      $project_members_ids = array();
      $group_members = array();
      foreach ($project_members as $member)
      {
        $project_members_ids[] = $member['FK_User_Id'];
        if(!$this->m_btf2_interest_groups->is_group_member($group_id, $member['FK_User_Id']))
        {
          $group_members[] = $member['FK_User_Id'];
        }
      }
      $project_members = serialize($project_members_ids);
      $this->m_btf2_interest_groups->notify_project_members_in_group($group_id, $project_id, $project_members_ids, 1);
      $this->db->query("INSERT INTO btf2_interest_group_members (FK_Interest_Group_Id, FK_Project_Id, Project_Members_In_Group) VALUES ('$group_id', '$project_id', '$project_members')");
      foreach ($group_members as $member)
      {
        $this->db->query("INSERT INTO btf2_interest_group_members (FK_Interest_Group_Id, FK_User_Id) VALUES ($group_id, $member)");
      }
    }

    function notify_project_members_in_group($group_id, $project_id, $members, $message)
    {
      $project = $this->m_btf2_projects->get_project_info($project_id);
      $group = $this->m_btf2_interest_groups->get_interest_group($group_id);
      $group = $group['Name'];
      if($message == 1)
      {
        $message = "Your project \"".$project['Name']."\" has joined \"".$group."\" group. You may choose to leave at any time.";
      } else if ($message == 2) {
        $message = "Your project \"".$project['Name']."\" has left \"".$group."\" group.";
      }
      foreach ($members as $id)
      {
        $groups = $this->db->query("SELECT interest_group_name FROM users WHERE id = $id");
        $groups = $groups->result_array();
        $groups = $groups[0]['interest_group_name'];
        $subject = $groups." Notification";
        $this->m_btf2_users->notify($id, $message, $subject);
      }
    }

    function leave_group($group_id, $user_id)
    {
      $this->db->query("DELETE FROM btf2_interest_group_members WHERE FK_User_Id = $user_id AND FK_Interest_Group_Id = $group_id");
      $group_projects = $this->db->query("SELECT FK_Project_Id, Project_Members_In_Group FROM btf2_interest_group_members WHERE FK_Interest_Group_Id = $group_id AND FK_User_Id = 0");
      if($group_projects->num_rows())
      {
        $group_projects = $group_projects->result_array();
        foreach ($group_projects as $project)
        {
          $members = unserialize($project['Project_Members_In_Group']);
          $pos = array_search($user_id, $members);
          if($pos == 0 || $pos) // if found (at zero or otherwise)
          {
            unset($members[$pos]);
            $members = serialize($members);
            $this->db->query("UPDATE btf2_interest_group_members SET Project_Members_In_Group = '$members' WHERE FK_Interest_Group_Id = '$group_id' AND FK_Project_Id = '$project[FK_Project_Id]'");
          }
        }
      }
    }

    function remove_group_project($group_id,$project_id)
    {
      $group_projects = $this->db->query("SELECT Project_Members_In_Group FROM btf2_interest_group_members WHERE FK_Interest_Group_Id = $group_id AND FK_Project_Id = '$project_id'");
      $this->db->query("DELETE FROM btf2_interest_group_members WHERE FK_Interest_Group_Id = '$group_id' AND FK_Project_Id = '$project_id'");
      if($group_projects->num_rows())
      {
        $group_projects = $group_projects->result_array()[0];
        $members = unserialize($group_projects['Project_Members_In_Group']);
        $this->m_btf2_interest_groups->notify_project_members_in_group($group_id, $project_id, $members, 2);
      }
    }

    function delete_group($group_id)
    {
      $this->db->query("DELETE FROM btf2_interest_group_members WHERE FK_Interest_Group_Id = '$group_id'");
      $this->db->query("DELETE FROM btf2_interest_group_feed WHERE FK_Interest_Group_Id = '$group_id'");
      $this->db->query("DELETE FROM btf2_interest_group WHERE PK_Interest_Group_Id = '$group_id'");
    }

    function delete_feed_post($feed_id)
    {
      $this->db->query("DELETE FROM btf2_interest_group_feed WHERE PK_Interest_Group_Feed_Id = '$feed_id'");
    }

    function delete_feed_comment($comment_id)
    {
      $this->db->query("DELETE FROM btf2_interest_group_comments WHERE PK_Comment_Id = '$comment_id'");
    }

  }

/* End of file m_btf2_projects.php */
/* Location: ./application/models/m_btf2_projects.php */
