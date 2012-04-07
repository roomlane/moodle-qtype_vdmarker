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

/**
 * Generates the output for drag-and-drop markers questions.
 *
 * @copyright  2010 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_vdmarker_renderer extends qtype_with_combined_feedback_renderer {

    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {
        $vd = new qtype_vdmarker_vd3( 'vdqa' . $qa->get_slot() );
        
        //!
        
        unset($vd);
        
//        global $PAGE, $OUTPUT;
//
//        $question = $qa->get_question();
//        $response = $qa->get_last_qt_data();
//
//        $questiontext = $question->format_questiontext($qa);
//
//        $output = html_writer::tag('div', $questiontext, array('class' => 'qtext'));
//
//        $bgimage = self::get_url_for_image($qa, 'bgimage');
//
//        $img = html_writer::empty_tag('img',
//                                        array('src'=>$bgimage,
//                                        'class'=>'dropbackground',
//                                        'alt' => get_string('dropbackground', 'qtype_ddmarker')));
//        $PAGE->requires->yui_module('moodle-qtype_ddmarker-dd',
//                                        'M.qtype_ddmarker.init_question',
//                                        array($params));
//        $output .= html_writer::tag('div', $hiddenfields, array('class'=>'ddform'));
//        return $output;
        
        
//        $output .= html_writer::tag('div',
//                $droparea . $dragitems . $dropzones . $hiddens, array('class'=>'ddarea'));
//        $topnode = 'div#q'.$qa->get_slot().' div.ddarea';
//        $params = array('drops' => $question->places,
//                        'topnode' => $topnode,
//                        'readonly' => $options->readonly);
        
    }
}
