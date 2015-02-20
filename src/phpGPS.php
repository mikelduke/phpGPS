<?php
  include "phpGPS_Settings.php";
  
  /**
   * 
   * @author Mikel
   * 
   * http://www.mikelduke.com
   * 
   * @version 1.0
   * 
   * 2015-02-19
   */
  
  class phpGPS_DB {
    
    /**
     * List of tables that the update/insert/delete scripts should check
     */
    public static $_allowedTables = array(
      "gps_owner",
      "gps_device",
      "gps_type",
      "gps_path",
      "gps_entries",
      "user_types",
      "users"
    );
  
    public static $_allowableTags = "<p><ul><li><b><u><hr><string><br><img><a>";
    
    //Create the database connection
    function connectToDB() {
      $con = mysqli_connect(phpGPS_Settings::$_host, phpGPS_Settings::$_username, phpGPS_Settings::$_password, phpGPS_Settings::$_dbname);
      if (mysqli_connect_errno()) {
        echo "Failed to connect to db: " . mysqli_connect_errno();
        return null;
      }
      
      return $con;
    }
    
    /*static function cleanInput($inputStr) {
      return cleanInput($inputStr, false);
    }
    
    static function cleanInput($inputStr, $addSlashes) {
      $ret = strip_tags($inputStr, phpGPS_DB::$_allowableTags);
      if ($addSlashes) $ret = addslashes($ret);
      return $ret;
    }*/
    
    static function cleanInput($inputStr) {
      $ret = strip_tags($inputStr, phpGPS_DB::$_allowableTags);
      $ret = addslashes($ret);
      return $ret;
    }
  }
  
  /**
   * @author Mikel
   * 
   * Common class to hold maps used for variable lookups when not using database
   */
  class phpGPS_LookUps {
    public static $gps_status_map = array(
      ""  => "Default",
      "H" => "Hidden",
      "P" => "Path Only"
    );
  }
  
  function getTableRow($row, $columnName, $table, $tableColumn, $preLink, $postLink) {
    $ret = "";
    $ret = $ret . "<td>";
    $ret = $ret . $preLink;
    $ret = $ret . "<a onclick='edit(\"$table\", \"" . $columnName . "\", \"" . $row[$columnName] . "\", \"$tableColumn=" . $row[$tableColumn] . "\")' href='javascript:void(0);'>";
    if (strlen($row[$columnName]) > 0)  $ret = $ret . $row[$columnName];
    else $ret = $ret . "[Set]";
    $ret = $ret . "</a>";
    $ret = $ret . $postLink;
    $ret = $ret . "</td>\n";
  
    return $ret;
  }
  
  /**
   * Build a simple dropdown which calls the javascript updateRecord funtion on change
   * The column name on the table to update should match an id column on the results from $query.
   * 
   * @param SQL Connection $con
   * @param SQL Query String $query
   * @param unknown $selectedOption
   * @param unknown $name
   * @param unknown $updateTable
   * @param unknown $updateColumn
   * @param unknown $displayColumn
   * @param unknown $whereColumn
   * @param unknown $id
   * @param unknown $includeNoneOpt
   * @return string
   */
  function buildDropDown($con, $query, $selectedOption, $name, $updateTable, $updateColumn, $displayColumn, $whereColumn, $id, $includeNoneOpt) {
    $results = mysqli_query($con, $query);
    if (!$results) {
      die('Invalid query: ' . mysql_error());
    }
  
    $ret = "<div class='form-group'>\n" .
        "  <select class='form-control' id='$name$id' " .
        "onchange='updateRecord(\"$updateTable\", \"$updateColumn\", this.value, \"$whereColumn=$id\")'>\n";
  
    if ($includeNoneOpt) {
      if ($selectedOption == "") $selected = " selected='selected'";
      else $selected = "";
      $ret = $ret . "    <option value=NULL$selected>None</option>\n";
    }
  
    while ($row = @mysqli_fetch_assoc($results)) {
      $selected = "";
      if ($row[$updateColumn] == $selectedOption) $selected = " selected='selected'";
      $ret = $ret . "    <option value='" . $row[$updateColumn] . "'$selected>";
      
      if ($row[$displayColumn] != "") $ret = $ret . $row[$displayColumn];
      else $ret = $ret . $name . " " . $row[$updateColumn];
      $ret = $ret . "</option>\n";
    }
  
    $ret = $ret . "  </select>\n ".
        "</div>\n";
    return $ret;
  }
  
  function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
?>
