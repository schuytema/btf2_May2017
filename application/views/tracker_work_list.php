<?php
	$user_info = $this->ion_auth->user()->row();
	$project_info = $this->m_btf2_projects->get_project_info($project_id);
?>
      <div class="starter-template">
				<a href="<?php echo base_url().'main/project_home/'.$project_id;?>" style="color:#333">
					<h1><?php echo $project_info['Name']; ?></h1>
				</a>
				<?php include 'project_buttons.php'; ?>
				</br>
        <a class="btn btn-primary" href="<?php echo base_url(); ?>main/update_work/<?php echo $project_info['PK_Project_Id']; ?>/0" role="button">Add New Work Record</a>
        <?php
        $work_records = $this->m_btf2_work_records->get_work_records($project_info['PK_Project_Id'], $user_info->id);
        if ($work_records != NULL)
        {
        	echo '<table class="table table-striped">';
        	echo '<thead>';
        	echo '<tr>';
        	echo '<th width="20%">Date</th>';
        	//echo '<th width="20%">Units</th>';
        	//echo '<th width="20%">Type</th>';
        	echo '<th width="20%">Value</th>';
        	echo '<th width="20%">Status</th>';
        	echo '<th width="40%">Actions</th>';
        	echo '</tr>';
        	echo '</thead>';
			foreach ($work_records as $work)
			{
				echo '<tr>';
				echo '<td align="left">';
				echo substr($work['Work_Date'], 5, 5);
				echo '</td>';
				//echo '<td align="left">';
				//echo $work['Work_Units'];
				//echo '</td>';
				//echo '<td align="left">';
				//echo $work['Unit_type'];
				//echo '</td>';
				echo '<td align="left">';
				$total_value = $work['Work_Units'] * $work['Unit_Value'];
				echo '$'.$total_value.'.00';
				echo '</td>';
				echo '<td align="left">';
				echo $work['Status'];
				echo '</td>';
				echo '<td align="left">';

				echo '<a href="'.base_url().'main/update_work/'.$project_info['PK_Project_Id'].'/'.$work['PK_Work_Record_Id'].'" type="button" class="btn btn-default btn-xs">';
				  echo '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>';
				echo '</a>';
				echo '&nbsp;&nbsp;';
				echo '<a href="'.base_url().'main/update_work/'.$project_info['PK_Project_Id'].'/'.$work['PK_Work_Record_Id'].'" type="button" class="btn btn-default btn-xs" onClick="return confirm(\'Confirm work record delete - this cannot be undone!\')">';
				  echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
				echo '</a>';

				echo '</td>';
				echo '</tr>';
			}
        	echo '</table>';
        	echo '<br>';
        	if ($this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']))
        	{
        		if ($this->m_btf2_work_records->get_num_of_team_work_records($project_info['PK_Project_Id'], $user_info->id) > 0)
        		{
        			echo '<a href="javascript:void(0)" class="btn btn-info" data-toggle="collapse" data-target="#demo">See Team Members\' Work</a>';
        		}
        		echo '<div id="demo" class="collapse">';
        		$team_ids = $this->m_btf2_projects->get_project_users($project_info['PK_Project_Id']);
        		foreach ($team_ids as $member_id)
        		{
        			if ($member_id['FK_User_Id'] != $user_info->id)
        			{
        				//$user_temp = $this->m_btf2_users->get_user_info_from_id($member_id);
        				//$temp_id = $member_id['FK_User_Id'];
        				//$user_temp = $this->ion_auth->user($temp_id)->row();
        				$user_temp = $this->m_btf2_users->get_user_info_from_id($member_id['FK_User_Id']);
        				echo '<h3>'.$user_temp['first_name'].' '.$user_temp['last_name'].'\'s Work Log</h3>';
        				$work_records = $this->m_btf2_work_records->get_work_records($project_info['PK_Project_Id'], $member_id['FK_User_Id']);
        				if ($work_records != NULL)
        				{
        					echo '<table class="table table-striped">';
        					echo '<thead>';
        					echo '<tr>';
        					echo '<th width="20%">Date</th>';
        					//echo '<th width="20%">Units</th>';
        					//echo '<th width="20%">Type</th>';
        					echo '<th width="20%">Value</th>';
        					echo '<th width="20%">Status</th>';
        					echo '<th width="40%">Actions</th>';
        					echo '</tr>';
        					echo '</thead>';
        					foreach ($work_records as $work)
        					{
        						echo '<tr>';
        						echo '<td align="left">';
        						echo substr($work['Work_Date'], 5, 5);
        						echo '</td>';
        						//echo '<td align="left">';
        						//echo $work['Work_Units'];
        						//echo '</td>';
        						//echo '<td align="left">';
        						//echo $work['Unit_type'];
        						//echo '</td>';
        						echo '<td align="left">';
        						$total_value = $work['Work_Units'] * $work['Unit_Value'];
        						echo '$'.$total_value.'.00';
        						echo '</td>';
        						echo '<td align="left">';
        						echo $work['Status'];
        						echo '</td>';
        						echo '<td align="left">';

        						echo '<a href="'.base_url().'main/update_work/'.$project_info['PK_Project_Id'].'/'.$work['PK_Work_Record_Id'].'" type="button" class="btn btn-default btn-xs">';
        						  echo '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>';
        						echo '</a>';
        						echo '&nbsp;&nbsp;';
        						echo '<a href="'.base_url().'main/update_work/'.$project_info['PK_Project_Id'].'/'.$work['PK_Work_Record_Id'].'" type="button" class="btn btn-default btn-xs" onClick="return confirm(\'Confirm work record delete - this cannot be undone!\')">';
        						  echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
        						echo '</a>';

        						echo '</td>';
        						echo '</tr>';
        					}
        					echo '</table>';
        				}
        			}
        		}
        		echo '</div>';
        	}
        }
        ?>

      </div>
