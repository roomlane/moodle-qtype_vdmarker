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
    protected $state = 255;
    
    public function __construct($ID = '') {
        $this->ID = $ID;
    }
    
    public function render(question_display_options $options = null) {
        global $CFG;
        
        //TODO: use html_writer::tag maybe?
        
        $html = '';
        $imagepath = $CFG->httpswwwroot .'/question/type/vdmarker/pix/';
        if ($this->readonly || $options->readonly) {
            // output static html, no JavaScript needed
            
            $overlays = '';
            for ($i = 0; $i < 8; $i++) {
                if ($this->areastate[$i]) {
                    $overlays .= html_writer::empty_tag('img', array('src'   => "{$imagepath}3c{$i}.png",
                                                                     'class' => 'vd-overlay-ro')); 
                }
            }
            
            $html .= html_writer::tag('div',
                                      html_writer::empty_tag('img', array('src' => $imagepath . '3c.png')) . $overlays, 
                                      array('class' => 'vd-holder-ro'));
        } else {
            $html .= '<div class="vd-holder" id=' . $this->ID . '>';
            
            $overlays = '';
            for ($i = 0; $i < 8; $i++) {
                    $overlays .= html_writer::empty_tag('img', array('src'   => "{$imagepath}3c{$i}.png",
                                                                     'class' => 'vd-overlay',
                                                                     'id'    => "ov{$i}")); 
            }
            
            $html .= html_writer::tag('div',
                                      html_writer::empty_tag('img', array('src' => $imagepath . '3c.png')) . $overlays, 
                                      array('class' => 'vd-holder',
                                            'id'    => $this->ID)
                                      );
            
            $this->attach_js();
        }
        
        return $html;
    }
    
    protected function attach_js() {
        global $PAGE;
        //TODO: get circles from json file in pix direcotry (not there yet)
        $circles = array('radius' => 60,
                         'c1' => array(80, 80),
                         'c2' => array(140, 80),
                         'c3' => array(110, 132));
        
        $params = array('topnode'       => $this->ID,
                        'state'         => $this->state,
                        'fieldtoupdate' => $this->fieldtoupdate,
                        'circles'       => $circles);
        
        $PAGE->requires->yui_module('moodle-qtype_vdmarker-vd',
                                    'M.qtype_vdmarker.init_vd',
                                    array($params) );
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
            if (true == $areastate[$i]) {
                $s += pow(2, $i);
            }
        }
        return $s;
    }
 
}