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

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/question/type/vdmarker/venndiagram.php');

/**
 * Venn diagram question definition class.
 *
 * @package    rs_questiontypes
 * @subpackage vdmarker
 * @author     immor@hot.ee
 * @copyright  &copy; 2012 Rommi Saar
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */ 
class qtype_vdmarker_question extends question_graded_automatically {

    public function get_expected_data() {
        return array('vdstate' => PARAM_INTEGER);
    }

    public function get_correct_response() {
        //TODO: calculate the right answer accoring to question definition's area grades
        return array('vdstate' => 0);
    }

    public function get_validation_error(array $response) {
        return '';
    }

    public function grade_response(array $response) {
        $correct = 0; //TODO: get actual value
        $penalty = 1; //TODO: get actual value
        $fraction = 1 - qtype_vdmarker_vd3::num_incorrect_areas($correct, $response->vdstate) * $penalty;
        //TODO: calculate the acutal fraction according to question definition grades set by teacher
        return array($fraction, question_state::graded_state_for_fraction($fraction));
    }

    public function is_complete_response(array $response) {
        return true;
    }

    public function is_same_response(array $prevresponse, array $newresponse) {
        if (!question_utils::arrays_same_at_key_integer($prevresponse, $newresponse, 'vdstate')) {
            return false;
        }
        return true;
    }

    public function summarise_response(array $response) {
        return null;
    }
    
    /**
     * Last response
     * 
     * @param question_attempt $qa
     * @return int, null if no previous attempt 
     */
    public function get_response(question_attempt $qa) {
        return $qa->get_last_qt_var('vdstate', null);
    }
}