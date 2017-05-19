<?php
	$user_info = $this->ion_auth->user()->row();
	$project_info = $this->m_btf2_projects->get_project_info($project_id);
?>
			<div>
        <h3><?php echo $project_info['Name']; ?></h3>
        <?php
        echo '<form action="'.base_url().'main/process_add_channel/'.$project_id.'/" method="post">';
        ?>
					<div class="form-group">
						<label for="Channel_Name">Channel Name</label>
	          <input type='text' class="form-control" name="Channel_Name" id="Channel_Name"/>
	        </div>
	        <div class="form-group">
	          <label for="Description">Description</label>
	          <textarea class="form-control" rows="2" id="Description" name="Description"></textarea>
	        </div>
					<input type="hidden" name="FK_User_Id" value="<?php echo set_value('FK_User_Id', (isset($new_message)) ? $new_message['FK_User_Id'] : $user_info->id); ?>">
	        <input type="hidden" name="FK_Project_Id" value="<?php echo $project_id; ?>">
	        <button type="submit" class="btn btn-primary">Create Channel</button>
	      </form>
			</div>
