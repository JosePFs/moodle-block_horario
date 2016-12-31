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
 * Admin helper.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_horario;

defined('MOODLE_INTERNAL') || die();

/**
 * Admin helper.
 * Manage admin blocks data.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_helper {
    
    /**
     * Returns all horario blocks in courses.
     * 
     * @global \block_horario\stdClass $DB
     * @return array $blocks
     */
    public function get_blocks() {
        global $DB;
        
        $getBlocks = function($instance) use ($DB) {
            $block = block_instance('horario', $instance);
            $instancepositions = $DB->get_record('block_positions', 
                    array('blockinstanceid' => $instance->id));
            $block->instance->visible = $instancepositions->visible;  
            return $block;
        };
        
        $search = array('blockname' => 'horario');
        $instances = $DB->get_records('block_instances', $search);
        $blocks = array_map($getBlocks, $instances);
        
        return $blocks;
    }
    
    /**
     * Show block.
     * 
     * @param int $blockinstanceid
     */
    public function show_block($blockinstanceid) {
        $this->update_block_visibility($blockinstanceid, 1);
    }
    
    /**
     * Hide block.
     * 
     * @param int $blockinstanceid
     */
    public function hide_block($blockinstanceid) {
        $this->update_block_visibility($blockinstanceid, 0);       
    }
    
    /**
     * Set block visibility.
     * 
     * @global stdClass $DB
     * @param int $blockinstanceid
     * @param int $newvisibility
     */
    private function update_block_visibility($blockinstanceid, $newvisibility) {
        global $DB;
        $DB->set_field('block_positions', 'visible', $newvisibility, array('blockinstanceid' => $blockinstanceid));
    }
}