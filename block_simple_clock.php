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
        $this->title = get_string('clock_title','block_simple_clock');
        $this->content_type = BLOCK_TYPE_TEXT;
        $this->version = 2010092000;
    }

    //--------------------------------------------------------------------------
    function instance_allow_config() {
        return true;
    }
    
    //--------------------------------------------------------------------------
    function hide_header() {
        return isset($this->config->hide_header) && $this->config->hide_header=='on';
    }
    
    //--------------------------------------------------------------------------
    function specialization() {
        $this->title = isset($this->config->clock_title)?$this->config->clock_title:get_string('clock_title','block_simple_clock');
    }

    //--------------------------------------------------------------------------
    // function preferred_width() {
        // The preferred value is in pixels
        // return 190;
    // }

    //--------------------------------------------------------------------------
    // This is a list block, the footer is used for code that updates the clocks
    function get_content() {
    
        // Access to settings needed
        global $USER, $COURSE, $CFG;

        // If content has already been generated, just update the time
        if ($this->content !== NULL) {
		
			// Try to get the most up-to-date time
            $this->content->text .='
			<script type="text/javascript">
			// <![CDATA[
				serverTimeStart = '.(time()*1000).';
				currentTime = new Date();
				timeDifference = currentTime.getTime() - serverTimeStart;
			//]]>
			</script>
			';
			return $this->content;
        }        

        // Begin list class content essentials
        $this->content = new stdClass;
        $this->content->text = '
        <table class="clockTable">
        ';

        // First item is the server's clock
        if(empty($this->config->show_clocks) || $this->config->show_clocks==B_SIMPLE_CLOCK_SHOW_BOTH || $this->config->show_clocks==B_SIMPLE_CLOCK_SHOW_SERVER_ONLY) {
            $this->content->text .= '<tr>';
            $this->content->text .= isset($this->config->hide_icons) && $this->config->hide_icons=='on'?'':'<td><img src="'.$CFG->themewww.'/'.$CFG->theme.'/favicon.ico" class="icon" alt="clockIcon" /></td>';
            $this->content->text .= '<td>'.get_string('server','block_simple_clock').':</td>';
            $this->content->text .= '<td><input class="clock" id="serverTime" value="'.get_string('loading','block_simple_clock').'"></td>';
            $this->content->text .= '</tr>';
        }

        // Next item is the user's clock
        if(empty($this->config->show_clocks) || $this->config->show_clocks==B_SIMPLE_CLOCK_SHOW_BOTH || $this->config->show_clocks==B_SIMPLE_CLOCK_SHOW_USER_ONLY){
            $this->content->text .= '<tr>';
            $this->content->text .= isset($this->config->hide_icons) && $this->config->hide_icons=='on'?'':'<td>'.( empty($USER->picture)?'':print_user_picture($USER->id, $COURSE->id, $USER->picture, 16, true, false, '', false)).'</td>';
            $this->content->text .= '<td>'.get_string('you','block_simple_clock').':</td>';
            $this->content->text .= '<td><input class="clock" id="youTime" value="Loading..."></td>';
            $this->content->text .= '</tr>';
        }
        $this->content->text .= '
        </table>
        ';
        
        // The updating code
        $this->content->text .= '
		<noscript>
			'.get_string('javascript_disabled','block_simple_clock').'
		</noscript>
        <script type="text/javascript">
        // <![CDATA[
            var serverTimeStart = '.(time()*1000).';
            var currentTime = new Date();
            var timeDifference = currentTime.getTime() - serverTimeStart;
            var youTime;
            var serverTime;
            
            function updateTime() {
        ';

        // Update the server clock if shown
        if(empty($this->config->show_clocks) || $this->config->show_clocks==B_SIMPLE_CLOCK_SHOW_BOTH || $this->config->show_clocks==B_SIMPLE_CLOCK_SHOW_SERVER_ONLY) {
            $this->content->text .= '
                serverTime = new Date();
                serverTime.setTime(serverTime.getTime() - timeDifference);
                hours = serverTime.getHours();
                minutes = serverTime.getMinutes();
                seconds = serverTime.getSeconds();
                document.getElementById("serverTime").value = (hours>12?hours-12:hours==0?12:hours)+"'.get_string('clock_separator','block_simple_clock').'"+(minutes<10?"0":"")+minutes+'.(isset($this->config->show_seconds) && $this->config->show_seconds=='on'?'"'.get_string('clock_separator','block_simple_clock').'"+(seconds<10?"0":"")+seconds+':'').'" "+(hours<12?"'.get_string('before_noon','block_simple_clock').'":"'.get_string('after_noon','block_simple_clock').'");
            ';
        }

        // Update the user clock if shown
        if(empty($this->config->show_clocks) || $this->config->show_clocks==B_SIMPLE_CLOCK_SHOW_BOTH || $this->config->show_clocks==B_SIMPLE_CLOCK_SHOW_USER_ONLY) {
            $this->content->text .= '
                youTime = new Date();
                hours = youTime.getHours();
                minutes = youTime.getMinutes();
                seconds = youTime.getSeconds();
                document.getElementById("youTime").value = (hours>12?hours-12:hours==0?12:hours)+"'.get_string('clock_separator','block_simple_clock').'"+(minutes<10?"0":"")+minutes+'.(isset($this->config->show_seconds) && $this->config->show_seconds=='on'?'"'.get_string('clock_separator','block_simple_clock').'"+(seconds<10?"0":"")+seconds+':'').'" "+(hours<12?"'.get_string('before_noon','block_simple_clock').'":"'.get_string('after_noon','block_simple_clock').'");
            ';
        }

        // Refresh in 1 second, do initial update
        $this->content->text .= '
                timer = setTimeout("updateTime()",1000);
            }

            updateTime();
        //]]>
        </script>
        ';

        $this->content->footer = '';
        return $this->content;
    }
}
?>
