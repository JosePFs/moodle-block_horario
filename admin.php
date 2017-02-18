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
 * Main admin page
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('classes/admin_helper.php');

$showid = optional_param('block_showid', null, PARAM_INT);
$hideid = optional_param('block_hideid', null, PARAM_INT);

require_login(0, false);
$PAGE->set_context(context_system::instance());

$PAGE->set_pagelayout('admin');
$PAGE->set_url('/blocks/horario/admin.php');
$PAGE->set_title(get_string('admin_title', 'block_horario'));
$PAGE->set_heading(get_string('admin_title', 'block_horario'));

$adminhelper = new block_horario\admin_helper();
if ($showid) {
    $adminhelper->show_block($showid);
}
if ($hideid) {
    $adminhelper->hide_block($hideid);
}

echo $OUTPUT->header();
$renderer = $PAGE->get_renderer('block_horario');
echo $renderer->admin_table($adminhelper);
echo $OUTPUT->footer();