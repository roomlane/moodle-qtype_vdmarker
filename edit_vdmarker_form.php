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
 * Defines the editing form for the Venn diagram question type.
 *
 * @package    rs_questiontypes
 * @subpackage vdmarker
 * @author     immor@hot.ee
 * @copyright  &copy; 2012 Rommi Saar
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */ 

defined('MOODLE_INTERNAL') || die();

/**
 * Drag-and-drop images onto images  editing form definition.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_vdmarker_edit_form extends question_edit_form {

    public function qtype() {
        return 'vdmarker';
    }

    protected function definition_inner($mform) {
        $this->add_per_answer_fields($mform, 'label',
                question_bank::fraction_options_full(), 8);

        $this->add_combined_feedback_fields(true);

// todo: what's that?
//        $this->add_interactive_settings(true, true);
   }

     protected function add_per_answer_fields(&$mform, $label, $gradeoptions,
            $minoptions = QUESTION_NUMANS_START, $addoptions = QUESTION_NUMANS_ADD) {
        $answersoption = '';
        $repeatedoptions = array();

        $repeated = $this->get_per_answer_fields($mform, $label, $gradeoptions,
                $repeatedoptions, $answersoption);

        //todo: replace this with our own, so that the vd get's correct parameters
        $this->repeat_elements($repeated, $repeatsatstart, $repeatedoptions,
                'noanswers', 'addanswers', $addoptions,
                get_string('addmorechoiceblanks', 'qtype_multichoice'));
    }

    // todo: maybe need to use add_per_answer_fields instead and repeat manually
    protected function get_per_answer_fields($mform, $label, $gradeoptions,
            &$repeatedoptions, &$answersoption) {
        $repeated = array();
        
        //todo: remove later - this should not be needed in our case
        $repeated[] = $mform->createElement('header', 'answerhdr', $label);
        
        //todo: This should be replaced by the visual vd component in readonly mode.
        //      Don't know yet how to configure the vd per answer
        $repeated[] = $mform->createElement('editor', 'answer',
                get_string('answer', 'question'), array('rows' => 1), $this->editoroptions);
        
        $repeated[] = $mform->createElement('select', 'fractionselected',
                get_string('grade_when_selected', 'qtype_vdmarker'), $gradeoptions);
        $repeated[] = $mform->createElement('select', 'fractionnotselected',
                get_string('grade_when_not_selected', 'qtype_vdmarker'), $gradeoptions);

        $repeated[] = $mform->createElement('editor', 'feedback',
                get_string('feedback', 'question'), array('rows' => 1), $this->editoroptions);
        
        //todo: find out what these do, seem to be standard
        $repeatedoptions['answer']['type'] = PARAM_RAW;
        $repeatedoptions['fraction']['default'] = 0;
        $answersoption = 'answers';
        return $repeated;
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        return $errors;
    }

}