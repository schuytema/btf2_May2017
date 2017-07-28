<?php
  $user_info = $this->ion_auth->user()->row();
  $full_name = $user_info->first_name . ' ' . $user_info->last_name;
  $project_info = $this->m_btf2_projects->get_project_info($project_id);
  $sort = $this->input->post('Sort_By');
  $sort_options = $this->config->item('task_sort_filters');
  $Pusers = $this->m_btf2_projects->get_project_users($project_id);
  if (isset($_SESSION['time']))
	{
		$time_zone = new DateTimeZone('Etc/' . str_replace(' ', '', str_replace('!', '-', str_replace('-', '+', str_replace('+', '!', $_SESSION['time'])))));
	}
	else {
    session_start();
		$time_zone = new DateTimeZone('Etc/' . str_replace(' ', '', str_replace('!', '-', str_replace('-', '+', str_replace('+', '!', $_SESSION['time'])))));
    /*$query = $this->db->query("SELECT last_login FROM users WHERE username = '$username'");
		if ($query->num_rows())
		{
			$results = $query->result_array();
			foreach($results AS $row)
			{
				$last_login = $row['last_login'];
			}
		}
		$_SESSION['last_login'] = $last_login;
    $time_zone = new DateTimeZone('Etc/' . str_replace(' ', '', str_replace('!', '-', str_replace('-', '+', str_replace('+', '!', $_SESSION['time'])))));*/
	}
  $g = 1;
  $m = 1;
?>
  <div class="starter-template">
    <a href="<?php echo base_url().'main/project_home/'.$project_id;?>" style="color:#333">
      <h1 style="text-align:center">
        <?php echo $project_info['Name'] . ' Tasks';?>
      </h1>
    </a>
    <?php include 'project_buttons.php'; ?>
      <?php echo '<form action="'.base_url().'main/tasks/'.$project_id.'/'.'" method="post">';
      foreach ($Pusers as $user)
      {
        $temp = $this->m_btf2_users->get_user_info_from_id($user['FK_User_Id']);
        $user_full_name = $temp['first_name'] . ' ' . $temp['last_name'];
        if ($full_name != $user_full_name)
        {
        $sort_options['Tasks assigned to ' . $user_full_name] = 'Tasks assigned to ' . $user_full_name;
        }
      }
      $sort_options['Archived'] = 'Archived';
      ?>
        <div class="row">
            <label for="Sort_By">Sort tasks</label>
            <div class="input-group">
              <?php
                echo form_dropdown('Sort_By', $sort_options, set_value('Sort_By', (isset($sort)) ? $sort : 'Date (descending)'), 'class="form-control" id="Sort_By" name="Sort_By"');
              ?>
              <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Go!</button>
              </span>
            </div>
        </div>
      </br>
          <input type="hidden" name="Something" value="<?php echo $user_info->id; ?>">
          <input type="hidden" name="full_name" value="<?php echo $user_info->first_name . ' ' . $user_info->last_name; ?>">
<?php
    $groups = $this->m_btf2_tasks->get_task_groups($project_info['PK_Project_Id'], $this->input->post('Sort_By'));?>
    <a href="#" onclick ="openall()" class="btn btn-default openall">open all</a> <a href="#" onclick="closeall()" class="btn btn-default closeall">close all</a>
    <hr>
    <div class="panel-group" id="accordion">
      <?php
    while ($g <= count($groups))
    {
        $i = 1;
        $tasks = $this->m_btf2_tasks->get_project_tasks($project_info['PK_Project_Id'], $groups[$g-1], $this->input->post('Sort_By'));
        if ($sort == 'Date (ascending)' || $sort == 'Date (descending)' || $sort == 'Completion status' || $sort == NULL)
        {
          $style = 'panel-collapse collapse';
        }
        else {
          $style = 'panel-collapse collapse in';
        }?>
          <?php
          if ($tasks != NULL)
          {?>
          <div class="panel panel-default" id="panel<?php echo $g;?>">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-target="#collapse<?php echo $g;?>" data-partent="#accordion" href="#collapse<?php echo $g;?>">
                <?php echo $groups[$g-1];?>
                </a>
              </h4>
            </div>
            <div id="collapse<?php echo $g;?>" class="<?php echo $style;?>">
              <div class="panel-body">
                  <table class="table table-striped">
                  <thead>
                  <tr>
                  <th width="10%"></th>
                  <th width="45%">Task</th>
                  <th width="45%" style="text-align:right">Complete By</th>
                  </tr>
                  </thead>
                  <?php
                    while ($i <= count($tasks))
                    {
                      $member_info = $this->m_btf2_users->get_user_info_from_id($tasks[$i-1]['FK_User_Id']);
                      ?>
                      <tr
                      <?php
                        /*switch ($tasks[$i-1]['Status'])
                        {
                          case 'Not Started':
                            echo 'style="color:#CC3333; font-weight:bold"';
                            break;
                          case 'Incomplete (Stalled)':
                            echo 'style="color:#FF9900; font-weight:bold"';
                            break;
                          case 'In Progress':
                            echo 'style="color:#000000; font-weight:bold"';
                            break;
                          case 'Nearing Completion':
                            echo 'style="color:#339933; font-weight:bold"';
                            break;
                          case 'Complete':
                            echo 'style="color:#003399; font-weight:bold"';
                            break;
                          }*/
                       ?>>
                        <?php
                        $confirm = 'Are you sure you wish to delete ' . $tasks[$i-1]['Task_Name'] . '?';
                        echo '<td align="right" style="vertical-align:top">';
                        if ($this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']) || $tasks[$i-1]['Assigned_To'] == $full_name)
                        {
                        echo '<a href="' . base_url() . 'main/complete_task/' . $project_info['PK_Project_Id'] . '/' .  $tasks[$i-1]['PK_Task_Id'] . '" type="button" style="float:left" class="btn btn-default btn-xs">';
                          echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                        echo '</a>';
                        echo '<a href="' . base_url() . 'main/create_task/' . $project_info['PK_Project_Id'] . '/' .  $tasks[$i-1]['PK_Task_Id'] . '" type="button" style="float:left" class="btn btn-default btn-xs">';
                          echo '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>';
                        echo '</a>';
                        }
                        if ($this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']) || $tasks[$i-1]['FK_User_Id'] == $user_info->id)
                        {?>
                        <a onclick="return confirm('<?php echo $confirm;?>');" href="<?php echo base_url();?>main/delete_task/<?php echo $tasks[$i-1]['PK_Task_Id'];?>" type="button" style="float:left" class="btn btn-default btn-xs"><?php
                          echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
                        echo '</a>';
                        }
                        echo '</td>';?>
                        <td align="left">
                          <a data-toggle="modal" data-target="#myModal<?php echo $m; ?>"><?php echo $tasks[$i-1]['Task_Name']?></a><?php
                          $st = $tasks[$i-1]['Update_Date']; //  a timestamp
                          $time = new DateTime("@$st");
    											$time->setTimezone($time_zone);?>
                          <p style="text-align:left; font-style:italic; font-size:x-small; color:#999999"><?php echo $tasks[$i-1]['Status'] . ' - last updated ' . $time->format('F d, Y') . ' at ' . $time->format('H:i'); ;?></p>
                        </td>
                        <td align="right" style="vertical-align:middle">
                          <?php
                          if ($tasks[$i-1]['Complete_Date'] != NULL)
                            {
                              echo $this->m_btf2_chat->convert_date($tasks[$i-1]['Complete_Date']) . ' ' . substr($tasks[$i-1]['Complete_Date'], 8, 2) . ', ' . substr($tasks[$i-1]['Complete_Date'], 0, 4);
                            }
                          else
                            {
                              echo 'No Completion Date';
                            }
                          ?>
                          <p style="text-align:right; font-style:italic; font-size:x-small; color:#999999"><?php echo $tasks[$i-1]['Assigned_To']; ?></p>
                        </td>
                      </tr>
                      <div id="myModal<?php echo $m; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title"><?php echo $tasks[$i-1]['Task_Name']?></h4>
                            </div>
                            <div class="modal-body">
                              <p style="text-align:center">Requested by <i><?php echo $member_info['first_name'] . ' ' . $member_info['last_name'];?></i> on <i><?php echo $this->m_btf2_chat->convert_date($tasks[$i-1]['Create_Date']) . ' ' . substr($tasks[$i-1]['Create_Date'], 8, 2) . ', ' . substr($tasks[$i-1]['Create_Date'], 0, 4); ?></i></br>
                              Assigned To: <i><?php echo (isset($tasks[$i-1]['Assigned_To']) ? $tasks[$i-1]['Assigned_To'] : 'Nobody');?></i></br>
                              Status: <i><?php echo $tasks[$i-1]['Status'];?></i></p>
                            </br>
                              <p style="text-align:left"><?php echo nl2br($tasks[$i-1]['Description']);?></p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php
                          $i++;
                          $m++;
                    }?>
                    </table>
                  </div>
              </div>
          </div>
      <?php }
      $g++;
    }
        ?>
      </div>
    <a align="center" class="btn btn-primary" href="<?php echo base_url(); ?>main/create_task/<?php echo $project_info['PK_Project_Id'] . '/0'; ?>" role="button">Create task</a>
  </div>

  <script>
  function closeall(){
  $('.panel-collapse.in')
    .collapse('hide');
  };
  function openall(){
    $('.panel-collapse:not(".in")')
      .collapse('show');
  };</script>
