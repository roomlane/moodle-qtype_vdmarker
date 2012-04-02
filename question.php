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

//    public function grade_response(array $response) {
//        list($right, $total) = $this->get_num_parts_right($response);
//        $fraction = $right / $total;
//        return array($fraction, question_state::graded_state_for_fraction($fraction));
//    }

//    public function compute_final_grade($responses, $totaltries) {
//        $maxitemsdragged = 0;
//        $wrongtries = array();
//        foreach ($responses as $i => $response) {
//            $maxitemsdragged = max($maxitemsdragged,
//                                                $this->total_number_of_items_dragged($response));
//            $hits = $this->choose_hits($response);
//            foreach ($hits as $place => $choiceitem) {
//                if (!isset($wrongtries[$place])) {
//                    $wrongtries[$place] = $i;
//                }
//            }
//            foreach ($wrongtries as $place => $notused) {
//                if (!isset($hits[$place])) {
//                    unset($wrongtries[$place]);
//                }
//            }
//        }
//        $numtries = count($responses);
//        $numright = count($wrongtries);
//        $penalty = array_sum($wrongtries) * $this->penalty;
//        $grade = ($numright - $penalty) / (max($maxitemsdragged, count($this->places)));
//        return $grade;
//    }
}