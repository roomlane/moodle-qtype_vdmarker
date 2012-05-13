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

require_once($CFG->dirroot . '/question/type/vdmarker/expression.php');

// required by qtype standard
$string['addingvdmarker'] = 'Venn\'i diagrammi märkija küsimuse lisamine';
$string['editingvdmarker'] = 'Venn\'i diagrammi märkija muutmine';
$string['vdmarker'] = 'Venn\'i diagrammi märkija';
$string['vdmarkersummary'] = 'Võimaldab anda vastuse märkides Venn\'i diagrammil erinevaid piirkondi vastavalt etteantud ülesande tekstile.';
$string['vdmarker_help'] = 'Õppetaja annab ülesande teksti, mis tavaliselt sisaldab hulgateoori avaldist. Õpilane valib hiirega Venn\'i diagrammil klõpsates piirkonnad, mis vastavad ülesande tekstile.';

// other
$string['penalty_per_wrong_area'] = 'Trahv iga valesti märgitud piirkonna eest';
$string['default_question_text'] = 'Märkida Venn\'i diagrammil piirkonnad, mis vastavad avaldisele ' . qtype_vdmarker_vd3_expression::get_chars_formatted(qtype_vdmarker_vd3_expression::ALLOWED_CHARS); // this text will appear initially in the question text field for teacher
$string['chars_for_copy_paste_caption'] = 'Lubatud tähemärgid';
$string['correct_answer'] = 'Õige vastus'; // text that appears left from the diagram on the question editing form
