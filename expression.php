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
 * Expression for Venn diagram of 3 circles.
 * 
 * @package    rs_questiontypes
 * @subpackage vdmarker
 * @author     immor@hot.ee
 * @copyright  &copy; 2012 Rommi Saar
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_vdmarker_vd3_expression {
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
     * All the possible characters that can appear after a character in the expression
     * 
     * @var array char => array(char1, char2, ..) 
     */
    private $legalcarsafter;
    
    /**
     * Max allowed expression length
     * if less than 1 then no limit set
     * 
     * @var int 
     */
    private $maxlen;
    
    private $allowedchars = self::ALLOWED_CHARS;
    
    /**
     * @param int $maxlen max. allowed expression length
     * @param string $allowedchars allowed characters (characters not in ALLOWED_CHARS are ignored)
     */
    public function __construct($maxlen = 0, $allowedchars = '') {
        $this->set_max_len($maxlen);
        $this->set_allowed_chars($allowedchars);
    }
    
    /**
     * places space and comma between each character in the string
     * 
     * @param string $chars 
     */
    public static function get_chars_formatted($chars) {
        if ($chars === '') {
            return '';
        }
        $chararray = array();
        $len = mb_strlen($chars, 'UTF-8');
        for ($i = 0; $i < $len; $i++) {
            $chararray[] = mb_substr($chars, $i, 1, 'UTF-8');
        }
        return implode(', ', $chararray);
    }
    
    /**
     * Set the characters that are allowed in expression
     * Ignores characters that are not in ALLOWED_CHARS
     * 
     * @param string $allowedchars subset of ALLOWED_CHARS
     */
    public function set_allowed_chars($allowedchars) {
        if ($allowedchars !== $this->allowedchars) {
            $chars = '';
            $len = mb_strlen($allowedchars, 'UTF-8');
            for ($i = 0; $i < $len; $i++) {
                $char = mb_substr($allowedchars, $i, 1, 'UTF-8');
                if (mb_strpos(self::ALLOWED_CHARS, $char, 0, 'UTF-8') !== false) {
                    $chars .= $char;
                }
            }
            if ($chars <> '') {
                $this->allowedchars = $chars;
            } else {
                $this->allowedchars = self::ALLOWED_CHARS;
            }
            $this->legalcarsafter = null;
        }
    }
    
    public function get_allowed_chars() {
        return $this->allowedchars;
    }
    
    public function get_allowed_chars_formatted() {
        return self::get_chars_formatted($this->allowedchars);
    }
    
    public function set_max_len($maxlen) {
        $this->maxlen = $maxlen;
        if ($this->maxlen < 1) {
            $this->maxlen = null;
        }
    }
    
    public function get_max_len() {
        return $this->maxlen;
    }

    /**
     * Produces array of legal characters after each character in the expression
     */
    private function init_legal_characters_after(){
        if (isset($this->legalcarsafter)) {
            return;
        }
        
        $legalcarsafterliteral_initial = array(
            self::CHAR_UNIVERSE, 
            self::CHAR_INTERSECTION, 
            self::CHAR_UNIONN, 
            self::CHAR_DIFFERENCE, 
            self::CHAR_SYMMETRIC_DIFFERENCE,
            self::CHAR_COMPLEMENT, 
            self::CHAR_CLOSING_BRACKET,
            '');
        foreach($legalcarsafterliteral_initial as $char) {
            if( ('' == $char) || (mb_strpos($this->allowedchars, $char, 0, 'UTF-8') !== false) ) {
                $legalcarsafterliteral[] = $char;
            }
        }
        $legalcarsafterbinaryoperator_initial = array(
            self::CHAR_OPENING_BRACKET, 
            self::CHAR_EMPTY_SET, 
            self::CHAR_SET_A, 
            self::CHAR_SET_B, 
            self::CHAR_SET_C,
            self::CHAR_UNIVERSE);
        foreach($legalcarsafterbinaryoperator_initial as $char) {
            if( ('' == $char) || (mb_strpos($this->allowedchars, $char, 0, 'UTF-8') !== false) ) {
                $legalcarsafterbinaryoperator[] = $char;
            }
        }

        $legalcarsafterbeginblock = $legalcarsafterbinaryoperator;
        $legalcarsafterendblock = $legalcarsafterliteral;

        $this->legalcarsafter = array(
            '' => $legalcarsafterbeginblock,
            self::CHAR_OPENING_BRACKET => $legalcarsafterbeginblock,
            self::CHAR_EMPTY_SET => $legalcarsafterliteral,
            self::CHAR_SET_A => $legalcarsafterliteral,
            self::CHAR_SET_B => $legalcarsafterliteral,
            self::CHAR_SET_C => $legalcarsafterliteral,
            self::CHAR_UNIVERSE => $legalcarsafterliteral,
            self::CHAR_INTERSECTION => $legalcarsafterbinaryoperator,
            self::CHAR_UNIONN => $legalcarsafterbinaryoperator,
            self::CHAR_DIFFERENCE => $legalcarsafterbinaryoperator,
            self::CHAR_SYMMETRIC_DIFFERENCE => $legalcarsafterbinaryoperator,
            self::CHAR_COMPLEMENT => $legalcarsafterliteral,
            self::CHAR_CLOSING_BRACKET => $legalcarsafterendblock
        );
    }
    
    /**
     * Checks if the expression in syntactically correct
     * 
     * @param string $expression
     * @return string error message or null 
     */
    public function syntax_check($expression) {
        $this->init_legal_characters_after();
        
        //TODO: use get_string in error messages
        if ('' == trim($expression)) {
            return 'Empty expression';
        } else {
            $len = mb_strlen($expression, 'UTF-8');
            if (($this->maxlen >= 1) && ((int)$len > (int)$this->maxlen)) {
                return 'Expression exceeds max. allowed length';
            }
            $lastchar = '';
            $backetbalance = 0;
            for($i = 0; $i < $len; $i++) {
                $char = mb_substr($expression, $i, 1, 'UTF-8');
                
                // ignore spaces
                if ($char === ' ') {
                    continue;
                }
                
                $allowednext = $this->legalcarsafter[$lastchar];
                if (($char !== '')&&(mb_strpos($this->allowedchars, $char, 0, 'UTF-8') === false)) {
                    return 'Unexcpected character "' . $char . '" after "' . mb_substr($expression, 0, $i, 'UTF-8') . 
                            '". Excpected: ' . implode(', ', $allowednext);
                }
                if (self::CHAR_OPENING_BRACKET == $char) {
                    $backetbalance++;
                } else if (self::CHAR_CLOSING_BRACKET == $char) {
                    $backetbalance--;
                }
                if (!in_array($char, $allowednext, true)) {
                    return 'Unexpected character "' . $char . '" after "' . mb_substr($expression, 0, $i, 'UTF-8') . 
                            '". Excpected: ' . implode(', ', $allowednext);
                }
            
                if ($backetbalance < 0) {
                    return 'Invalid placement of brackets';
                }
                $lastchar = $char;
            }

            $allowednext = $this->legalcarsafter[$lastchar];
            if (!in_array('', $allowednext, true)) {
                return 'Unexpected end of expression "' . $expression . '". Expected: ' . implode(', ', $allowednext);
            }
            
            if (0 != $backetbalance) {
                return 'Bracket count mismatch';
            }
        }
        return null;
    }
    
    /**
     * Lower priority operators have to be evaluated befor higher ones
     * 
     * @var array operator => priority 
     */
    private $operators;
    
    private $literals;
    
    private function init_operators() {
        if (isset($this->operators)) {
            return;
        }
        $this->operators = array(
            self::CHAR_COMPLEMENT => 1,
            self::CHAR_INTERSECTION => 2,
            self::CHAR_UNIONN => 3,
            self::CHAR_DIFFERENCE => 3,
            self::CHAR_SYMMETRIC_DIFFERENCE => 3
        );
    }
    
    private function init_literals() {
        if (isset($this->literals)) {
            return;
        }
        $this->literals = array(
            self::CHAR_EMPTY_SET   => 0,
            self::CHAR_SET_A       => 2 + 8 + 32 + 128,
            self::CHAR_SET_B       => 4 + 8 + 64 + 128,
            self::CHAR_SET_C       => 16 + 32 + 64 + 128,
            self::CHAR_UNIVERSE    => 1 + 2 + 4 + 8 + 16 + 32 + 64 + 128
        );
    }
    
    /**
     * Helper function for sub_expression_to_state
     * 
     * @param string $subexpression
     * @param int $startpos
     * @param int $level
     * @return array ($operatorpos, $removebrackets, $level) 
     */
    private function find_higher_level_operator_left($subexpression, $startpos, $level) {
        $removebrackets = false;
        $bracketbalance = 0;
        for($i = $startpos; $i >= 0; $i--) {
            $char = mb_substr($subexpression, $i, 1, 'UTF-8');

            // ignore spaces
            if ($char === ' ') {
                continue;
            }
            
            if ($bracketbalance !== 0) {
                if ($char === self::CHAR_CLOSING_BRACKET) {
                    $bracketbalance++;
                } else if ($char === self::CHAR_OPENING_BRACKET) {
                    $bracketbalance--;
                }
            } else if ($char === self::CHAR_CLOSING_BRACKET) {
                $bracketbalance++;
                if ($i == $startpos) {
                    $removebrackets = true;
                }
            } else if (array_key_exists($char, $this->operators)) {
                $templevel = $this->operators[$char];
                if ($templevel > $level) {
                    return array($i, $removebrackets, $templevel);
                } else {
                    $removebrackets = false;
                }
            }
        }
        return array(-1, $removebrackets, $level);
   }
    
    /**
     * Calculates value of subexpression. No syntax checks.
     * 
     * @param string $subexpression must have passed the syntax check
     * @return byte state 
     */
    private function sub_expression_to_state($subexpression) {
        $subexpression = trim($subexpression, ' ');
        if (array_key_exists($subexpression, $this->literals)) {
            return $this->literals[$subexpression];
        } else {
            //! find rightmost parameter, operand and left parameter
            
            $length = mb_strlen($subexpression, 'UTF-8');
            $startpos = $length - 1;
            $operatorlevel = 0;
            
            $leftpatameter = null;
            $rightparameter = null;
            $operator = null;
            
            $removebrackets = false;
            $operatorpos = 0;
            
            $first = true;
            $removebracketsrightparam = false;
            
            while (!isset($leftpatameter)) {
                list($operatorpos, $removebrackets, $operatorlevel) = $this->find_higher_level_operator_left($subexpression, $startpos, $operatorlevel);
                if ($operatorpos == -1) {
                    if ($removebrackets === true) {
                        $leftpatameter = mb_substr($subexpression, 1, $startpos - 1, 'UTF-8');
                    } else {
                        $leftpatameter = mb_substr($subexpression, 0, $startpos + 1, 'UTF-8');
                    }
                    
                    $operator = mb_substr($subexpression, $startpos + 1, 1, 'UTF-8');

                    if ($removebracketsrightparam === true) {
                        $rightparameter = mb_substr($subexpression, $startpos + 3, $length - $startpos - 4, 'UTF-8');
                    } else {
                        $rightparameter = mb_substr($subexpression, $startpos + 2, $length - $startpos - 2, 'UTF-8');
                    }
                } else {
                    $startpos = $operatorpos - 1;
                    $removebracketsrightparam = $first && $removebrackets;
                    $first = false;
                }
            }
            
            if (!isset($operator) || ($operator == '')) {
                // only brackets removed
                return $this->sub_expression_to_state($leftpatameter);
            } else if (self::CHAR_COMPLEMENT === $operator) {
                $leftvalue = $this->sub_expression_to_state($leftpatameter);
                return 255 ^ $leftvalue;
            } else if (self::CHAR_INTERSECTION === $operator) {
                $leftvalue = $this->sub_expression_to_state($leftpatameter);
                $righvalue = $this->sub_expression_to_state($rightparameter);
                return $leftvalue & $righvalue;
            } else if (self::CHAR_UNIONN === $operator) {
                $leftvalue = $this->sub_expression_to_state($leftpatameter);
                $righvalue = $this->sub_expression_to_state($rightparameter);
                return $leftvalue | $righvalue;
            } else if (self::CHAR_DIFFERENCE === $operator) {
                $leftvalue = $this->sub_expression_to_state($leftpatameter);
                $righvalue = $this->sub_expression_to_state($rightparameter);
                return $leftvalue ^ ($leftvalue & $righvalue);
            } else if (self::CHAR_SYMMETRIC_DIFFERENCE === $operator) {
                $leftvalue = $this->sub_expression_to_state($leftpatameter);
                $righvalue = $this->sub_expression_to_state($rightparameter);
                return ($leftvalue | $righvalue) ^ ($leftvalue & $righvalue);
            }
        }
    }
    
    /**
     * Calculates Venn diagram state from the given expression
     * 
     * @param string $expression
     * @return null if invalid syntax, otherwize byte
     */
    public function expression_to_state($expression) {
        $error = $this->syntax_check($expression);
        if (isset($error)) {
            return null;
        }
        
        $this->init_operators();
        $this->init_literals();
        return $this->sub_expression_to_state($expression);
    }
 }