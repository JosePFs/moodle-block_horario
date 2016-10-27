<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Form for editing config Horario block instances.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use block_horario\edit_helper;

/**
 * Form for editing config Horario block instances.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_horario_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $cohortsoptions = edit_helper::get_cohorts_options();
        if (empty($cohortsoptions)) {
            $mform->addElement('html',
                    '<div class="alert alert-info">'.
                        get_string('blocksettings_warning', 'block_horario').
                    '</div>'
                    );
            return true;
        }

        // Select restriction mode.
        $restrict = array();
        $restrict[] = $mform->createElement('radio', 'config_restrict_mode', '', get_string('yes'), 1, array());
        $restrict[] = $mform->createElement('radio', 'config_restrict_mode', '', get_string('no'), 0, array());
        $mform->addGroup($restrict, 'config_restrict_mode', get_string('restrict_mode', 'block_horario'), array(' '), false);
        $mform->setDefault('config_restrict_mode', 1);
        $mform->addHelpButton('config_restrict_mode', 'config_restrict_mode', 'block_horario');

        // Select cohorts.
        $selectcohorts = $mform->addElement(
                'select',
                'config_cohorts',
                get_string('cohorts', 'block_horario'),
                $cohortsoptions,
                array()
                );
        $selectcohorts->setMultiple(true);
        $mform->addHelpButton('config_cohorts', 'config_cohorts', 'block_horario');

        // Select week days.
        $daysoptions = edit_helper::get_days_options();
        $selectdays = $mform->addElement(
                'select',
                'config_days',
                get_string('days', 'block_horario'),
                $daysoptions,
                array()
                );
        $selectdays->setMultiple(true);

        $houroptions = edit_helper::get_hour_options();
        $minuteoptions = edit_helper::get_minute_options();

        // Select hour from.
        $mform->addElement(
                'select',
                'config_hour_from',
                get_string('hour_from', 'block_horario'),
                $houroptions,
                array()
                );

        // Select minute from.
        $mform->addElement(
                'select',
                'config_minute_from',
                get_string('minute_from', 'block_horario'),
                $minuteoptions,
                array()
                );

        // Select hour to.
        $mform->addElement(
                'select',
                'config_hour_to',
                get_string('hour_to', 'block_horario'),
                $houroptions,
                array()
                );

        // Select minute to.
        $mform->addElement(
                'select',
                'config_minute_to',
                get_string('minute_to', 'block_horario'),
                $minuteoptions,
                array()
                );

        // Select show block to student.
        $showblock = array();
        $showblock[] = $mform->createElement('radio', 'config_show_block', '', get_string('yes'), 1, array());
        $showblock[] = $mform->createElement('radio', 'config_show_block', '', get_string('no'), 0, array());
        $mform->addGroup($showblock, 'config_show_block', get_string('show_block', 'block_horario'), array(' '), false);
        $mform->setDefault('config_show_block', 0);
        $mform->addHelpButton('config_show_block', 'config_show_block', 'block_horario');
    }

    public function validation($data, $files) {
        if (!isset($data['config_hour_from'])) {
            return array();
        }

        $errors = array();
        if ($this->is_from_greater_equal_to($data)) {
            $errors['config_hour_from'] = get_string('from_to_interval_error', 'block_horario');
        }

        return $errors;
    }

    /**
     * Checks to time is greater or equal from time.
     *
     * @param array $data
     * @return boolean
     */
    private function is_from_greater_equal_to(array $data) {
        $fromtime = new \DateTime();
        $totime = clone $fromtime;
        $fromtime->setTime($data['config_hour_from'], $data['config_minute_from']);
        $totime->setTime($data['config_hour_to'], $data['config_minute_to']);

        return $fromtime >= $totime;
    }
}