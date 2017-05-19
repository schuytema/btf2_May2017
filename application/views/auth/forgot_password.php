<div class="starter-template">
  <img src="<?php echo base_url(); ?>img/home_logo_2.png"/>
  </br>
  <div style="max-width:400px; margin-left:auto; margin-right:auto;">
    <div align="left">
      <h2><?php echo lang('forgot_password_heading');?></h2>
      <p>
        Please enter your username and we will send you and email to reset your password.
        <!--<?php// echo sprintf(lang('forgot_password_subheading'), $identity_label);?>-->
      </p>
      <?php
      if ($message == "ERR1")
  		{
  			echo '<div id="infoMessage" style="color:red">Invalid username. Please make sure you enter a valid username to reset your password</div></br>';
  		} elseif ($message == "ERR2")
  		{
  			echo '<div id="infoMessage" style="color:red">Something went wrong! Please re-enter your username to reset your password</div></br>';
  		}
      echo '<form action="'.base_url().'auth/send_forgot_password_email" method="post">';
      ?>
        <div class="form-group">
          <label for="identity"><?php echo lang('forgot_password_identity_label');?></label>
          <input type="text" class="form-control" id="identity" name="identity">
        </div>
    </div>
    <button type="submit" class="btn btn-primary"><?php echo lang('forgot_password_submit_btn')?></button>
  </div>
</div>
