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

    /**
     * Max cohorts select element options vertical size.
     */
    const MAX_COHORTS_SIZE = 20;

    /**
     * Specific form definition
     *
     * @param MoodleQuickForm $mform
     * @return boolean|null
     */
    protected function specific_definition($mform) {
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // If no cohorts, show message.
        $cohortsoptions = edit_helper::get_cohorts_options();
        if (empty($cohortsoptions)) {
            $mform->addElement('html',
                    '<div class="alert alert-info">'.
                        get_string('blocksettings_warning', 'block_horario').
                    '</div>'
                    );
            return true;
        }

        // Select scheduling mode.
        $schedulingmode = $mform->addElement(
                'select',
                'config_scheduling_mode',
                get_string('config_scheduling_mode', 'block_horario'),
                edit_helper::get_mode_options(),
                array()
                );
        $schedulingmode->setMultiple(false);
        $mform->addHelpButton('config_scheduling_mode', 'config_scheduling_mode', 'block_horario');

        // Select cohorts.
        $countcohorts = count($cohortsoptions);
        $sizeselectcohorts = $countcohorts <= self::MAX_COHORTS_SIZE ? $countcohorts : self::MAX_COHORTS_SIZE;
        $selectcohorts = $mform->addElement(
                'select',
                'config_cohorts',
                get_string('cohorts', 'block_horario'),
                $cohortsoptions,
                array('size' => $sizeselectcohorts)
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
                array('size' => count($daysoptions))
                );
        $selectdays->setMultiple(true);

        $houroptions = edit_helper::get_hour_options();
        $minuteoptions = edit_helper::get_minute_options();

        $timefrom = array();

        // Select hour from.
        $timefrom[] =& $mform->createElement(
                'select',
                'config_hour_from',
                get_string('hour_from', 'block_horario'),
                $houroptions,
                array()
                );

        // Select minute from.
        $timefrom[] =& $mform->createElement(
                'select',
                'config_minute_from',
                '',
                $minuteoptions,
                array()
                );

        // Group hour minute from.
        $mform->addGroup($timefrom, 'config_time_from', get_string('hour_from', 'block_horario'), array(' '), false);

        $timeto = array();

        // Select hour to.
        $timeto[] =& $mform->createElement(
                'select',
                'config_hour_to',
                get_string('hour_to', 'block_horario'),
                $houroptions,
                array()
                );

        // Select minute to.
        $timeto[] =& $mform->createElement(
                'select',
                'config_minute_to',
                '',
                $minuteoptions,
                array()
                );

        // Group hour minute to.
        $mform->addGroup($timeto, 'config_time_from', get_string('hour_to', 'block_horario'), array(' '), false);

        // Select show block to student.
        $showblock = array();
        $showblock[] = $mform->createElement('radio', 'config_show_block', '', get_string('yes'), 1, array());
        $showblock[] = $mform->createElement('radio', 'config_show_block', '', get_string('no'), 0, array());
        $mform->addGroup($showblock, 'config_show_block', get_string('show_block', 'block_horario'), array(' '), false);
        $mform->setDefault('config_show_block', 0);
        $mform->addHelpButton('config_show_block', 'config_show_block', 'block_horario');
    }

    /**
     * Return form errors
     *
     * @param array $data
     * @param array $files
     * @return array $errors
     */
    public function validation($data, $files) {
        if (!isset($data['config_hour_from'])) {
            return array();
        }

        $errors = array();
        if (!isset($data['config_cohorts'])) {
            $errors['config_cohorts'] = get_string('missingcohorts', 'block_horario');
        }
        if (!isset($data['config_days'])) {
            $errors['config_days'] = get_string('missingdays', 'block_horario');
        }
        if ($this->is_from_greater_equal_to($data)) {
            $errors['config_time_from'] = get_string('from_to_interval_error', 'block_horario');
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