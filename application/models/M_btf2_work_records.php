<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_btf2_work_records extends CI_Model{
    
    function __construct(){
        parent::__construct();
    }

    
    function get_work_records($project_id, $user_id)
    {
    	$results = NULL;
    	$this->db->order_by('Work_Date', 'DESC');
    	$query = $this->db->get_where('btf2_work_records', array('FK_Project_Id' => $project_id, 'FK_User_Id' => $user_id));
    	if ($query->num_rows())
    	{
    		$results = $query->result_array();
    	}
    	return $results;
    }
    
    function get_num_of_team_work_records($project_id, $user_id)
    {
    	//number of work records for a project OTHER than the indicated user id
    	$this->db->order_by('Work_Date', 'DESC');
    	$query = $this->db->get_where('btf2_work_records', array('FK_Project_Id' => $project_id, 'FK_User_Id !=' => $user_id));
    	return $query->num_rows();
    }
    
    function get_specific_work_record($record_id)
    {
    	$results = NULL;
    	$query = $this->db->get_where('btf2_work_records', array('PK_Work_Record_Id' => $record_id));
    	if ($query->num_rows())
    	{
    		$results = $query->result_array();
    	}
    	return $results[0];
    }
    
    function delete_work_record($record_id=NULL) 
    {
    	if(isset($record_id))
    	{
            // Delete record
            $this->db->where('PK_Work_Record_Id', $record_id);
            $this->db->limit(1);
            $this->db->delete('btf2_work_records');
    	}
    }
    
    function new_work_record($record_id)
    {
    	$data = array(
            'FK_User_Id' => $this->pcs_utility->id_clean($this->input->post('FK_User_Id')),
            'FK_Project_Id' => $this->pcs_utility->id_clean($this->input->post('FK_Project_Id')),
            'FK_Task_Id' => $this->pcs_utility->id_clean($this->input->post('FK_Task_Id')),
            'Work_Date' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Work_Date'))),
            'Description' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Description'))),
            'Status' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Status'))),
            'Unit_Type' => $this->pcs_utility->db_clean(strip_tags($this->input->post('Unit_Type'))),
            'Unit_Value' => $this->pcs_utility->id_clean($this->input->post('Unit_Value')),
            'Work_Units' => $this->pcs_utility->id_clean($this->input->post('Work_Units'))
    	);
    	 
    	if($record_id == 0) 
    	{
            $this->db->insert('btf2_work_records', $data);
    	}
    	else {
            $this->db->where('PK_Work_Record_Id',$record_id);
            $this->db->update('btf2_work_records', $data);
    	}
    }
    

    
        
}
/* End of file m_btf2_work_records.php */
/* Location: ./application/models/m_btf2_work_records.php */