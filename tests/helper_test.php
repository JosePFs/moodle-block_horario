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
 * Unit tests for block_horario helper
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */

defined('MOODLE_INTERNAL') || die();

use block_horario\helper;

/**
 * Unit tests for block_horario helper
 *
 * @package    block_horario
 * @copyright  2016 José Puente
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */
class block_horario_helper_testcase extends advanced_testcase {

    /**
     * Test course admin user
     * @return void
     */
    public function test_course_admin_user() {
        $this->resetAfterTest(true);
        $this->setAdminUser();
        
        $helper = new helper(new \stdClass());
        $is_course_admin = $helper->is_course_admin();
        
        $this->assertEquals(true, $is_course_admin);
    }
    
    /**
     * Test course no admin user
     * @return void
     */
    public function test_course_no_admin_user() {
        $this->resetAfterTest(true);
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);
        
        $helper = new helper(new \stdClass());
        $is_course_admin = $helper->is_course_admin();
        
        $this->assertEquals(false, $is_course_admin);
    }
    
    /**
     * Test course available admin user
     * @return void
     */
    public function test_course_available_admin_user() {
        $this->resetAfterTest(true);
        $this->setAdminUser();
        
        $helper = new helper(new \stdClass());
        $is_course_admin = $helper->is_current_course_available();
        
        $this->assertEquals(true, $is_course_admin);
    }
    
    /**
     * Test course available no cohort member and no admin user
     * @return void
     */
    public function test_course_available_no_cohort_no_admin_user() {
        $this->resetAfterTest(true);
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);
        
        $config = new \stdClass();
        $config->cohorts = ['1'];
        $config->days = ['0', '1', '2', '3', '4', '5', '6'];
        $config->hour_from = '0';
        $config->minute_from = '0';
        $config->hour_to = '0';
        $config->minute_to = '0';
        
        $helper = new helper($config);
        $is_course_admin = $helper->is_current_course_available();
        
        $this->assertEquals(true, $is_course_admin);
    }
    
    /**
     * Test course no available cohort member and no admin user
     * @return void
     */
    public function test_course_no_available_cohort_no_admin_user() {
        $this->resetAfterTest(true);
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);
        
        $config = new \stdClass();
        $config->cohorts = ['1'];
        $config->days = ['0', '1', '2', '3', '4', '5', '6'];
        $config->hour_from = '0';
        $config->minute_from = '0';
        $config->hour_to = '0';
        $config->minute_to = '0';
        
        $helper = $this->getMockBuilder('\block_horario\helper')
            ->setMethods(array('user_is_not_in_cohort'))
            ->setConstructorArgs(array($config))
            ->getMock();
        
        $helper->expects($this->once())
            ->method('user_is_not_in_cohort')
            ->will($this->returnValue(false));
        
        $is_course_admin = $helper->is_current_course_available();
        
        $this->assertEquals(false, $is_course_admin);
    }
    
}