<?php
$user_info = $this->ion_auth->user()->row();
$private = $this->m_btf2_chat->get_global_pms($user_info->id);
$uri = "$_SERVER[REQUEST_URI]";
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

if ($user_info->id == substr($uri, 24))
{
  ?>

  <div class="starter-template">
  <h2>Your Messages</h2>
  </br>
  </br>
  <table class="table table-striped">
    <thead>
      <tr>
        <th width = "20%">From</th>
        <th width = "60%">Message</th>
        <th width = "20%">Date Received</th>
      </tr>
    </thead>
    <?php
    if ($private != NULL)
    {
      foreach ($private as $message)
      {
        $sender_info = $this->m_btf2_users->get_user_info_from_id($message['FK_User_Id']);
        $sender_full_name = $sender_info['first_name'] . ' ' . $sender_info['last_name'];
        $time = new DateTime($message['Create_Date']);
        $time->setTimezone($time_zone);
        echo '<tr>';
        echo '<td align = "left">';
        echo '<a href = "' . base_url() . 'main/user_profile/' . $message['FK_User_Id'] . '">' . $sender_full_name . '</a>';
        echo '</td>';
        echo '<td align = "left">';
        echo '<a href = "' . base_url() . 'main/message_window/' . $message['PK_Private_Message_Id'] . '">' . $message['Message'] . '</a>';
        echo '</td>';
        echo '<td align = "left">' . $time->format('F d, Y') . ' at ' . $time->format('H:i') . '</td>';
        echo '</tr>';
      }
    }?>
  </table>
</div><?php
}
else {
  redirect('main/home');
}
