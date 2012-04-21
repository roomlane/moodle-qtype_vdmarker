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
abstract class qtype_vdmarker_edit_form_base extends question_edit_form {
   /**
     * Adds the fields for showing the Venn's diagram where teacher can define the areas of correct answer.
     * 
     * @param MoodleQuickForm $mform 
     */
    protected function add_vd_fields($mform) {
        $vd = new qtype_vdmarker_vd3("correct_answer_vd");
        $vd->readonly = false;
        if (isset($this->question->options)) {
            $state = $this->question->options->vd_correctanswer;
        } else {
            $state = 0;
        }
        $vd->set_state($state);
        $vd->fieldtoupdate = 'vd_correctanswer';
        $mform->addElement('hidden', $vd->fieldtoupdate, $vd->get_state(), 'id="' . str_replace(':', '_', $vd->fieldtoupdate) . '"');
        $mform->addElement('static', 'diagram', get_string('correct_answer', 'qtype_vdmarker'), $vd->render());
        unset($vd);
    }
    
    protected function data_preprocessing($question) {
        
        // set the default text for question
        if ('' == $question->questiontext['text']) {
            $question->questiontext['text'] = get_string('default_question_text', 'qtype_' . $this->qtype());
        }
        return $question;
    }
  
}