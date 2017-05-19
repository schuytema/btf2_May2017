<?php
	$user_info = $this->ion_auth->user()->row();
	$project_info = $this->m_btf2_projects->get_project_info($project_id);
?>
      <div class="starter-template">
				<a href="<?php echo base_url().'main/project_home/'.$project_id;?>" style="color:#333">
      		<h1 style="text-align:center"><?php echo $project_info['Name']; ?></h1>
				</a>
				<?php include 'project_buttons.php'; ?>
      	<h3>Invite a Team Member to Your Project</h3>
        <?php
        	echo '<form action="'.base_url().'main/send_project_invite_email" method="post">';
        ?>
          <div class="form-group">
            <label for="Email">Enter their email</label>
          	<input type="text" class="form-control" id="Email" name="Email" value="">
          </div>
          <input type="hidden" name="User_Id" value="<?php echo $user_info->id; ?>">
          <input type="hidden" name="Project_Id" value="<?php echo $project_info['PK_Project_Id']; ?>">
          <button type="submit" class="btn btn-primary">Send Invite Email</button>
        </form>



      </div>
