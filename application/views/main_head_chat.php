<?php
$chat_log = $this->m_btf2_chat->get_existing_channels($project_id);
$channel = strpos("$_SERVER[REQUEST_URI]", '-');
$current_channel = "#" . substr("$_SERVER[REQUEST_URI]", $channel + 1);
$channel_users = $this->m_btf2_projects->get_project_users($project_id);
$user_info = $this->ion_auth->user()->row();
$project_info = $this->m_btf2_projects->get_project_info($project_id);
?>



            <li class="dropdown"> <!-- Start list element for channels dropdown-->
              <a href="#" class="dropdown-toggle" role="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Channels<span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li style="padding-left:5px">channels:</li>
                <!-- List channels in Alphabetical order, with #general being always on top. Highlight the current channel -->
                <li> <!-- #general channel -->
                  <?php
                  if($current_channel === "#general")
                  {
                    echo '<a href="'.base_url().'main/chat_list/' . $project_id . '/-general" style="background-color:#E0E0E0">';
                  } else {
                    echo '<a href="'.base_url().'main/chat_list/' . $project_id . '/-general">';
                  }
                  echo '#general</a>
                </li>';
                foreach ($chat_log as $channel) // the rest of the channels
                {
                  $channel_name = $channel['Channel_Name'];
                  if(substr($channel_name, 1, 1) != '-' && $channel_name != "#general")
                  {
                    if($current_channel === $channel_name )
                    {
                      echo '<li><a href="'.base_url().'main/chat_list/' . $project_id . '/-' . substr($channel_name, 1) . '" style="background-color:#E0E0E0">' . $channel_name . '</a></li>';
                    } else {
                      echo '<li><a href="'.base_url().'main/chat_list/' . $project_id . '/-' . substr($channel_name, 1) . '">' . $channel_name . '</a></li>';
                    }
                  }
                } // end foreach ($chat_log as $channel)
                // if the current user is admin display a Create New Channel button
                if ($this->m_btf2_projects->is_admin($user_info->id, $project_info['PK_Project_Id']))
                {
                  echo '<li><a href="'.base_url().'main/add_channel/'.$project_id.'"><b>Create New Channel</b></a></li>';
                }
                ?>
                <li role="separator" class="divider"></li>
                <li style="padding-left:5px">private chats:</li>
                <!-- List all project members for private chatting except this user -->
                <?php
                foreach ($channel_users as $user)
                {
                  $member = $member_info = $this->m_btf2_users->get_user_info_from_id($user['FK_User_Id']);
                  $user_name = '-' . $member['first_name'] . ' ' . $member['last_name'];
                  if($user['FK_User_Id'] != $user_info->id)
                  {
                    if($current_channel === $user_name)
                    {
                      echo '<li><a href="'.base_url().'main/chat_list/' . $project_id . '/--' . $user['FK_User_Id'] . '" style="background-color:#E0E0E0">@' . substr($user_name, 1) . '</a></li>';
                    } else {
                      echo '<li><a href="'.base_url().'main/chat_list/' . $project_id . '/--' . $user['FK_User_Id'] . '">@' . substr($user_name, 1) . '</a></li>';
                    }
                  }
                } // end foreach ($channel_users as $user)
                ?>
              </ul> <!-- End of #ChannelsList-->
            </li> <!-- End of .dropdown for the channels and private chats -->
