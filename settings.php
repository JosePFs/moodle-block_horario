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
 * Main settings file
 *
 * @package   block_horario
 * @copyright 2016 José Puente <jpuentefs@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// Create link in admin block menu.
$ADMIN->add('blocksettings', new admin_externalpage('block_horario', get_string('pluginname', 'block_horario'),
        $CFG->wwwroot.'/blocks/horario/admin.php'));

// Prevent Moodle from adding settings block in standard location.
$settings = null;