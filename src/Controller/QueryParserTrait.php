<?php

namespace App\Controller;

trait QueryParserTrait
{

    /**
     * Raw query input to check if there is right quotas
     *
     * @param $input string
     * @return bool|string
     */
    public function CheckQuotation($input) {

        // check for double quote
        $doubleQ = preg_split('/\"/', $input);
        if (count($doubleQ) == 1) {
            if ((strlen($input)) == (strlen($doubleQ[0]))) {
                $doubleQ = false;
            }
        }
        else {
            if ((count($doubleQ) % 2) == 1) {
                $doubleQ = true;
            }
            else {
                return 'Wrong count of quotations';
            }
        }

        $singleQ = preg_split('/\'/', $input);
        if (count($singleQ) == 1) {
            if ((strlen($input)) == (strlen($singleQ[0]))) {
                $singleQ = false;
            }
        }
        else {
            if ((count($singleQ) % 2) == 1) {
                $doubleQ = true;
            }
            else {
                return 'Wrong count of quotations';
            }
        }

        if ($singleQ && $doubleQ) {
            return 'Use one way of notation';
        }
        else {
            if ($singleQ || $doubleQ) {
                return false;
            }
        }

        return false;
   }

    // check of same count of (, )
    public function checkParenthesis($input) {
        $left = preg_split('/\(/', $input);
        $right = preg_split('/\)/', $input);
        if (count($right) == count($left)) {
            return false;
        }

        return 'Wrong number of paranthesis';
    }

    /**
     * Check if query variable is type of string
     *
     * @param $value string
     * @return bool
     */
    function isText($value) {
        $matches = null;
        preg_match('/^(["\'])(.*?)(\1)$/', $value, $matches);
        if (count($matches) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Check if query variable is type of number
     *
     * @param $value string
     * @return bool
     */
    function isNumber($value) {
        $matches = null;
        preg_match('/^[0-9]+$/', $value, $matches);

        if (count($matches) > 0) {
            return true;
        }

        return false;
    }

    function changeSelectString($matches) {
        if (count($matches) > 0) {
            return preg_replace('/\s/', '§', $matches[0]);
        }

        return null;
    }

    function rechangeSelectString($matches) {
        if (count($matches) > 0) {
            return preg_replace('/§/', ' ', $matches[0]);
            //return $matches[0] . $newString . $matches[2];
        }

        return null;
    }

    public function connectTexts($text) {
        return preg_replace_callback('/(["\'])(.*?)(\1)/',
            function ($m) {return $this->changeSelectString($m);}, $text);
    }

    public function disconnectText($text) {
            return preg_replace_callback('/(["\'])(.*?)(\1)/',
            function ($m) {return $this->rechangeSelectString($m);}, $text);
    }

    public function checkStartBracket($value) {
        $brackets = [];
        preg_match_all('/\(/', $value, $brackets);

        return count($brackets[0]);
    }

    public function checkEndBracket($value) {
        $brackets = [];
        preg_match_all('/\)/', $value, $brackets);

        return count($brackets[0]);
    }

    //private $connectors = ['AND', 'OR'];
    private $fields = [
        'nazov_suboru' => [
            'original_field' => 'name',
            'type' => 'Text'
        ],
        'sirka' => [
            'original_field' => 'width',
            'type' => 'Number'
        ],
        'vyska' => [
            'original_field' => 'height',
            'type' => 'Number'
        ]
    ];

    private $operators = [
        'nazov_suboru' => ['CONTAINS', '='],
        'sirka' => ['<', '='],
        'vyska' => ['<', '=', '>']
    ];
    public function invalidField($field, $fields) {
        if (!isset($fields[$field])) {
            return 'Unknown field "' . $field . '"';
        }

        return false;
    }

    public function invalidOperator($field, $fields, $operator) {
        if ((!isset($fields[$field])) || (in_array($operator, $fields[$field]))) {
            return 'Unknown operator: "' . $operator . '" for field: "' . $field . '"';
        }

        return false;
    }

    public function invalidValue($fields, $field, $value) {
        if (strcmp('Text', $fields[$field]['type']) == 0) {
            if (!$this->isText($value)) {
                return 'Wrong type of value for field: "' . $field . '"';
            }
        }
        else {
            if (!$this->isNumber($value)) {
                return 'Wrong type of value for field: "' . $field . '"';
            }
        }

        return false;
    }


    /**
     * Check if raw query is ok
     *
     * @param $query string
     * @return array
     */
    public function parser($query) {
        $result = [
            'error' => false,
            'query' => ''
        ];
        $parenthesis = $this->checkParenthesis($query);
        if ($parenthesis) {
            $result['error'] = $parenthesis;
            return $result;
        }

        $quoats = $this->CheckQuotation($query);
        if ($quoats) {
            $result['error'] = $quoats;
            return $result;
        }
        $query = $this->connectTexts($query);
        $phase = 0;
        $allParts = preg_split('/\s/', $query);
        $resolve = '';
        $partsCount = count($allParts);
        // check if there is at least 3 words
        if ($partsCount < 3) {
            $result['error'] = 'Wrong query, please write more query parameters';
            return $result;
        }

        for ($p = 0; $p < $partsCount; $p++) {
            switch ($phase) {
                case 0: {
                    //check if there is AND, OR
                    if ((strcmp($allParts[$p], 'AND') == 0) || (strcmp($allParts[$p], 'OR') ==0)) {
                        $resolve.= ' ' . $allParts[$p];
                        $phase--;
                    }
                    else {
                        $field = $allParts[$p];
                        $brackets = $this->checkStartBracket($allParts[$p]);
                        $resolvePart = '';
                        if ($brackets > 0) {
                            $field = substr($allParts[$p], $brackets, strlen($allParts[$p])  - $brackets);
                            $resolvePart = substr($allParts[$p],0,$brackets);
                            $allParts[$p] = $field;
                        }

                        $fieldCheck = $this->invalidField($field, $this->fields);
                        if ($fieldCheck) {
                            $result['error'] = $fieldCheck;
                            return $result;
                        }
                        $resolve.= ' ' . $resolvePart . $this->fields[$field]['original_field'];

                    }
                    break;
                }
                case 1: {
                    // check operand
                    $operatorCheck = $this->invalidOperator($allParts[$p -1 ], $this->fields, $this->operators);
                    if ($operatorCheck) {
                        $result['error'] = 'Operator: "' . $allParts[$p] . '" is not supported';
                        return $result;
                    }

                    // check if operator is contains
                    break;
                }
                case 2: {
                    $value = $allParts[$p];
                    $brackets = $this->checkEndBracket($allParts[$p]);
                    $resolvePart = '';
                    if ($brackets > 0) {
                        $value = substr($allParts[$p], 0, strlen($allParts[$p]) - $brackets);
                        $resolvePart = substr($allParts[$p],strlen($allParts[$p]) - 1, $brackets);
                    }
                    $valueCheck = $this->invalidValue($this->fields, $allParts[$p - 2], $value);
                    if ($valueCheck) {
                        $result['error'] = 'Wrong value: "' . $allParts[$p] . '"';
                        return $result;
                    }

                    if ($this->isText($allParts[$p])) {
                        $value = (strcmp('=', $allParts[$p - 1]) == 0) ?
                            'LIKE ' . $value :
                            'LIKE ' . '"%' . substr($value, 1, strlen($value) -2 ) . '%"';
                    }
                    else {
                        $value = $allParts[$p - 1] . ' ' . $value;
                    }
                    $resolve.= ' ' . $value . $resolvePart;

                }
            }
            $phase = ++$phase % 3;
        }


        $resolve = $this->disconnectText($resolve);
        $result['query'] = $resolve;

        return $result;
    }
}