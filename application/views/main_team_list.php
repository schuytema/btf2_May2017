<?php
	$user_info = $this->ion_auth->user()->row();
	$project_info = $this->m_btf2_projects->get_project_info($project_id);
?>
      <div class="starter-template">
				<a href="<?php echo base_url().'main/project_home/'.$project_id;?>" style="color:#333">
					<h1><?php echo $project_info['Name']; ?></h1>
				</a>
				<?php
				include 'project_buttons.php';
        $team = $this->m_btf2_projects->get_project_users($project_info['PK_Project_Id']);
        if ($team != NULL)
        {
        	echo '<table class="table table-striped">';
        	echo '<thead>';
        	echo '<tr>';
        	echo '<th width="60%">Name</th>';
        	echo '<th width="20%">Admin</th>';
        	echo '<th width="20%">Drop</th>';
        	echo '</tr>';
        	echo '</thead>';
			foreach ($team as $member)
			{
				if (strtotime($member['Join_Date']) >= strtotime($_SESSION['last_login']))
				{
					$style = ' style="background-color:#BCED91"';
				}
				else {
					$style = NULL;
				}
				$member_info = $this->m_btf2_users->get_user_info_from_id($member['FK_User_Id']);
				echo '<tr>';
				echo '<td align="left"'.$style.'>';
				$name = $member_info['first_name'].' '.$member_info['last_name'];
				echo '<a href="'.base_url().'main/user_profile/'.$member['FK_User_Id'].'">';
				if($this->m_btf2_projects->is_admin($member['FK_User_Id'], $project_info['PK_Project_Id']))
				{
					echo '<b>'.$name.' (admin)</b>';
				}
				else {
					echo $name;
				}
				echo '</a>';
				if($user_info->id != $member['FK_User_Id']){
					echo ' <a href="'.base_url().'main/chat_list/'.$project_id.'/--'.$member_info['first_name'].'" type="button" class="btn btn-default btn-xs">';
					echo '<span class="glyphicon glyphicon-comment"></span>';
					echo '<span class="tooltiptext">Chat</span>';
					echo '</a>';
				}
				echo '</td>';
				echo '<td align="left"'.$style.'>';
				if($this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']))
				{
					if($this->m_btf2_projects->is_admin($member['FK_User_Id'], $project_info['PK_Project_Id']))
					{
						if ($this->m_btf2_projects->num_admin($project_info['PK_Project_Id']) > 1)
						{
							echo '<a href="' . base_url() . 'main/process_change_admin/' . $project_info['PK_Project_Id'] . '/'.$member['FK_User_Id'].'" type="button" class="btn btn-default btn-xs">';
							echo '<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>';
							echo '</a>';
						} else {
							echo '&nbsp;&nbsp;';
						}
					} else {
						echo '<a href="' . base_url() . 'main/process_change_admin/' . $project_info['PK_Project_Id'] . '/'.$member['FK_User_Id'].'" type="button" class="btn btn-default btn-xs">';
						echo '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
						echo '</a>';
					}
				} else {
					echo '&nbsp;&nbsp;';
				}
				echo '</td>';
				echo '<td align="left"'.$style.'>';
				if($this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']))
				{
					if ($this->m_btf2_projects->num_admin($project_info['PK_Project_Id']) > 1)
					{
						echo '<a href="' . base_url() . 'main/process_remove_member/' . $project_info['PK_Project_Id'] . '/'.$member['FK_User_Id'].'" type="button" class="btn btn-default btn-xs" onClick="return confirm(\'Confirm team member removal - this cannot be undone!\')">';
						echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
						echo '</a>';
					}
				} else {
					//not an admin, just a team member, so can remove themselves
					if ($member['FK_User_Id'] == $user_info->id)
					{
						echo '<a href="' . base_url() . 'main/process_remove_member/' . $project_info['PK_Project_Id'] . '/'.$member['FK_User_Id'].'" type="button" class="btn btn-default btn-xs" onClick="return confirm(\'Confirm team member removal - this cannot be undone!\')">';
						echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
						echo '</a>';
					}
				}
				echo '</td>';
				echo '</tr>';
			}
        	echo '</table>';
        }
				if($this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']))
        {
					echo '</br>';
        	echo '<a class="btn btn-primary" style="width:150px" href="'.base_url().'main/project_invite_email/'.$project_info['PK_Project_Id'].'" role="button">Invite New Member</a>';
        }
        ?>

      </div>
