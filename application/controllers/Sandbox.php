<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sandbox extends CI_Controller {

	public function __construct()
	{
    parent::__construct();

    $this->load->database();

    $this->load->library('session');
    $this->load->library('pcs_utility');
    $this->load->library('ion_auth');

    $this->load->helper('url');
    $this->load->helper('form');

    $this->load->model('m_btf2_users');
    $this->load->model('m_btf2_interest_groups');
    $this->load->model('m_btf2_projects');
    $this->load->model('m_btf2_work_records');
	$this->load->model('m_btf2_chat');
	$this->load->model('m_btf2_tasks');
	$this->load->model('m_btf2_tags');

    $this->load->config('btf2');

	}

	public function index()
	{
		redirect('main/no_rights');
	}



	function project_list()
	{
	    $data = array();
	    $data['page'] = 'project_list';
        $this->load->view('main_head', $data);
      	$this->load->view('main_project_list');
      	$this->load->view('main_foot');
	}

	function update_project($project_id = 0)
	{
	    $data = array();
	    $data['page'] = 'update_project';
	    $data['project_id'] = $project_id;
	    if ($project_id != 0)
	    {
	    	$data['project_info'] = $this->m_btf2_projects->get_project_info($project_id);
	    } else {
	    	$data['project_info'] = NULL;
	    }
	    $this->load->view('main_head', $data);
	  	$this->load->view('main_project_update');
	  	$this->load->view('main_foot');
	}

	function process_update_project($project_id)
	{

		$this->m_btf2_projects->new_project($project_id);
		redirect('main/project_list/');

	}

	function project_home($project_id)
	{
	    if ($this->session->has_userdata('user_info'))
	    {
	    	$data = array();
	    	$data['page'] = 'project_home';
	    	$data['project_id'] = $project_id;
	      $this->load->view('main_head', $data);
	    	$this->load->view('main_project_home', $data);
	    	$this->load->view('main_foot');
	    } else {
	    	redirect('main/no_rights');
	    }
	}

	function project_invite($good_code = 0)
	{
		if($this->ion_auth->logged_in())
		{
	    	$data = array();
	    	$data['page'] = 'project_invite';
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
				redirect('sandbox/project_invite/0');
			}
	    } else {
	    	redirect('auth/login');
	    }
	}

	function work_list($project_id)
	{
	    if ($this->session->has_userdata('user_info'))
	    {
	    	$data = array();
	    	$data['page'] = 'work_list';
	    	$data['project_id'] = $project_id;
	      $this->load->view('main_head', $data);
	    	$this->load->view('tracker_work_list', $data);
	    	$this->load->view('main_foot');
	    } else {
	    	redirect('main/no_rights');
	    }
	}

	function team_list($project_id)
	{
	    if ($this->session->has_userdata('user_info'))
	    {
	    	$data = array();
	    	$data['page'] = 'team_list';
	    	$data['project_id'] = $project_id;
	      $this->load->view('main_head', $data);
	    	$this->load->view('main_team_list', $data);
	    	$this->load->view('main_foot');
	    } else {
	    	redirect('main/no_rights');
	    }
	}

	function update_work($project_id, $work_id)
	{
	    if ($this->session->has_userdata('user_info'))
	    {
	    	$data = array();
	    	$data['page'] = 'work_update';
	    	$data['project_id'] = $project_id;
	    	$data['work_id'] = $work_id;
	    	$data['work_info'] = $this->m_btf2_work_records->get_specific_work_record($work_id);
	      $this->load->view('main_head', $data);
	    	$this->load->view('tracker_work_update', $data);
	    	$this->load->view('main_foot');
	    } else {
	    	redirect('main/no_rights');
	    }
	}

	function process_update_work($project_id, $work_id)
	{
	    if ($this->session->has_userdata('user_info'))
	    {
				$this->m_btf2_work_records->new_work_record($work_id);
				redirect('tracker/work_list/'.$project_id);
	    } else {
	    	redirect('main/no_rights');
	    }
	}

	function project_invite_email($project_id)
	{
		if($this->ion_auth->logged_in())
		{
	    	$data = array();
	    	$data['page'] = 'project_invite_email';
	    	$data['project_id'] = $project_id;
	      	$this->load->view('main_head', $data);
	    	$this->load->view('main_project_invite_email', $data);
	    	$this->load->view('main_foot');
	    } else {
	    	redirect('auth/login');
	    }
	}

	public function send_project_invite_email()
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
			$msg = $msg."Your Project Invite Key: 8asaxuRu\n\n";
			$msg = $msg."You have been invited by ".$user_info->first_name." ".$user_info->last_name."\n\n";
			$msg = $msg."If you are not a member, you can join for free. Insert join link here.\n\n";
			$msg = $msg."Once logged in, you can click Enter Project Invite on your Project page. You can also use this direct link:\n\n";
			$msg = $msg."http://63.247.137.231/~btffellows/main/project_invite/1\n\n";


			$this->email->message($msg);
			$this->email->send();

			redirect('main/project_home/'.$project_info['PK_Project_Id']);

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
	    		'location' => $this->pcs_utility->db_clean(strip_tags($this->input->post('location')),100),
	    		'description' => $this->pcs_utility->db_clean(strip_tags($this->input->post('description')),255)
	    	);
	        $this->ion_auth->update($user_info->id, $data);
	        redirect('sandbox/user_settings/account');
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
					redirect('sandbox/user_settings/account');
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
                redirect('sandbox/user_settings/image');
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
    		if($this->input->post('Tag_Text')){
    		    $this->m_btf2_tags->new_tag();
    		}
    		$user_info = $this->ion_auth->user()->row();
    		redirect('sandbox/user_settings/tags');
    	} else {
    		redirect('auth/login');
    	}
    }

    function delete_tag($tag_id)
    {
        if($this->ion_auth->logged_in())
        {
            $this->m_btf2_tags->delete_tag($tag_id);
            redirect('sandbox/user_settings/tags');
        } else {
        	redirect('auth/login');
        }

    }

    function privacy()
    {
        if($this->ion_auth->logged_in())
        {
            $data = array();
            $data['page'] = 'privacy_policy';
            $data['in_project'] = false;

            $this->load->view('main_head', $data);
            $this->load->view('main_privacy', $data);
            $this->load->view('main_foot');

        } else {
        	redirect('auth/login');
        }

    }

    function help()
    {
        if($this->ion_auth->logged_in())
        {
            $data = array();
            $data['page'] = 'privacy_policy';
            $data['in_project'] = false;

            $this->load->view('main_head', $data);
            $this->load->view('main_help', $data);
            $this->load->view('main_foot');

        } else {
        	redirect('auth/login');
        }

    }

    function about()
    {
        if($this->ion_auth->logged_in())
        {
            $data = array();
            $data['page'] = 'privacy_policy';
            $data['in_project'] = false;

            $this->load->view('main_head', $data);
            $this->load->view('main_about', $data);
            $this->load->view('main_foot');

        } else {
        	redirect('auth/login');
        }

    }

    function contact()
    {
        if($this->ion_auth->logged_in())
        {
            $data = array();
            $data['page'] = 'privacy_policy';
            $data['in_project'] = false;

            $this->load->view('main_head', $data);
            $this->load->view('main_contact', $data);
            $this->load->view('main_foot');

        } else {
        	redirect('auth/login');
        }

    }

	function process_contact()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->library('email');

	        $email = $this->input->post('Email');

			$this->email->from($email, $email);
			$this->email->to('paul@schuytema');

			$this->email->subject($this->input->post('Subject'));

			$msg = "Email sent from the BTF2.dev contact form.\n\n";
			$msg = $msg.$this->input->post('Message');

			$this->email->message($msg);
			$this->email->send();

			redirect('main/home');

		} else {
			redirect('auth/login');
		}
	}

	function test_sms()
	{
		if($this->ion_auth->logged_in())
		{
			$this->load->library('email');

	        $email = 'noreply@breakthroughfoundry.com';

			$this->email->from($email, $email);
			$this->email->to('3093377007@vtext.com');

			$this->email->subject('');

			$msg = "Sam: new tasks have been posted on BTF Test for effectiveness!: http://63.247.137.231/~btffellows/main/tasks/47";

			$this->email->message($msg);
			$this->email->send();

			redirect('main/home');

		} else {
			redirect('auth/login');
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
			redirect('sandbox/interest_groups/');
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
			redirect('sandbox/interest_groups/');
		} else {
			redirect('auth/login');
		}
	}

	function publish_feed($feed_id, $group_id)
	{
		if($this->ion_auth->logged_in())
		{
			$this->m_btf2_interest_groups->publish_feed($feed_id, $group_id);
			redirect('sandbox/interest_groups/'.$group_id);
		} else {
			redirect('auth/login');
		}
	}

	function hide_feed($feed_id, $group_id)
	{
		if($this->ion_auth->logged_in())
		{
			$this->db->query("UPDATE btf2_interest_group_feed SET Published = 'hidden' WHERE PK_Interest_Group_Feed_Id = $feed_id");
			redirect('sandbox/interest_groups/'.$group_id);
		} else {
			redirect('auth/login');
		}
	}

	function interest_group_post($group_id, $post_id)
	{
		$data = array();
		$data['page'] = 'interest_group';
		$data['in_project'] = false;
		$data['group_id'] = $group_id;
		$data['post_id'] = $post_id;

		$this->load->view('main_head',$data);
		$this->load->view('interest_group_post', $data);
		$this->load->view('main_foot');
	}

	function join_group($group_id, $user_id)
	{
		if($this->ion_auth->logged_in())
		{
			$this->db->query("INSERT INTO btf2_interest_group_members (FK_User_Id, FK_Interest_Group_Id) VALUES ($user_id, $group_id)");
			redirect('sandbox/interest_groups/'.$group_id);
		} else {
			redirect('auth/login');
		}
	}

	function leave_group($group_id)
	{
		if($this->ion_auth->logged_in())
		{
			$user_id = $this->ion_auth->user()->row()->id;
			$this->db->query("DELETE FROM btf2_interest_group_members WHERE FK_User_Id = $user_id AND FK_Interest_Group_Id = $group_id");
			redirect('sandbox/interest_groups/'.$group_id);
		} else {
			redirect('auth/login');
		}
	}

	function connectp2g($group_id)
	{
		if($this->ion_auth->logged_in())
		{
			$project_name = $this->pcs_utility->db_clean($this->input->post('Chosen_Project'));
			$project_id = $this->m_btf2_projects->get_project_id_by_name($project_name);
			$project_members = $this->m_btf2_projects->get_project_users($project_id['PK_Project_Id']);

			foreach ($project_members as $member)
			{
				$this->db->query("INSERT INTO btf2_interest_group_members (FK_User_Id, FK_Interest_Group_Id, FK_Project_Id) VALUES ($member[FK_User_Id], $group_id, $project_id[PK_Project_Id])");
			}
		}
		redirect('sandbox/interest_groups/'.$group_id);
	}
}
?>
