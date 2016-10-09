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
    
    private $is_course_admin;
    
    /** @var $plugin_config \block_horario\plugin_config  */
    private $plugin_config;

    public function __construct(\stdClass $config) {
        $this->plugin_config = config_builder::instance($config)
                                ->build()
                                ->get_config();
        $this->is_course_admin = $this->set_course_admin();
    }
    
    public function is_current_course_available() {
        if ($this->is_course_admin()) {
            return true;
        }
        
        if ($this->user_can_view_course()) {
            return true;
        }

        return false;
    }
    
    public function get_plugin_config() {
        return $this->plugin_config;
    }
    
    public function is_course_admin() {
        return $this->is_course_admin;
    }

    private function set_course_admin() {
        global $COURSE;
        $context = \context_course::instance($COURSE->id);
        $can_update_course = \has_capability ('moodle/course:update', $context) ? true : false;
    
        return $can_update_course;
    }
    
    private function user_can_view_course() {
        if ($this->user_is_not_in_cohort()) {
            return true;
        }
        
        if ($this->is_allowed_time_zone()) {
            return true;
        }

        return false;
    }
    
    protected function user_is_not_in_cohort() {
        global $CFG, $USER;
        require_once("$CFG->dirroot/cohort/lib.php");
        
        $cohorts = $this->plugin_config->get_cohorts();
        
        $is_not_in_cohort = true;
        foreach ($cohorts as $cohort_id) {
            if (\cohort_is_member($cohort_id, $USER->id)) {
                $is_not_in_cohort = false;
                break;
            }
        }
        
        return $is_not_in_cohort;
    }
    
    private function is_allowed_time_zone() {
        $today = new \DateTime();
        $today_day = (int) $today->format('w'); 
        if (!in_array($today_day, $this->plugin_config->get_days())) {
            return false;
        }
        
        $current_hour = $today->format('H:i');
        $time_from = $this->plugin_config->get_time_from();
        $time_to = $this->plugin_config->get_time_to();
        
        if ($time_from < $current_hour && $time_to > $current_hour) {
            return true;
        }
        
        return false;
    }
}