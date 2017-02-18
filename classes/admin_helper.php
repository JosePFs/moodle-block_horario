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
 * Admin helper
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_horario;

defined('MOODLE_INTERNAL') || die();

/**
 * Manage admin blocks data
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_helper {

    /**
     * @var block_horario[] $blocks
     */
    private $blocks = null;

    /**
     * Constructor
     */
    public function __construct() {
        $this->set_blocks();
    }

    /**
     * Check if click on edit block.
     *
     * @return void|redirect
     */
    public static function edit_horario() {
        global $USER;

        $edithorario = optional_param('edit_horario', null, PARAM_ALPHA);
        if (is_null($edithorario)) {
            return;
        }

        if ($edithorario === 'on' && confirm_sesskey()) {
            $USER->editing = 1;
            $params = array();
            $params['bui_editid'] = required_param('bui_editid', PARAM_ALPHANUM);
            $params['sesskey'] = required_param('sesskey', PARAM_RAW);
            $params['id'] = required_param('id', PARAM_INT);
            $params['notifyeditingon'] = required_param('id', PARAM_INT);
            redirect(new \moodle_url('/course/view.php', $params));
        }
    }

    /**
     * Set all horario blocks in courses.
     *
     */
    private function set_blocks() {
        global $DB;

        $getblocks = function($instance) use ($DB) {
            $block = block_instance('horario', $instance);
            $instancepositions = $DB->get_records('block_positions',
                    array('blockinstanceid' => $instance->id), 'id DESC', '*', 0, 1);
            $instancepositions = reset($instancepositions);
            if (!$instancepositions) {
                $block->instance->visible = 1;
                $block->instance->blockpositionid = null;
            } else {
                $block->instance->visible = $instancepositions->visible;
                $block->instance->blockpositionid = $instancepositions->id;
            }
            return $block;
        };

        $search = array('blockname' => 'horario');
        $instances = $DB->get_records('block_instances', $search);
        $this->blocks = array_map($getblocks, $instances);
    }

    /**
     * Get all block_horario blocks
     *
     * @return block_horario[]
     */
    public function get_blocks() {
        return $this->blocks;
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
     * @param int $blockinstanceid
     * @param int $newvisibility
     */
    private function update_block_visibility($blockinstanceid, $newvisibility) {
        global $DB;

        $block = $this->blocks[$blockinstanceid];
        $block->instance->visible = $newvisibility;

        if (null !== $block->instance->blockpositionid) {
            $DB->set_field('block_positions', 'visible', $newvisibility, array('blockinstanceid' => $blockinstanceid));
        } else {
            $this->insert_position($block);
        }
        redirect(new \moodle_url('/blocks/horario/admin.php'));
    }

    /**
     * Insert basic position block record.
     *
     * @param stdClass $block
     */
    private function insert_position($block) {
        global $DB;

        $blockposition = new \stdClass;
        $blockposition->blockinstanceid = $block->instance->id;
        $blockposition->contextid = $block->instance->parentcontextid;
        $blockposition->visible = $block->instance->visible;
        $blockposition->region = $block->instance->defaultregion;
        $blockposition->weight = $block->instance->defaultweight;
        $blockposition->pagetype = 'course-view-'. $block->get_course()->format;
        $DB->insert_record('block_positions', $blockposition);
    }
}