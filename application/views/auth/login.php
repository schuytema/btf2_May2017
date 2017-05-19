<div class="starter-template">
  <img src="<?php echo base_url(); ?>img/home_logo_2.png"/>
  <div style="max-width:400px; margin-left:auto; margin-right:auto; border:1px solid #888; padding:8px">
    <div align="left">
      <h2><?php echo lang('login_heading');?></h2>
      <?php
        if ("$message" === "Password Successfully Changed")
        {
          $color = "green";
        } else {
          $color = "red";
        }
      ?>
      <div id="infoMessage" style="color:<?php echo $color;?>"><?php echo $message;?></div>

      <?php echo form_open("auth/login");?>
        <div class="form-group">
          <label for="identity"><?php echo lang('login_identity_label', 'identity');?></label>
          <input type="text" class="form-control" id="identity" name="identity" autofocus="true">
          <!--<?php echo form_input($identity);?>-->
        </div>
        <div class="form-group">
          <label for="password"><?php echo lang('login_password_label', 'password');?></label>
          <input type="password" class="form-control" id="password" name="password">
          <!--<?php echo form_input($password);?>-->
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" id="remember" name="remember"><?php echo lang('login_remember_label', 'remember');?>
          </label>
        </div>


        <button type="submit" class="btn btn-primary"><?php echo lang('login_submit_btn');?></button>

      <?php echo form_close();?>
      </br>
      <p>
        <a href="forgot_password"><?php echo lang('login_forgot_password');?></a>
        </br>
        <a href="create_user">Create an account</a>
      </p>
    </div>
  </div>
</div>
