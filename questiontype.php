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
 * The Venn diagram marker question type class.
 *
 * @package    rs_questiontypes
 * @subpackage vdmarker
 * @author     immor@hot.ee
 * @copyright  &copy; 2012 Rommi Saar
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_vdmarker extends question_type {

    public function save_question_options($formdata) {
//        global $DB, $USER;
//        $context = $formdata->context;
//
//        $options = $DB->get_record('qtype_ddmarker', array('questionid' => $formdata->id));
//        if (!$options) {
//            $options = new stdClass();
//            $options->questionid = $formdata->id;
//            $options->correctfeedback = '';
//            $options->partiallycorrectfeedback = '';
//            $options->incorrectfeedback = '';
//            $options->id = $DB->insert_record('qtype_ddmarker', $options);
//        }
//
//        $options->shuffleanswers = !empty($formdata->shuffleanswers);
//        $options->showmisplaced = !empty($formdata->showmisplaced);
//        $options = $this->save_combined_feedback_helper($options, $formdata, $context, true);
//        $this->save_hints($formdata, true);
//        $DB->update_record('qtype_ddmarker', $options);
//        $DB->delete_records('qtype_ddmarker_drops', array('questionid' => $formdata->id));
//        foreach (array_keys($formdata->drops) as $dropno) {
//            if ($formdata->drops[$dropno]['choice'] == 0) {
//                continue;
//            }
//            $drop = new stdClass();
//            $drop->questionid = $formdata->id;
//            $drop->no = $dropno + 1;
//            $drop->shape = $formdata->drops[$dropno]['shape'];
//            $drop->coords = $formdata->drops[$dropno]['coords'];
//            $drop->choice = $formdata->drops[$dropno]['choice'];
//
//            $DB->insert_record('qtype_ddmarker_drops', $drop);
//        }
//
//        //an array of drag no -> drag id
//        $olddragids = $DB->get_records_menu('qtype_ddmarker_drags',
//                                    array('questionid' => $formdata->id),
//                                    '', 'no, id');
//        foreach (array_keys($formdata->drags) as $dragno) {
//            if (!empty($formdata->drags[$dragno]['label'])) {
//                $drag = new stdClass();
//                $drag->questionid = $formdata->id;
//                $drag->no = $dragno + 1;
//                $drag->infinite = empty($formdata->drags[$dragno]['infinite'])? 0 : 1;
//                $drag->label = $formdata->drags[$dragno]['label'];
//
//                if (isset($olddragids[$dragno +1])) {
//                    $drag->id = $olddragids[$dragno +1];
//                    unset($olddragids[$dragno +1]);
//                    $DB->update_record('qtype_ddmarker_drags', $drag);
//                } else {
//                    $drag->id = $DB->insert_record('qtype_ddmarker_drags', $drag);
//                }
//
//            }
//
//        }
//        if (!empty($olddragids)) {
//            list($sql, $params) = $DB->get_in_or_equal(array_values($olddragids));
//            $DB->delete_records_select('qtype_ddmarker_drags', "id $sql", $params);
//        }
//
//        self::constrain_image_size_in_draft_area($formdata->bgimage,
//                                                    QTYPE_DDMARKER_BGIMAGE_MAXWIDTH,
//                                                    QTYPE_DDMARKER_BGIMAGE_MAXHEIGHT);
//        file_save_draft_area_files($formdata->bgimage, $formdata->context->id,
//                                    'qtype_ddmarker', 'bgimage', $formdata->id,
//                                    array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 1));
    }

}
