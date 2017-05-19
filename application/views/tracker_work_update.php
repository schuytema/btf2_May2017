<?php
	$user_info = $this->ion_auth->user()->row();
	$project_info = $this->m_btf2_projects->get_project_info($project_id);
?>
      <div>
      	<?php
      		if (isset($work_info) && ($work_info['FK_User_Id'] != $user_info->id))
      		{
      			$user_temp = $this->m_btf2_users->get_user_info_from_id($work_info['FK_User_Id']);
      			echo '<h2>Log Work for '.$user_temp['first_name'].' '.$user_temp['last_name'].'</h2>';
      		} else {
      			echo '<h2>Log Your Work</h2>';
      		}
      	?>
        <h3><?php echo $project_info['Name']; ?></h3>
        <?php
        	echo '<form action="'.base_url().'main/process_update_work/'.$project_id.'/'.$work_id.'" method="post">';
        ?>
          <div class="form-group">
            <label for="FK_Task_Id">Project Task Associated with Work</label>
            <?php
        		echo form_dropdown('FK_Task_Id', $this->m_btf2_tasks->get_user_tasks_for_work_record($user_info->id, $project_id), set_value('FK_Task_Id', (isset($work_info)) ? $work_info['FK_Task_Id'] : 0), 'class="form-control" id="Unit_Type" name="Unit_Type"');
        	?>
          </div>
          <div class="form-group">
            <label for="Work_Date">Work Date</label>
            <div class='input-group date' id='datetimepicker1'>
	            <input type='text' class="form-control" name="Work_Date" id="Work_Date" value="<?php echo set_value('Work_Date', (isset($work_info)) ? $work_info['Work_Date'] : NULL); ?>" />
	            <span class="input-group-addon">
	                <span class="glyphicon glyphicon-calendar"></span>
	            </span>
	        </div>
          </div>
          <div class="form-group">
            <label for="Description">Description</label>
            <textarea class="form-control" rows="2" id="Description" name="Description"><?php echo set_value('Description', (isset($work_info)) ? $work_info['Description'] : NULL); ?></textarea>
          </div>
          <div class="form-group">
            <label for="Work_Units">How much Work (hours, etc.)?</label>
          	<input type="text" class="form-control" id="Work_Units" name="Work_Units" value="<?php echo set_value('Work_Units', (isset($work_info)) ? $work_info['Work_Units'] : NULL); ?>">
          </div>
          <div class="form-group">
            <label for="Unit_Type">What Type of Contribution?</label>
          	<?php
          		echo form_dropdown('Unit_Type', $this->config->item('work_units'), set_value('Unit_Type', (isset($work_info)) ? $work_info['Unit_Type'] : 'hours'), 'class="form-control" id="Unit_Type" name="Unit_Type"');
          	?>
          </div>
          <div class="form-group">
            <label for="Unit Value">Value of One Unit of Contribution Type?</label>
          	<input type="text" class="form-control" id="Unit_Value" name="Unit_Value" value="<?php echo set_value('Unit_Vale', (isset($work_info)) ? $work_info['Unit_Value'] : NULL); ?>">
          </div>
          <div class="form-group">
            <label for="Status">Status</label>
            <?php
            	if ($this->m_btf2_projects->is_admin($user_info->id, $project_id))
            	{
	            	echo form_dropdown('Status', $this->config->item('status'), set_value('Status', (isset($work_info)) ? $work_info['Status'] : 'logged'), 'class="form-control" id="Status" name="Status"');
            	} else {
            		echo '<input type="text" class="form-control" id="Status" name="Status" value="';
            		echo set_value('Status', (isset($work_info)) ? $work_info['Status'] : 'logged');
            		echo '" readonly>';
            	}
            ?>
          </div>
          <input type="hidden" name="FK_User_Id" value="<?php echo set_value('FK_User_Id', (isset($work_info)) ? $work_info['FK_User_Id'] : $user_info->id); ?>">
          <input type="hidden" name="FK_Project_Id" value="<?php echo $project_id; ?>">
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>



      </div>
