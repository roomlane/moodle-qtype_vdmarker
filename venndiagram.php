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
    public $areastate;

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
    public $state;
    
    public function __construct($ID) {
        $this->ID = $ID;
    }
    
    /**
     * Converts packed (byte) set to an array of bool where each element is an area on Venn's diagram
     * 
     * @param byte $state {@see $state}
     * @return array of bool {@see $areastate}
     */
    public static function state_to_areastate($state) {
        $a = array();
        for ($i = 0; $i < 8; $i++) {
            if ( 0 == ($state % pow(2, $i)) ) {
                $a[] = true;
            } else {
                $a[] = false;
            }
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