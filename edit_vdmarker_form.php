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

require_once($CFG->dirroot . '/question/type/vdmarker/edit_vdmarker_form_base.php');

/**
 * Venn diagram marking question definition editing form.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_vdmarker_edit_form extends qtype_vdmarker_edit_form_base {

    public function qtype() {
        return 'vdmarker';
    }
    
    /**
     * Adds the combobox to select penalty for each incorrectly selected area.
     * By default 12.5% (1/8) is selected 
     * 
     * @param MoodleQuickForm $mform
     */
    protected function add_penalty_fields($mform) {
        $penalties = array(
            1.000,
            0.125
        );
        $penaltyoptions = array();
        foreach ($penalties as $penalty) {
            $penaltyoptions["$penalty"] = (100 * $penalty) . '%';
        }
        $fldname = 'vd_penalty';
        $mform->addElement('select', $fldname,
                            get_string('penalty_per_wrong_area', 'qtype_vdmarker'), 
                            $penaltyoptions);
        $mform->setDefault($fldname, 0.125);
    }

    protected function definition_inner($mform) {
        $this->add_vd_fields($mform);
        $this->add_penalty_fields($mform);
        
        $this->add_combined_feedback_fields(true);

        $this->add_interactive_settings(true, true);
   }
}