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
    
    /**
     * Add settings for each area on Venn's diagram (with 3 circles).
     * 
     * @param object $mform the form being built.
     */
    private function add_areas($mform, $label, $gradeoptions) {
        for ($i = 0; $i < 8; $i++) {
            $mform->addElement('header', "answerhdr[$i]", str_ireplace('{no}', ($i + 1), $label));

            //todo: Insert the visual vd component in readonly mode.
            //      Don't know yet how to configure the vd per answer

            $mform->addElement('select', "fractionselected[$i]",
                               get_string('grade_when_selected', 'qtype_vdmarker'), 
                               $gradeoptions);
            $mform->addElement('select', "fractionnotselected[$i]",
                               get_string('grade_when_not_selected', 'qtype_vdmarker'), 
                               $gradeoptions);

            $mform->addElement('editor', "feedback[$i]",
                               get_string('feedback', 'question'), 
                               array('rows' => 1), $this->editoroptions);
        }
    }

    /**
     * Add question-type specific form fields.
     * TODO: more comments
     *
     * @param object $mform the form being built.
     */
    protected function definition_inner($mform) {
        $this->add_areas($mform, 
                         get_string('area_header', 'qtype_vdmarker', '{no}'),
                         question_bank::fraction_options_full());

        $this->add_combined_feedback_fields(true);

        $this->add_interactive_settings(true, true);
   }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        return $errors;
    }

}