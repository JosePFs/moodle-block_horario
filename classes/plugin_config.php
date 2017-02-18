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
 * Plugin configuration
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_horario;

defined('MOODLE_INTERNAL') || die();

/**
 * DTO that holds block configuration.
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plugin_config {

    /**
     * @var array $cohorts Ids
     */
    private $cohorts;

    /**
     * @var array $days
     */
    private $days;

    /**
     * @var integer $hourfrom
     */
    private $hourfrom;

    /**
     * @var integer $minutefrom
     */
    private $minutefrom;

    /**
     * @var integer $hourto
     */
    private $hourto;

    /**
     * @var integer $minuteto
     */
    private $minuteto;

    /**
     * @var integer $showblock
     */
    private $showblock;

    /**
     * @var integer $schedulingmode
     */
    private $schedulingmode;

    /**
     * Gets cohorts
     * @return array Cohorts
     */
    public function get_cohorts() {
        return $this->cohorts;
    }

    /**
     * Gets days
     * @return integer Days
     */
    public function get_days() {
        return $this->days;
    }

    /**
     * Gets hour from
     * @return integer Hour from
     */
    public function get_hour_from() {
        return $this->hourfrom;
    }

    /**
     * Gets minute from
     * @return integer Minute from
     */
    public function get_minute_from() {
        return $this->minutefrom;
    }

    /**
     * Gets hour to
     * @return integer Hour to
     */
    public function get_hour_to() {
        return $this->hourto;
    }

    /**
     * Gets minute to
     * @return integer Minute to
     */
    public function get_minute_to() {
        return $this->minuteto;
    }

    /**
     * Gets show block option
     * @return integer Show block
     */
    public function get_show_block() {
        return $this->showblock;
    }

    /**
     * Gets scheduling mode
     * @return integer Scheduling mode
     */
    public function get_scheduling_mode() {
        return $this->schedulingmode;
    }

    /**
     * Sets cohorts
     * @param array $cohorts
     */
    public function set_cohorts($cohorts) {
        $this->cohorts = $cohorts;
    }

    /**
     * Sets days
     * @param integer $days
     */
    public function set_days($days) {
        $this->days = $days;
    }

    /**
     * Sets hour from
     * @param integer $hourfrom
     */
    public function set_hour_from($hourfrom) {
        $this->hourfrom = $hourfrom;
    }

    /**
     * Sets minute from
     * @param integer $minutefrom
     */
    public function set_minute_from($minutefrom) {
        $this->minutefrom = $minutefrom;
    }

    /**
     * Sets hour to
     * @param integer $hourto
     */
    public function set_hour_to($hourto) {
        $this->hourto = $hourto;
    }

    /**
     * Sets minute to
     * @param integer $minuteto
     */
    public function set_minute_to($minuteto) {
        $this->minuteto = $minuteto;
    }

    /**
     * Sets show block option
     * @param integer $showblock
     */
    public function set_show_block($showblock) {
        $this->showblock = $showblock;
    }

    /**
     * Sets scheduling mode
     * @param integer $shedulingmode
     */
    public function set_scheduling_mode($shedulingmode) {
        $this->schedulingmode = $shedulingmode;
    }

    /**
     * Returns formated time from
     *
     * @return string $time
     */
    public function get_time_from() {
        $time = sprintf('%02d:%02d', $this->get_hour_from(), $this->get_minute_from());

        return $time;
    }

    /**
     * Returns formated time to
     *
     * @return string $time
     */
    public function get_time_to() {
        $time = sprintf('%02d:%02d', $this->get_hour_to(), $this->get_minute_to());

        return $time;
    }

    /**
     * Returns formated week days
     *
     * @return string Days
     */
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