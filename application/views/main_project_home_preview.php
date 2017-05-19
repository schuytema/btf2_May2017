<?php
	$user_info = $this->ion_auth->user()->row();
	$project_info = $this->m_btf2_projects->get_project_info($project_id);
	$last_login = $_SESSION['last_login'];
	/*$query = $this->db->query("SELECT * FROM btf2_project_members WHERE FK_Project_Id = $project_id AND Join_Date >= '$last_login'");
	//$results = $query->result_array();
	$query = $this->db->query('SELECT Project_Pages FROM btf2_projects WHERE PK_Project_Id ='.$project_info['PK_Project_Id']);
	if ($query->num_rows())
	{
	  $result = $query->result_array();
	  foreach ($result as $row)
	  {
	    $pages[] = $row;
	  }
	}

	$pages = unserialize($pages[0]['Project_Pages']);*/
?>
      <div class="starter-template">
        <h1><?php echo $project_info['Name']; ?></h1>
        <p class="lead"><?php echo $project_info['Description']; ?></p>
			</div>
