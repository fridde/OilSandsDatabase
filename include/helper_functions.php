<?php

function echop($array) {
    foreach ($array as $key => $element) {
        if (gettype($element) == "string") {
            echo $key . " => " . $element . "<br>";
        } else {
            echo $key . " => ";
            echo print_r($element) . "<br>";
        }
    }
    echo "<br>";

}

function power_perms($arr) {

    $power_set = power_set($arr);
    $result = array();
    foreach ($power_set as $set) {
        $perms = perms($set);
        $result = array_merge($result, $perms);
    }
    return $result;
}

function power_set($in, $minLength = 1) {

    $count = count($in);
    $members = pow(2, $count);
    $return = array();
    for ($i = 0; $i < $members; $i++) {
        $b = sprintf("%0" . $count . "b", $i);
        $out = array();
        for ($j = 0; $j < $count; $j++) {
            if ($b{$j} == '1')
                $out[] = $in[$j];
        }
        if (count($out) >= $minLength) {
            $return[] = $out;
        }
    }

    //usort($return,"cmp");  //can sort here by length
    return $return;
}

function factorial($int) {
    if ($int < 2) {
        return 1;
    }

    for ($f = 2; $int - 1 > 1; $f *= $int--);

    return $f;
}

function perm($arr, $nth = null) {

    if ($nth === null) {
        return perms($arr);
    }

    $result = array();
    $length = count($arr);

    while ($length--) {
        $f = factorial($length);
        $p = floor($nth / $f);
        $result[] = $arr[$p];
        array_delete_by_key($arr, $p);
        $nth -= $p * $f;
    }

    $result = array_merge($result, $arr);
    return $result;
}

function perms($arr) {
    $p = array();
    for ($i = 0; $i < factorial(count($arr)); $i++) {
        $p[] = perm($arr, $i);
    }
    return $p;
}

function array_delete_by_key(&$array, $delete_key, $use_old_keys = FALSE) {

    unset($array[$delete_key]);

    if (!$use_old_keys) {
        $array = array_values($array);
    }

    return TRUE;
}

function make_comparer() {
    // Normalize criteria up front so that the comparer finds everything tidy
    $criteria = func_get_args();
    foreach ($criteria as $index => $criterion) {
        $criteria[$index] = is_array($criterion) ? array_pad($criterion, 3, null) : array($criterion, SORT_ASC, null);
    }

    return function($first, $second) use (&$criteria) {
        foreach ($criteria as $criterion) {
            // How will we compare this round?
            list($column, $sortOrder, $projection) = $criterion;
            $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

            // If a projection was defined project the values now
            if ($projection) {
                $lhs = call_user_func($projection, $first[$column]);
                $rhs = call_user_func($projection, $second[$column]);
            } else {
                $lhs = $first[$column];
                $rhs = $second[$column];
            }

            // Do the actual comparison; do not return if equal
            if ($lhs < $rhs) {
                return -1 * $sortOrder;
            } else if ($lhs > $rhs) {
                return 1 * $sortOrder;
            }
        }

        return 0;
        // tiebreakers exhausted, so $first == $second
    };
}

function curPageURL() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function link_for($url, $label = "", $class = "") {

    $returnString = '<a href="' . $url . '" ';
    if ($class != "") {
        $returnString .= 'class="' . $class . '"';
    }
    $returnString .= '>';
    if ($label == "") {
        $returnString .= $url;
    } else {
        $returnString .= $label;
    }
    $returnString .= "</a>";

    echo $returnString;
}

function redirect($to) {
    @session_write_close();
    if (!headers_sent()) {
        header("Location: $to");
        flush();
        exit();
    } else {
        print "<html><head><META http-equiv='refresh' content='0;URL=$to'></head><body><a href='$to'>$to</a></body></html>";
        flush();
        exit();
    }
}

function print_r2($Array, $Name = '', $size = 2, $depth = '', $Tab = '', $Sub = '', $c = 0) {
    /** wrote to display with large multi dimensional arrays, // Dave Husk , easyphpscripts.com
     * print_r2($Array,'Name_for_array'(optional));
     */
    if (!is_array($Array))
        return (FALSE);
    if ($Name && $depth == '')
        $Name1 = '$' . $Name;
    $CR = "\r\n";
    if ($c == 0) {
        $display = '';
        //defualt to open at start
        echo $CR . '<script>function poke_that_array(dir){x=document.getElementById(dir);if(x.style.display == "none"){x.style.display = "";}else{x.style.display = "none";}}</script>' . $CR;
    } else
        $display = 'none';
    $BR = '<br>';
    $Red = '<font color="#DD0000" size=' . $size . '>';
    $Green = '<font color="#007700" size=' . $size . '>';
    $Blue = '<font color="#0000BB" size=' . $size . '>';
    $Black = '<font color="#000000" size=' . $size . '>';
    $Orange = '<font color="#FF9900" size=' . $size . '>';
    $Font_end = '</font>';
    $Left = $Green . '' . '[' . $Font_end;
    $Right = $Green . ']' . $Font_end;
    $At = $Black . ' => ' . $Font_end;
    $lSub = $Sub;
    $c++;
    foreach ($Array as $Key => $Val) {
        if ($Key) { $output = 1;
            $rKey = rand(100, 10000);
            echo $CR . '<div><a name="print_r2' . $rKey . $c . '">' . $Tab . '' . $Green . $Font_end . ' ' . $At . '<a href="#print_r2' . $rKey . $c . '" onClick=poke_that_array("print_r2' . $rKey . $c . '")><font  size=' . $size . '>Array(' . $Sub . '</font></a>' . $CR . '<div style="display:' . $display . ';" id="print_r2' . $rKey . $c . '">' . $CR;
            break;
        }
    }
    foreach ($Array as $Key => $Val) { $c++;
        $Type = gettype($Val);
        $q = '';
        if (is_array($Array[$Key]))
            $Sub = $Orange . ' /** [' . @htmlentities($Key) . '] */' . $Font_end;
        if (!is_numeric($Key))
            $q = '"';
        if (!is_numeric($Val) & !is_array($Val) & $Type != 'boolean')
            $Val = '"' . $Val . '"';
        if ($Type == 'NULL')
            $Val = 'NULL';
        if ($Type == 'boolean')
            $Val = ($Val == 1) ? 'TRUE' : 'FALSE';
        if (!is_array($Val)) { $At = $Blue . ' = ' . $Font_end;
            $e = ';';
        }
        if (is_array($Array[$Key]))
            $At = '';
        echo $CR . $Tab . (chr(9)) . '&nbsp;&nbsp;' . $depth . $Left . $Blue . $q . @htmlentities($Key) . $q . $Font_end . $Right . $At . $Red . @htmlentities($Val) . $Font_end . $e . $BR . $CR;
        if ($depth == '')
            unset($lSub);
        $e = '';
        if (is_array($Array[$Key]))
            print_r2($Array[$Key], $Name, $size, $depth . $Left . $Blue . $q . @htmlentities($Key) . $q . $Font_end . $Right, (chr(9)) . '&nbsp;&nbsp;&nbsp;' . $Tab, $Sub, $c);
    }
    if ($output)
        echo $CR . '</div>' . $Tab . '<font  size=' . $size . '>)' . $lSub . '</font></div>' . $CR;

}

class Table {

    protected $opentable = "\n<table cellspacing=\"0\" cellpadding=\"0\">\n";
    protected $closetable = "</table>\n";
    protected $openrow = "\t<tr>\n";
    protected $closerow = "\t</tr>\n";

    function __construct($data) {
        $this -> string = $this -> opentable;
        foreach ($data as $row) {
            $this -> string .= $this -> buildrow($row);
        }
        $this -> string .= $this -> closetable;
    }

    function addfield($field, $style = "null") {
        if ($style == "null") {
            $html = "\t\t<td>" . $field . "</td>\n";
        } else {
            $html = "\t\t<td class=\"" . $style . "\">" . $field . "</td>\n";
        }
        return $html;
    }

    function buildrow($row) {
        $html .= $this -> openrow;
        foreach ($row as $field) {
            $html .= $this -> addfield($field);
        }
        $html .= $this -> closerow;
        return $html;
    }

    function draw() {
        echo $this -> string;
    }

}

class CSV_DataSource {

    public function __construct($string) {
        $file = "temp/current.csv";
        $handle = fopen($file, "w+");
        fwrite($handle, $string);
        $csv = new File_CSV_DataSource;
        $csv -> load($file);

    }

}

class Helper {

    public static function count_col_row($array) {

        $rows = count($array);
        $cols = 0;
        foreach ($array as $currentRow) {
            $cols = max($cols, count($currentRow));
        }

        return array("col" => $cols, "row" => $rows);
    }

    public static function fill_array($array, $cols = NULL) {

        $col_row = Helper::count_col_row($array);
        if (is_null($cols)) {
            $cols = $col_row["col"];
        } else {
            $cols = $col_row["col"] + intval($cols);
        }

        foreach ($array as $key => $row) {
            $array[$key] = array_pad($row, $cols, NULL);
        }

        return $array;
    }

    public static function array_to_csv($dataArray, $filePointer = NULL, $delimiter = ',', $enclosure = '"', $encloseAll = TRUE, $nullToMysqlNull = false) {

        if (isset($filePointer)) {
            $filePointer = fopen($filePointer, "w+");
        }

        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');

        foreach ($dataArray as $row) {
            if (empty($row)) {
                continue;
            }
            $output = array();
            foreach ($row as $field) {
                if ($field === null && $nullToMysqlNull) {
                    $output[] = 'NULL';
                    continue;
                }
                $field = trim($field);

                // Enclose fields containing $delimiter, $enclosure or whitespace
                if ($encloseAll || preg_match("/(?:${delimiter_esc}|${enclosure_esc}|[[:blank:]])/", $field)) {
                    $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
                } else {
                    $output[] = $field;
                }
            }

            $csvstring .= implode($delimiter, $output) . PHP_EOL;
        }
        if (isset($filePointer)) {
            fwrite($filePointer, $csvstring);
            fclose($filePointer);
        }

        return $csvstring;
    }

    public static function csvstring_to_array($string, $separatorChar = ',', $enclosureChar = '"', $newlineChar = "\n") {

        $array = array();
        $size = strlen($string);
        $columnIndex = 0;
        $rowIndex = 0;
        $fieldValue = "";
        $isEnclosured = false;
        for ($i = 0; $i < $size; $i++) {

            $char = $string{$i};
            $addChar = "";

            if ($isEnclosured) {
                if ($char == $enclosureChar) {

                    if ($i + 1 < $size && $string{$i + 1} == $enclosureChar) {
                        // escaped char
                        $addChar = $char;
                        $i++;
                        // dont check next char
                    } else {
                        $isEnclosured = false;
                    }
                } else {
                    $addChar = $char;
                }
            } else {
                if ($char == $enclosureChar) {
                    $isEnclosured = true;
                } else {

                    if ($char == $separatorChar) {
                        $array[$rowIndex][$columnIndex] = $fieldValue;
                        $fieldValue = "";

                        $columnIndex++;
                    } elseif ($char == $newlineChar) {
                        $array[$rowIndex][$columnIndex] = $fieldValue;
                        $fieldValue = "";
                        $columnIndex = 0;
                        $rowIndex++;
                    } else {
                        $addChar = $char;
                    }
                }
            }
            if ($addChar != "") {
                $fieldValue .= $addChar;

            }
        }

        if ($fieldValue) {// save last field

            $array[$rowIndex][$columnIndex] = $fieldValue;
        }

        return $array;
    }

    public static function parse($rawText, $code) {

        $text = $rawText;

        call_user_func($code, $text);

        return $text;
    }

    public static function create_html_from_csv($csv) {
        $array = Helper::csvstring_to_array($csv);
        return Helper::create_html_from_array($array);
    }

    public static function create_html_from_array($array) {
        $col_row = Helper::count_col_row($array);
        $cols = $col_row["col"];
        $rows = $col_row["row"];

        $html = "<p><table border = '1'>";
        for ($i = 0; $i < $rows; $i++) {
            $html .= '<tr><td class="index">[' . $i . "]</td>";
            for ($j = 0; $j < $cols; $j++) {
                $html .= "<td>" . stripcslashes($array[$i][$j]) . "</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</table></p>";

        return $html;
    }

    public static function jump($array, $pivotColumn, $copy) {
        /* sends the content of the cell in a certain column, the pivotColumn,
         * to the last column available if its cell to the right is empty or "copy" is set to true
         * */
        if ($pivotColumn == "") {
            $pivotColumn = 0;
        }
        if ($copy == "") {
            $copy = FALSE;
        }
        $cols_rows = Helper::count_col_row($array);
        $cols = $cols_rows["col"];

        foreach ($array as $key => $row) {

            if ($copy == "TRUE" || empty($row[$pivotColumn + 1])) {
                $row[$cols - 1] = $row[$pivotColumn];
                $array[$key] = $row;
            }
        }

        return $array;
    }

    public static function copy_column($array, $column) {
        /* will copy the content of a column to the last column */
        return Helper::jump($array, $column, $copy = "TRUE");
    }

    public static function remove_whitelines($array) {

        foreach ($array as $key => $row) {
            if (strlen(trim(implode($row))) == 0) {
                $array[$key] = NULL;
            }
        }
        $array = array_filter($array);
        return $array;
    }

    public static function fill_from_above($array, $pivotCols, $pivotRows) {
        /* will choose the specified rows in the specified column and copy onto the cells below all the way down
         * until the next specified row is reached
         *  */
        $cols_rows = Helper::count_col_row($array);
        if ($pivotCols == "") {
            $pivotCols = array("0");
        } else {
            $pivotCols = explode(",", $pivotCols);
        }

        if (gettype($pivotRows) == "string" && $pivotRows != "") {
            $pivotRows = explode(",", $pivotRows);
        } else {
            $pivotRows = array();
            foreach ($pivotCols as $col) {
                foreach ($array as $rowKey => $row) {
                    if (trim($row[$col]) != "") {
                        $pivotRows[] = $rowKey;
                    }

                }
            }
        }
        // echop($pivotRows);
        foreach ($array as $rowKey => $row) {
            if (!(in_array($rowKey, $pivotRows))) {
                foreach ($row as $colKey => $cellValue) {
                    if (in_array($colKey, $pivotCols)) {
                        $array[$rowKey][$colKey] = $array[$rowKey - 1][$colKey];
                    }
                }
            }
        }
        // echop($array);
        return $array;
    }

    public static function remove_lines($array, $lines) {
        if (gettype($lines) == "string") {
            $lines = explode(",", $lines);
        }
        $newArray = array();

        foreach ($array as $key => $row) {
            if (!(in_array($key, $lines)))
                $newArray[] = $row;
        }
        return $newArray;
    }

    public static function html_to_csv($htmlString, $number = 0) {

        $html = str_get_html($htmlString);
        //$table =  $html->find('table');

        $csv = "";

        foreach ($html->find('tr') as $element) {
            $td = array();
            foreach ($element->find('th') as $row) {
                $td[] = trim($row -> plaintext);
            }
            $csv .= implode(",", $td) . PHP_EOL;

            $td = array();
            foreach ($element->find('td') as $row) {
                $cell = addslashes(trim($row -> plaintext));
                $td[] = $cell;
            }
            $csv .= '"' . implode('","', $td) . '"' . PHP_EOL;
        }

        return $csv;
    }

    public static function remove_columns($array, $columns, $keepKeys) {
        if ($columns === "") {
            $columns = Helper::empty_columns($array);
        } else {
            $columns = explode(",", $columns);
        }

        $newArray = array();
        foreach ($array as $rowKey => $row) {
            foreach ($row as $colKey => $cell) {
                if (!(in_array($colKey, $columns))) {
                    if ($keepKeys == "true") {
                        $newArray[$rowKey][$colKey] = $cell;
                    } else {
                        $newArray[$rowKey][] = $cell;
                    }
                }
            }
        }

        return $newArray;
    }

    public static function empty_columns($array) {
        $columns = $array[0];
        $resColumns = array();

        foreach ($columns as $colKey => $col) {
            $colArray = array();
            foreach ($array as $rowKey => $row) {
                if (trim($array[$rowKey][$colKey]) != "") {
                    $colArray[] = $array[$rowKey][$colKey];
                }
            }
            if (implode($colArray) == "") {
                $resColumns[] = $colKey;
            }
        }
        return $resColumns;
    }

    public static function interject_rows($array, $number, $copy) {
        if ($number == "") {
            $number = 1;
        }
        if ($copy == "") {
            $copy = FALSE;
        }
        $emptyRow = array_fill(0, count($array[0]), NULL);

        $newArray = array();

        foreach ($array as $rowKey => $row) {
            $newArray[] = $row;

            for ($i = 0; $i < $number; $i++) {
                if ($copy == "TRUE") {
                    $newArray[] = $newArray[$rowKey];
                } else {
                    $newArray[] = $emptyRow;
                }
            }
        }

        return $newArray;
    }

    public static function add_column($csv, $cols) {
        if ($cols == "") {
            $cols = 1;
        };
        return Helper::fill_array($csv, $cols);
    }

    public static function copy_where($array, $col, $regex) {
        if ($col == "") {
            $col = 0;
        }
        if ($regex == "") {
            $regex = "%\w+%";
        }

        $endCol = count($array[0]) - 1;
        foreach ($array as $rowKey => $row) {
            if (preg_match($regex, $row[$col]) == 1) {
                $array[$rowKey][$endCol] = $array[$rowKey][$col];
            }
        }

        return $array;
    }

    public static function remove_from($array, $col, $regex) {

        $col = explode(",", $col);
        $regex = explode(",", $regex);
        foreach ($col as $colKey => $colValue) {
            foreach ($array as $rowKey => $row) {

                $array[$rowKey][$colValue] = preg_replace($regex[$colKey], "", $row[$colValue]);
            }
        }

        return $array;
    }

    public static function nonempty_keys($array, $col = 0) {
        $nonEmpty = array();
        foreach ($array as $rowKey => $row) {
            if (!(empty($row[$col]))) {
                $nonEmpty[] = $rowKey;
            }
        }
        return $nonEmpty;
    }

    public static function slice_at($array, $startpoints) {
        $diffpoints = array();
        $startpoints = array_unique($startpoints);

        foreach ($startpoints as $key => $value) {
            if ($key != count($startpoints) - 1) {
                $diffpoints[] = $startpoints[$key + 1] - $value;
            }
        }
        $diffpoints[] = count($array) - end($diffpoints);
        $newArray = array();
        foreach ($startpoints as $key => $value) {
            $newArray[] = array_slice($array, $value, $diffpoints[$key]);
        }
        $newArray = array_values($newArray);
        return $newArray;
    }

    public static function convert_project_plan($array) {

        $nonEmpty = Helper::nonempty_keys($array);
        $array = Helper::slice_at($array, $nonEmpty);

        foreach ($array as $key => $Company) {
            $array[$key] = call_user_func_array('array_merge', array_values($Company));
            $array[$key] = array_values(array_filter($array[$key], 'strlen'));
        }

        $newArray = array();
        foreach ($array as $companyKey => $Company) {
            $CompanyName = $Company[0];
            $projectKeys = Helper::find_project_keys($Company);
            $Projects = Helper::slice_at($Company, $projectKeys);

            foreach ($Projects as $projectKey => $Project) {
                $ProjectName = $Project[0];

                $phaseKeys = Helper::find_phase_keys($Project);
                $Phases = Helper::slice_at($Project, $phaseKeys);
                $Phases = array_values($Phases);

                foreach ($Phases as $phaseKey => $Phase) {
                    array_unshift($Phase, $CompanyName, $ProjectName);
                    $newArray[] = $Phase;
                }
            }
        }
        $newArray = Helper::fill_array($newArray);
        $col_row = Helper::count_col_row($newArray);
        $col1 = $col_row["col"] - 2;
        $col2 = $col_row["col"] - 1;
        Helper::merge_columns($newArray, $col1, $col2);

        return $newArray;
    }

    public static function find_phase_keys($array) {

        $returnArray = array();
        foreach ($array as $key => $value) {
            if (intval($key) < 2) {
                continue;
            }

            if (Helper::is_phase($value)) {
                $returnArray[] = $key;

            }

        }
        return $returnArray;
    }

    public static function is_phase($string) {

        $limit = 30;
        $validPhases = array("stage", "phase", "pilot", "demonstration", "bottleneck", "MR2", "Expansion", "Extension", "Leismer ", "Thornbury", "Pod One", "Hangingstone", "Reliability Tranche 2", "Millennium Mine");
        $invalidPhases = array("Hangingstone Pilot", "Single cycle pilot");
        $is_phase = FALSE;

        foreach ($validPhases as $key => $value) {
            if (gettype(stripos($string, $value)) == "integer") {
                $is_phase = TRUE;
            }
        }
        $is_invalid = FALSE;
        foreach ($invalidPhases as $key => $value) {
            if (gettype(stripos($string, $value)) == "integer" || strlen($string) > $limit) {
                $is_invalid = TRUE;
            }
        }

        if ($is_phase && !($is_invalid)) {
            return TRUE;

        } else {
            return FALSE;
        }

    }

    public static function find_project_keys($array) {

        $nameArray = array();
        $array = array_values($array);

        foreach ($array as $key => $value) {
            $is_owner = Helper::is_project_owner($value);

            if ($is_owner) {
                $is_first_owner = !(Helper::is_project_owner($array[$key - 1]));
            }

            if ($is_owner && $is_first_owner) {

                if (Helper::is_project_date($array[$key - 2])) {
                    $nameArray[] = $key - 3;
                } else {
                    $nameArray[] = $key - 1;
                }
            }
        }
        return $nameArray;
    }

    public static function is_project_owner($string) {

        return (preg_match("~\d%\)~", $string) == 1);
    }

    public static function is_project_date($string) {

        return (preg_match("~[[:upper:]]{1}[[:lower:]]{2}[[:blank:]]{1}\d{4}:~", $string) == 1);
    }

    public static function array_each_add($array, $number) {
        foreach ($array as $key => $value) {
            $array[$key] = intval($array[$key]) + $number;
        }
        return $array;
    }

    public static function multiple_search($haystack_array, $needle_array, $length_limit = FALSE) {
        $returnArray = array();
        if (gettype($needle_array) != "array") {
            $needle_array = array($needle_array);
        }

        foreach ($needle_array as $needleKey => $needleValue) {
            foreach ($haystack_array as $haystackKey => $haystackValue) {
                $is_found = gettype(stripos($haystackValue, $needleValue)) == "integer";
                $is_short = $length_limit == FALSE || strlen($haystackValue) < $length_limit;
                if ($is_found && $is_short) {
                    $returnArray[] = $haystackKey;
                }
            }
        }
        $returnArray = array_unique(array_values($returnArray));
        sort($returnArray);

        return $returnArray;
    }

    public static function merge_columns($array, $col1, $col2) {

        foreach ($array as $rowKey => $row) {

            $array[$rowKey][$col1] = $array[$rowKey][$col1] . $array[$rowKey][$col2];
        }
        $array = Helper::remove_columns($array, $columns = $col2);

        return $array;
    }

    public static function split_at($array, $col, $char) {
        $col = intval($col);
        if (strlen(trim($char)) < 1) {
            $char = "%[[:blank:]]{1}%";
        } else {
            $char = "%" . $char . "%";
        }

        foreach ($array as $rowKey => $row) {

            $cell = $row[$col];

            $splitArray = preg_split($char, $cell);
            $leftCell = array($splitArray[0]);
            $rightCell = array($splitArray[1]);

            if ($col == 0) {
                $array[$rowKey] = array_merge($leftCell, $rightCell, array_slice($row, 2));

            } else {
                $leftPart = array_slice($row, 0, $col - 1);
                $rightPart = array_slice($row, $col + 1);
                $array[$rowKey] = array_merge($leftPart, $leftCell, $rightCell, $rightPart);
            }
        }

        return $array;
    }

    public static function transpose($array) {
        array_unshift($array, null);
        return call_user_func_array('array_map', $array);
    }

    public static function add_header($array, $header) {
        $header = explode(",", $header);

        array_unshift($array, $header);

        return $array;
    }

    public static function remove_duplicates($array) {

        return array_unique($array, SORT_REGULAR);
    }

    public static function draw_left($array, $targetCol) {
        if ($targetCol == "") {
            $targetCol = 0;
        }

        foreach ($array as $rowKey => $row) {
            foreach ($row as $colKey => $cell) {
                if ($cell != "" && $targetCol <= $colKey) {
                    $array[$rowKey] = array_splice($row, $colKey);
                    continue 2;
                }
            }
        }
        return $array;
    }

    public static function find_rows($array, $regex, $col) {
        if ($col == "") {
            $col = 0;
        }

        $returnArray = array();
        foreach ($array as $rowKey => $row) {
            if ($row[$col] == "" && $regex == "") {
                $returnArray[] = $rowKey;
            } else {
                if (preg_match($regex, $row[$col]) == 1) {
                    $returnArray[] = $rowKey;
                }
            }
        }
        return $returnArray;
    }

    public static function split_row_at($array, $colRowRegex, $delimiterRegex) {
        $colRowRegex = explode(",", $colRowRegex, 2);
        $col = $colRowRegex[0];
        if (count($colRowRegex) > 1) {
            $rowRegex = $colRowRegex[1];
        } else {
            $rowRegex = "%\w+%";
            //means "at least something"
        }

        $returnArray = array();
        $rows = Helper::find_rows($array, $rowRegex, $col);
        foreach ($array as $rowKey => $row) {
            if (in_array($rowKey, $rows)) {
                $splitCells = array_keys(preg_grep($delimiterRegex, $array[$rowKey]));
                $added_arrays = Helper::slice_at($row, $splitCells);
            } else {
                $added_arrays = $row;
            }
            foreach ($added_arrays as $singleRow) {
                $returnArray[] = $singleRow;
            }
        }
        return $returnArray;
    }

    public static function remove_lines_where($array, $regex, $col) {

        $linesToRemove = Helper::find_rows($array, $regex, $col);

        $array = Helper::remove_lines($array, $linesToRemove);
        return $array;
    }

    public static function fill_from_above_where($array, $pivotCols, $regex) {
        $pivotRows = Helper::find_rows($array, $regex, $pivotCols);
        $array = Helper::fill_from_above($array, $pivotCols, $pivotRows);
        return $array;
    }

    public static function remove_if_other_col($array, $checkCol, $removeCol) {
        $checkCol = explode(",", $checkCol, 2);
        $regex = "%\w+%";
        $checkCol = $checkCol[0];
        if (count($checkCol) > 1) {
            $regex = $checkCol[1];
        }

        foreach ($array as $rowKey => $row) {
            if (preg_match($regex, $row[$checkCol]) == 1) {
                $array[$rowKey][$removeCol] = "";
            }
        }

        return $array;
    }

    public static function melt($array, $colsToSplit, $header) {
        // header contains only the words used for the value and the word of differentiation, for example "Value" and "Product"
        $colsToSplit = explode(",", $colsToSplit);
        $header = explode(",", $header);

        $newHeader = array_diff_key($array[0], array_flip($colsToSplit));
        $newHeader = array_merge(array_values($newHeader), $header);

        $newArray = array($newHeader);

        foreach ($colsToSplit as $splitCol) {
            $property = $array[0][$splitCol];
            foreach ($array as $rowKey => $row) {
                if ($rowKey == 0) {//don't include old header
                    continue 1;
                }
                $newRow = array();
                foreach ($row as $colKey => $cell) {
                    if ($colKey == $splitCol) {
                        $propertyValue = $cell;
                    }
                    if (!(in_array($colKey, $colsToSplit))) {
                        $newRow[] = $cell;
                    }
                }
                $newRow[] = $propertyValue;
                $newRow[] = $property;
                $newArray[] = $newRow;
            }
        }
        return $newArray;
    }

    public static function find_most_similar($needle, $haystack) {

        $bestWord = $haystack[0];
        $shortestDistance = levenshtein($needle, $bestWord);

        foreach ($haystack as $key => $value) {
            $thisDistance = levenshtein($needle, $value);
            if ($thisDistance < $shortestDistance) {
                $bestWord = $value;
                $shortestDistance = $thisDistance;
            }
        }
        return $bestWord;
    }

    public static function result_column_to_array($resultSet, $colName) {

        $returnArray = array();
        foreach ($resultSet as $result) {
            $returnArray[] = $result -> $colName;
        }
        return $returnArray;
    }

    public static function get_array_column($array, $col, $header) {

        $newArray = array();

        if (gettype($header) == "boolean" && $header == TRUE) {
            $newHeader = $array[0][$col];
            $array = array_slice($array, 1);
        } elseif (gettype($header) == "string" && strlen($header) > 0) {
            $newHeader = $header;
            $array = array_slice($array, 1);
        }

        foreach ($array as $rowKey => $row) {
            $newArray[] = $row[$col];
        }
        $returnArray = array("header" => $newHeader, "values" => $newArray);

        return $returnArray;
    }

    public static function sql_remove_duplicates($sqlTable, $ignoreArray = array("id")) {

        $rowsBefore = ORM::for_table($sqlTable) -> count();

        $headers = Helper::sql_get_columns($sqlTable);
        $headers = array_diff($headers, $ignoreArray);
        echo print_r($headers) . "<br><br>";
        $query = " ALTER TABLE " . $sqlTable . " ADD COLUMN tmp_col TEXT(300); ";
        $query .= ' UPDATE ' . $sqlTable . ' SET tmp_col = concat_ws(",", ' . implode(", ", $headers) . ' ); ';
        $query .= "CREATE TABLE tmp LIKE " . $sqlTable . " ; ";
        $query .= " ALTER TABLE tmp ADD UNIQUE (tmp_col(300)) ; ";
        $query .= "INSERT IGNORE INTO tmp SELECT * FROM " . $sqlTable . " ; ";
        $query .= "RENAME TABLE " . $sqlTable . " TO deleteme, tmp TO " . $sqlTable . " ; ";
        $query .= " ALTER TABLE " . $sqlTable . " DROP COLUMN tmp_col ; ";
        $query .= "DROP TABLE deleteme ;";
        ORM::for_table($sqlTable) -> raw_execute($query);

        $rowsAfter = ORM::for_table($sqlTable) -> count();

        return $rowsBefore - $rowsAfter;
    }

    public static function sql_convert_dates($sourceId, $tableName) {
        $ORM = ORM::for_table($tableName) -> where("Source_Id", $sourceId) -> find_many();

        $dateHeaders = array("Year", "Date", "Actual_Application", "Expected_Approval", "Regulatory_Approval", "Construction_Start", "First_Steam", "Production_Start", "Month", "Startup_Date");
        $quartalHeaders = array("Actual_Application", "Expected_Approval", "Regulatory_Approval", "Construction_Start", "First_Steam", "Production_Start");

        // Year (Decimal or integer)
        // Date (Decimal or integer)
        // Actual_Application (Q4 2012)
        // Expected_Approval (Q4 2015 or integer)
        // Regulatory_Approval (Q4 2015 or integer)
        // Construction_Start (Q4 2015 or integer)
        // First_Steam (Q4 2015 or integer)
        // Production_Start (Q4 2015 or integer)
        // Month (November, nov, Nov)
        // Startup_Date (2013-07-01)
        foreach ($ORM as $row) {
            $existsArray = array();
            foreach ($dateHeaders as $header) {
                $exists = $row -> $header == NULL || $row -> $header == "" || $row -> $header == "NULL";
                $existsArray[$header] = !($exists);
            }
            $existsMonthYear = var_export($existsArray["Month"], TRUE) . var_export($existsArray["Year"], TRUE);

            switch ($existsMonthYear) {
                case 'truetrue' :
                    $date = array("Year" => $row -> Year, "Month" => $row -> Month, "Day" => "01");
                    $newDate = Helper::convert_date($date, "YMD");
                    break;

                case 'falsetrue' :
                    $date = $row -> Year;
                    if (fmod(floatval($date), 1.0) == 0) {
                        $newDate = Helper::convert_date($date, "int");
                    } else {
                        $newDate = Helper::convert_date($date, "dec");
                    }
                    break;

                case "falsefalse" :
                    $date = $row -> Date;

                    $date = explode("-", $date);
                    if (count($date) == 3) {

                        $date = array("Year" => $date[0], "Month" => $date[1], "Day" => $date[2]);
                        $newDate = Helper::convert_date($date, "YMD");
                    }
                    if (count($date) == 2) {
                        $date = array("Year" => $date[0], "Month" => $row -> Month, "Day" => "01");
                        $newDate = Helper::convert_date($date, "YMD");
                    }
                    if (count($date) == 1) {
                        $date = $date[0];
                        if (strlen($date) == 0) {
                            $newDate = NULL;
                        } else {
                            if (fmod($date, 1) == 0) {
                                $newDate = Helper::convert_date($date, "int");
                            } else {
                                $newDate = Helper::convert_date($date, "dec");
                            }
                        }
                    }

                    break;
            }
            $row -> Date = $newDate;
            foreach ($quartalHeaders as $qHeader) {
                if ($existsArray[$qHeader]) {
                    $date = $row -> $qHeader;

                    if (substr($date, 0, 1) == "Q") {
                        $newDate = Helper::convert_date($date, "Q");
                    } else {
                        $newDate = Helper::convert_date($date, "int");
                    }
                    $row -> $qHeader = $newDate;
                }
            }
            $row -> Month = NULL;
            $row -> Year = NULL;

            $row -> save();
        }

    }

    public static function convert_date($date, $type) {

        $qArray = array("02" => 'Q1', "05" => 'Q2', "08" => 'Q3', "11" => 'Q4');

        switch ($type) {
            case 'dec' :
                $Year = floor($date);
                $remainder = fmod($date, 1);
                $days = floor($remainder * 365.0);
                $interval = new DateInterval("P" . $days . "D");
                $firstDay = date_create($Year . "-01-01");
                $returnDate = date_format(date_add($firstDay, $interval), 'Y-m-d');
                break;
            case 'int' :
                $date = trim($date);
                $returnDate = $date . "-07-01";
                break;
            case 'YMD' :
                $year = trim($date["Year"]);
                $month = Helper::convert_month($date["Month"]);
                $day = trim($date["Day"]);
                $returnDate = $year . "-" . $month . "-" . $day;
                break;

            case 'Q' :
                $date = explode(" ", $date);
                $year = trim($date[1]);
                $month = array_search($date[0], $qArray);
                $day = "15";

                $returnDate = $year . "-" . $month . "-" . $day;

                break;
        }
        return $returnDate;

    }

    public static function convert_month($month) {

        if (strlen($month) == 2 || strlen($month) == 0) {
            return $month;
        } elseif (strlen($month) == 1) {
            return "0" . $month;
        } else {
            $monthNumbers = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
            $monthNames = array("jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec");
            $allMonths = array_combine($monthNumbers, $monthNames);
            $month = strtolower(substr($month, 0, 3));
            $newMonth = array_search($month, $allMonths);

            if ($newMonth != FALSE) {
                return $newMonth;
            } else {
                return "Error";
            }
        }

    }

    public static function sql_to_barrels_per_day($sourceId, $dataTable) {

        $ORM = ORM::for_table($dataTable) -> where("Source_Id", $sourceId) -> find_many();

        $barrel_per_cubic_meter = 1 / 0.158987295;
        $thousand = 1000;
        $one_per_thousand = 1 / 1000;
        $days_per_year = 365;
        $years_per_day = 1 / $days_per_year;
        $months_per_day = 1 / 30.417;

        $unitFactors["Thousand Cubic Metres per year"] = $thousand * $barrel_per_cubic_meter * $years_per_day;
        $unitFactors["Thousand barrels per day"] = $thousand;
        $unitFactors["Barrels per day"] = 1;
        $unitFactors["Million barrels per day"] = $thousand * $thousand;
        $unitFactors["Cubic metres per year"] = $barrel_per_cubic_meter * $years_per_day;
        $unitFactors["Thousand Cubic meters per day"] = $thousand * $barrel_per_cubic_meter;
        $unitFactors["Thousand Cubic metres per month"] = $thousand * $barrel_per_cubic_meter * $months_per_day;

        $possibleUnits = array_keys($unitFactors);

        foreach ($ORM as $row) {

            $unit = $row -> Unit;
            $value = $row -> Value;

            if (!in_array($unit, $possibleUnits)) {
                // if the unit is omitted, the standard unit should be assumed
                if (trim($unit) == "") {
                    $unit = "Barrels per day";
                } else {
                    $unit = Helper::find_most_similar($unit, $possibleUnits);
                }
            }
            $factor = $unitFactors[$unit];

            $row -> Value = $value * $factor;
            $row -> Unit = NULL;

            $row -> save();
        }
    }

    public static function interpolate_table($sourceId) {

        /* chooses all rows of a given source from the table osdb_data, interpolates the values with a step
         length of one day and inserts these values into osdb_working

         since every source may contain series for several different subgrupts (scenario, products or both),
         the interpolation has to be done for each subgroup individually

         All columns that are not within $ignoreArray (the standard columns) are considered to contain subgroups */
        $ignoreArray = array("id", "Source_Id", "Date", "Value");

        $ORMArray = ORM::for_table('osdb_data') -> find_array();
        $ORMArray = Helper::filter_for_value($ORMArray, "Source_Id", $sourceId);

        $subGroupHeaders = array_keys(array_filter(reset($ORMArray)));
        // creates an array of all non-standard column headers
        $subGroupHeaders = array_diff($subGroupHeaders, $ignoreArray);

        // now $subGroupHeaders contains all column-names that contain subgroups
        if (count($subGroupHeaders) > 0) {
            $subGroupArray = Helper::sql_select_columns($ORMArray, $subGroupHeaders, TRUE);
            $subGroupArray = array_unique($subGroupArray, SORT_REGULAR);

            Helper::sql_add_columns("osdb_working", $subGroupHeaders);
        } else {
            $subGroupArray = array(NULL);
        }
        $workingTableHeaders = Helper::sql_get_columns("osdb_working");
        /* each row of $subGroupArray now contains  the names of a different dataset that should or could
         be interpolated

         now the interpolation begins */
        foreach ($subGroupArray as $subGroup) {
            $queryArray = array();
            $currentORM = $ORMArray;
            $currentCompilationName = ORM::for_table('osdb_sources') -> find_one($sourceId);
            $currentCompilationName = $currentCompilationName -> ShortName;

            /* removes all element that are not part of the current Subgroup
             filtering is not required if the table only contains one type of data */
            if ($subGroup != NULL) {
                foreach ($subGroup as $key => $element) {
                    $currentORM = Helper::filter_for_value($currentORM, $key, $element);
                    if ($key == 'Time_Accuracy') {
                        $currentCompilationName .= " - " . $element . " months accuracy";
                    } else {
                        $currentCompilationName .= " - " . $element;
                    }
                }
            }
            // interpolation is only applicable if there is more than one datapoint to start from
            if (count($currentORM) > 1) {
                // At the same time, a new Compilation has to be defined for this subgroup
                $newCompilation = ORM::for_table('osdb_compilations') -> create();
                $newCompilation -> Name = $currentCompilationName;
                $newCompilation -> Source_Id = $sourceId;
                $newCompilation -> save();
                $compilationId = $newCompilation -> id();

                // since $currentORM is still in the form of [Data_row_Id]=>array(), we only filter out the values
                $currentORM = Helper::sort_by($currentORM, "Date");
                $currentORM = array_values($currentORM);

                foreach ($currentORM as $rowKey => $row) {

                    // traverse all rows in the current ORM except for the last
                    if ($rowKey != count($currentORM) - 1) {
                        $firstDay = date_create_from_format("Y-m-d", $row["Date"]);
                        $firstValue = $row["Value"];
                        $lastDay = date_create_from_format("Y-m-d", $currentORM[$rowKey + 1]["Date"]);
                        $lastValue = $currentORM[$rowKey + 1]["Value"];
                        $valueDiff = $lastValue - $firstValue;

                        $interval = $firstDay -> diff($lastDay);
                        $intInterval = intval($interval -> format("%a"));

                        $currentDay = $firstDay;
                        $timeFraction = 0;

                        while ($currentDay < $lastDay) {
                            $newRow = array();
                            foreach ($workingTableHeaders as $header) {
                                switch($header) {
                                    case "id" :
                                        // we let MySQL decide the new id
                                        $newRow[$header] = "";
                                        break;
                                    case "Date" :
                                        $newRow[$header] = $currentDay -> format('Y-m-d');
                                        break;
                                    case "Value" :
                                        $newRow[$header] = $firstValue + ($timeFraction * $valueDiff);
                                        break;
                                    case "Compilation_Id" :
                                        $newRow[$header] = $compilationId;
                                        break;
                                    default :
                                        $newRow[$header] = $row[$header];
                                }
                            }

                            $queryArray[] = $newRow;

                            // preparing for the next step
                            $currentDay = $currentDay -> modify('+ 1 day');
                            $timeFraction = $timeFraction + (1 / $intInterval);
                        }
                    }
                }
                Helper::sql_insert_array($queryArray, "osdb_working");

            }
        }

    }

    public static function sql_add_columns($sqlTable, $columns) {
        $dummyRow = ORM::for_table($sqlTable) -> create();
        $dummyRow -> save();
        $dummyRowId = $dummyRow -> id();
        $sql_table = ORM::for_table($sqlTable) -> find_one() -> as_array();

        foreach ($columns as $column) {
            if (!(array_key_exists($column, $sql_table))) {
                $query = "ALTER TABLE " . $sqlTable . " ADD COLUMN " . $column . " TINYTEXT";
                ORM::for_table($sqlTable) -> raw_execute($query);
            }
        }

        $dummyRow = ORM::for_table($sqlTable) -> find_one($dummyRowId);
        $dummyRow -> delete();

    }

    public static function filter_for_value($array, $key, $values) {
        // goes through each element of an 2d-array and returns only those rows where the element $key is $value

        if (gettype($values) != "array") {
            $values = array($values);
            // $functionText = 'return (is_array($arrayRow) && $arrayRow["' . $key . '"] == ' . $values . ');';
        }
        // else {
        // $functionText = 'return (is_array($arrayRow) && in_array($arrayRow["' . $key . '"], array("' . implode('","', $values) . '")));';
        // }
        // $filterFunction = create_function('$arrayRow', $functionText);

        // $newArray = array_filter($array, $filterFunction);

        $newArray = array_filter($array, function($arrayRow) use ($key, $values) {
            return (is_array($arrayRow) && in_array($arrayRow[$key], $values));
        });

        return $newArray;
    }

    public static function sql_select_columns($array, $columns, $alwaysAsArray = FALSE) {
        if (gettype($columns) != "array") {
            $columns = array($columns);
        }
        $newArray = array();

        foreach ($array as $rowKey => $row) {
            foreach ($row as $colKey => $cell) {
                if (in_array($colKey, $columns)) {
                    if (count($columns) > 1 || $alwaysAsArray) {
                        $newArray[$rowKey][$colKey] = $cell;
                    } else {
                        $newArray[$rowKey] = $cell;
                    }
                }
            }
        }
        return $newArray;
    }

    public static function sql_get_columns($sqlTable) {
        $dummyRow = ORM::for_table($sqlTable) -> create();
        $dummyRow -> save();
        $dummyRowId = $dummyRow -> id();
        $sqlColumns = array_keys(ORM::for_table($sqlTable) -> find_one() -> as_array());

        $dummyRow = ORM::for_table($sqlTable) -> find_one($dummyRowId);
        $dummyRow -> delete();

        return $sqlColumns;
    }

    public static function add_or_subtract($array, $method, $onlyCommonDates = TRUE) {
        $allDates = array();
        foreach ($array as $compilationId => $rowsBelongingToCompilation) {
            $array[$compilationId] = Helper::rebuild_keys($array[$compilationId], "Date");
            // $newDates = Helper::sql_select_columns($rowsBelongingToCompilation, "Date");
            $allDates = array_merge($allDates, array_keys($array[$compilationId]));

        }
        $allDates = array_unique($allDates);
        sort($allDates);

        $returnArray = array();

        foreach ($allDates as $date) {
            $newRow = array();
            foreach ($array as $compilationId => $rowsBelongingToCompilation) {
                if (isset($array[$compilationId][$date])) {
                    $newRow[] = $array[$compilationId][$date]["Value"];
                } else {
                    $newRow[] = NULL;
                }
            }
            if (!$onlyCommonDates || array_filter($newRow) === $newRow) {
                if ($method == "Subtract") {
                    for ($i = 1; $i < count($newRow); $i++) {
                        $newRow[$i] = -1 * $newRow[$i];
                    }
                }
                $returnArray[] = array("Date" => $date, "Value" => array_sum($newRow));
            }
        }
        return $returnArray;
    }

    public static function concat_time_series($array) {

        $allDates = array();
        $array = call_user_func_array("array_merge", $array);
        $allDates = array_unique(Helper::sql_select_columns($array, "Date"));
        sort($allDates);

        //echo print_r($allDates);

        $newArray = array();
        foreach ($allDates as $dateKey => $date) {
            $rowsWithRightDate = Helper::filter_for_value($array, "Date", $date);

            if (count($rowsWithRightDate) == 1) {
                $rowsWithRightDate = reset($rowsWithRightDate);
                $newArray[] = array("Date" => $date, "Value" => $rowsWithRightDate["Value"], "Time_Accuracy" => $rowsWithRightDate["Time_Accuracy"]);

            } else {
                $lowestAccuracy = min(Helper::sql_select_columns($rowsWithRightDate, "Time_Accuracy"));
                $rowWithLowestAccuracy = reset(Helper::filter_for_value($rowsWithRightDate, "Time_Accuracy", $lowestAccuracy));
                $newArray[] = array("Date" => $date, "Value" => $rowWithLowestAccuracy["Value"], "Time_Accuracy" => $rowWithLowestAccuracy["Time_Accuracy"]);
            }
        }
        return $newArray;
    }

    public static function combine_data($compilationIdArray, $method, $newName, $changeArray, $onlyCommonDates) {

        //creating a subgroup with only the relevant compilations
        foreach ($compilationIdArray as $compilationId) {
            $array[$compilationId] = ORM::for_table("osdb_working") -> order_by_asc('Date') -> where("Compilation_Id", $compilationId) -> find_array();
        }

        $firstRow = reset(reset($array));
        $sourceId = $firstRow["Source_Id"];

        $newCompilation = ORM::for_table('osdb_compilations') -> create();
        $newCompilation -> Name = $newName;
        $newCompilation -> Source_Id = $sourceId;
        $newCompilation -> save();
        $newCompilationId = $newCompilation -> id();

        if (in_array($method, array("Add", "Subtract"))) {
            $newDateAndValues = Helper::add_or_subtract($array, $method, $onlyCommonDates);

        } elseif ($method == "Concatenate") {
            $newDateAndValues = Helper::concat_time_series($array);
        }

        $headers = Helper::sql_get_columns("osdb_working");
        $changeArray = array_combine($headers, $changeArray);
        $changeArray = array_filter($changeArray);

        foreach ($changeArray as $key => $valueToChange) {
            $firstRow[$key] = $valueToChange;
        }
        foreach ($newDateAndValues as $rowKey => $row) {
            foreach ($headers as $headerKey => $header) {

                switch ($header) {
                    case 'id' :
                        $value = "";
                        break;
                    case 'Compilation_Id' :
                        $value = $newCompilationId;
                        break;
                    case 'Source_Id' :
                        $value = $sourceId;
                        break;
                    case 'Date' :
                        $value = $row["Date"];
                        break;
                    case 'Value' :
                        $value = $row["Value"];
                        break;
                    case 'Time_Accuracy' :
                        if ($method == "Concatenate") {
                            $value = $row["Time_Accuracy"];
                        } else {
                            $value = $firstRow[$header];
                        }
                        break;

                    default :
                        $value = $firstRow[$header];
                        break;
                }

                $newArray[$rowKey][$header] = $value;
            }
        }
        // echop($newArray);
        Helper::sql_insert_array($newArray, "osdb_working");

    }

    public static function shorten_names($inputArray) {

        $ORM = ORM::for_table("osdb_synonyms") -> find_array();
        $returnArray = $inputArray;

        // flatten array one level

        foreach ($ORM as $ORMKey => $ORMRow) {
            $returnArray = str_replace($ORMRow["Synonym"], $ORMRow["Replacement"], $returnArray);
        }
        return $returnArray;
    }

    public static function sql_insert_array($array, $sqlTable, $maxString = 5000) {
        //echo print_r($array) .  "<br>";
        if (count($array) < 1) {
            echo "Empty array given! <br>";
            return;
        }

        $headers = array_keys(reset($array));

        Helper::sql_add_columns($sqlTable, $headers);

        $queryStart = "INSERT INTO " . $sqlTable . " (" . implode(" , ", $headers) . ") VALUES ";
        $query = "";

        foreach ($array as $rowKey => $row) {
            $newRow = array();
            foreach ($row as $colKey => $cell) {
                switch ($colKey) {
                    case "id" :
                        // we let MySQL decide the new id
                        $newRow[$colKey] = "";
                        break;
                    default :
                        $newRow[$colKey] = $cell;
                }
            }
            $newRow = "('" . implode("' , '", $newRow) . "'),";
            $query .= $newRow;

            if (strlen($query) > $maxString) {
                $totalQuery = $queryStart . rtrim($query, ",") . ";";
                ORM::for_table($sqlTable) -> raw_execute($totalQuery);
                $query = "";
            }
        }
        //add the rest
        if (strlen($query) > 2) {
            $totalQuery = $queryStart . rtrim($query, ",") . ";";
            ORM::for_table($sqlTable) -> raw_execute($totalQuery);
        }
    }

    public static function add_tags($compilationIdArray, $tagArray, $newTags) {

        $newTags = explode(",", $newTags);

        $tagArray = array_merge($tagArray, $newTags);
        $tagArray = array_filter($tagArray);
        echo print_r($compilationIdArray) . "<br><br>";
        echo print_r($tagArray);
        foreach ($compilationIdArray as $compilationId) {
            foreach ($tagArray as $tag) {
                $queryArray[] = array("id" => "", "Name" => $tag, "Compilation_Id" => $compilationId);
            }
        }
        Helper::sql_insert_array($queryArray, "osdb_tags");
    }

    public static function sort_by($arrayToSort, $column, $order = SORT_ASC) {
        $array = $arrayToSort;
        usort($array, make_comparer(array($column, $order)));

        return $array;
    }

    public static function calculate_error_statistics($compilationIdArray, $mainId, $newName) {

        $compilationIdArray = array_diff($compilationIdArray, array($mainId));
        $mainArray = ORM::for_table("osdb_working") -> order_by_asc('Date') -> where("Compilation_Id", $mainId) -> find_array();
        $mainArray = Helper::rebuild_keys($mainArray, "Date");
        $mainDates = Helper::sql_select_columns($mainArray, "Date");
        echop($mainDates);
        foreach ($compilationIdArray as $compilationId) {

            $array = ORM::for_table("osdb_working") -> order_by_asc('Date') -> where("Compilation_Id", $compilationId) -> find_array();
            $array = Helper::rebuild_keys($array, "Date");

            $firstRow = reset($array);
            $publicationDate = ORM::for_table("osdb_sources") -> find_one($firstRow["Source_Id"]) -> PublicationDate;
            $prognosisDates = Helper::filter_dates($mainDates, $publicationDate);
            $publicationDate = new DateTime($publicationDate);

            $errorArray = array();

            foreach ($prognosisDates as $date) {

                if (isset($array[$date])) {
                    $yRow = $array[$date];

                    $xRow = $mainArray[$date];

                    $errorRow["Date"] = $xRow["Date"];
                    $errorRow["Error"] = $yRow["Value"] - $xRow["Value"];
                    if ($xRow["Value"] != 0) {
                        $errorRow["ErrorPercentage"] = $errorRow["Error"] / $xRow["Value"];
                    } else {
                        $errorRow["ErrorPercentage"] = "";
                    }

                    $xDate = new DateTime($xRow["Date"]);
                    $diff = $xDate -> diff($publicationDate);

                    $errorRow["Day"] = $diff -> format('%a');

                    $errorRow["Main_Id"] = $mainId;
                    $errorRow["Compilation_Id"] = $compilationId;

                    $errorArray[] = $errorRow;

                }

            }
            // Helper::sql_insert_array($errorArray, "osdb_errors");

        }

    }

    public static function filter_dates($dates, $constantDate, $after = TRUE) {

        $returnDates = array();
        foreach ($dates as $dateToCheck) {
            $dateIsAfter = strtotime($dateToCheck) > strtotime($constantDate);
            if ($after == $dateIsAfter) {
                $returnDates[] = $dateToCheck;
            }
        }

        return $returnDates;
    }

    public static function rebuild_keys($array, $key) {
        // rebuilds a two-dimensional array to have a certain value from each "row" as each key
        //usage: $array = array([0]=>array("Fruit"=>"Banana", "Taste"=>"good"),
        //[1]=>array("Fruit"=>"Apple", "Taste"=>"boring"));
        // $newArray = rebuild_keys($array, "Fruit");

        $newArray = array();
        foreach ($array as $rowKey => $arrayRow) {
            if (isset($newArray[$arrayRow[$key]])) {
                $duplicate[] = $rowKey;
            } else {
                $newArray[$arrayRow[$key]] = $arrayRow;
            }
        }
        if (isset($duplicate)) {
            echo "Error: The key you specified is not unique. Some values appear at least twice. Invalid keys at row " . implode(", ", $duplicate);
        } else {
            return $newArray;
        }
    }

    public static function calculate_ranking() {
        $foo = ORM::for_table("osdb_ranking") -> raw_execute("TRUNCATE TABLE osdb_ranking;");
        $combinationIdArray = ORM::for_table('osdb_errors') -> distinct() -> select_many("Main_Id", "Compilation_Id") -> find_array();
        // echop($combinationIdArray);
        foreach ($combinationIdArray as $combination) {
            $maxDay = ORM::for_table('osdb_errors') -> where("Main_Id", $combination["Main_Id"]) -> where("Compilation_Id", $combination["Compilation_Id"]) -> order_by_desc('Day') -> find_one();
            $maxArray[] = $maxDay -> Day;
        }
        $maxArray = array_unique($maxArray);
        sort($maxArray);

        echop($maxArray);
        $mainIdArray = array_unique(Helper::sql_select_columns($combinationIdArray, "Main_Id"));
        // echop($mainIdArray);
        // echop($mainIdArray);
        foreach ($mainIdArray as $mainId) {
            $validErrorCompilations = Helper::filter_for_value($combinationIdArray, "Main_Id", $mainId);
            $validErrorCompilations = Helper::sql_select_columns($validErrorCompilations, "Compilation_Id");
            // echop($validErrorCompilations);
            if (count($validErrorCompilations) > 1) {
                $compilationsToCompare = power_perms($validErrorCompilations);
                array_walk($compilationsToCompare, "sort");
                $compilationsToCompare = array_filter($compilationsToCompare, create_function('$v', 'return count($v) == 2 ;'));
                $compilationsToCompare = array_unique($compilationsToCompare, SORT_REGULAR);
                echop($compilationsToCompare);
                $queryArray = array();
                foreach ($compilationsToCompare as $combination) {
                    $firstCompilation = ORM::for_table('osdb_errors') -> where("Compilation_Id", $combination[0]) -> order_by_asc('Day') -> find_array();
                    $firstCompilation = Helper::rebuild_keys($firstCompilation, "Day");
                    $secondCompilation = ORM::for_table('osdb_errors') -> where("Compilation_Id", $combination[1]) -> order_by_asc('Day') -> find_array();
                    $secondCompilation = Helper::rebuild_keys($secondCompilation, "Day");
                    //echop($secondCompilation);
                    $day = 0;
                    $errorDiff = array();
                    while ($day <= max($maxArray)) {
                        if (isset($firstCompilation[$day]) && isset($secondCompilation[$day])) {
                            $errorDiff[$day] = pow($firstCompilation[$day]["ErrorPercentage"], 2) - pow($secondCompilation[$day]["ErrorPercentage"], 2);
                        }
                        if (count($errorDiff) > 1 && in_array($day, $maxArray)) {
                            $meanDifferential = array_sum($errorDiff) / count($errorDiff);
                            $autocovariance = Helper::autocovariance($errorDiff);
                            $errorStatistic = $meanDifferential / sqrt($autocovariance);
                            $arrayToAdd = array("Main_Id" => $mainId, "Compilation_1" => $combination[0], "Compilation_2" => $combination[1], "Day" => count($errorDiff), "Mean_Differential" => $meanDifferential, "ErrorStatistic" => $errorStatistic);
                            if (!in_array($arrayToAdd, $queryArray)) {
                                $queryArray[] = $arrayToAdd;
                            }
                        }
                        $day++;
                    }

                }
            }

        }
        Helper::sql_insert_array($queryArray, "osdb_ranking");
        Helper::sql_remove_duplicates("osdb_ranking");
    }

    public static function autocovariance($array, $stepSize = 1) {
        $array = array_values($array);
        $mean = array_sum($array) / count($array);
        $sum = 0;
        for ($i = 0; $i < count($array) - $stepSize; $i++) {
            $sum += ($array[$i] - $mean) * ($array[$i + $stepSize] - $mean);
        }
        $covariance = $sum / count($array);
        return $covariance;
    }

    public static function create_tournament_ranking($array) {
        $returnArray = array();

        foreach ($array as $row) {
            $c1 = $row["Compilation_1"];
            $c2 = $row["Compilation_2"];

            if (!isset($returnArray[$c1])) {
                $returnArray[$c1] = 0;
            }
            if (!isset($returnArray[$c2])) {
                $returnArray[$c2] = 0;
            }
            if ($row["ErrorStatistic"] > 0) {
                $returnArray[$c1] = $returnArray[$c1] + 1;
            } else {
                $returnArray[$c2] = $returnArray[$c2] + 1;
            }
        }
        arsort($returnArray);
        return $returnArray;
    }

    public static function remove_source_from_database($sourceId, $archive = TRUE) {

        $compilations = ORM::for_table("osdb_working") -> where("Source_Id", $sourceId) -> select("Compilation_Id") -> find_array();
        $compilations = array_unique($compilations, SORT_REGULAR);
        $tables = array("compilations" => "Source_Id", "data" => "Source_Id", "errors" => array("Main_Id", "Compilation_Id"), "ranking" => array("Main_Id", "Compilation_1", "Compilation_2"), "tags" => "Compilation_Id", "working" => array("Compilation_Id", "Source_Id"));

        foreach ($tables as $tableName => $columns) {
            if (gettype($columns) == "string") {
                $columns = array($columns);
            }
            foreach ($columns as $columnName) {
                if (in_array($columnName, array("Main_Id", "Compilation_Id", "Compilation_1", "Compilation_2"))) {
                    foreach ($compilations as $compilationId) {
                        $compilationId = $compilationId["Compilation_Id"];
                        ORM::for_table('osdb_' . $tableName) -> where_equal($columnName, $compilationId) -> delete_many();
                    }
                } else {
                    ORM::for_table('osdb_' . $tableName) -> where_equal($columnName, $sourceId) -> delete_many();
                }
            }
        }
        if ($archive) {
            $source = ORM::for_table("osdb_sources") -> find_one($sourceId);
            $source -> Archived = 1;
            $source -> save();
        }

    }

}
?>