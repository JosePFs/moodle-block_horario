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
 * Language file
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Course schedules';
$string['horario:addinstance'] = 'Add a new course schedules block';
$string['courses'] = 'Courses';
$string['cohorts'] = 'Cohorts';
$string['hour_from'] = 'From hour:';
$string['minute_from'] = 'From minute:';
$string['hour_to'] = 'To hour:';
$string['days'] = 'Days';
$string['sunday'] = 'Sunday';
$string['monday'] = 'Monday';
$string['tuesday'] = 'Tuesday';
$string['wednesday'] = 'Wednesday';
$string['thursday'] = 'Thursday';
$string['friday'] = 'Friday';
$string['saturday'] = 'Saturday';
$string['page_title'] = 'Course not available at this moment';
$string['page_heading'] = 'Course not available at this moment';
$string['error_allowed_mode'] = 'You can not access the course at the moment. Allowed schedule: {$a}';
$string['error_restricted_mode'] = 'You can not access the course at the moment. Restricted schedule: {$a}';
$string['from_to_interval_error'] = 'To time must be greater than from time';
$string['restricted_access'] = 'Restricted access:';
$string['granted_access'] = 'Granted access:';
$string['show_block'] = 'Show block to students';
$string['config_scheduling_mode'] = 'Scheduling mode';
$string['config_scheduling_mode_help'] = 'Set scheduling mode: allow access in the selected time range or deny access in the selected time range.';
$string['access_allowed'] = 'Access allowed only in specified times';
$string['access_denied'] = 'Access denied in specified times';
$string['config_cohorts'] = 'Cohorts used';
$string['config_cohorts_help'] = 'Symtem cohorts used to restrict or allow user access.';
$string['config_show_block'] = 'Show schedule information';
$string['config_show_block_help'] = 'No course admin user can view schedule information in block.';
$string['blocksettings_warning'] = 'You must create a cohort at least to enable this restriction options';
$string['missingcohorts'] = 'You must select a cohort at least';
$string['missingdays'] = 'You must select a day at least';
$string['admin_title'] = 'Admin courses schedules';
$string['configureblock'] = 'Configure {$a} block';
$string['hideblock'] = 'Hide {$a} block';
$string['showblock'] = 'Show {$a} block';