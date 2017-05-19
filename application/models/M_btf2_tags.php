<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_btf2_tags extends CI_Model{

    function __construct(){
        parent::__construct();
    }

	function new_tag(){
    $tag_type = $this->pcs_utility->db_clean(strip_tags($this->input->post('Tag_Type')),100);
    if($tag_type == "Interest")
    {
      $tag_text = strtolower($this->pcs_utility->db_clean(strip_tags($this->input->post('I_Tag_Text')),100));
    } else if ($tag_type == "Skill") {
      $tag_text = strtolower($this->pcs_utility->db_clean(strip_tags($this->input->post('S_Tag_Text')),100));
    }
		$data = array(
			'Tag_Text' => $tag_text,
			'Tag_Type' => $tag_type,
			'FK_User_Id' => $this->pcs_utility->id_clean($this->input->post('FK_User_Id'))
		);

		$this->db->set($data);
        $this->db->insert('btf2_tags');

    }

    function delete_tag($tag_id=NULL)
    {
        if(isset($tag_id))
        {
            $this->db->where('PK_Tag_Id', $tag_id);
            $this->db->delete('btf2_tags');
        }
    }

    function get_tag_count($type)
    {
        $query = $this->db->query("SELECT DISTINCT Tag_Text FROM btf2_tags WHERE Tag_Type = '$type';");
        return $query->num_rows();
    }

    function get_tag_count_type($user_id, $type)
    {
        $query = $this->db->query("SELECT * FROM btf2_tags WHERE Tag_Type = '$type' AND FK_User_Id = $user_id");
        return $query->num_rows();
    }

    function get_tag_count_by_text($text, $type)
    {
        $query = $this->db->query("SELECT * FROM btf2_tags WHERE Tag_Text = '$text' AND Tag_Type = '$type'");
        return $query->num_rows();
    }

    function get_tag_text($tag_id)
    {
        $tag_text = 'none';
        $query = $this->db->get_where('btf_tags', array('PK_Tag_id' => $tag_id));
        if ($query->num_rows())
        {
            foreach ($query->result_array() as $row)
            {
                $tag_text = $row['Tag_Text'];
            }
        }
        return $tag_text;
    }


    function get_tags_for_user($user_id, $type)
    {
        $query = $this->db->get_where('btf2_tags', array('FK_User_Id' => $user_id, 'Tag_Type' => $type));
        if ($query->num_rows())
        {
        	$results = $query->result_array();
        } else {
        	$results = array();
        }
        return $results;
    }

    function get_starred_tags_for_user($user_id, $type)
    {
        return $this->db->get_where('btf_tags', array('FK_user_id' => $user_id, 'Tag_Type' => $type, 'Self_Endorse' => 1));
    }

    function get_tags_by_type($type)
    {
        return $this->db->query("SELECT DISTINCT Tag_Text FROM btf_tags WHERE Tag_Type = '$type' ORDER BY Tag_Text ASC");
    }

    function get_tags_by_type_and_letter($type, $letter)
    {
        return $this->db->query("SELECT DISTINCT Tag_Text FROM btf_tags WHERE Tag_Type = '$type' AND Tag_Text LIKE '$letter%' ORDER BY Tag_Text ASC");
    }

    function get_top_tags_by_type($type)
    {
        $top_count = $this->config->item('top_tag_count');
        $query =    "SELECT t1.Tag_Text ".
                    "FROM ( ".
                    "SELECT Tag_Text FROM btf_tags ".
                    "WHERE Tag_Type = '$type' ".
                    "GROUP BY Tag_Text ".
                    "ORDER BY COUNT(PK_Tag_Id) DESC ".
                    "LIMIT $top_count ".
                    ") as t1 ".
                    "ORDER BY t1.Tag_Text ASC";
        return $this->db->query($query);
    }


    function get_user_ids_by_tag($text)
    {
        return $this->db->query("SELECT DISTINCT FK_user_id FROM btf_tags WHERE (Tag_Text = '$text') AND (Tag_Type != 'Project')");
    }



    function generate_tag_cloud($type)
    {
        $data = '';
        //$query = $this->get_tags_by_type($type);
        $query = $this->get_top_tags_by_type($type);
        foreach ($query->result_array() as $row)
        {
            $count = $this->get_tag_count_by_text($row['Tag_Text'], $type);
            if ($count > 0)
            {
                switch($count)
                {
                    case ($count <= $this->config->item('tag_small')):
                        $style = "tag_small";
                        $link_style = "link_tag_small";
                    break;

                    case ($count <= $this->config->item('tag_medium')):
                        $style = "tag_medium";
                        $link_style = "link_tag_medium";
                    break;
                    case ($count <= $this->config->item('tag_large')):
                        $style = "tag_large";
                        $link_style = "link_tag_large";
                    break;
                    case ($count <= $this->config->item('tag_xlarge')):
                        $style = "tag_very_large";
                        $link_style = "link_tag_very_large";
                    break;
                    case ($count > $this->config->item('tag_xlarge')):
                        $style = "tag_very_very_large";
                        $link_style = "link_tag_very_very_large";
                    break;
                }
                //$data = $data.'<span class="'.$style.'"><a href="'.base_url().'main/search_tag/'.$row['Tag_Text'].'" class="'.$link_style.'">'.ucwords($row['Tag_Text']).'('.$count.')</a></span> ';
                $data = $data.'<span class="'.$style.'"><a href="'.base_url().'main/search_tag/'.$row['Tag_Text'].'" class="'.$link_style.'">'.ucwords($row['Tag_Text']).'</a></span> ';
            }
        }
        return $data;
    }

    function generate_alpha_tag_list($type)
    {
        $data = '';
        $letters = $this->config->item('letters');
        foreach ($letters as $letter)
        {
            $query = $this->get_tags_by_type_and_letter($type, $letter);
            if ($query->num_rows())
            {
                $data .= '<dl>';
                $data .= '<dt>';
                $data .= $letter;
                $data .= '</dt>';
                foreach ($query->result_array() as $row)
                {
                    $count = $this->get_tag_count_by_text($row['Tag_Text'], $type);
                    $data .= '<dd>';
                    $data .= '<a href="'.base_url().'main/search_tag/'.$row['Tag_Text'].'" >'.ucwords($row['Tag_Text']).'&nbsp;('.$count.')</a>';
                    $data .= '</dd>';
                }
                $data .= '</dl>';
            }
        }
        return $data;
    }

    function generate_skills_for_endorse($user_id)
    {
        $checkboxes = '';
        $skills = $this->get_starred_tags_for_user($user_id, 'Skill');
        if ($skills->num_rows())
        {
            $checkboxes .= '<label class="checkbox">';
            foreach ($skills->result_array() as $skill)
            {
                $checkname = str_replace(' ', '', $skill['Tag_Text']);
                $checkboxes .= '<input type="checkbox" name="'.$checkname.'" value="'.$skill['PK_Tag_Id'].'"> '.ucwords($skill['Tag_Text']).'<br>';
            }
            $checkboxes .= '</label>';
        }
        return $checkboxes;
    }


    function get_endorse_value($skill_id)
    {
        $skills = $this->db->get_where('btf_tags', array('PK_Tag_id' => $skill_id));
        if ($skills->num_rows())
        {
            foreach ($skills->result_array() as $skill)
            {
                return $skill['Other_Endorse'];
            }
        }

    }

    function endorse_skill($skill_id)
    {
        $cur_value = $this->get_endorse_value($skill_id);
        $data = array(
            'Other_Endorse' => $cur_value + 1
        );

        $this->db->set($data);
        $this->db->where('PK_Tag_Id',$this->pcs_utility->id_clean($skill_id));
        $this->db->update('btf_tags');

    }

    function user_has_tag($user_id, $tag_text)
    {
        $has = FALSE;
        $result = $this->get_user_ids_by_tag($tag_text);
        if ($result->num_rows())
        {
            foreach ($result->result_array() as $id)
            {
                if ($id['FK_user_id'] == $user_id)
                {
                    $has = TRUE;
                }
            }
        }
        return $has;
    }

    function suggest($tag="", $type="")
    {
      $this->db->distinct();
      $this->db->select('Tag_Text');
      $this->db->where('Tag_Type = "'.$type.'"');
      $this->db->like('Tag_Text', $this->pcs_utility->db_clean($tag,200));
      $this->db->order_by('Tag_Text');
      $this->db->limit(10);
      return $this->db->get('btf2_tags');
    }
}
/* End of file m_btf2_tags.php */
/* Location: ./application/models/m_btf2_tags.php */
