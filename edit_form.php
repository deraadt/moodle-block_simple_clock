<?php

class block_simple_clock_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        // Start block specific section in config form
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // Clock block instance title
        $mform->addElement('text', 'config_clock_title', get_string('config_title', 'block_simple_clock'));
        $mform->setDefault('config_clock_title', get_string('clock_title_default','block_simple_clock'));
        $mform->setType('config_clock_title', PARAM_MULTILANG);
        $mform->addHelpButton('config_clock_title','config_title', 'block_simple_clock');

        // Clocks shown options
        $showClockOptions = array(
            B_SIMPLE_CLOCK_SHOW_BOTH => get_string('config_show_both_clocks', 'block_simple_clock'),
            B_SIMPLE_CLOCK_SHOW_SERVER_ONLY => get_string('config_show_server_clock', 'block_simple_clock'),
            B_SIMPLE_CLOCK_SHOW_USER_ONLY => get_string('config_show_user_clock', 'block_simple_clock')
        );
        $mform->addElement('select', 'config_show_clocks', get_string('config_clock_visibility', 'block_simple_clock'), $showClockOptions);
        $mform->setDefault('config_show_clocks', B_SIMPLE_CLOCK_SHOW_BOTH);
        $mform->addHelpButton('config_show_clocks','config_clock_visibility', 'block_simple_clock');

        // Control visibility of day
        $mform->addElement('selectyesno', 'config_show_date', get_string('config_date', 'block_simple_clock'));
        $mform->setDefault('config_show_date', 0);
        $mform->addHelpButton('config_show_date','config_date', 'block_simple_clock');

        // Control visibility of seconds
        $mform->addElement('selectyesno', 'config_show_seconds', get_string('config_seconds', 'block_simple_clock'));
        $mform->setDefault('config_show_seconds', 0);
        $mform->addHelpButton('config_show_seconds','config_seconds', 'block_simple_clock');

        // Control visibility of icons
        $mform->addElement('selectyesno', 'config_show_icons', get_string('config_icons', 'block_simple_clock'));
        $mform->setDefault('config_show_icons', 1);
        $mform->addHelpButton('config_show_icons','config_icons', 'block_simple_clock');

        // Control visibility of the block header
        $mform->addElement('selectyesno', 'config_show_header', get_string('config_header', 'block_simple_clock'));
        $mform->setDefault('config_show_header', 1);
        $mform->addHelpButton('config_show_header','config_header', 'block_simple_clock');
    }
}
