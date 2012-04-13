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

require_once($CFG->dirroot . '/question/type/vdmarker/venndiagram.php');

/**
 * Venn diagram marking question definition editing form.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_vdmarker_edit_form extends question_edit_form {

    public function qtype() {
        return 'vdmarker';
    }
    
    /**
     * Add question-type specific form fields.
     *
     * @param object $mform the form being built.
     */
    protected function definition_inner($mform) {
        
        $vd = new qtype_vdmarker_vd3("correct_answer_vd");
        $vd->readonly = false;
        
        //TODO: get from db or refault to 0
        $vd->set_state(0);
        $vd->fieldtoupdate = 'vdcorrectanswer';
        $hiddenfield = array('type'  => 'hidden',
                             'name'  => $vd->fieldtoupdate,
                             'id'  => str_replace(':', '_', $vd->fieldtoupdate),
                             'value' => $vd->get_state());
        $mform->addElement('static', 'diagram', get_string('correct_answer', 'qtype_vdmarker'), $vd->render());
        unset($vd);
        
        $penalties = array(
            1.000,
            0.125
        );
        $penaltyoptions = array();
        foreach ($penalties as $penalty) {
            $penaltyoptions["$penalty"] = (100 * $penalty) . '%';
        }
        $fldname = 'penaltyperwrongarea';
        $mform->addElement('select', $fldname,
                            get_string('penalty_per_wrong_area', 'qtype_vdmarker'), 
                            $penaltyoptions);
        $mform->setDefault($fldname, 0.125);

        
        $this->add_combined_feedback_fields(true);

        $this->add_interactive_settings(true, true);
   }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        return $errors;
    }

}