<?php
$user_info = $this->ion_auth->user()->row();
$project_info = $this->m_btf2_projects->get_project_info($project_id);
$users = $this->m_btf2_projects->get_project_users($project_id);
$groups = $this->m_btf2_tasks->get_task_groups($project_info['PK_Project_Id']);
?>
  <div>
    <h1 align="center">
      Create/Modify Task
    </h1>
<?php
  $task_id = (isset($task_info)) ? $task_info['PK_Task_Id'] : '0';
  echo '<form action="'.base_url().'main/process_task/'.$project_id.'/'. $task_id .'" method="post">';
  ?>
    <div class="form-group">
      <label style="text-align=:left" for="Task_Name">Task Name</label>
      <input type="text" class="form-control" id="Task_Name" name="Task_Name" value="<?php echo set_value('Task_Name', (isset($task_info)) ? $task_info['Task_Name'] : NULL); ?>" />
    </div>
    <div class="form-group" name="Group_Div" id="Group_Div">
      <label for="Group_Name">Task Group</label>
      <div class="input-group group" id="input-group group">
        <select class="form-control" name="Group_Name" id="Group_Name">
          <?php
            foreach ($groups as $group)
            {
              if ($group != 'Archived')
              {
                if ($group != $task_info['Group_Name'])
                {?>
                  <option>
                    <?php echo $group;?>
                  </option><?php
                }
                else {?>
                  <option selected="selected">
                    <?php echo $group?>
                  </option><?php
                }
              }
            }
            if ($this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']) && $task_info['Group_Name'] == 'Archived')
            {
            echo '<option selected="selected">Archived</option>';
            }
            else {
              echo '<option>Archived</option>';
            }?>
        </select>
        <span class="input-group-addon">
          <button type="button" style="background-color:transparent; color:black; border: 0px" data-toggle="modal" data-target="#CreateGroup">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
          </button>
        </span>
      </div>
    </div>
    <div class="form-group">
      <label style="text-align:left" for="Description">Description</label>
      <textarea class="form-control" rows="8" id="Description" name="Description"><?php echo set_value('Description', (isset($task_info)) ? $task_info['Description'] : NULL); ?></textarea>
    </div>
    <div class="form-group">
      <label for="Complete_Date" style="text-align:left">Completion Date</label>
      <div class='input-group date' id='datetimepicker1'>
      <input type='text' class="form-control" name="Complete_Date" id="Complete_Date" value="<?php echo set_value('Complete_Date', (isset($task_info)) ? $task_info['Complete_Date'] : NULL); ?>" />
      <span class="input-group-addon">
          <span class="glyphicon glyphicon-calendar"></span>
      </span>
      </div>
    </div>
    <div class="form-group">
      <label for="Assigned_To">Assign To</label>
      <select class="form-control" name="Assigned_To" id="Assigned_To">
        <option>Unassigned</option>
        <?php
          $old_assignment = "NONE";
          foreach ($users as $user)
          {
            $member_info = $this->m_btf2_users->get_user_info_from_id($user['FK_User_Id']);
            $full_name = $member_info['first_name'] . ' ' . $member_info['last_name'];
            if ($full_name != $task_info['Assigned_To'])
            {
              ?>
              <option>
                <?php echo $full_name;?>
              </option><?php
            }
            else {
              $old_assignment = $full_name;
              ?>
              <option selected="selected">
                <?php echo $full_name?>
              </option><?php
            }
          } ?>
      </select>
  </div>
    <div class="form-group">
    <label for="Status">Status</label>
      <?php
        echo form_dropdown('Status', $this->config->item('task_status'), set_value('Status', (isset($task_info)) ? $task_info['Status'] : 'Not Started'), 'class="form-control" id="Status" name="Status"');
      ?>
  </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <input type="hidden" name="old_assignment" value="<?php echo $old_assignment; ?>">
    <input type="hidden" name="FK_Project_Id" value="<?php echo $project_id; ?>">
    <input type="hidden" name="FK_User_Id" value="<?php echo $user_info->id; ?>">
    <input type="hidden" name="Create_Date" value="<?php echo (isset($task_info)) ? $task_info['Create_Date'] : NULL; ?>">
    <div id="CreateGroup" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add a Task Group</h4>
          </div>
          <div class="modal-body">
            <p>
              <input type="text" class="form-control" id="NewGroup" name="NewGroup" autofocus/>
            </p>
          </div>
          <div class="modal-footer">
            <button onclick="runList()" type="button" class="btn btn-default" data-dismiss="modal">Add</button>
          </div>
        </div>
      </div>
    </div>

    <?php
      echo "<script>
      function runList()
      {
        var select = document.getElementById('Group_Name');
        var NewOpt = document.getElementById('NewGroup').value
        select.options[select.options.length] = new Option(NewOpt, NewOpt);
        NewGroup.value = '';
        select.value = NewOpt;
      }
        </script>";?>
