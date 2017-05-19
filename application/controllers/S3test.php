<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class s3test extends CI_Controller
{
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
      $this->load->model('m_btf2_chat');
      $this->load->model('m_btf2_tasks');
      $this->load->model('m_btf2_tags');
      $this->load->config('btf2');
      $this->load->config('s3');
    }


    function files($project_id, $msg = NULL)
    {
      if($this->ion_auth->logged_in())
      {
        $data = array();
        $data['page'] = 'files';
        $data['in_project'] = true;
        $data['project_id'] = $project_id;
        if ($msg === 'success'){
          $data['msg'] = 'File uploaded successfully.</br>';
        }else {
          $data['msg'] = '';
        }
        $this->load->view('main_head', $data);
        $this->load->view('main_files', $data);
        $this->load->view('main_foot');
      } else {
        redirect('auth/login');
      }
    }

    public function addObject(){
		if(!empty($_FILES)){
			$bucket = 'btf2_project_'.$_POST['FK_Project_Id'];
			$fileName = str_replace(" ","_",$_FILES['userFile']['name']);
			$filePath = $_FILES['userFile']['tmp_name'];
			$input = S3::inputFile($filePath, false);
			$acl = S3::ACL_PUBLIC_READ;
			S3::putObject(
				$input,
				$bucket,
				$fileName,
				$acl
			);
			//set flash message
			redirect('s3test/files/'.$_POST['FK_Project_Id'].'/success');

		}
	}

	//Delete bucket and its object
	public function delete($project_id, $uri){
		//print_r($_GET);exit
    S3::deleteObject('btf2_project_'.$project_id, $uri);
    redirect('s3test/files/'.$project_id);
	}
}

/* End of file s3test.php */
/* Location: ./application/controllers/s3test.php */
