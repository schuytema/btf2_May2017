<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_btf2_tasks extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_project_tasks($project_id, $group, $sort)
    {
      $tasks = array();
      switch ($sort)
      {
        case 'Date (ascending)':
          $query = $this->db->query("SELECT * FROM btf2_tasks WHERE FK_Project_Id = $project_id AND Group_Name = '$group' ORDER BY Update_Date");
          break;
        case 'Date (descending)':
          $query = $this->db->query("SELECT * FROM btf2_tasks WHERE FK_Project_Id = $project_id AND Group_Name = '$group' ORDER BY Update_Date DESC");
          break;
        case 'Tasks assigned to me':
          $query = $this->db->query("SELECT * FROM btf2_tasks WHERE FK_Project_Id = $project_id AND Group_Name = '$group' AND Assigned_To = '" . $this->input->post('full_name') . "' ORDER BY (CASE Assigned_To WHEN '" . $this->input->post('full_name') . "' THEN 1 ELSE 100 END)");
          break;
        case 'My requested tasks':
          $query = $this->db->query("SELECT * FROM btf2_tasks WHERE FK_Project_Id = $project_id AND Group_Name = '$group' AND FK_User_Id = '" . $this->input->post('Something') . "'ORDER BY (CASE FK_User_Id WHEN " . $this->input->post('Something') . " THEN 1 ELSE 100 END)");
          break;
        case 'Completion status':
          $query = $this->db->query("SELECT * FROM btf2_tasks WHERE FK_Project_Id = $project_id AND Group_Name = '$group' ORDER BY (CASE Status
            WHEN 'Not Started' THEN 1
            WHEN 'Incomplete (Stalled)' THEN 2
            WHEN 'In Progress' THEN 3
            WHEN 'Nearing Completion' THEN 4
            WHEN 'Complete' THEN 5
            ELSE 100 END)");
            break;
          case 'Archived':
            $query = $this->db->query("SELECT * FROM btf2_tasks WHERE FK_Project_Id = $project_id AND Group_Name = 'Archived' ORDER BY Create_Date");
            break;
        default:
        if ($sort != NULL)
        {
          $name = substr($sort, 18);
          $query = $this->db->query("SELECT * FROM btf2_tasks WHERE FK_Project_Id = $project_id AND Group_Name = '$group' AND Assigned_To = '" . $name . "'");
        }
        else {
          $query = $this->db->query("SELECT * FROM btf2_tasks WHERE FK_Project_Id = $project_id AND Group_Name = '$group' ORDER BY Update_Date DESC");
        }
      }

      if ($query->num_rows())
      {
        $results = $query->result_array();
        foreach ($results as $row)
        {
          $tasks[] = $row;
        }
      }
      return $tasks;
    }

    function new_task($task_id)
    {
      $new_assignment = false; //is this task being newly assigned to a member?
      $old_assigned_to = $this->pcs_utility->db_clean(strip_tags($this->input->post('old_assignment')));
      $assigned_to = $this->pcs_utility->db_clean(strip_tags($this->input->post('Assigned_To')));
      $task_name = $this->pcs_utility->db_clean(strip_tags($this->input->post('Task_Name')));
      // get wehre this task is assigned to the member from the input form
      //$query = $this->db->get_where('btf2_tasks', array('Task_Name' => $task_name, 'Assigned_To' => $assigned_to));
      if($old_assigned_to != $assigned_to) // "NONE" != "Unassigned" but the nested if statements will disregard this condition.
      {
        if($old_assigned_to != "NONE") // if this task was assigned to a user before
        { // notify the old user of the change of assignment
          $first_name = substr($old_assigned_to,0,strpos($old_assigned_to,' '));
          $last_name = substr($old_assigned_to,strpos($old_assigned_to,' ') + 1);
          $text = 'BTF2:'."\r\n".'The assignment of one of your tasks has been changed.';
          $query = $this->db->get_where('users', array('first_name' => $first_name, 'last_name' => $last_name));
          $results = NULL;
          if($query->num_rows())
          {
            $results = $query->result_array();
          }
          $user_id = $results[0]['id'];
          $subject = "Task Assignment Change";
          $this->m_btf2_users->notify($user_id, $text, $subject);
        }
        if($assigned_to != "Unassigned") { // if the new assigned_to is not "Unassigned"
          $new_assignment = true; // this task is being assigned to a new member
        }
      }
      $data = array(
            'FK_Project_Id' => $this->pcs_utility->id_clean($this->input->post('FK_Project_Id')),
            'Create_Date' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Create_Date'))),
            'Task_Name' => $task_name,
            'Description' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Description'))),
            'Complete_Date' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Complete_Date'))),
            'Update_Date' => time(),
            'Status' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Status'))),
            'Group_Name' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Group_Name'))),
            'Assigned_To' => $assigned_to);
      if ($task_id == 0)
      {
        $data['FK_User_Id'] = $this->pcs_utility->id_clean($this->input->post('FK_User_Id'));
        $this->db->insert('btf2_tasks', $data);
      } else {
        $this->db->where('PK_Task_Id',$task_id);
        $this->db->update('btf2_tasks', $data);
    	}

      if($new_assignment)
      {
        $first_name = substr($assigned_to,0,strpos($assigned_to,' '));
        $last_name = substr($assigned_to,strpos($assigned_to,' ') + 1);
        $text = 'BTF2:'."\r\n".'New task has been assigned to you.';
        $query = $this->db->get_where('users', array('first_name' => $first_name, 'last_name' => $last_name));
        $results = NULL;
        if($query->num_rows())
        {
          $results = $query->result_array();
        }
        $user_id = $results[0]['id'];
        $subject = "New Task";
        $this->m_btf2_users->notify($user_id, $text, $subject);
      }
  }

  function get_task_info($task_id)
  {
    $results = NULL;
    $query = $this->db->get_where('btf2_tasks', array('PK_Task_Id' => $task_id));
    if ($query->num_rows())
    {
      $results = $query->result_array();
    }
    return $results[0];
  }

  function get_task_groups($project_id, $sort = NULL)
  {
    $tasks = array();
    if ($sort != 'Archived')
    {
      $query = $this->db->query("SELECT DISTINCT Group_Name FROM btf2_tasks WHERE FK_Project_Id = $project_id AND Group_Name <> 'Archived' ORDER BY Group_Name");
      if ($query->num_rows())
      {
        $results = $query->result_array();
        foreach ($results as $row)
        {
          $tasks[] = $row['Group_Name'];
        }
      }
    }
    else {
      $tasks[] = 'Archived';
    }
    return $tasks;
  }

  function get_user_tasks_for_work_record($user_id, $project_id)
  {
  	$user_temp = $this->ion_auth->user($user_id)->row();
  	$user_name = $user_temp->first_name.' '.$user_temp->last_name;
  	$tasks = array();
  	$tasks[0] = 'No related task';
  	//$query = $this->db->get_where('btf2_tasks', array('FK_User_Id' => $user_id, 'FK_Project_Id' => $project_id));
  	$query = $this->db->get_where('btf2_tasks', array('Assigned_To' => $user_name, 'FK_Project_Id' => $project_id));
  	if ($query->num_rows())
  	{
  	  $results = $query->result_array();
  	  foreach ($results as $row)
  	  {
  	    $tasks[$row['PK_Task_Id']] = $row['Task_Name'];
  	  }
  	}
  	return $tasks;
  }

  function home_user_tasks_by_project($user_info, $project_id)
  {
    $full_name = $user_info->first_name . ' ' . $user_info->last_name;
    $query = $this->db->query("SELECT Task_Name, Group_Name FROM btf2_tasks WHERE Assigned_To = '$full_name' AND FK_Project_Id = $project_id AND Group_Name <> 'Archived'");
    if ($query->num_rows())
  	{
  	  $results = $query->result_array();
  	  foreach ($results as $row)
  	  {
  	    $tasks[] = $row;
  	  }
  	return $tasks;
    }
  }

  function home_grab_user_tasks($user_info)
  {
    $full_name = $user_info->first_name . ' ' . $user_info->last_name;
    $query = $this->db->query("SELECT * FROM btf2_tasks WHERE Assigned_To = '$full_name' AND Group_Name <> 'Archived'");
    if ($query->num_rows())
  	{
  	  $results = $query->result_array();
  	  foreach ($results as $row)
  	  {
  	    $tasks[] = $row;
  	  }
  	return $tasks;
    }
  }

  function home_new_tasks($project_id, $last_login)
  {
    $query = $this->db->query("SELECT * FROM btf2_tasks WHERE FK_Project_Id = $project_id AND Group_Name <> 'Archived' AND Create_Date >= '$last_login'");
    if ($query->num_rows())
  	{
  	  $results = $query->result_array();
  	  foreach ($results as $row)
  	  {
  	    $tasks[] = $row;
  	  }
  	return $tasks;
    }
  }

  function get_upcoming_events($date, $user_name, $project_id)
  {
    $events = array();
    $query = $this->db->query("SELECT Task_Name AS Event_Name, Complete_Date AS Event_Date, Description, FK_User_Id, PK_Task_Id AS Event_Id FROM btf2_tasks WHERE Assigned_To = '$user_name' AND FK_Project_Id = $project_id AND Complete_Date >= '$date' UNION SELECT Event_Name, Event_Date, Description, FK_User_Id, Event_Id FROM btf2_schedule WHERE FK_Project_Id = $project_id AND Event_Date >= '$date' ORDER BY Event_Date");
    if($query->num_rows())
    {
      $results = $query->result_array();
      foreach ($results as $row)
      {
        $events[] = $row;
      }
    }
    return $events;
  }

  function get_past_events($date, $user_name, $project_id)
  {
    $events = array();
    $query = $this->db->query("SELECT Task_Name AS Event_Name, Complete_Date AS Event_Date, Description, FK_User_Id, PK_Task_Id AS Event_Id FROM btf2_tasks WHERE Assigned_To = '$user_name' AND FK_Project_Id = $project_id AND Complete_Date < '$date' UNION SELECT Event_Name, Event_Date, Description, FK_User_Id, Event_Id FROM btf2_schedule WHERE FK_Project_Id = $project_id AND Event_Date < '$date' ORDER BY Event_Date");
    if($query->num_rows())
    {
      $results = $query->result_array();
      foreach ($results as $row)
      {
        $events[] = $row;
      }
    }
    return $events;
  }

  // This function gets upcoming events from all projects to be displayed in a master schedule table.
  // This function is not currently being used
  function get_all_upcoming_events($date, $user_name)
  {
    $events = array();
    $query = $this->db->query("SELECT FK_Project_Id AS Project_Name, Task_Name AS Event_Name, Complete_Date AS Event_Date FROM btf2_tasks WHERE Assigned_To = '$user_name' AND Complete_Date >= '$date' UNION SELECT Project_Name, Event_Name, Event_Date FROM schedule WHERE Event_Date >= '$date' ORDER BY Event_Date");
    if($query->num_rows())
    {
      $results = $query->result_array();
      foreach ($results as $row)
      {
        $events[] = $row;
      }
    }
    return $events;
  }

  // This function gets past events from all projects to be displayed in a master schedule table.
  // This function is not currently being used
  function get_all_past_events($date, $user_name)
  {
    $events = array();
    $query = $this->db->query("SELECT FK_Project_Id AS Project_Name, Task_Name AS Event_Name, Complete_Date AS Event_Date FROM btf2_tasks WHERE Assigned_To = '$user_name' AND Complete_Date < '$date' UNION SELECT Project_Name, Event_Name, Event_Date FROM schedule WHERE Event_Date < '$date' ORDER BY Event_Date DESC");
    if($query->num_rows())
    {
      $results = $query->result_array();
      foreach ($results as $row)
      {
        $events[] = $row;
      }
    }
    return $events;
  }
}
?>
