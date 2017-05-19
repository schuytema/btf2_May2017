<!--script >
function add_feed_like (userId, feedId, likes, url)
{
  /*$.ajax({
    method: "POST",
    url: url+"main/add_feed_like",
    data: { uId: userId, fId: feedId }
  });*/
  document.getElementById("likes").textContent = likes+1 +" likes";//<"+"?php $this->m_btf2_interest_groups->add_feed_like("+ userId + "," + feedId + ");?>";
  document.getElementById("heart").style.color = "hotpink";
  document.getElementById("likeButton").onclick = function(event)
    {
      event.preventDefault();
    }
  //document.getElementById("heart").textContent = "<"+"?php $this->m_btf2_interest_groups->add_feed_like("+ userId + "," + feedId + ");?>";
  //$('#addLike').load(document.URL +  ' #addLike');
  var text = "<"+"?php $this->m_btf2_interest_groups->add_feed_like("+ userId + "," + feedId + ");?>";
  var el = document.createElement("span");
  el.style.color = "#FFFFFF";
  el.appendChild(document.createTextNode(text));
  document.getElementById("likes").appendChild(el);
}
</script-->
<?php
$feed = $this->m_btf2_interest_groups->get_group_feed($group['PK_Interest_Group_Id']);
if($feed!=null)
{
 ?>
  <div style="max-height:40vh; overflow-x: hidden; overflow-y:scroll;">
    <?php
    $first_element = true;
    foreach ($feed as $element) {
      $feed_id = $element['PK_Interest_Group_Feed_Id'];
      $poster = $this->m_btf2_users->get_user_info_from_id($element['FK_User_Id']);
      $comments = $this->m_btf2_interest_groups->get_feed_comments_count($feed_id);
      $likes = unserialize($element['Feed_Likes']);
      $read_more = false;
      $text = $element['Content'];
      $max_length = $this->config->item('group_feed_read_more_length');
      $add_like = false;
      $confirm = 'Are you sure you want to delete this post?';
      $is_admin = $this->m_btf2_interest_groups->is_group_admin($group['PK_Interest_Group_Id'],$user_info->id);
      if(strlen($element['Content']) > $max_length)
      {
        $read_more = true;
        $text = substr($text, 0, $max_length).'... ';
      }
      if($element['Published']=="yes"){?>
        <div style="display:block;border:1px solid #9A9A9A;border-radius:2px;width:95%;margin-left:auto;margin-right:auto;padding-top:10px;padding-left:2%;padding-right:1%;min-height:40px;text-align:left;">
          <?php
          echo '<h3 style="display:inline-block">'.$element['Title'].'</h3>';
          if ($is_admin || $element['FK_User_Id'] == $user_info->id)
          {?>
            <a onclick="return confirm('<?php echo $confirm;?>');" href="<?php echo base_url();?>main/delete_feed_post/<?php echo $element['PK_Interest_Group_Feed_Id'];?>" type="button" class="btn btn-default btn-xs" style="float:right;display:inline-block;"><?php
              echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
            echo '</a>';
          }
          echo '</br>';
          if (($element['Image'] != '') && $first_element)
          {
          	echo '<img src="'.$element['Image'].'" alt="Feed Image" width="100%">';
          }
          $first_element = false;
          ?>
          <span style="display:inline-block;width:48%;text-align:left;">
            <a href="<?php echo base_url().'main/user_profile/'.$element['FK_User_Id'];?>">
            <?php echo $poster['first_name'].' '.$poster['last_name'];?>
            </a>
          </span>
          <span style="display:inline-block;width:49%;text-align:right;color:#AAAAAA;font-size:12px;"><i>
            <?php
            $time = new DateTime($element['Post_Date']);
            $time->setTimezone($time_zone);
            echo $time->format('F d, Y') . ' at ' . $time->format('H:i');
            ?>
          </i></span>
          </br>
          <p style="width:95%">
            <?php echo nl2br($this->m_btf2_interest_groups->make_clickable($text));
            if($read_more)
              echo '<a href="'.base_url().'main/interest_group_post/'.$group['PK_Interest_Group_Id'].'/'.$feed_id.'">Read More</a>';?>
          </p>
          <div style="display:block;width:100%;border-top:1px dotted #595959;text-align:left;padding:5px;">
            <a href="<?php echo base_url().'main/interest_group_post/'.$group['PK_Interest_Group_Id'].'/'.$feed_id.'/cmt';?>" style="color:#888888;text-decoration:none;"><?php echo $comments;?> comments&nbsp;</a>
            <?php if($element['FK_User_Id'] == $user_info->id)
            {?>
              <span id="heart" class="glyphicon glyphicon-heart" style="color:#505050;"aria-hidden="true"></span><?php
            } else if(array_search($user_info->id, $likes)=== false) {?>
              <a id="likeButton" href="<?php echo base_url().'main/add_feed_like/'.$user_info->id.'/'.$feed_id.'/'.$group['PK_Interest_Group_Id'].'/feed';?>" type="button" style="background-color:transparent;border:0px;text-decoration:none;">
                <span id="heart" class="glyphicon glyphicon-heart" style="color:#505050;"aria-hidden="true"></span>
                <!--div id="addLike"><?php if($add_like){$this->m_btf2_interest_groups->add_feed_like($user_info->id, $feed_id);}?></div-->
              </a><?php
            } else {?>
              <span id="heart" class="glyphicon glyphicon-heart" style="color:#CC1111;"aria-hidden="true"></span><?php
            }?>
            <span id="likes" style="color:#888888"><?php echo ' '.count($likes).' ';?>likes</span>
          </div>
        </div>
        </br><?php

      } else if($is_admin){// posts for group creator to approve or disapprove?>
        <span style="display:block">
          <a href="<?php echo base_url().'main/publish_feed/'.$feed_id.'/'.$group['PK_Interest_Group_Id'];?>" type="button" style="display:inline-block;" >
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
          </a>
          <div style="display:inline-block;border:1px solid #9A9A9A;border-radius:2px;width:95%;margin-left:auto;margin-right:auto;padding-top:10px;padding-left:2%;padding-right:1%;min-height:40px;text-align:left;">
            <span style="display:inline-block;width:48%;text-align:left;">
              <a href="<?php echo base_url().'main/user_profile/'.$element['FK_User_Id'];?>">
              <?php echo $poster['first_name'].' '.$poster['last_name'];?>
              </a>
            </span>
            <span style="display:inline-block;width:49%;text-align:right;color:#AAAAAA;font-size:12px;"><i>
              <?php
              $time = new DateTime($element['Post_Date']);
              $time->setTimezone($time_zone);
              echo $time->format('F d, Y') . ' at ' . $time->format('H:i');
              ?>
            </i></span>
            </br>
            <p style="display:block;width:95%">
              <?php echo $this->m_btf2_interest_groups->make_clickable($text);
              if($read_more)
                echo '<a href="'.base_url().'main/interest_group_post/'.$group['PK_Interest_Group_Id'].'/'.$element['PK_Interest_Group_Feed_Id'].'">Read More</a>';?></p>
          </div>
          <a href="<?php echo base_url().'main/hide_feed/'.$element['PK_Interest_Group_Feed_Id'].'/'.$group['PK_Interest_Group_Id'];?>" type="button" style="display:inline-block;">
            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
          </a>
        </span>
        </br>
        <?php
      }
    }
    ?>
  </div>
  <?php
} else {
  ?>
  <p><i>No feed to show. Add the first content</i></p>
  <?php
} ?>
