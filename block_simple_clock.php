<?php

// If you want to change the clock names, do this in the language file

// Three states, must show at least one clock
define('B_SIMPLE_CLOCK_SHOW_BOTH',        0);
define('B_SIMPLE_CLOCK_SHOW_SERVER_ONLY', 1);
define('B_SIMPLE_CLOCK_SHOW_USER_ONLY',   2);

//------------------------------------------------------------------------------
// Main clock class
class block_simple_clock extends block_base {

    //--------------------------------------------------------------------------
    function init() {
        $this->title = get_string('clock_title_default','block_simple_clock');
    }

    //--------------------------------------------------------------------------
    function applicable_formats() {
        return array('all' => true);
    }

    //--------------------------------------------------------------------------
    function instance_allow_multiple() {
        return true;
    }

    //--------------------------------------------------------------------------
    function has_config() {
        return true;
    }

    //--------------------------------------------------------------------------
    function hide_header() {
        return isset($this->config->show_header) && $this->config->show_header==0;
    }

    //--------------------------------------------------------------------------
    function specialization() {
        $this->title = isset($this->config->clock_title)?$this->config->clock_title:get_string('clock_title_default','block_simple_clock');
    }

    //--------------------------------------------------------------------------
    // This is a list block, the footer is used for code that updates the clocks
    function get_content() {

        // Access to settings needed
        global $USER, $COURSE, $OUTPUT, $CFG;

        // Settings variables based on config
        $showServerClock = !isset($this->config->show_clocks) ||
            $this->config->show_clocks==B_SIMPLE_CLOCK_SHOW_BOTH ||
            $this->config->show_clocks==B_SIMPLE_CLOCK_SHOW_SERVER_ONLY;
        $showUserClock = !isset($this->config->show_clocks) ||
            $this->config->show_clocks==B_SIMPLE_CLOCK_SHOW_BOTH ||
            $this->config->show_clocks==B_SIMPLE_CLOCK_SHOW_USER_ONLY;
        $showIcons = !isset($this->config->show_icons) || $this->config->show_icons==1;
        $showSeconds = isset($this->config->show_seconds) && $this->config->show_seconds==1;

        $this->content = new stdClass;
        $this->content->text = '<table class="clockTable">';

        // First item is the server's clock
        if($showServerClock) {
            $this->content->text .= '<tr>';
            $this->content->text .= $showIcons?'<td><img src="'.$CFG->wwwroot.'/theme/'.$CFG->theme.'/pix/favicon.ico" class="icon" alt="clockIcon" /></td>':'';
            // $this->content->text .= $showIcons?'<td><img src="'.$OUTPUT->pix_url('favicon','theme').'" class="icon" alt="clockIcon" /></td>':'';
            $this->content->text .= '<td>'.get_string('server','block_simple_clock').':</td>';
            $this->content->text .= '<td><input class="clock" id="serverTime" value="'.get_string('loading','block_simple_clock').'"></td>';
            $this->content->text .= '</tr>';
        }

        // Next item is the user's clock
        if($showUserClock){
            $this->content->text .= '<tr>';
            $this->content->text .= $showIcons?'<td>'.$OUTPUT->user_picture($USER, array('courseid'=>$COURSE->id, 'size'=>16, 'link'=>false)).'</td>':'';
            $this->content->text .= '<td>'.get_string('you','block_simple_clock').':</td>';
            $this->content->text .= '<td><input class="clock" id="youTime" value="'.get_string('loading','block_simple_clock').'"></td>';
            $this->content->text .= '</tr>';
        }
        $this->content->text .= '</table>';

        // Set up JavaScript
        $this->content->text .= '<noscript>'.get_string('javascript_disabled','block_simple_clock').'</noscript>';
        $timeArray = localtime(time(),true);
        $arguments = array(
            $showServerClock,
            $showUserClock,
            $showSeconds,
            $timeArray['tm_year']+1900,
            $timeArray['tm_mon'],
            $timeArray['tm_mday'],
            $timeArray['tm_hour'],
            $timeArray['tm_min'],
            $timeArray['tm_sec']+2 // arbitrary load time added
        );
        $jsmodule = array(
            'name' => 'block_simple_clock',
            'fullpath' => '/blocks/simple_clock/module.js',
            'requires' => array(),
            'strings' => array(
                array('clock_separator', 'block_simple_clock'),
                array('before_noon', 'block_simple_clock'),
                array('after_noon', 'block_simple_clock'),
            ),
        );
        $this->page->requires->js_init_call('initSimpleClock',$arguments,false,$jsmodule);

        $this->content->footer = '';
        return $this->content;
    }
}
