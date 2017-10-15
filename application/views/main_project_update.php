<?php
	$user_info = $this->ion_auth->user()->row();
	$user_project_count = $user_info->projects_owned;//$this->m_btf2_projects->count_user_projects($user_info->id);
	$config_check = 'UL' . $user_info->user_level . '_projects';

	if($project_info != NULL)
  {
		$query = $this->db->query('SELECT Project_Pages FROM btf2_projects WHERE PK_Project_Id ='.$project_info['PK_Project_Id']);
	  if ($query->num_rows())
	  {
	    $results = $query->result_array();
	    foreach ($results as $row)
	    {
	      $buttons[] = $row;
	    }
			$buttons = unserialize($buttons[0]['Project_Pages']);
	  }
	} else {
		$buttons = array("1","2","3","4","5","6");
	}
?>
      <div>
      	<?php
					//echo $user_project_count;
					/*if ($this->config->item($config_check) > $user_project_count)
					{*/
	      		if (isset($project_info))
	      		{
	      			echo '<h2>Edit Your Project</h2>';
	      		} else {
	      			echo '<h2>Create A New Project</h2>';
	      		}
	      	?>
	        <?php
	        	echo '<form action="'.base_url().'main/process_update_project/'.$project_id.'" method="post">';
	        ?>
	          <div class="form-group">
	            <label for="Work_Units">Project Name</label>
	          	<input type="text" class="form-control" id="Name" name="Name" value="<?php echo set_value('Name', (isset($project_info)) ? $project_info['Name'] : NULL); ?>">
	          </div>
	          <div class="form-group">
	            <label for="Description">Description</label>
	            <textarea class="form-control" rows="2" id="Description" name="Description"><?php echo set_value('Description', (isset($project_info)) ? $project_info['Description'] : NULL); ?></textarea>
	          </div>
	          <div class="form-group">
	            <label for="Status">Status</label>
	            <?php
					echo form_dropdown('Status', $this->config->item('project_status'), set_value('Status', (isset($project_info)) ? $project_info['Status'] : 'active'), 'class="form-control" id="Status" name="Status"');
	            ?>
	          </div>
            <div class="form-group">
              <label for="Project_Pages">Available Pages:</label></br>
              <input type="checkbox" name="Available_Pages[]" value="1" <?php if(in_array("1",$buttons)){echo " checked";}?>>
                <label>&nbsp;Chat</label></br>
              <input type="checkbox" name="Available_Pages[]" value="2" <?php if(in_array("2",$buttons)){echo " checked";}?>>
                <label>&nbsp;Schedule</label></br>
              <input type="checkbox" name="Available_Pages[]" value="3" <?php if(in_array("3",$buttons)){echo " checked";}?>>
                <label>&nbsp;Manage Tasks</label></br>
              <input type="checkbox" name="Available_Pages[]" value="4" <?php if(in_array("4",$buttons)){echo " checked";}?>>
                <label>&nbsp;Team Members</label></br>
              <input type="checkbox" name="Available_Pages[]" value="5" <?php if(in_array("5",$buttons)){echo " checked";}?>>
                <label>&nbsp;Project Files</label></br>
              <input type="checkbox" name="Available_Pages[]" value="6" <?php if(in_array("6",$buttons)){echo " checked";}?>>
                <label>&nbsp;Contributions</label></br>
            </div>
	          <input type="hidden" name="FK_User_Id" value="<?php echo set_value('FK_User_Id', (isset($project_info)) ? $project_info['FK_User_Id'] : $user_info->id); ?>">
	          <button type="submit" class="btn btn-primary">Submit</button>
	        </form><?php
				/*}
				else {
					redirect('main/upgrade');
				}*/?>


      </div>
