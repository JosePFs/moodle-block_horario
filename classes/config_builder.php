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
 * Config builder.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_horario;

defined('MOODLE_INTERNAL') || die();

use block_horario\plugin_config;

/**
 * Config Builder.
 * Transforms stdClass to plugin_class.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class config_builder {

    private $config;
    
    private $config_object;
    
    public static function instance(\stdClass $config) {
        return new config_builder($config);
    }
    
    public function __construct(\stdClass $config) {
        $this->config = $config;
        $this->config_object = new plugin_config();
    }
    
    public function build() {
        foreach ($this->config as $key => $config) {
            $method = "set_$key";
            if (method_exists($this->config_object, $method)) {
                $this->config_object->{$method}($config);
            }
        }
        
        return $this;
    }
    
    /**
     * Returns built object.
     * 
     * @return \block_horario\plugin_config
     */
    public function get_config() {
        return $this->config_object;
    }
}