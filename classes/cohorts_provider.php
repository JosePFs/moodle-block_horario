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
 * Cohorts provider helper.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_horario;

defined('MOODLE_INTERNAL') || die();

use block_horario\cohorts_provider_interface;

/**
 * Cohorts provider helper.
 * Uses new cohorts library.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cohorts_provider implements cohorts_provider_interface {
    
    /**
     * Returns all cohorts.
     * 
     * @global stdClass $CFG
     * @return array $cohorts
     */
    public function get_all_cohorts() {
        global $CFG;
        require_once("$CFG->dirroot/cohort/lib.php");
        
        $cohorts = \cohort_get_all_cohorts();
        
        return $cohorts;
    }
}