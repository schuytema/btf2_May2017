<?php
$user_info = $this->ion_auth->user()->row();

echo "Yo, wazzzz up";

$new_messages = $this->m_btf2_chat->home_new_messages($_SESSION['last_login']); //yo this is a comment
$projects = $this->m_btf2_projects->get_project_menu($user_info->id);
$tasks = $this->m_btf2_tasks->home_grab_user_tasks($user_info);
$chat_output = '*';?>

<div class="starter-template"><?php
  if ($new_messages != NULL)
  {?>
  <div style="max-width:400px; text-align:left; margin-left:auto; margin-right:auto; border:1px solid #888; border-radius: 10%; padding:15px">
  <h4 style="text-align:center">You have new messages!</h4>
  <?php
    foreach ($new_messages as $test)
    {
      $PMTarget = $this->m_btf2_users->get_user_info_from_id($test['FK_User_Id']);
      $PInfo = $this->m_btf2_projects->get_project_info($test['FK_Project_Id']);
      if (substr($test['Channel'], 1) == 'general' && !strpos($chat_output, 'in ' . $PInfo['Name'] . ': Channel general'))
        $chat_output = $chat_output . '<a href="' . base_url() . 'main/chat_list/' . $test['FK_Project_Id'] . '/-general">in ' . $PInfo['Name'] . ': Channel ' . substr($test['Channel'], 1) . '</a></br>';
      elseif (substr($test['Channel'], 1, 1) != '-' && !strpos($chat_output, substr($test['Channel'],1)))
      {
        $chat_output = $chat_output . '<a href="' . base_url() . 'main/chat_list/' . $test['FK_Project_Id'] . '/-' . substr($test['Channel'] , 1) . '">in ' . $PInfo['Name'] . ': Channel ' . substr($test['Channel'], 1) . '</a></br>';
      }
      elseif (substr($test['Channel'], 2) == $user_info->first_name && !strpos($chat_output, $PMTarget['first_name']))
      {
        $chat_output = $chat_output . '<a href="' . base_url() . 'main/chat_list/' . $test['FK_Project_Id'] . '/--' . $PMTarget['first_name'] . '">Private chat with ' . $PMTarget['first_name'] . '</a></br>';
      }
      elseif (substr($test['Channel'],1 ,1) == '-' && !strpos($chat_output, substr($test['Channel'], 2)))
      {
        $chat_output = $chat_output . '<a href="' . base_url() . 'main/chat_list/' . $test['FK_Project_Id'] . '/--' . $PMTarget['first_name'] . '">Private chat with ' . substr($test['Channel'], 2) . '</a></br>';
      }
    }
  echo '<div style="min-height:10vh; max-height:20vh; overflow-y:scroll">';
  echo substr($chat_output, 1);
  echo '</div>';
  }
  ?>
</div>
</br>
</br><?php
if ($projects != NULL && $tasks != NULL)
  {?>
    <div style="max-width:400px; text-align:left; margin-left:auto; margin-right:auto; border:1px solid #888; border-radius: 10%; padding:8px">
    <h4 style="text-align:center">Tasks assigned to you</h4>
    <?php
    echo '<div style="min-height:10vh; max-height:20vh; overflow-y:scroll">';
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
          echo $test['Task_Name'] . '</br>';
        }
      }
      echo '<input type="hidden" name="Sort_By" value="Tasks assigned to me">';
      echo '<input type="hidden" name="full_name" value="' . $user_info->first_name . ' ' . $user_info->last_name . '">';
      echo '</form>';
    }
    echo '</div>';
    echo '</div>';
  }

?>
</div>
