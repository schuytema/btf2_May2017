<?php
	$user_info = $this->ion_auth->user()->row();
?>
      <div class="starter-template">
        <h1>Your Projects</h1>

        <?php
        	$projects = $this->m_btf2_projects->get_project_menu($user_info->id);
        	echo '<table class="table table-striped">';
        	echo '<thead>';
        	echo '<tr>';
        	echo '<th width="70%">Name</th>';
        	echo '<th width="30%" >Actions</th>';
        	echo '</tr>';
        	echo '</thead>';
        	foreach ($projects as $project)
        	{
        		echo '<tr>';
        		echo '<td align="left">';
        		echo '<a href="'.base_url().'main/project_home/'.$project['project_id'].'">';
        		echo $project['Name'];
        		echo '</a>';
        		echo '</td>';
        		echo '<td align="left">';
        		if($this->m_btf2_projects->is_admin($user_info->id, $project['project_id']))
        		{
        			echo '<a href="' . base_url() . 'main/update_project/' . $project['project_id'] . '" type="button" class="btn btn-default btn-xs">';
        			  echo '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>';
        			echo '</a>';
        			echo '&nbsp;&nbsp;';
        			echo '<a href="' . base_url() . 'main/process_delete_project/' . $project['project_id'] . '" type="button" class="btn btn-default btn-xs" onClick="return confirm(\'Confirm project delete - this cannot be undone!\')">';
        			  echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
        			echo '</a>';
        		} else {
        			echo '&nbsp;';
        		}
        		echo '</td>';
        		echo '</tr>';
        	}
        	echo '</table>';
        ?>
        <a class="btn btn-primary" style="width:150px" href="<?php echo base_url(); ?>main/update_project/0" role="button">Create New Project</a><br><br>
        <a class="btn btn-primary" style="width:150px" href="<?php echo base_url(); ?>main/project_invite/1" role="button">Enter Project Invite</a><br><br>
      </div>
