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
 * Renderer for block block_horario
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

use block_horario\plugin_config;

/**
 * block_horario block renderer
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_horario_renderer extends plugin_renderer_base {

    public function shedule_message() {
        global $SESSION;

        $output = html_writer::tag('div',
            $SESSION->block_horario_flash,
            array('class' => 'alert alert-error'));
        $output .= $this->continue_button('/');
        
        return $output;
    }
    
    public function text(plugin_config $plugin_config) {
        $output = html_writer::tag('p', $plugin_config->get_week_days());
        $output .= html_writer::tag('p', 
                '[' . $plugin_config->get_time_from() . 
                ' - ' . $plugin_config->get_time_to() . 
                ' ]'
                );
        
        return $output;
    }
    
    public function flash_notification(plugin_config $plugin_config) {
        $output = $plugin_config->get_week_days() . ' [ ';
        $output .= $plugin_config->get_time_from() . ' - ' . 
                    $plugin_config->get_time_to() . ' ] ';
        
        return $output;
    }
}
