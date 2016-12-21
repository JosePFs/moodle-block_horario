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
 * Plugin configuration.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_horario;

defined('MOODLE_INTERNAL') || die();

/**
 * Plugin configuration.
 * DTO that holds block configuration.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plugin_config {

    private $cohorts;
    private $days;
    private $hourfrom;
    private $minutefrom;
    private $hourto;
    private $minuteto;
    private $showblock;
    private $schedulingmode;

    public function get_cohorts() {
        return $this->cohorts;
    }

    public function get_days() {
        return $this->days;
    }

    public function get_hour_from() {
        return $this->hourfrom;
    }

    public function get_minute_from() {
        return $this->minutefrom;
    }

    public function get_hour_to() {
        return $this->hourto;
    }

    public function get_minute_to() {
        return $this->minuteto;
    }

    public function get_show_block() {
        return $this->showblock;
    }

    public function get_scheduling_mode() {
        return $this->schedulingmode;
    }

    public function set_cohorts($cohorts) {
        $this->cohorts = $cohorts;
    }

    public function set_days($days) {
        $this->days = $days;
    }

    public function set_hour_from($hourfrom) {
        $this->hourfrom = $hourfrom;
    }

    public function set_minute_from($minutefrom) {
        $this->minutefrom = $minutefrom;
    }

    public function set_hour_to($hourto) {
        $this->hourto = $hourto;
    }

    public function set_minute_to($minuteto) {
        $this->minuteto = $minuteto;
    }

    public function set_show_block($showblock) {
        $this->showblock = $showblock;
    }

    public function set_scheduling_mode($shedulingmode) {
        $this->schedulingmode = $shedulingmode;
    }

    public function get_time_from() {
        $time = sprintf('%02d:%02d', $this->get_hour_from(), $this->get_minute_from());

        return $time;
    }

    public function get_time_to() {
        $time = sprintf('%02d:%02d', $this->get_hour_to(), $this->get_minute_to());

        return $time;
    }

    public function get_week_days() {
        $daysnames = [
            get_string('sunday', 'block_horario'),
            get_string('monday', 'block_horario'),
            get_string('tuesday', 'block_horario'),
            get_string('wednesday', 'block_horario'),
            get_string('thursday', 'block_horario'),
            get_string('friday', 'block_horario'),
            get_string('saturday', 'block_horario'),
        ];
        $days = array();
        foreach ($this->get_days() as $dayindex) {
            $days[$dayindex] = $daysnames[$dayindex];
        }

        return implode(', ', $days);
    }
}