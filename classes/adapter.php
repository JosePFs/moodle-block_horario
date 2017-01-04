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
 * Moodle versions adapter.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_horario;

defined('MOODLE_INTERNAL') || die();

/**
 * Adapts Moodle versions. 
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class adapter {

    const MOODLE_26_VERSION = 2013110500;
    
    private $version = null;
    private $isoldversion = false;
    
    private static $instance = null;
    
    public static function old_versions() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        global $CFG;
        require_once($CFG->dirroot.'/lib/adminlib.php');
        $this->version = (int) \get_component_version('core');
        if ($this->version <= self::MOODLE_26_VERSION) {
            $this->isoldversion = true;
            $this->load_classes();
        }
    }
    
    private function load_classes() {
        require_once('admin_helper.php');
        require('cohorts_service.php');
        require('config_builder.php');
        require('edit_helper.php');
        require('helper.php');
        require('plugin_config.php');
    }
}

\block_horario\adapter::old_versions();