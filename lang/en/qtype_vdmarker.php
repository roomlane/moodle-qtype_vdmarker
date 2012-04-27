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
 * The language strings for the Venn diagram question type.
 *
 * @package    rs_questiontypes
 * @subpackage vdmarker
 * @author     immor@hot.ee
 * @copyright  &copy; 2012 Rommi Saar
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */ 

require_once($CFG->dirroot . '/question/type/vdmarker/venndiagram.php');

// required by qtype standard
$string['addingvdmarker'] = 'Adding Venn diagram marker.';
$string['editingvdmarker'] = 'Editing Venn diagram marker.';
$string['vdmarker'] = 'Venn diagram marker';
$string['vdmarkersummary'] = 'Mark areas of the Venn diagram.';
$string['vdmarker_help'] = 'Teacher can define the question (a set theory expression) and marks for every reagion on Venn diagram. Student can click on Venn diagram to mark regions selected or unselected.';

// other
$string['penalty_per_wrong_area'] = 'Penalty per wrongly marked area';
$string['default_question_text'] = 'Mark the areas on Venn\'s diagram when the expression is ' . qtype_vdmarker_vd3_expression::ALLOWED_CHARS; // this text will appear initially in the question text field for teacher
$string['chars_for_copy_paste_caption'] = 'Valid characters';
$string['correct_answer'] = 'Correct answer'; // text that appears left from the diagram on the question editing form
