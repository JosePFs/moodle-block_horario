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
 * Cohorts service.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_horario;

defined('MOODLE_INTERNAL') || die();


/**
 * Cohorts service.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cohorts_service {

    /**
     * @var \block_horario\cohorts_service $instance
     */
    private static $instance = null;

    /**
     * Returns service helper that get cohorts.
     *
     * @return cohorts_service
     */
    public static function instance() {
        if (null === self::$instance) {
            self::load_cohort_library();
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Returns all system context cohorts.
     *
     * @return array $cohorts
     */
    public function get_all_cohorts() {
        global $CFG;
        
        require_once($CFG->libdir.'/accesslib.php');

        $context = \context_system::instance();
        $cohorts = \cohort_get_cohorts($context->id);

        return $cohorts;
    }
    
    /**
     * Returns cohorts by ids. 
     * 
     * @global stdClass $DB
     * @param array $ids
     * @return stdClass[] $cohorts
     */
    public function get_cohorts_by_ids(array $ids) {
        global $DB;
        $cohorts = $DB->get_records_list('cohort', 'id', $ids, null, 'id,name');
        return $cohorts;
    }

    /**
     * Load cohort library.
     *
     * @global stdClass $CFG
     * @return void
     */
    private static function load_cohort_library() {
        global $CFG;

        include_once("$CFG->dirroot/cohort/lib.php");
    }
}