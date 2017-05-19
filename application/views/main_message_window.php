<?php
$user_info = $this->ion_auth->user()->row();
$message_info = $this->m_btf2_chat->get_global_pm_info($message_id);
$read_check = array(
  'Is_Read' => 1
);

if ($message_info['Recipient_Id'] != $user_info->id)
{
  redirect('main/home');
}

echo '<div class="starter-template">';
echo '<h2>Private Message</h2>';
echo '</div>';
echo '<b>Subject:</b>';
echo '</br>';
echo $message_info['Title'];
echo '</br></br></br>';
echo '<b>Message:</b>';
echo '</br>';
echo $message_info['Message'];

$this->db->where('PK_Private_Message_Id', $message_id);
$this->db->update('btf2_private_messages', $read_check);?>
