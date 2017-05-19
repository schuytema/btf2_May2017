<?php
$user_info = $this->ion_auth->user()->row();
$pm_target = $this->input->post('PM_Target');
if ($pm_target == NULL)
{
  $pm_target = $user_id;
}
$pm_info = $this->m_btf2_users->get_user_info_from_id($pm_target);
$pm_target_full_name = $pm_info['first_name'] . ' ' . $pm_info['last_name'];
?>

<div class="starter-template">
  <h2>Send a message to <?php echo $pm_target_full_name;?></h2>
</br>
</br>
<font style="color:#339933"><?php echo $succ_status;?></font>
<form method="post" id="PM_Form" action="<?php echo base_url().'main/pm_user/'.$pm_target;?>">
  <div class="form-group" id="title-input">
    <textarea rows="1" class="form-control" style="margin-top:15px" placeholder="Message subject" id="Title" name="Title" autocomplete="off" autofocus></textarea>
  </div>
  <div class="form-group" id="message-input">
    <textarea rows="10" class="form-control" style="margin-top:15px" placeholder="Add a message..." id="Message" name="Message" autocomplete="off"></textarea>
  </div>
  <input type="hidden" name="FK_User_Id" value="<?php echo set_value('FK_User_Id', (isset($new_message)) ? $new_message['FK_User_Id'] : $user_info->id); ?>">
  <button type="submit" class="btn btn-primary">Submit</button>
</div>
