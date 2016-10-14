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
 * Cohorts service.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cohorts_service implements cohorts_provider_interface {
    
    private $provider;
    
    /**
     * Returns cohorts provider depending on Moodle version.
     * 
     * @return cohorts_provider_legacy|cohorts_provider
     */
    public static function instance() {
        $isnewversion = function_exists('cohort_get_all_cohorts');
        switch ($isnewversion) {
            case true:
                $provider = new cohorts_provider();
                break;
            
            case false:
                $provider = new cohorts_provider_legacy();
                break;
        }
        
        return new cohorts_service($provider);
    }
    
    public function __construct(cohorts_provider_interface $provider) {
        $this->provider = $provider;
    }

    /**
     * Returns all cohorts.
     * 
     * @return array $cohorts
     */
    public function get_all_cohorts() {
        return $this->provider->get_all_cohorts();
    }
}