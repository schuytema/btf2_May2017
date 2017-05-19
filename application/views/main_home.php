<?php
	$user_info = $this->ion_auth->user()->row();
	$new_messages = $this->m_btf2_chat->home_new_messages($_SESSION['last_login'], $user_info->first_name);
	$projects = $this->m_btf2_projects->get_project_menu($user_info->id);
	$tasks = $this->m_btf2_tasks->home_grab_user_tasks($user_info);
	$chat_output = '*';?>

  <div class="starter-template">
  <img src="<?php echo base_url(); ?>img/home_logo_2.png" style="display:block;margin:auto"/>
	<p class="lead" style="text-align:center">Welcome <?php echo $user_info->first_name.' '.$user_info->last_name; ?>!</p>
	<div id="hud">
	<?php
		if ($new_messages != NULL)
		{?>
		<div id="message_hud" style="margin-bottom:10px; vertical-align:top; min-height:25vh;max-height:25vh;display:inline-block; min-width:25vw; max-width:25vw; text-align:left; border:1px solid #888; padding:8px">
			<h4 style="text-align:center">You have new messages!</h4>
			<?php
				foreach ($new_messages as $test)
				{
					$PMTarget = $this->m_btf2_users->get_user_info_from_id($test['FK_User_Id']);
					$PInfo = $this->m_btf2_projects->get_project_info($test['FK_Project_Id']);
					if (substr($test['Channel'], 1) == 'general' && !strpos($chat_output, 'in ' . $PInfo['Name'] . ': Channel general'))
					{
						$chat_output = $chat_output . '<a href="' . base_url() . 'main/chat_list/' . $test['FK_Project_Id'] . '/-general">in ' . $PInfo['Name'] . ': Channel ' . substr($test['Channel'], 1) . '</a></br>';
					}
					else if (substr($test['Channel'], 1, 1) != '-' && !strpos($chat_output, substr($test['Channel'],1)))
					{
						$chat_output = $chat_output . '<a href="' . base_url() . 'main/chat_list/' . $test['FK_Project_Id'] . '/-' . substr($test['Channel'] , 1) . '">in ' . $PInfo['Name'] . ': Channel ' . substr($test['Channel'], 1) . '</a></br>';
					}
					else if (substr($test['Channel'], 2) == $user_info->first_name && !strpos($chat_output, $PMTarget['first_name']))
					{
						$chat_output = $chat_output . '<a href="' . base_url() . 'main/chat_list/' . $test['FK_Project_Id'] . '/--' . $PMTarget['first_name'] . '">Private chat with ' . $PMTarget['first_name'] . '</a></br>';
					}
					else if (substr($test['Channel'],1 ,1) == '-' && !strpos($chat_output, substr($test['Channel'], 2)))
					{
						$chat_output = $chat_output . '<a href="' . base_url() . 'main/chat_list/' . $test['FK_Project_Id'] . '/--' . substr($test['Channel'], 2) . '">Private chat with ' . substr($test['Channel'], 2) . '</a></br>';
					}
				}
			?>
			<div style="max-height:13vh;overflow-y:scroll">
				<?php echo substr($chat_output, 1);?>
			</div>
		</div>
	<?php
}
	if ($projects != NULL)
	{
		$nt_exist = false;
		$new_task_text = "";
		foreach ($projects as $project)
		{
			$new_tasks = $this->m_btf2_tasks->home_new_tasks($project['project_id'], $_SESSION['last_login']);
			if ($new_tasks != NULL)
			{
				$nt_exist = true;
				$new_task_text = $new_task_text.'<a href="' . base_url() . 'main/tasks/' . $project['project_id'].'"> New tasks in ' . $project['Name'] . '</a></br>';
			}
		}
		if($nt_exist)
		{?>
			<div id="ntask_hud" align="left" style="margin-bottom:10px; vertical-align:top; min-height:25vh;max-height:25vh;display:inline-block;min-width:25vw;max-width:25vw;text-align:left; border:1px solid #888; padding:8px">
		    <h4 style="text-align:center">New tasks in projects</h4>
				<?php echo $new_task_text;?>
			</div><?php
		}
	 	if ($tasks != NULL)
		{?>
	    <div id="task_hud" style="margin-bottom:10px; vertical-align:top; min-height:25vh;max-height:25vh;display:inline-block; min-width:25vw; max-width:25vw;text-align:left;border:1px solid #888; padding:8px">
	    <h4 style="text-align:center">Tasks assigned to you</h4>
	    <?php
	    echo '<div style="max-height:13vh;overflow-y:scroll;word-wrap:break-word">';
	    foreach ($projects as $project)
	    {
	      $user_tasks = $this->m_btf2_tasks->home_user_tasks_by_project($user_info, $project['project_id']);
	      if ($user_tasks != NULL)
	      {
	        echo '<form id="form' . $project['project_id'] . '" action="'.base_url().'main/tasks/' . $project['project_id'] . '/" method="post">';
	        echo '<button style="border-style:none;background-color:transparent" type="submit"><b>' . $project['Name'] . '➻</b></button></br>';
	        echo '<div style="margin-left:7px">';
	        foreach ($user_tasks as $test)
	        {
						if ($test['Group_Name'] != 'Archived')
						{
							echo $test['Task_Name'] . '</br>';
						}
	        }
					echo '</div>';
	      }
	      echo '<input type="hidden" name="Sort_By" value="Tasks assigned to me">';
	      echo '<input type="hidden" name="full_name" value="' . $user_info->first_name . ' ' . $user_info->last_name . '">';
	      echo '</form>';
	    }
	    echo '</div>';
	    echo '</div>';
	  }
	}
	$_SESSION['last_login'] = date('Y-m-d H:i:s');?>
	</div>
	</br>
	</br>
	</br>
  </div>

	<script>
		function check_width(){
			var hdiv = document.getElementById("hud");
			var mdiv = document.getElementById("message_hud");
			var tdiv = document.getElementById("task_hud");
			var ndiv = document.getElementById("ntask_hud");
			if(screen.width <= 500) {
				if(mdiv != null){
					mdiv.style.minWidth = "80vw";
					mdiv.style.maxWidth = "80vw";
				}
				if(tdiv != null){
					tdiv.style.maxWidth = "80vw";
					tdiv.style.minWidth = "80vw";
				}
				if(ndiv != null){
					ndiv.style.maxWidth = "80vw";
					ndiv.style.minWidth = "80vw";
				}
			}
		}
		check_width();
	</script>
