<?php
	$user_info = $this->ion_auth->user()->row();
	$project_info = $this->m_btf2_projects->get_project_info($project_id);
	$last_login = $_SESSION['last_login'];
	$query = $this->db->query("SELECT * FROM btf2_project_members WHERE FK_Project_Id = $project_id AND Join_Date >= '$last_login'");
	$results = $query->result_array();
	$query = $this->db->query('SELECT Project_Pages FROM btf2_projects WHERE PK_Project_Id ='.$project_info['PK_Project_Id']);
	if ($query->num_rows())
	{
	  $result = $query->result_array();
	  foreach ($result as $row)
	  {
	    $pages[] = $row;
	  }
	}

	$pages = unserialize($pages[0]['Project_Pages']);
?>
      <div class="starter-template">
        <h1><?php echo $project_info['Name']; ?></h1>
				<?php include 'project_buttons.php'; ?>
        <p class="lead"><?php echo $project_info['Description']; ?></p>
				<table border="0" align="center" style="width:83vw; max-width:500px">
					<tr>
						<td align="left">
	        		<a class="btn btn-primary" style="width:120px" href=<?php if(in_array("1",$pages)){echo '"'.base_url(); ?>main/chat_list/<?php echo $project_info['PK_Project_Id'] . '/-general"';} else {echo '"#" disabled';} ?> role="button">Chat</a>
							</br></br>
						</td>
						<td align="right">
							<a class="btn btn-<?php	if($results != NULL){echo 'success';}else{echo 'primary';}?>" style="width:135px" href=<?php if(in_array("4",$pages)){echo '"'.base_url(); ?>main/team_list/<?php echo $project_info['PK_Project_Id'].'"';}else {echo'"#" disabled';}?> role="button">Team Members</a>
								<!--a class="btn btn-primary" style="width:135px" href="<?php echo base_url(); ?>main/team_list/<?php echo $project_info['PK_Project_Id']; ?>" role="button" <?php	if(!in_array("4",$pages)){echo 'disabled';}?>>Team Members</a-->
							</br></br>
						</td>
					</tr>
					<tr>
						<td align="left">
							<a class="btn btn-primary" style="width:120px" href=<?php if(in_array("2",$pages)){echo '"'.base_url(); ?>main/project_schedule/<?php echo $project_info['PK_Project_Id'].'"';} else {echo '"#" disabled';} ?> role="button">Schedule</a>
							</br></br>
						</td>
						<td align="right">
							<a class="btn btn-primary" style="width:135px" href=<?php if(in_array("5",$pages)){echo '"'.base_url(); ?>s3test/files/<?php echo $project_info['PK_Project_Id'].'"';} else {echo '"#" disabled';} ?> role="button">Project Files</a>
							</br></br>
						</td>
					</tr>
					<tr>
						<td align="left">
							<a class="btn btn-primary" style="width:120px" href=<?php if(in_array("3",$pages)){echo '"'.base_url(); ?>main/tasks/<?php echo $project_info['PK_Project_Id'].'"';} else {echo '"#" disabled';} ?> role="button">Manage Tasks</a>
							</br></br>
						</td>
						<td align="right">
			        <a class="btn btn-primary" style="width:135px" href=<?php if(in_array("6",$pages)){echo '"'.base_url(); ?>main/work_list/<?php echo $project_info['PK_Project_Id'].'"';} else {echo '"#" disabled';} ?> role="button">Contributions</a>
							</br></br>
						</td>
					</tr>
				</table>
			</div>
