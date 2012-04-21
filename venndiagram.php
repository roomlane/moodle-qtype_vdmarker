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
 * Venn's diagram of 3 circles.
 * U - umivese
 * A, B, C - partially overlaping circles
 * 
 * @package    rs_questiontypes
 * @subpackage vdmarker
 * @author     immor@hot.ee
 * @copyright  &copy; 2012 Rommi Saar
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_vdmarker_vd3 {

    /**
     * Does not chagne the value of {@see $fieldtoupdate}.
     * Does not change it's state when clicked on it.
     *  
     * @var bool 
     */
    public $readonly;

    /**
     * div id that is used by YUI to connect JavaScript and settings to html element.
     * Sometihng like 'div#q'.$qa->get_slot().' div.vd-holder';
     * 
     * @var string 
     */
    public $ID;

    /**
     * (Hidden) form filed to write the state.
     * 
     * @var string 
     */
    public $fieldtoupdate;

    /**
     * {@see $state}
     * {@see state_to_areastate}
     * {@see areastate_to_state}
     * 
     * Area          A|B|C
     * 0 - U/(AvBvC) -|-|-
     * 1 - A/(BvC)   +|-|-
     * 2 - B/(AvC)   -|+|-  
     * 3 - (A&B)/C   +|+|-
     * 4 - C/(AvB)   -|-|+
     * 5 - (A&C)/B   +|-|+
     * 6 - (B&C)/A   -|+|+
     * 7 - A&B&C     +|+|+
     *  
     * @var array of bool
     */
    protected $areastate = array(true, true, true, true, true, true, true, true);

    /**
     * {@see $areastate}
     * {@see state_to_areastate}
     * {@see areastate_to_state}
     * 
     * Bitmask of area
     * pow(2, area1) || pow(2, area2) || .. || pow(2, areaN)
     * 
     * Examples:
     * Empty set = 0
     * U = 0b11111111 = 255
     * A = pow(2, 1) || pow(2, 3) || pow(2, 5) || pow(2, 7) = 0b01010101 = 2 + 8 + 32 + 128 = 170
     *  
     * @var byte
     */
    protected $state = 3; //255;

    public function __construct($ID = '') {
        $this->ID = $ID;
    }

    public function render() {
        global $CFG;

        //TODO: use html_writer::tag maybe?

        $html = '';
        $imagepath = $CFG->httpswwwroot . '/question/type/vdmarker/pix/';
        if ($this->readonly) {
            // output static html, no JavaScript needed

            $overlays = '';
            for ($i = 0; $i < 8; $i++) {
                if ($this->areastate[$i]) {
                    $overlays .= html_writer::empty_tag('img', array('src' => "{$imagepath}3c{$i}.png",
                                'class' => 'vd-overlay-ro'));
                }
            }

            $html .= html_writer::tag('div', html_writer::empty_tag('img', array('src' => $imagepath . '3c.png')) . $overlays, array('class' => 'vd-holder-ro'));
        } else {
            // initially all the overlay layers are visible - this way browser downloads all the images right away
            
            $overlays = '';
            for ($i = 0; $i < 8; $i++) {
                $overlays .= html_writer::empty_tag('img', array('src' => "{$imagepath}3c{$i}.png",
                            'class' => 'vd-overlay',
                            'id' => "ov{$i}"));
            }
            $overlays .= html_writer::empty_tag('img', array('src' => $CFG->httpswwwroot . '/pix/i/loading.gif',
                        'class' => 'vd-overlay',
                        'id' => "loading"));
            //TODO: add a "loading" image until js is attached and hides it. Afeter F5 in browser it can take some time to set up

            $html .= html_writer::tag('div', html_writer::empty_tag('img', array('src' => $imagepath . '3c.png')) . $overlays, array('class' => 'vd-holder',
                        'id' => $this->ID)
            );

            $this->attach_js();
        }

        return $html;
    }

    protected function attach_js() {
        global $PAGE;
        //TODO: get circles from json file in pix direcotry (not there yet)
        $circles = array('radius' => 60,
                         'cnt' => 3,
                         'points' => array(array(80, 80),
                                           array(140, 80),
                                           array(110, 132)
                                           )
                         );

        $params = array('topnode' => $this->ID,
            'state' => $this->state,
            'fieldtoupdate' => $this->fieldtoupdate,
            'circles' => $circles);

        $PAGE->requires->yui_module('moodle-qtype_vdmarker-vd', 'M.qtype_vdmarker.init_vd', array($params));
    }

    public function get_state() {
        return $this->state;
    }

    public function get_areastate() {
        return $this->areastate;
    }

    public function set_state($state) {
        $this->state = $state;
        $this->areastate = $this->state_to_areastate($state);
    }

    public function set_areastate($areastate) {
        $this->state = $this->areastate_to_state($areastate);
        $this->areastate = $areastate;
    }

    /**
     * Converts packed (byte) set to an array of bool where each element is an area on Venn's diagram
     * 
     * @param byte $state {@see $state}
     * @return array of bool {@see $areastate}
     */
    public static function state_to_areastate($state) {
        $a = array();
        $one = 1;
        for ($i = 0; $i < 8; $i++) {
            if ($state & $one) {
                $a[] = true;
            } else {
                $a[] = false;
            }
            $one = $one << 1;
        }
        return $a;
    }

    /**
     * Converts array of bool to bitset (byte)
     * 
     * @param array $areastate of bool {@see $areastate}
     * @return byte {@see $state}
     */
    public static function areastate_to_state($areastate) {
        $s = 0;
        for ($i = 0; $i < 8; $i++) {
            if (true === $areastate[$i]) {
                $s += pow(2, $i);
            }
        }
        return $s;
    }
    
    /**
     * Calculates number incorrect areas
     * 
     * @param byte $correctstate
     * @param byte $state
     * @return int 
     */
    public static function num_incorrect_areas($correctstate, $state) {
        $diffbits = (int)$state ^ (int)$correctstate;
        $cnt = 0;
        $one = 1;
        for ($i = 0; $i < 8; $i++) {
            if ($diffbits & $one) {
                $cnt++;
            }
            $one = $one << 1;
        }
        return $cnt;
    }
    
    const ALLOWED_CHARS = '(∅ABCU∩∪\\Δ\')';

    const CHAR_OPENING_BRACKET = '(';
    const CHAR_EMPTY_SET = '∅';
    const CHAR_SET_A = 'A';
    const CHAR_SET_B = 'B';
    const CHAR_SET_C = 'C';
    const CHAR_UNIVERSE = 'U';
    const CHAR_INTERSECTION = '∩';
    const CHAR_UNIONN = '∪';
    const CHAR_DIFFERENCE = '\\';
    const CHAR_SYMMETRIC_DIFFERENCE = 'Δ';
    const CHAR_COMPLEMENT = "'";
    const CHAR_CLOSING_BRACKET = ')';

    /**
     * Produces array of legal characters after each character in the formula
     * 
     * @return array
     */
    private static function init_legal_characters_after(){
        $legalcarsafterliteral = array(
            qtype_vdmarker_vd3::CHAR_UNIVERSE, 
            qtype_vdmarker_vd3::CHAR_INTERSECTION, 
            qtype_vdmarker_vd3::CHAR_UNIONN, 
            qtype_vdmarker_vd3::CHAR_DIFFERENCE, 
            qtype_vdmarker_vd3::CHAR_SYMMETRIC_DIFFERENCE,
            qtype_vdmarker_vd3::CHAR_COMPLEMENT, 
            qtype_vdmarker_vd3::CHAR_CLOSING_BRACKET,
            '');
        $legalcarsafterbinaryoperator = array(
            qtype_vdmarker_vd3::CHAR_OPENING_BRACKET, 
            qtype_vdmarker_vd3::CHAR_EMPTY_SET, 
            qtype_vdmarker_vd3::CHAR_SET_A, 
            qtype_vdmarker_vd3::CHAR_SET_B, 
            qtype_vdmarker_vd3::CHAR_SET_C,
            qtype_vdmarker_vd3::CHAR_UNIVERSE);
        $legalcarsafterbeginblock = $legalcarsafterbinaryoperator;
        $legalcarsafterendblock = $legalcarsafterliteral;

        return array(
            '' => $legalcarsafterbeginblock,
            qtype_vdmarker_vd3::CHAR_OPENING_BRACKET => $legalcarsafterbeginblock,
            qtype_vdmarker_vd3::CHAR_EMPTY_SET => $legalcarsafterliteral,
            qtype_vdmarker_vd3::CHAR_SET_A => $legalcarsafterliteral,
            qtype_vdmarker_vd3::CHAR_SET_B => $legalcarsafterliteral,
            qtype_vdmarker_vd3::CHAR_SET_C => $legalcarsafterliteral,
            qtype_vdmarker_vd3::CHAR_UNIVERSE => $legalcarsafterliteral,
            qtype_vdmarker_vd3::CHAR_INTERSECTION => $legalcarsafterbinaryoperator,
            qtype_vdmarker_vd3::CHAR_UNIONN => $legalcarsafterbinaryoperator,
            qtype_vdmarker_vd3::CHAR_DIFFERENCE => $legalcarsafterbinaryoperator,
            qtype_vdmarker_vd3::CHAR_SYMMETRIC_DIFFERENCE => $legalcarsafterbinaryoperator,
            qtype_vdmarker_vd3::CHAR_COMPLEMENT => $legalcarsafterliteral,
            qtype_vdmarker_vd3::CHAR_CLOSING_BRACKET => $legalcarsafterendblock
        );
    }
    
    /**
     *
     * @param type $formula
     * @return string error message or null 
     */
    public static function formula_syntax_check($formula) {
        $legalcarsafter = qtype_vdmarker_vd3::init_legal_characters_after();
        
        //TODO: use get_string in error messages
        if ('' == trim($formula)) {
            return 'Empty formula';
        } else {
            $lastchar = '';
            $backetbalance = 0;
            for($i = 0; $i < mb_strlen($formula, 'UTF-8'); $i++) {
                $char = mb_substr($formula, $i, 1, 'UTF-8');
                if (($char !== '')&&(mb_strpos(qtype_vdmarker_vd3::ALLOWED_CHARS, $char, 0, 'UTF-8') === false)) {
                    return 'Unexcpected character in formula: ' . $char;
                }
                if (qtype_vdmarker_vd3::CHAR_OPENING_BRACKET == $char) {
                    $backetbalance++;
                } else if (qtype_vdmarker_vd3::CHAR_CLOSING_BRACKET == $char) {
                    $backetbalance--;
                }
                $allowednext = $legalcarsafter[$lastchar];
                if (!in_array($char, $allowednext, true)) {
                    return 'Unexpected character "' . $char . '" after "' . mb_substr($formula, 0, $i, 'UTF-8') . 
                            '. Excpected: ' . implode(', ', $allowednext);
                }
            
                if ($backetbalance < 0) {
                    return 'Invalid placement of brackets';
                }
                $lastchar = $char;
            }

            $allowednext = $legalcarsafter[$lastchar];
            if (!in_array('', $allowednext, true)) {
                return 'Unexpected end of formula "' . $formula . '". Expected: ' . implode(', ', $allowednext);
            }
            
            if (0 != $backetbalance) {
                return 'Bracket count mismatch';
            }
        }
        return null;
    }
    
    /**
     * Calculates value of subformula. No syntax checks.
     * 
     * @param string $subformula must have passed the syntax check
     * @return byte state 
     */
    private static function sub_formula_to_state($subformula) {
        $state = 0;
        
        return $state;
    }
    
    /**
     * Calculates Venn's diagram state from the given formula
     * 
     * @param string $formula
     * @return null if invalid syntax, otherwize byte
     */
    public static function formula_to_state($formula) {
        if (isset(qtype_vdmarker_vd3::formula_syntax_check($formula))) {
            return null;
        }
        
        return qtype_vdmarker_vd3::sub_formula_to_state($formula);
    }
 }