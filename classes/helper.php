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
 * Verifications helper.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_horario;

defined('MOODLE_INTERNAL') || die();

use block_horario\config_builder;

/**
 * Verifications helper.
 * Checks if user can or not view current course.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {

    private $iscourseadmin;

    /** @var $pluginconfig \block_horario\plugin_config  */
    private $pluginconfig;

    public function __construct(\stdClass $config) {
        $this->pluginconfig = config_builder::instance($config)
                                ->build()
                                ->get_config();
        $this->iscourseadmin = $this->set_course_admin();
    }

    /**
     * Returns true if user can view course.
     *
     * @return boolean
     */
    public function is_current_course_available() {
        if ($this->is_course_admin()) {
            return true;
        }

        if ($this->user_can_view_course()) {
            return true;
        }

        return false;
    }

    /**
     * Returns current block configuration.
     *
     * @return \block_horario\plugin_config $pluginconfig
     */
    public function get_plugin_config() {
        return $this->pluginconfig;
    }

    /**
     * Returns true if current user is course admin.
     *
     * @return boolean
     */
    public function is_course_admin() {
        return $this->iscourseadmin;
    }

    /**
     * Returns true if current user can edit/update course.
     *
     * @global stdClass $COURSE
     * @return boolean
     */
    private function set_course_admin() {
        global $COURSE;
        $context = \context_course::instance($COURSE->id);
        $canupdatecourse = \has_capability ('moodle/course:update', $context) ? true : false;

        return $canupdatecourse;
    }

    /**
     * Returns truen if current no admin user can view user.
     *
     * @return boolean
     */
    private function user_can_view_course() {
        if ($this->user_is_not_in_cohort()) {
            return true;
        }

        if ($this->is_allowed_time_interval()) {
            return true;
        }

        return false;
    }

    /**
     * Returns true if current no admin user is not in restricted cohort.
     *
     * @global stdClass $CFG
     * @global stdClass $USER
     * @return boolean $isnotincohort
     */
    protected function user_is_not_in_cohort() {
        global $CFG, $USER;
        require_once("$CFG->dirroot/cohort/lib.php");

        $cohorts = $this->pluginconfig->get_cohorts();

        $isnotincohort = true;
        foreach ($cohorts as $cohortid) {
            if (\cohort_is_member($cohortid, $USER->id)) {
                $isnotincohort = false;
                break;
            }
        }

        return $isnotincohort;
    }

    /**
     * Returns true if time is in correct range.
     *
     * @return boolean
     */
    private function is_allowed_time_interval() {
        $restrictmode = (bool) $this->pluginconfig->get_restrict_mode();
        $isallowedinterval = !$restrictmode;
        $today = new \DateTime();
        $todayday = (int) $today->format('w');
        if (!in_array($todayday, $this->pluginconfig->get_days())) {
            $isallowedinterval = !$isallowedinterval;
        }

        $startdatetime = $this->get_start_datetime($today);
        $enddatetime = $this->get_end_datetime($today);
        if ($this->is_not_datetime_in_interval($today, $startdatetime, $enddatetime)) {
            $isallowedinterval = !$isallowedinterval;
        }

        return $isallowedinterval;
    }

    /**
     * Checks if datetime between two datetimes.
     * Use format because we need to check if they are equals regardless seconds.
     *
     * @param \DateTime $today
     * @param \DateTime $startdatetime
     * @param \DateTime $enddatetime
     * @return boolean
     */
    private function is_not_datetime_in_interval(\DateTime $today, \DateTime $startdatetime, \DateTime $enddatetime) {
        $todayhour = $today->format('Hi');
        $startdatetimehour = $startdatetime->format('Hi');
        $enddatetimehour = $enddatetime->format('Hi');

        if ($startdatetimehour <= $todayhour && $todayhour <= $enddatetimehour) {
            return false;
        }

        return true;
    }

    /**
     * Returns start interval.
     *
     * @param \DateTime $today
     * @return \DateTime $startdatetime
     */
    private function get_start_datetime(\DateTime $today) {
        $startdatetime  = clone $today;
        $hourfrom = $this->pluginconfig->get_hour_from();
        $minutefrom = $this->pluginconfig->get_minute_from();
        $startdatetime->setTime($hourfrom, $minutefrom);

        return $startdatetime;
    }

    /**
     * Returns end interval.
     *
     * @param \DateTime $today
     * @return \DateTime $enddatetime
     */
    private function get_end_datetime(\DateTime $today) {
        $enddatetime  = clone $today;
        $hourto = $this->pluginconfig->get_hour_to();
        $minuteto = $this->pluginconfig->get_minute_to();
        $enddatetime->setTime($hourto, $minuteto);

        return $enddatetime;
    }
}