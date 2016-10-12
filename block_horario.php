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
 * class block_horario
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use block_horario\helper;

/**
 * class block_horario
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_horario extends block_base {

    /** @var $helper \block_horario\helper */
    private $helper;

    public function init() {
        $this->title = get_string('pluginname', 'block_horario');
    }

    public function specialization() {
        if (isset($this->config)) {
            $this->helper = new helper($this->config);
            if (!$this->helper->is_current_course_available()) {
                $this->prepare_notification();
                redirect(new \moodle_url('/blocks/horario/view.php'));
            }
        }
    }

    public function has_config() {
        return false;
    }

    public function applicable_formats() {
        return array(
                    'site-index' => false,
                    'course-view' => true,
                    'mod' => true,
                    'admin' => false,
                    'my' => false
                    );
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function instance_can_be_docked() {
        return true;
    }

    public function get_content() {
        if (null !== $this->content) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->footer = '';
        if (isset($this->helper) && $this->helper->is_course_admin()) {
            $renderer = $this->page->get_renderer('block_horario');
            $this->content->text = $renderer->text($this->helper->get_plugin_config());
        } else {
            $this->content->text = '';
        }

        return $this->content;
    }

    /**
     * Set flash error message.
     *
     * @global stdClass $SESSION
     * @return void
     */
    private function prepare_notification() {
        global $SESSION;

        $renderer = $this->page->get_renderer('block_horario');
        $schedule = $renderer->flash_notification($this->helper->get_plugin_config());

        $SESSION->block_horario_flash = get_string('error', 'block_horario', $schedule);
    }
}