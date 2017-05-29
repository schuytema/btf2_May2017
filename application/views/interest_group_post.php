<script>
function getFocus()
{
  document.getElementById("Comment").focus();
}
</script>

<?php
$user_info = $this->ion_auth->user()->row();
$post = $this->m_btf2_interest_groups->get_post_by_id($post_id);
if (isset($_SESSION['time']))
{
  $time_zone = new DateTimeZone('Etc/' . str_replace(' ', '', str_replace('!', '-', str_replace('-', '+', str_replace('+', '!', $_SESSION['time'])))));
} else {
  $query = $this->db->query("SELECT last_login FROM users WHERE id = $user_info->id");
  if ($query->num_rows())
  {
    $last_login = $query->result_array();
    $last_login = $last_login[0]['last_login'];
  }
  $_SESSION['last_login'] = $last_login;
  $time_zone = new DateTimeZone('Etc/' . str_replace(' ', '', str_replace('!', '-', str_replace('-', '+', str_replace('+', '!', $_SESSION['last_login'])))));
}
$group = $this->m_btf2_interest_groups->get_interest_group($group_id);
?>
<div class="starter-template" id="postbody">
  <a href="<?php echo base_url();?>main/interest_groups/<?php echo $group_id;?>" style="color:#333">
    <h1>
      <?php echo $group['Name'];?>
    </h1>
  </a>
  <?php
  echo '</br>';
  $feed_id = $post['PK_Interest_Group_Feed_Id'];
  $comments_count = $this->m_btf2_interest_groups->get_feed_comments_count($feed_id);
  $likes = unserialize($post['Feed_Likes']);
  $poster = $this->m_btf2_users->get_user_info_from_id($post['FK_User_Id']);
  $is_admin = $this->m_btf2_interest_groups->is_group_admin($group['PK_Interest_Group_Id'],$user_info->id);
  if($post['Published']=="yes"){?>
    <div style="display:block; border:1px solid #9A9A9A; border-radius:2px; width:95%; margin-left:auto; margin-right:auto; padding-top:10px; padding-left:2%; padding-right:1%; min-height:40px; max-height:70vh; text-align:left; overflow-x: hidden; overflow-y:scroll;">
    	<?php
    		echo '<h2 style="display:inline-block">'.$post['Title'].'</h2>';
        if ($is_admin || $post['FK_User_Id'] == $user_info->id)
        {
          $confirm = 'Are you sure you want to delete this post?';?>
          <a onclick="return confirm('<?php echo $confirm;?>');" href="<?php echo base_url();?>main/delete_feed_post/<?php echo $post['PK_Interest_Group_Feed_Id'] .'/'. $post['FK_Interest_Group_Id'];?>" type="button" class="btn btn-default btn-xs" style="float:right;display:inline-block;"><?php
            echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
          echo '</a>';
        }
        echo '</br>';
    		if ($post['Image'] != '')
    		{
    			echo '<img src="'.$post['Image'].'" alt="Feed Image" width="100%">';
    		}
    	?>
      <span style="display:inline-block;width:48%;text-align:left;">
        <a href="<?php echo base_url().'main/user_profile/'.$post['FK_User_Id'];?>">
        <?php echo $poster['first_name'].' '.$poster['last_name'];?>
        </a>
      </span>
      <span style="display:inline-block;width:49%;text-align:right;color:#AAAAAA;font-size:12px;"><i>
        <?php
        $time = new DateTime($post['Post_Date']);
        $time->setTimezone($time_zone);
        echo $time->format('F d, Y') . ' at ' . $time->format('H:i');
        ?>
      </i></span>
      </br>
      <p style="width:95%">
        <?php
        	echo nl2br($this->m_btf2_interest_groups->make_clickable($post['Content']));
        ?>
      </p>
      <div style="display:block;width:100%;border-top:1px dotted #595959;text-align:left;padding:5px;">
        <a href="#" onclick="getFocus()" style="color:#888888;text-decoration:none;"><?php echo $comments_count;?> comments&nbsp;</a>
        <?php if($post['FK_User_Id'] == $user_info->id)
        {?>
          <span id="heart" class="glyphicon glyphicon-heart" style="color:#505050;"aria-hidden="true"></span><?php
        } else if(array_search($user_info->id, $likes)=== false) {?>
          <a id="likeButton" href="<?php echo base_url().'main/add_feed_like/'.$user_info->id.'/'.$feed_id.'/'.$group['PK_Interest_Group_Id'].'/post';?>" type="button" style="background-color:transparent;border:0px;text-decoration:none;">
            <span id="heart" class="glyphicon glyphicon-heart" style="color:#505050;"aria-hidden="true"></span>
          </a><?php
        } else {?>
          <span id="heart" class="glyphicon glyphicon-heart" style="color:#CC1111;"aria-hidden="true"></span><?php
        }?>
        <span id="likes" style="color:#888888"><?php echo ' '.count($likes).' ';?>likes</span>
        <span id="views" style="color:#888888; float:right;text-align:right;margin-right:5px;z-index:-1;"><?php echo $post['Feed_Views'].' view'.($post['Feed_Views'] != 1?'s':'');?></span>
      </div>
      <?php include 'interest_group_post_comments.php';?>
      <form id="form" action="<?php echo base_url().'main/add_feed_comment/'.$feed_id.'/'.$group_id;?>" method="post">
        <div class="form-group" id="comment-input">
          <input type="text" class="form-control" style="margin-top:10px" placeholder="Add a comment..." id="Comment" name="Comment" autocomplete="off" <?php if($autofocus){echo 'autofocus';}?>>
        </div>
      </form>
    </div>
    </br><?php
  } else if($group['FK_User_Id'] == $user_info->id){// posts for group creator to approve or disapprove?>
      <span style="display:block">
        <a href="<?php echo base_url().'main/publish_feed/'.$post['PK_Interest_Group_Feed_Id'].'/'.$group['PK_Interest_Group_Id'];?>" type="button" style="display:inline-block;position:relative;left:-20%">
          <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
        </a>
        <a href="<?php echo base_url().'main/hide_feed/'.$post['PK_Interest_Group_Feed_Id'].'/'.$group['PK_Interest_Group_Id'];?>" type="button" style="display:inline-block;position:relative;right:-20%;">
          <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
        </a>
        </br>
        </br>
        <div style="display:block; border:1px solid #9A9A9A; border-radius:2px; width:95%; margin-left:auto; margin-right:auto; padding-top:10px; padding-left:2%; padding-right:1%; min-height:40px; max-height:70vh; text-align:left; overflow-x: hidden; overflow-y:scroll;">
          <span style="display:inline-block;width:48%;text-align:left;">
            <a href="<?php echo base_url().'main/user_profile/'.$post['FK_User_Id'];?>">
            <?php echo $poster['first_name'].' '.$poster['last_name'];?>
            </a>
          </span>
          <span style="display:inline-block;width:49%;text-align:right;color:#AAAAAA;font-size:12px;"><i>
            <?php
            $time = new DateTime($post['Post_Date']);
            $time->setTimezone($time_zone);
            echo $time->format('F d, Y') . ' at ' . $time->format('H:i');
            ?>
          </i></span>
          </br>
          <p style="display:block;width:95%"><?php echo nl2br($this->m_btf2_interest_groups->make_clickable($post['Content']));?></p>
        </div>
      </span>
      </br>
      <?php
    }
  ?>
</div>
