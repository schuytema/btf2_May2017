<?php
$interest_group_projects = $this->m_btf2_interest_groups->pull_group_projects($group['PK_Interest_Group_Id']);
$users_projects_w_admin_status = $this->m_btf2_projects->get_project_menu($user_info->id);
?>

<div class="panel-body">
<?php
foreach ($interest_group_projects as $project)
{
  if ($project != 0)
  {
    $info = $this->m_btf2_projects->get_project_info($project);

    if ($info != NULL)
    {
      echo '<a href="'.base_url().'main/project_home/'.$project.'" style="display:inline-block;width:90%;text-align:left;float:left";>';
      echo $info['Name'];
      echo '</a>';
      if($this->m_btf2_projects->is_admin($user_info->id, $info['PK_Project_Id']))
      {
        $confirm = 'Are you sure you want to remove your project from this group?\\r';
        $confirm .= '(All project members will be notified of this, but they will not be removed until they choose to)';
        echo '<a onclick="return confirm('.$confirm.');" href="'.base_url().'main/remove_group_project/'.$group['PK_Interest_Group_Id'].'/'.$info['PK_Project_Id'].'" style="display:inline-block" type="button">';
        echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
        echo '</a>';
      }
      echo '</br>';
      $projectlist[] = $info['Name'];
    }
  }
}
echo '<hr>';
if ($users_projects_w_admin_status != NULL)
{
  echo '<form action="'.base_url().'main/connectp2g/'.$group['PK_Interest_Group_Id'].'" method="post">';?>
  <label for="Group_Name">Want in? Add your project now!</label>
  <div class="input-group group" id="input-group group">
  <select class="form-control" name="Chosen_Project" id="Chosen_Project">
  <?php
    foreach ($users_projects_w_admin_status as $project)
        {
          if (!in_array($project['Name'], $projectlist))
          {?>
            <option value="<?php echo $project['project_id'];?>">
              <?php echo $project['Name'];?>
            </option><?php
          }
        }?>
        </select>
        <span class="input-group-addon">
          <button type="submit" style="background-color:transparent; color:black; border: 0px" href=data-toggle="modal" data-target="#CreateGroup">
            Join!
          </button>
        </span>
    </div><?php
  }?>
 </div>
