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
 * Venn diagram marker question type renderer.
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
 * Generates the output for drag-and-drop markers questions.
 *
 * @copyright  2010 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_vdmarker_renderer extends qtype_with_combined_feedback_renderer {

    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {
        $question = $qa->get_question();
//        $response = $qa->get_last_qt_data();

        $questiontext = $question->format_questiontext($qa);

        $output = html_writer::tag('div', $questiontext, array('class' => 'qtext'));
        
        $vd = new qtype_vdmarker_vd3( str_replace(':', '_', $qa->get_qt_field_name('vdqa')) );
        //TODO: get the state from last attempt
        
        $vd->fieldtoupdate = $qa->get_qt_field_name( $question->field() );
        
        $hiddenfield = array('type'  => 'hidden',
                             'name'  => $vd->fieldtoupdate,
                             'id'  => str_replace(':', '_', $vd->fieldtoupdate),
                             'value' => $vd->get_state());
        $output .= html_writer::empty_tag('input', $hiddenfield);
        $output .= $vd->render($options);
        unset($vd);
        
        return $output;
    }
}
