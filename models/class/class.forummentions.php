<?php
class mentions
{
    private $m_names = array();
    private $m_denied_chars = array(
        "@",
        "#",
        "?",
        "¿"
    );
    private $m_link = ""; // + name of the username, link or whatever

    /*
    * this class can also be used for specific links
    * start editing from here
    * */

    public function add_name($name)
    {
        array_push($this->m_names, $name);
    }

    public function process_text($text_txt)
    {
        $expl_text = explode(" ", $text_txt);

        /*
        * a character will be ignores which can be specified next this comment
        * :)
        * */

        $sp_sign = "@"; // this is what you can change freely...
        
        $mention_text = "";
        for ($i = 0; $i < count($expl_text); ++$i) {
            $spec_w = $expl_text[$i];
            $print_link = false;
            $name_link = "";
            if(empty($spec_w[0])){
              echo $spec_w;
            }else if ($spec_w[0] == $sp_sign) { // then can be a mention...
                $name = "";
                $break_b = false;
                for ($x = 1; $x < strlen($spec_w); ++$x) {
                    if ($spec_w[$x] == '.' || $spec_w[$x] == ",") {
                        if (in_array($name, $this->m_names)) {
                            $print_link = true;
                            $name_link = $name;
                            break;
                        }
                    }
                    if (in_array($spec_w[$x], $this->m_denied_chars)) {
                        $break_b = true;
                        break;
                    }
                    $name .= $spec_w[$x];
                }
                if ($break_b == true) {
                    $print_link = false;
                    break;
                } else {
                    if (in_array($name, $this->m_names)) {
                        $print_link = true;
                        $name_link = $name;
                    }
                }
            }
            if ($print_link == true) {
                $mention_text .= "<a href='".FULL_PATH."/user/" . $this->m_link . "" . $name_link . "' class='user-tooltip' data-user='".$name."'>" . $spec_w . "</a>";
                if ($i < count($expl_text)) $mention_text .= " ";
            } else {
                $mention_text .= $spec_w;
                if ($i < count($expl_text)) $mention_text .= " ";
            }
        }
        return $mention_text;
    }
}