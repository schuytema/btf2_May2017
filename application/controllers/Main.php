<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Main extends CI_Controller {
	public function __construct()
	{
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
    $this->load->library('pcs_utility');
		$this->load->library('ion_auth');
		$this->load->library('s3');
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->model('m_btf2_users');
    $this->load->model('m_btf2_projects');
    $this->load->model('m_btf2_work_records');
		$this->load->model('m_btf2_interest_groups');
		$this->load->model('m_btf2_chat');
		$this->load->model('m_btf2_tasks');
		$this->load->model('m_btf2_tags');
    $this->load->config('btf2');
		$this->load->config('s3');
		//dev profiler
    //$this->output->enable_profiler(true);
	}
	public function index()
	{
		if($this->ion_auth->logged_in())
		{
			redirect('main/home');
		} else {
			redirect('auth/login');
		}
	}
/*	public function access($key = NULL)
	{
		if ($key != NULL)
		{
			$data = array();
			$data['user_key'] = $key;
			$this->load->view('main_access', $data);
		} else {
			redirect('auth/login');
		}
	}*/
	/*public function login()
	{
		if (isset($_POST['User_Key']))
		{
			$key = $this->input->post('User_Key');
			if ($this->m_btf2_users->valid_user($key))
			{
				$user_info = $this->m_btf2_users->get_user_info($key);
				$this->session->set_userdata('user_info', $user_info[0]);
				redirect('main/home');
			} else {
				redirect('auth/login');
			}
		} else {
			redirect('auth/login');
		}
	}*/
	public function home()
	{
		if($this->ion_auth->logged_in())
		{
			$data = array();
			$data['page'] = 'home';
			$data['in_project'] = false;
			$this->load->view('main_head', $data);
			$this->load->view('main_home');
			$this->load->view('main_foot');
		} else {
			redirect('auth/login');
		}
	}
	public function no_rights()
	{
		$this->session->unset_userdata('user_info');
		$this->load->view('main_no_rights');
	}
	function project_list()
	{
	    if($this->ion_auth->logged_in())
	    {
	    	$data = array();
	    	$data['page'] = 'project_list';
				$data['in_project'] = false;
	      $this->load->view('main_head', $data);
	      $this->load->view('main_project_list');
	      $this->load->view('main_foot');
		} else {
			redirect('auth/login');
		}
	}

	function project_home($project_id)
	{
	    if($this->ion_auth->logged_in())
	    {
				$data = array();
				$data['page'] = 'project_home';
				$data['project_id'] = $project_id;
				$data['in_project'] = true;
				if($this->m_btf2_projects->is_project_member($project_id))
				{
		      $this->load->view('main_head', $data);
		    	$this->load->view('main_project_home', $data);
		    	$this->load->view('main_foot');
				} else {
					$this->load->view('main_head', $data);
		    	$this->load->view('main_project_home_preview', $data);
		    	$this->load->view('main_foot');
				}
	    } else {
	    	redirect('auth/login');
	    }
	}

	function project_invite($good_code = 0)
	{
		if($this->ion_auth->logged_in())
		{
	    	$data = array();
	    	$data['page'] = 'project_invite';
				$data['in_project'] = false;
	    	if ($good_code == 0)
	    	{
	    		$data['bad_invite_code'] = '<p><font color="red">Please enter a valid invite code.</font></p>';
	    	} else {
	    		$data['bad_invite_code'] = '';
	    	}
	      $this->load->view('main_head', $data);
	    	$this->load->view('main_project_invite_code', $data);
	    	$this->load->view('main_foot');
	    } else {
	    	redirect('auth/login');
	    }
	}

	function process_project_invite()
	{
		if($this->ion_auth->logged_in())
		{
			$invite_code = $this->pcs_utility->db_clean(strip_tags($this->input->post('Invite_Code')));
			if ($this->m_btf2_projects->project_invite($invite_code))
			{
				redirect('main/project_list');
			} else {
				redirect('main/project_invite/0');
			}
	    } else {
	    	redirect('auth/login');
	    }
	}

	function update_project($project_id = 0)
	{
		if($this->ion_auth->logged_in())
		{
		    $data = array();
		    $data['page'] = 'update_project';
		    $data['project_id'] = $project_id;
				$data['in_project'] = false;
		    if ($project_id != 0)
		    {
		    	$data['project_info'] = $this->m_btf2_projects->get_project_info($project_id);
		    } else {
		    	$data['project_info'] = NULL;
		    }
		    $this->load->view('main_head', $data);
		  	$this->load->view('main_project_update');
		  	$this->load->view('main_foot');
		} else {
			redirect('auth/login');
		}
	}

	function process_update_project($project_id)
	{
		if($this->ion_auth->logged_in())
		{
			$this->m_btf2_projects->new_project($project_id);
			redirect('main/project_list/');
		} else {
			redirect('auth/login');
		}
	}

	function process_delete_project($project_id)
	{
		if($this->ion_auth->logged_in())
		{
			$this->m_btf2_projects->delete_project($project_id);
			redirect('main/project_list/');
		} else {
			redirect('auth/login');
		}
	}

	function project_invite_email($project_id)
	{
		if($this->ion_auth->logged_in())
		{
			if($this->m_btf2_projects->is_project_member($project_id))
			{
	    	$data = array();
	    	$data['page'] = 'project_invite_email';
	    	$data['project_id'] = $project_id;
				$data['in_project'] = true;
	      $this->load->view('main_head', $data);
	    	$this->load->view('main_project_invite_email', $data);
	    	$this->load->view('main_foot');
			} else {
				redirect('main/project_list');
			}
    } else {
    	redirect('auth/login');
    }
	}

	function send_project_invite_email()
	{
		if($this->ion_auth->logged_in())
		{
			$project_info = $this->m_btf2_projects->get_project_info($this->input->post('Project_Id'));
			$user_info = $this->ion_auth->user()->row();;

			$this->load->library('email');

	        $email = $this->input->post('Email');

			$this->email->from('admin@btffellows.com', 'Breakthrough Foundry (2) dev server');
			$this->email->to($email);

			$this->email->subject('Breakthrough Foundry 2: You have received a project invite.');

			$msg = "Email sent from the BTF2.dev webserver.\n\n";
			$msg = $msg."You have been invited to join a BTF project.\n\n";
			$msg = $msg."Project name: ".$project_info['Name']."\n\n";
			$msg = $msg."Your Project Invite Key: ".$project_info['Invite_Key']."\n\n";
			$msg = $msg."You have been invited by ".$user_info->first_name." ".$user_info->last_name."\n\n";
			$msg = $msg."If you are not a member, you can join for free. Below is the link to create an account:\n\n";
			$msg = $msg.base_url()."auth/create_user\n\n";
			$msg = $msg."Once logged in, you can click Enter Project Invite on your Project page. You can also use this direct link:\n\n";
			$msg = $msg.base_url()."main/project_invite/1\n\n";


			$this->email->message($msg);
			$this->email->send();

			redirect('main/team_list/'.$project_info['PK_Project_Id']);

		} else {
			redirect('auth/login');
		}
	}
	function work_list($project_id)
	{
	    if($this->ion_auth->logged_in())
	    {
				if($this->m_btf2_projects->is_project_member($project_id))
				{
		    	$data = array();
		    	$data['page'] = 'work_list';
		    	$data['project_id'] = $project_id;
					$data['in_project'] = true;
		      $this->load->view('main_head', $data);
		    	$this->load->view('tracker_work_list', $data);
		    	$this->load->view('main_foot');
				} else {
					redirect('main/project_list');
				}
	    } else {
	    	redirect('auth/login');
	    }
	}
	function team_list($project_id)
	{
	    if($this->ion_auth->logged_in())
	    {
				if($this->m_btf2_projects->is_project_member($project_id))
				{
		    	$data = array();
		    	$data['page'] = 'team_list';
		    	$data['project_id'] = $project_id;
					$data['in_project'] = true;
		      $this->load->view('main_head', $data);
		    	$this->load->view('main_team_list', $data);
		    	$this->load->view('main_foot');
				} else {
					redirect('main/project_list');
				}
	    } else {
	    	redirect('auth/login');
	    }
	}
	function process_remove_member($project_id, $user_id)
	{
		if($this->ion_auth->logged_in())
		{
			$this->m_btf2_projects->remove_team_member($project_id, $user_id);
			redirect('main/team_list/'.$project_id);
		} else {
			redirect('auth/login');
		}

	}
	function process_change_admin($project_id, $user_id)
	{
		if($this->ion_auth->logged_in())
		{
			$this->m_btf2_projects->change_admin_status($project_id, $user_id);
			redirect('main/team_list/'.$project_id);
		} else {
			redirect('auth/login');
		}

	}
	function update_work($project_id, $work_id)
	{
	    if($this->ion_auth->logged_in())
	    {
				if($this->m_btf2_projects->is_project_member($project_id))
				{
		    	$data = array();
		    	$data['page'] = 'work_update';
		    	$data['project_id'] = $project_id;
					$data['in_project'] = true;
		    	$data['work_id'] = $work_id;
		    	$data['work_info'] = $this->m_btf2_work_records->get_specific_work_record($work_id);
		      $this->load->view('main_head', $data);
		    	$this->load->view('tracker_work_update', $data);
		    	$this->load->view('main_foot');
				} else {
					redirect('main/project_list');
				}
	    } else {
	    	redirect('auth/login');
	    }
	}
	function process_update_work($project_id, $work_id)
	{
	    if($this->ion_auth->logged_in())
	    {
				$this->m_btf2_work_records->new_work_record($work_id);
				redirect('main/work_list/'.$project_id);
	    } else {
	    	redirect('auth/login');
	    }
	}
	function delete_work($project_id, $work_id)
	{
	    if($this->ion_auth->logged_in())
	    {
				$this->m_btf2_work_records->delete_work_record($work_id);
				redirect('main/work_list/'.$project_id);
	    } else {
	    	redirect('auth/login');
	    }
	}
	function chat_list($project_id)
	{
	    if($this->ion_auth->logged_in())
	    {
				if($this->m_btf2_projects->is_project_member($project_id))
				{
		    	$data = array();
		    	$data['page'] = 'chat_list';
		    	$data['project_id'] = $project_id;
					$data['in_project'] = true;
		      $this->load->view('main_head', $data);
		    	$this->load->view('main_chat_list', $data);
		    	$this->load->view('main_foot');
				} else {
					redirect('main/project_list');
				}
	    } else {
		    redirect('auth/login');
	    }
	}
	function add_message($message_id)
	{
	    if($this->ion_auth->logged_in())
	    {
				//I just Used a hidden input from the submission form's value for the project ID here.
		    $project_id = $this->pcs_utility->id_clean($this->input->post('FK_Project_Id'));
				$channel = $this->pcs_utility->db_clean($this->input->post('Channel'));
				$this->m_btf2_chat->new_message($message_id); //Direct to the new_message function
				$sender = $this->pcs_utility->id_clean($this->input->post('FK_User_Id'));
				$this->m_btf2_chat->notify_chatters($project_id, $channel, $sender);
				redirect('main/chat_list/' . $project_id . '/-' . $channel); //As well as here to properly redirect the page.
	    } else {
	    	redirect('auth/login');
	    }
	}

	function pm_user($user_id)
	{
		if($this->ion_auth->logged_in())
		{
			$this->m_btf2_chat->new_pm($user_id);
			redirect('main/message_user/' . $user_id . '/success');
		}
		else {
			redirect('auth/login');
		}
	}

	function add_channel($project_id)
	{
		if($this->ion_auth->logged_in())
		{
			if($this->m_btf2_projects->is_project_member($project_id))
			{
				$data = array();
				$data['page'] = 'add_channel';
				$data['project_id'] = $project_id;
				$data['in_project'] = true;
				$this->load->view('main_head', $data);
				$this->load->view('create_new_channel', $data);
				$this->load->view('main_foot');
			} else {
				redirect('main/project_list');
			}
		} else {
			redirect('auth/login');
		}
	}
	function process_add_channel($project_id)
	{
		if($this->ion_auth->logged_in())
		{
			$this->m_btf2_chat->new_channel($project_id);
		} else {
			redirect('auth/login');
		}
	}
	function delete_message($message_id, $channel)
	{
		$query = $this->db->get_where('btf2_chat_messages', array('PK_Chat_Message_Id' => $message_id));
		$project_info = $query->result_array()['0'];
		//print_r($project_info);
		if (substr($channel, 0, 7) == "Private")
		{
			$channel = "-".substr($channel, 7);
		}
		if($this->ion_auth->logged_in())
		{
			$this->db->delete('btf2_chat_messages', array('PK_Chat_Message_Id' => $message_id));
			redirect('main/chat_list/' . $project_info['FK_Project_Id'] . '/-' . $channel, 1);
		} else {
			redirect('auth/login');
		}
	}
	function tasks($project_id)
	{
		if($this->ion_auth->logged_in())
		{
			if($this->m_btf2_projects->is_project_member($project_id))
			{
				$data = array();
				$data['page'] = 'tasks';
				$data['project_id'] = $project_id;
				$data['in_project'] = true;
				$this->load->view('main_head', $data);
				$this->load->view('main_tasks');
				$this->load->view('main_foot');
			} else {
				redirect('main/project_list');
			}
		} else {
			redirect('auth/login');
		}
	}
	function create_task($project_id, $task_id)
	{
		if($this->ion_auth->logged_in())
		{
			if($this->m_btf2_projects->is_project_member($project_id))
			{
				$data = array();
				$data['page'] = 'tasks';
				$data['project_id'] = $project_id;
				$data['in_project'] = true;
				$data['task_info'] = $this->m_btf2_tasks->get_task_info($task_id);
				$this->load->view('main_head', $data);
				$this->load->view('main_task_create', $data);
				$this->load->view('main_foot');
			} else {
				redirect('main/project_list');
			}
		} else {
			redirect('auth/login');
		}
	}
	function process_task($project_id, $task_id)
	{
		if($this->ion_auth->logged_in())
		{
			$data = array();
			$data['page'] = 'tasks';
			$data['project_id'] = $project_id;
			$data['in_project'] = true;
			$data['task_id'] = $task_id;
			$this->m_btf2_tasks->new_task($task_id); //Direct to the new_message function
			redirect('main/tasks/' . $this->pcs_utility->id_clean($this->input->post('FK_Project_Id')) . '/'); //As well as here to properly redirect the page.
		} else {
			redirect('auth/login');
		}
	}

	function complete_task($project_id, $task_id)
	{
		if($this->ion_auth->logged_in())
		{
			$data = array(
				'PK_Task_Id' => $task_id,
	      'Status' => 'Complete');
				$this->db->where('PK_Task_Id', $task_id);
	      $this->db->update('btf2_tasks', $data);
			redirect('main/tasks/' . $project_id . '/'); //As well as here to properly redirect the page.
		} else {
			redirect('auth/login');
		}
	}

	function logged()
	{
		if($this->ion_auth->logged_in())
		{
			$data = array();
			$data['page'] = 'logged_in';
			$data['in_project'] = false;
			$this->load->view('main_head', $data);
			$this->load->view('main_logged_in');
			$this->load->view('main_foot');
		} else {
			redirect('auth/login');
		}
	}

	function delete_task($task_id)
	{
		$query = $this->db->get_where('btf2_tasks', array('PK_Task_Id' => $task_id));
		$project_id = $query->result_array();
		if($this->ion_auth->logged_in())
		{
			$data = array();
			$data['page'] = 'delete_task';
			$data['project_id'] = $project_id;
			$data['in_project'] = true;
			$data['task_id'] = $task_id;
			$this->db->delete('btf2_tasks', array('PK_Task_Id' => $task_id));
			redirect('main/tasks/' . $project_id[0]['FK_Project_Id'] . '/'); //As well as here to properly redirect the page.
		} else {
			redirect('auth/login');
		}
	}

	function test()
	{
		$data = array();
		$data['page'] = 'test';
		$data['in_project'] = false;
		$this->load->view('main_head', $data);
		$this->load->view('main_test', $data);
		$this->load->view('main_foot');
	}

	// This function is currently not being used
	function schedule($user_id)
	{
		if($this->ion_auth->logged_in())
		{
			$data = array();
			$data['page'] = 'schedule';
			$data['in_project'] = false;
			$this->load->view('main_head', $data);
			$this->load->view('main_schedule');
			$this->load->view('main_foot');
		} else {
			redirect('auth/login');
		}
	}

	function project_schedule($project_id)
	{
		if($this->ion_auth->logged_in())
		{
			if($this->m_btf2_projects->is_project_member($project_id))
			{
				$data = array();
				$data['page'] = 'project_schedule';
				$data['in_project'] = true;
				$data['project_id'] = $project_id;
				$this->load->view('main_head', $data);
				$this->load->view('main_project_schedule', $data);
				$this->load->view('main_foot');
			} else {
				redirect('main/project_list');
			}
		} else {
			redirect('auth/login');
		}
	}

	function create_event($project_id)
	{
		if($this->ion_auth->logged_in())
		{
			if($this->m_btf2_projects->is_project_member($project_id))
			{
				$data = array();
				$data['page'] = 'create_event';
				$data['in_project'] = false;
				$data['project_id'] = $project_id;
				$this->load->view('main_head', $data);
				$this->load->view('main_create_event', $data);
				$this->load->view('main_foot');
			} else {
				redirect('main/project_list');
			}
		} else {
			redirect('auth/login');
		}
	}

	function add_event()
	{
		if($this->ion_auth->logged_in())
		{
			$recur = $this->pcs_utility->db_clean(strip_tags($this->input->post('sel1')));
			$y = substr($this->input->post('Event_Date'), 0, 4);
			$m = substr($this->input->post('Event_Date'), 5, 2);
			$d = substr($this->input->post('Event_Date'), 8, 2);
			$date = strtotime($m."/".$d."/".$y." ".$this->input->post('Event_Time'));
			@$create_date = $CURRENT_TIMESTAMP;
			$user_id = $this->input->post('FK_User_Id');
			$event_name = $this->input->post('Event_Name');
			$project_id = $this->input->post('FK_Project_Id');
			$description = 'Event. '.$this->input->post('Description');
			$data = array(
					'Event_Name' => $event_name,
					'Event_Date' => date("Y-m-d H:i", $date),
					'FK_Project_Id' => $project_id,
					'Create_Date' => $create_date,
					'FK_User_Id' => $user_id,
					'Description' => $description);
			$this->db->insert('btf2_schedule', $data);
			if ($recur > 1)
			{
				for ($i = 1; $i < $recur; $i++)
				{
					$date = strtotime("+7 days", $date);
					$data = array(
							'Event_Name' => $event_name,
							'Event_Date' => date("Y-m-d H:i", $date),
							'FK_Project_Id' => $project_id,
							'Create_Date' => $create_date,
							'FK_User_Id' => $user_id,
							'Description' => $description);
					$this->db->insert('btf2_schedule', $data);
				}
			}
			redirect('main/project_schedule/'.$this->input->post('FK_Project_Id'));
		} else {
			redirect('auth/login');
		}
	}

	function delete_event($project_id, $event_id)
	{
		if($this->ion_auth->logged_in())
		{
			$this->db->delete('btf2_schedule', array('Event_Id' => $event_id));
			redirect('main/project_schedule/'.$project_id);
		} else {
			redirect('auth/login');
		}
	}

	function user_profile($user_id)
		{
		    if($this->ion_auth->logged_in())
		    {
		    	$data = array();
		    	$data['page'] = 'user_profile';
				$data['in_project'] = false;
				$data['user_info'] = $this->ion_auth->user($user_id)->row();
				$data['interests'] = $this->m_btf2_tags->get_tags_for_user($user_id, 'Interest');
				$data['skills'] = $this->m_btf2_tags->get_tags_for_user($user_id, 'Skill');

		      	$this->load->view('main_head', $data);
		    	$this->load->view('main_user_profile', $data);
		    	$this->load->view('main_foot');
		    } else {
		    	redirect('auth/login');
		    }
		}

		function user_settings($tab = 'account')
		{
		    if($this->ion_auth->logged_in())
		    {
		    	$data = array();
		    	$data['page'] = 'user_settings';
				$data['in_project'] = false;
				$data['tab'] = $tab;
				$data['user_info'] = $this->ion_auth->user()->row();
				$data['interest_count'] = $this->m_btf2_tags->get_tag_count_type($data['user_info']->id, 'Interest');
				$data['interests'] = $this->m_btf2_tags->get_tags_for_user($data['user_info']->id, 'Interest');
				$data['skill_count'] = $this->m_btf2_tags->get_tag_count_type($data['user_info']->id, 'Skill');
				$data['skills'] = $this->m_btf2_tags->get_tags_for_user($data['user_info']->id, 'Skill');

				//echo $data['user_info']->password;
		      	$this->load->view('main_head', $data);
		    	$this->load->view('main_user_settings', $data);
		    	$this->load->view('main_foot');
		    } else {
		    	redirect('auth/login');
		    }
		}

		function update_account()
		{
		    if($this->ion_auth->logged_in())
		    {
		    	//first, update basic info
		    	$user_info = $this->ion_auth->user()->row();
		    	$data = array(
		    		'first_name' => $this->pcs_utility->db_clean(strip_tags($this->input->post('first_name')),100),
		    		'last_name' => $this->pcs_utility->db_clean(strip_tags($this->input->post('last_name')),100),
		    		'username' => $this->pcs_utility->db_clean(strip_tags($this->input->post('username')),100),
		    		'email' => $this->pcs_utility->db_clean(strip_tags($this->input->post('email')),100),
						'notification' => $this->pcs_utility->db_clean(strip_tags($this->input->post('notification')),100),
						'phone' => $this->pcs_utility->db_clean(strip_tags($this->input->post('phone')),100),
						'phone_carrier' => $this->pcs_utility->db_clean(strip_tags($this->input->post('phone_carrier')),100),
		    		'location' => $this->pcs_utility->db_clean(strip_tags($this->input->post('location')),100),
		    		'description' => $this->pcs_utility->db_clean(strip_tags($this->input->post('description')),255),
		    		'interest_group_name' => $this->pcs_utility->db_clean(strip_tags($this->input->post('interest_group_name')),100)
		    	);
		        $this->ion_auth->update($user_info->id, $data);
		        redirect('main/user_settings/account');
		    } else {
		    	redirect('auth/login');
		    }
		}

		function update_password()
		{
		    if($this->ion_auth->logged_in())
		    {
		    	$user_info = $this->ion_auth->user()->row();

	    		if ($this->input->post('new_password') == $this->input->post('new_password_again'))
	    		{
					if ($this->ion_auth->change_password($user_info->username, $this->input->post('password'), $this->input->post('new_password')))
					{
						redirect('main/user_settings/account');
					} else {
						echo $this->ion_auth->errors();
					}
	    		}
		    } else {
		    	redirect('auth/login');
		    }
		}

	    function update_photo()
	    {
	        if($this->ion_auth->logged_in())
	        {

	        	$config['upload_path']          = $this->config->item('btf_mug_path');
	        	$config['allowed_types']        = 'gif|jpg|png';
	        	$config['max_width']            = 480;
	        	$config['max_height']           = 480;

	        	$this->load->library('upload', $config);

	            if ($this->upload->do_upload('userfile'))
	            {
	            	$user_info = $this->ion_auth->user()->row();
	                if ($user_info->mug != 'mystery-man.jpg')
	                {
	                    $path_to_file = $this->config->item('btf_mug_path').$user_info->mug;
	                    unlink($path_to_file);
	                }
	                $file_info = $this->upload->data();
	                $this->ion_auth->update($user_info->id, array('mug' => $file_info['file_name']));
	                redirect('main/user_settings/image');
	            } else {
	            	echo $this->upload->display_errors();
	            }

	        } else {
	        	redirect('auth/login');
	        }
	    }

	    function add_tag()
	    {
	    	if($this->ion_auth->logged_in())
	    	{
	    		if($this->input->post('I_Tag_Text')||$this->input->post('S_Tag_Text')){
	    		    $this->m_btf2_tags->new_tag();
	    		}
	    		$user_info = $this->ion_auth->user()->row();
	    		redirect('main/user_settings/tags');
	    	} else {
	    		redirect('auth/login');
	    	}
	    }

	    function delete_tag($tag_id)
	    {
	        if($this->ion_auth->logged_in())
	        {
	            $this->m_btf2_tags->delete_tag($tag_id);
	            redirect('main/user_settings/tags');
	        } else {
	        	echo $this->upload->display_errors();
	        }

	    }

			function timezone()
			{
				$this->load->view("timezone");
			}

			function privacy()
		    {
		            $data = array();
		            $data['page'] = 'privacy_policy';
		            $data['in_project'] = false;

		            $this->load->view('main_head', $data);
		            $this->load->view('main_privacy', $data);
		            $this->load->view('main_foot');


		    }

		    function help()
		    {
		            $data = array();
		            $data['page'] = 'privacy_policy';
		            $data['in_project'] = false;

		            $this->load->view('main_head', $data);
		            $this->load->view('main_help', $data);
		            $this->load->view('main_foot');

		    }

		    function about()
		    {
		            $data = array();
		            $data['page'] = 'privacy_policy';
		            $data['in_project'] = false;

		            $this->load->view('main_head', $data);
		            $this->load->view('main_about', $data);
		            $this->load->view('main_foot');

		    }

		    function contact()
		    {

		            $data = array();
		            $data['page'] = 'privacy_policy';
		            $data['in_project'] = false;

		            $this->load->view('main_head', $data);
		            $this->load->view('main_contact', $data);
		            $this->load->view('main_foot');

		    }

			function process_contact()
			{
					$this->load->library('email');

			        $email = $this->input->post('Email');
			        $subject = $this->input->post('Subject');
			        $user_msg = $this->input->post('Message');

					$this->email->from($email, $email);
					$this->email->to('paul@schuytema.com');

					$this->email->subject($subject);

					$msg = "Email sent from the BTF2.dev contact form.\n\n";
					$msg = $msg.$user_msg;

					$this->email->message($msg);
					$this->email->send();

					redirect('main/home');
			}

			function tag_suggest($tag="", $type="")
			{
				if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
				{
						$data['message'] = NULL;
						$query = $this->m_btf2_tags->suggest($tag, $type);
						if($query->num_rows() > 0)
						{
								$data['message'] = array();
								foreach($query->result() as $row)
								{
										$data['message'][] = array('label'=> $row->Tag_Text, 'value'=> $row->Tag_Text);
								}
						}
						echo json_encode($data);
				}
			}

			function upgrade()
			{

							$data = array();
							$data['page'] = 'upgrade';
							$data['in_project'] = false;
							$data['user_info'] = $this->ion_auth->user()->row();

							$this->load->view('main_head', $data);
							$this->load->view('main_upgrade', $data);
							$this->load->view('main_foot');
			}

			function submit_upgrade($user_id)
			{
				$this->m_btf2_users->update_user_level($user_id);

				redirect('main/upgrade_complete');
			}

			function upgrade_complete()
			{

							$data = array();
							$data['page'] = 'upgrade_complete';
							$data['in_project'] = false;
							$data['user_info'] = $this->ion_auth->user()->row();

							$this->load->view('main_head', $data);
							$this->load->view('main_upgrade_complete', $data);
							$this->load->view('main_foot');
			}

			function inbox()
			{
				if ($this->ion_auth->logged_in())
					{
						$data = array();
						$data['page'] = 'inbox';
						$data['in_project'] = false;
						$data['user_info'] = $this->ion_auth->user()->row();

						$this->load->view('main_head', $data);
						$this->load->view('main_user_inbox', $data);
						$this->load->view('main_foot');
					}
				else
				{
					redirect('auth_login');
				}
			}

			function message_user($user_id, $succ_status = NULL)
			{
				if ($this->ion_auth->logged_in())
					{
						$data = array();
						$data['page'] = 'message_user';
						$data['in_project'] = false;
						$data['user_id'] = $user_id;
						if ($succ_status === 'success')
						{
							$data['succ_status'] = 'Message sent successfully!';
						}
						else {
							$data['succ_status'] = '';
						}

						$this->load->view('main_head', $data);
						$this->load->view('main_message_user', $data);
						$this->load->view('main_foot');
					}
				else
				{
					redirect('auth_login');
				}
			}

			function message_window($message_id)
			{
				if ($this->ion_auth->logged_in())
				{
					$data = array();
					$data['page'] = 'message_window';
					$data['in_project'] = false;
					$data['message_id'] = $message_id;

					$this->load->view('main_head', $data);
					$this->load->view('main_message_window', $data);
					$this->load->view('main_foot');
				}
				else
				{
					redirect('auth_login');
				}
			}

			function interest_groups($group_id = 0)
			{
				if($this->ion_auth->logged_in())
				{
					$data = array();
					$data['page'] = 'interest_group';
					$data['in_project'] = false;
					$data['group_id'] = $group_id;

					$this->load->view('main_head', $data);
					$this->load->view('main_interest_groups', $data);
					$this->load->view('main_foot');
				} else {
					redirect('auth/login');
				}
			}

			function create_interest_group()
			{
				if($this->ion_auth->logged_in())
				{
					$data = array();
					$data['page'] = 'interest_group';
					$data['in_project'] = false;

					$this->load->view('main_head', $data);
					$this->load->view('create_interest_group', $data);
					$this->load->view('main_foot');
				} else {
					redirect('auth/login');
				}
			}

			function process_add_interest_group()
			{
				if($this->ion_auth->logged_in())
				{
					$this->m_btf2_interest_groups->add_interest_group();
					redirect('main/interest_groups/');
				} else {
					redirect('auth/login');
				}
			}

			function add_content_to_group($group_id)
			{
				if($this->ion_auth->logged_in())
				{
					$data = array();
					$data['page'] = 'interest_group';
					$data['in_project'] = false;
					$data['group_id'] = $group_id;

					$this->load->view('main_head', $data);
					$this->load->view('interest_group_add_content', $data);
					$this->load->view('main_foot');
				} else {
					redirect('auth/login');
				}
			}

			function process_add_group_content($group_id)
			{
				if($this->ion_auth->logged_in())
				{
					$this->m_btf2_interest_groups->add_content_to_group($group_id);
					redirect('main/interest_groups/'.$group_id);
				} else {
					redirect('auth/login');
				}
			}

			function publish_feed($feed_id, $group_id)
			{
				if($this->ion_auth->logged_in())
				{
					$this->m_btf2_interest_groups->publish_feed($feed_id, $group_id);
					redirect('main/interest_groups/'.$group_id);
				} else {
					redirect('auth/login');
				}
			}

			function hide_feed($feed_id, $group_id)
			{
				if($this->ion_auth->logged_in())
				{
					$this->db->query("UPDATE btf2_interest_group_feed SET Published = 'hidden' WHERE PK_Interest_Group_Feed_Id = $feed_id");
					redirect('main/interest_groups/'.$group_id);
				} else {
					redirect('auth/login');
				}
			}

			function add_feed_comment($feed_id,$group_id)
			{
				if($this->ion_auth->logged_in())
				{
					$this->m_btf2_interest_groups->add_feed_comment($feed_id);
					redirect('main/interest_group_post/'.$group_id.'/'.$feed_id);
				} else {
					redirect('auth/login');
				}

			}

			function add_feed_like($user_id,$feed_id,$group_id,$sourse)
			{
				if($this->ion_auth->logged_in())
				{
					$this->m_btf2_interest_groups->add_feed_like($user_id, $feed_id);
					if($sourse == 'feed')
					{
						redirect('main/interest_groups/'.$group_id);
					} else if ($sourse == 'post') {
						redirect('main/interest_group_post/'.$group_id.'/'.$feed_id);
					}
				} else {
					redirect('auth/login');
				}
			}

			function interest_group_post($group_id, $post_id, $sourse = 'none')
			{
				$data = array();
				$data['page'] = 'interest_group';
				$data['in_project'] = false;
				$data['group_id'] = $group_id;
				$data['post_id'] = $post_id;
				$data['autofocus'] = $sourse == 'cmt'? true : false;
				$this->m_btf2_interest_groups->increase_feed_views($post_id);
				$this->load->view('main_head',$data);
				$this->load->view('interest_group_post', $data);
				$this->load->view('main_foot');
			}

			function join_group($group_id, $user_id)
			{
				if($this->ion_auth->logged_in())
				{
					$this->db->query("INSERT INTO btf2_interest_group_members (FK_User_Id, FK_Interest_Group_Id) VALUES ($user_id, $group_id)");
					redirect('main/interest_groups/'.$group_id);
				} else {
					redirect('auth/login');
				}
			}

			function leave_group($group_id)
			{
				if($this->ion_auth->logged_in())
				{
					$this->m_btf2_interest_groups->leave_group($group_id, $this->ion_auth->user()->row()->id);
					redirect('main/interest_groups/'.$group_id);
				} else {
					redirect('auth/login');
				}
			}

			function connectp2g($group_id)
			{
				if($this->ion_auth->logged_in())
				{
					$this->m_btf2_interest_groups->connect_project_to_group($group_id);
					redirect('main/interest_groups/'.$group_id);
				} else {
					redirect('auth/login');
				}
			}

			function group_invite_email($group_id)
			{
				if($this->ion_auth->logged_in())
				{
					if($this->m_btf2_interest_groups->is_group_member($group_id,$this->ion_auth->user()->row()->id))
					{
			    	$data = array();
			    	$data['page'] = 'project_invite_email';
			    	$data['group_id'] = $group_id;
						$data['in_project'] = false;
			      $this->load->view('main_head', $data);
			    	$this->load->view('interest_group_invite_email', $data);
			    	$this->load->view('main_foot');
					} else {
						redirect('main/interest_groups');
					}
		    } else {
		    	redirect('auth/login');
		    }
			}

			function send_group_invite_email()
			{
				if($this->ion_auth->logged_in())
				{
					$group_info = $this->m_btf2_interest_groups->get_interest_group($this->input->post('Group_Id'));
					$user_info = $this->ion_auth->user()->row();;

					$this->load->library('email');

					$email = $this->input->post('Email');

					$this->email->from('admin@btffellows.com', 'Breakthrough Foundry (2) dev server');
					$this->email->to($email);

					$this->email->subject('Breakthrough Foundry 2: You have received a project invite.');

					$msg = "Email sent from the BTF2.dev webserver.\n\n";
					$msg = $msg."You have been invited to join a BTF group.\n\n";
					$msg = $msg."Group name: ".$group_info['Name']."\n\n";
					$msg = $msg."Your Group Invite Key: ".$group_info['Invite_Key']."\n\n";
					$msg = $msg."You have been invited by ".$user_info->first_name." ".$user_info->last_name."\n\n";
					$msg = $msg."If you are not a member, you can join for free. Below is the link to create an account:\n\n";
					$msg = $msg.base_url()."auth/create_user\n\n";
					$msg = $msg."Once logged in, you can click Enter Group Invite on the Interest Group page. You can also use this direct link:\n\n";
					$msg = $msg.base_url()."main/group_invite/1\n\n";


					$this->email->message($msg);
					$this->email->send();

					redirect('main/interest_groups/'.$group_info['PK_Interest_Group_Id']);

				} else {
					redirect('auth/login');
				}
			}

			function group_invite($good_code = 0)
			{
				if($this->ion_auth->logged_in())
				{
			    	$data = array();
			    	$data['page'] = 'group_invite';
						$data['in_project'] = false;
			    	if ($good_code == 0)
			    	{
			    		$data['bad_invite_code'] = '<p><font color="red">Please enter a valid invite code.</font></p>';
			    	} else {
			    		$data['bad_invite_code'] = '';
			    	}
			      $this->load->view('main_head', $data);
			    	$this->load->view('interest_group_invite_code', $data);
			    	$this->load->view('main_foot');
			    } else {
			    	redirect('auth/login');
			    }
			}

			function process_group_invite()
			{
				if($this->ion_auth->logged_in())
				{
					$invite_code = $this->pcs_utility->db_clean(strip_tags($this->input->post('Invite_Code')));
					if ($this->m_btf2_interest_groups->group_invite($invite_code))
					{
						redirect('main/interest_groups');
					} else {
						redirect('main/group_invite/0');
					}
			   } else {
			   	 redirect('auth/login');
			   }
			}

			function remove_group_project($group_id, $project_id)
			{
				if($this->ion_auth->logged_in())
				{
					$this->m_btf2_interest_groups->remove_group_project($group_id, $project_id);
					redirect('main/interest_groups/'.$group_id);
				} else {
					redirect('auth/login');
				}
			}

			function delete_group($group_id)
			{
				if($this->ion_auth->logged_in())
				{
					$this->m_btf2_interest_groups->delete_group($group_id);
					redirect('main/interest_groups');
				} else {
					redirect('auth/login');
				}
			}

			function delete_feed_post($feed_id,$group_id)
			{
				if($this->ion_auth->logged_in())
				{
					$this->m_btf2_interest_groups->delete_feed_post($feed_id);
					redirect('main/interest_groups/'.$group_id);
				} else {
					redirect('auth/login');
				}
			}

			function delete_feed_comment($comment_id, $post_id)
			{
				if($this->ion_auth->logged_in())
				{
					$this->m_btf2_interest_groups->delete_feed_comment($comment_id);
					$group_id = $this->m_btf2_interest_groups->get_group_id_from_post($post_id);
					redirect('main/interest_group_post/'.$group_id.'/'.$post_id);
				} else {
					redirect('auth/login');
				}
			}

			function make_group_admin($group_id, $user_id)
			{
				if($this->ion_auth->logged_in())
				{
					if($this->m_btf2_interest_groups->is_group_admin($group_id, $this->ion_auth->user()->row()->id))
					{
						$this->m_btf2_interest_groups->make_admin($group_id, $user_id);
						redirect('main/interest_groups/'.$group_id.'#people');
					} else {
						redirect('main/interest_groups');
					}
				} else {
					redirect('auth/login');
				}
			}
			function make_default_group($group_id, $user_id)
			{
				if($this->ion_auth->logged_in())
				{
					if($this->m_btf2_interest_groups->is_group_member($group_id, $this->ion_auth->user()->row()->id))
					{
						$this->m_btf2_interest_groups->make_default_group($group_id, $user_id);
						redirect('main/interest_groups/'.$group_id);
					} else {
						redirect('main/interest_groups');
					}
				} else {
					redirect('auth/login');
				}
			}

		}
?>
