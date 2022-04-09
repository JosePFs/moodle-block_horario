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

use block_horario\plugin_config;
use block_horario\helper;
use block_horario\admin_helper;
use block_horario\cohorts_service;

/**
 * block_horario block renderer
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_horario_renderer extends plugin_renderer_base {

    /**
     * Returns HTML error access to course message.
     *
     * @return string $output
     */
    public function schedule_message() {
        global $SESSION;

        $output = html_writer::tag('div',
            $SESSION->block_horario_flash,
            array('class' => 'alert alert-error'));
        $output .= $this->continue_button('/');

        return $output;
    }

    /**
     * Returns HTML block text.
     *
     * @param plugin_config $pluginconfig
     * @return string $output
     */
    public function text(plugin_config $pluginconfig) {
        if ($pluginconfig->get_scheduling_mode()) {
            $mode = get_string('restricted_access', 'block_horario');
        } else {
            $mode = get_string('granted_access', 'block_horario');
        }
        $output = html_writer::tag('p', $mode);
        $output .= html_writer::tag('p', $pluginconfig->get_week_days());
        $output .= html_writer::tag('p',
                '[ ' . $pluginconfig->get_time_from() .
                ' - ' . $pluginconfig->get_time_to() .
                ' ]'
                );

        return $output;
    }

    /**
     * Returns shedule course text that is shown
     * in schedule message.
     *
     * @param plugin_config $pluginconfig
     * @return string $output
     */
    public function flash_notification(plugin_config $pluginconfig) {
        $output = $pluginconfig->get_week_days() . ' [ ';
        $output .= $pluginconfig->get_time_from() . ' - ' .
                    $pluginconfig->get_time_to() . ' ] ';

        return $output;
    }

    /**
     * Returns admin course schedules link.
     * usr/bin/phpcs
     * @return string $output
     */
    public function admin_link() {
        $output = html_writer::link(
                new moodle_url('/blocks/horario/admin.php'),
                get_string('admin_title', 'block_horario')
                );
        return $output;
    }

    /**
     * Returns admin table.
     *
     * @param admin_helper $adminhelper
     * @return string HTML table
     */
    public function admin_table(admin_helper $adminhelper) {
        $table = new html_table();
        $table->head = array(
            get_string('course'),
            get_string('schedule'),
            get_string('cohorts', 'core_cohort'),
            get_string('status'));
        $table->data = array();

        $blocks = $adminhelper->get_blocks();
        foreach ($blocks as $id => $block) {
            $table->data[$id] = $this->admin_row($block);
        }
        return html_writer::table($table);
    }

    /**
     * Returns a table row with course schedule info.
     *
     * @param block_horario $block
     * @return array $row
     */
    private function admin_row(block_horario $block) {
        $row = array();

        // Course link.
        $course = $block->get_course();
        $url = new moodle_url('/course/view.php', array('id' => $course->id));
        $row[] = html_writer::link($url, $course->fullname);

        // Schedule info.
        $helper = new helper($block->config);
        $config = $helper->get_plugin_config();
        $row[] = $this->text($config);

        // Block cohorts.
        $row[] = $this->cohorts($config);

        // Block status.
        $row[] = $this->status($block);

        return $row;
    }

    /**
     * Returns block cohorts cell.
     *
     * @param plugin_config $pluginconfig
     * @return string $link HTML
     */
    private function cohorts(plugin_config $pluginconfig) {
        $cohortsids = $pluginconfig->get_cohorts();
        $cohortsservice = cohorts_service::instance();
        $cohorts = $cohortsservice->get_cohorts_by_ids($cohortsids);
        $output = '';
        $url = new moodle_url('/cohort/index.php');
        foreach ($cohorts as $cohort) {
            $output .= html_writer::tag('p', $cohort->name);
        }
        $link = html_writer::link($url, $output);
        return $link;
    }

    /**
     * Returns block status admin controls.
     *
     * @param block_horario $block
     * @return string $controls HTML
     */
    private function status(block_horario $block) {
        $controls = '';
        $usercaneditblocks = $block->page->user_can_edit_blocks();

        if ($usercaneditblocks || $block->user_can_edit()) {
            $controls .= $this->edit_control($block);
        }

        if ($usercaneditblocks && $block->instance_can_be_hidden()) {
            $controls .= $this->visibility_control($block);
        }
        return $controls;
    }

    /**
     * Returns edit block control.
     *
     * @param block_horario $block
     * @return string $control HTML
     */
    private function edit_control(block_horario $block) {
        $str = get_string('configureblock', 'block_horario', $block->title);
        $url = new moodle_url('/course/view.php', array(
            'bui_editid' => $block->instance->id,
            'sesskey' => sesskey(),
            'id' => $block->get_course()->id,
            'notifyeditingon' => 1,
            'edit_horario' => 'on')
            );
        $icon = new pix_icon('t/edit', $str, 'moodle', array('class' => 'icon', 'title' => ''));
        $attributes = array('class' => 'editing_edit', 'title' => $str);
        $control = $this->output->action_icon($url, $icon, null, $attributes);
        return $control;
    }

    /**
     * Returns visibility block control.
     *
     * @param block_horario $block
     * @return string $control HTML
     */
    private function visibility_control(block_horario $block) {
        $url = new moodle_url('/blocks/horario/admin.php', array(
            'sesskey' => sesskey(),
            'id' => $block->get_course()->id,
            'notifyeditingon' => 1)
            );
        $blocktitle = $block->title;
        if (empty($blocktitle)) {
            $blocktitle = $block->arialabel;
        }
        // Show/hide icon.
        if ($block->instance->visible) {
            $url = $url->out(false, array('block_hideid' => $block->instance->id));
            $str = get_string('hideblock', 'block_horario', $blocktitle);
            $icon = new pix_icon('t/hide', $str, 'moodle', array('class' => 'icon', 'title' => ''));
            $attributes = array('class' => 'editing_hide', 'title' => $str);
        } else {
            $url = $url->out(false, array('block_showid' => $block->instance->id));
            $str = get_string('showblock', 'block_horario', $blocktitle);
            $icon = new pix_icon('t/show', $str, 'moodle', array('class' => 'icon', 'title' => ''));
            $attributes = array('class' => 'editing_show', 'title' => $str);
        }
        $control = $this->output->action_icon($url, $icon, null, $attributes);
        return $control;
    }
}
