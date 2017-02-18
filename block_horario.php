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

// Support for < 2.6 Moodle version.
require_once($CFG->dirroot.'/blocks/horario/classes/adapter.php');

use block_horario\helper;
use block_horario\cohorts_service;
use block_horario\admin_helper;

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

    /**
     * {@inheritDoc}
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_horario');
    }

    /**
     * {@inheritDoc}
     */
    public function specialization() {
        admin_helper::edit_horario();
        $cohortsservice = cohorts_service::instance();
        $systemcohorts = $cohortsservice->get_all_cohorts();
        if (empty($systemcohorts['cohorts'])) {
            return false;
        }

        if (isset($this->config)) {
            $this->helper = new helper($this->config);
            if (!$this->helper->is_current_course_available()) {
                $this->prepare_notification();
                redirect(new \moodle_url('/blocks/horario/view.php'));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function has_config() {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function applicable_formats() {
        return array(
                    'site-index' => false,
                    'course-view' => true,
                    'mod' => true,
                    'admin' => false,
                    'my' => false
                    );
    }

    /**
     * {@inheritDoc}
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function instance_can_be_docked() {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function get_content() {
        if (null !== $this->content) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->footer = '';

        $blockisconfigured = isset($this->helper);

        if (!$blockisconfigured) {
            $this->content->text = '';
        } else {
            $renderer = $this->page->get_renderer('block_horario');
        }

        if ($blockisconfigured && $this->helper->is_course_admin()) {
            $this->content->text = $this->get_admin_text($renderer);
        } else if ($blockisconfigured &&
                $this->helper->user_is_in_cohort() &&
                $this->helper->get_plugin_config()->get_show_block()
                ) {
            $this->content->text = $this->get_text($renderer);
        }

        return $this->content;
    }

    /**
     * Returns course in which block is located.
     *
     * @return stdClass $course
     */
    public function get_course() {
        global $DB;

        $coursecontext = $this->context->get_course_context();
        $course = $DB->get_record('course', array('id' => $coursecontext->instanceid));

        return $course;
    }

    /**
     * Returns block text and admin link.
     *
     * @param block_horario_renderer $renderer
     * @return string $text
     */
    private function get_admin_text(block_horario_renderer $renderer) {
        $text = $this->get_text($renderer);
        $text .= $renderer->admin_link();

        return $text;
    }

    /**
     * Returns block text.
     *
     * @param block_horario_renderer $renderer
     * @return string $text
     */
    private function get_text(block_horario_renderer $renderer) {
        $text = $renderer->text($this->helper->get_plugin_config());

        return $text;
    }

    /**
     * Set flash error message.
     *
     * @return void
     */
    private function prepare_notification() {
        global $SESSION;

        $pluginconfig = $this->helper->get_plugin_config();
        $renderer = $this->page->get_renderer('block_horario');
        $schedule = $renderer->flash_notification($pluginconfig);

        if ($pluginconfig->get_scheduling_mode()) {
            $SESSION->block_horario_flash = get_string('error_restricted_mode', 'block_horario', $schedule);
        } else {
            $SESSION->block_horario_flash = get_string('error_allowed_mode', 'block_horario', $schedule);
        }
    }
}