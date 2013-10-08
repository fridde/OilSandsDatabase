<?php function ResetTable($text, $par1 = "", $par2 = "") {}

function AsTable($text, $par1 = "", $par2 = "") {}

function RegReplace($text, $pattern, $replacement) {return preg_replace($pattern, $replacement, $text);}

function SplitAt($text, $col, $char) {$text = Helper::csvstring_to_array($text); $text = Helper::split_at($text, $col, $char); return Helper::array_to_csv($text);}

function EqualizeColumns($text, $par1 = "", $par2 = "") {$text = Helper::csvstring_to_array($text); $text = Helper::fill_array($text); return Helper::array_to_csv($text);}

function CopyColumn($text, $column, $par2 = "") {$text = Helper::csvstring_to_array($text); $text = Helper::copy_column($text, $column); return Helper::array_to_csv($text);}

function Jump($text, $pivotColumn, $copy) {$text = Helper::csvstring_to_array($text); $text = Helper::jump($text, $pivotColumn, $copy); return Helper::array_to_csv($text);}

function RemoveWhitelines($text, $par1 = "", $par2 = "") {$text = Helper::csvstring_to_array($text); $text = Helper::remove_whitelines($text); return Helper::array_to_csv($text);}

function FromAbove($text, $pivotCols, $pivotRows) {$text = Helper::csvstring_to_array($text); $text = Helper::fill_from_above($text, $pivotCols, $pivotRows); return Helper::array_to_csv($text);}

function RemoveLines($text, $lines, $par2 = "") {$text = Helper::csvstring_to_array($text); $text = Helper::remove_lines($text, $lines); return Helper::array_to_csv($text);}

function htmlToCsv($text, $number, $par2 = "") {return Helper::html_to_csv($text, $number);}

function RemoveColumns($text, $columns, $par2 = "") {$text = Helper::csvstring_to_array($text); $text = Helper::remove_columns($text, $columns); return Helper::array_to_csv($text);}

function InterjectRows($text, $number, $copy) {$text = Helper::csvstring_to_array($text); $text = Helper::interject_rows($text, $number, $copy); return Helper::array_to_csv($text);}

function AddColumn($text, $cols, $par2 = "") {$text = Helper::csvstring_to_array($text); $text = Helper::add_column($text, $cols); return Helper::array_to_csv($text);}

function CopyWhere($text, $col, $regex) {$text = Helper::csvstring_to_array($text); $text = Helper::copy_where($text, $col, $regex); return Helper::array_to_csv($text);}

function RemoveFrom($text, $col, $regex) {$text = Helper::csvstring_to_array($text); $text = Helper::remove_from($text, $col, $regex); return Helper::array_to_csv($text);}

function ConvertProjectPlan($text, $par1 = "", $par2 = "") {$text = Helper::csvstring_to_array($text);$text = Helper::convert_project_plan($text); return Helper::array_to_csv($text);}

function MergeColumns($text, $col1, $col2) {$text = Helper::csvstring_to_array($text); $text = Helper::merge_columns($text, $col1, $col2); return Helper::array_to_csv($text);}

function Undo($text, $par1 = "", $par2 = "") {}

function AddHeader($text, $header, $par2 = "") {$text = Helper::csvstring_to_array($text); $text = Helper::add_header($text, $header); return Helper::array_to_csv($text);}

function Transpose($text, $par1 = "", $par2 = "") {$text = Helper::csvstring_to_array($text); $text = Helper::transpose($text); return Helper::array_to_csv($text);}

function RemoveDuplicateLines($text, $par1 = "", $par2 = "") {$text = Helper::csvstring_to_array($text); $text = Helper::remove_duplicates($text); return Helper::array_to_csv($text);}

function DrawLeft($text, $targetCol, $par2 = "") {$text = Helper::csvstring_to_array($text); $text = Helper::draw_left($text, $targetCol); return Helper::array_to_csv($text);}

function SplitRowAt($text, $colRowRegex, $delimiterRegex) {$text = Helper::csvstring_to_array($text); $text = Helper::split_row_at($text, $colRowRegex, $delimiterRegex); return Helper::array_to_csv($text);}

function RemoveLinesWhere($text, $regex, $col) {$text = Helper::csvstring_to_array($text); $text = Helper::remove_lines_where($text, $regex, $col); return Helper::array_to_csv($text);}

function FillFromAboveWhere($text, $pivotCols, $regex) {$text = Helper::csvstring_to_array($text); $text = Helper::fill_from_above_where($text, $pivotCols, $regex); return Helper::array_to_csv($text);}

function RemoveIfOtherCol($text, $checkCol, $removeCol) {$text = Helper::csvstring_to_array($text); $text = Helper::remove_if_other_col($text, $checkCol, $removeCol); return Helper::array_to_csv($text);}

function EditManually($text, $par1 = "", $par2 = "") {}

function Melt($text, $colsToSplit, $header) {$text = Helper::csvstring_to_array($text); $text = Helper::melt($text, $colsToSplit, $header); return Helper::array_to_csv($text);}

 ?>