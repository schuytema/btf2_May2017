<?php
$query = $this->db->query('SELECT Project_Pages FROM btf2_projects WHERE PK_Project_Id ='.$project_info['PK_Project_Id']);
if ($query->num_rows())
{
  $result = $query->result_array();
  foreach ($result as $row)
  {
    $buttons[] = $row;
  }
}

$buttons = unserialize($buttons[0]['Project_Pages']);

?>

<div align="center">
  <?php if(in_array("1",$buttons)){?>
  <a href="<?php echo base_url().'main/chat_list/'.$project_info['PK_Project_Id'].'/-general';?>" >
    <img src="<?php echo base_url();?>img/chat_icon.png"/>
    <span class="tooltiptext">Chat</span>
  </a>
  <?php }
  if(in_array("2",$buttons)){?>
  <a href="<?php echo base_url().'main/project_schedule/'.$project_info['PK_Project_Id'];?>">
    <img src="<?php echo base_url();?>img/schedule_icon.png"/>
    <span class="tooltiptext">Schedule</span>
  </a>
  <?php }
  if(in_array("3",$buttons)){?>
  <a href="<?php echo base_url().'main/tasks/'.$project_info['PK_Project_Id'];?>">
    <img src="<?php echo base_url();?>img/task_icon.png"/>
    <span class="tooltiptext">Tasks</span>
  </a>
  <?php }
  if(in_array("4",$buttons)){?>
  <a href="<?php echo base_url().'main/team_list/'.$project_info['PK_Project_Id'];?>">
    <img src="<?php echo base_url();?>img/members_icon.png"/>
    <span class="tooltiptext">Members</span>
  </a>
  <?php }
  if(in_array("5",$buttons)){?>
  <a href="<?php echo base_url().'s3test/files/'.$project_info['PK_Project_Id'];?>">
    <img src="<?php echo base_url();?>img/files_icon.png"/>
    <span class="tooltiptext">Files</span>
  </a>
  <?php }
  if(in_array("6",$buttons)){?>
  <a href="<?php echo base_url().'main/work_list/'.$project_info['PK_Project_Id'];?>">
    <img src="<?php echo base_url();?>img/tracker_icon.png"/>
    <span class="tooltiptext">Tracker</span>
  </a>
  <?php }?>
</div>
