<?php

/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 06/03/2017
 * Time: 10:34 AM
 */
class query_builder extends db_connection
{
    private $del_bind_param;
    private $con = false; // Check to see if the connection is active
    private $result = array(); // Any results from a query will be stored here
    private $myQuery = "";// used for debugging process with SQL return
    private $numResults = "";// used for returning the number of rows
    private $value_type = array();
    //Insert an array of data into a table.
    public function where($column, $field)
    {
        //Encaps in single quotes
        $encapsField = '\'' . $field . '\'';

        $newWhere = str_replace('?', $encapsField, $column);

        $this->_where = 'WHERE ' . $newWhere;

        return $this;
    }
    // Private function to check if table exists for use with queries
    private function tableExists($table){
        $tablesInDb = $this->mysqli->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$table.'"');
        if($tablesInDb){
            if($tablesInDb->num_rows == 1){
                return true; // The table exists
            }else{
                array_push($this->result,$table." does not exist in this database");
                return false; // The table does not exist
            }
        }
    }
    public function insert($table, $data){
        if(!$this->tableExists($table)){
            return false;
        }else {
            foreach ($data as $field => $content) {
                $colm[] .= $this->escapeString($field);
                $values[] .= "?"; // ? placeholder is for the data we want to escape.
                switch (true) {
                    case  is_bool($content):
                        $content .= $content ? 'true' : 'false'; //converts a bool value to a string.
                        $value_type[] .= "b"; // b stand for boolean
                        break;
                    case  is_numeric($content):
                        $value_type[] .= "i"; // i stands for integer. A numeric value.
                        break;
                    default:
                        $value_type[] .= "s"; // s stands for a string.
                        break;
                }

                $bind_values[] .= $content;

                $query = "INSERT INTO $table (" . implode(",", $colm) . ") VALUE (" . implode(",", $values) . ")"; // the query to insrt into a table
                $stmt = $this->mysqli->prepare($query); // we prepare the query for attacks, escaping ans serializing it.

            }
            $stmt->bind_param(implode(",", $value_type), implode(",", $bind_values)); // we bind all the values to match up with the ? placeholders
            if ($stmt->execute()) { // we then run the query.
                return true;
            } else {
                return false;
            }
        }
    }


    public function select($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null){

        $q = 'SELECT '.$rows.' FROM '.$table;

        if($where != null){
            $q .= ' WHERE '.$where;
        }
        if($order != null){
            $q .= ' ORDER BY '.$order;
        }
        if($limit != null){
            $q .= ' LIMIT '.$limit;
        }

        if($this->tableExists($table)){
            // The table exists, run the query
            $query = $this->mysqli->query($q);
            if($query){
                // If the query returns >= 1 assign the number of rows to numResults
                $this->numResults = $query->num_rows;
                // Loop through the query results by the number of rows returned
                for($i = 0; $i < $this->numResults; $i++){
                    $r = $query->fetch_array();
                    $key = array_keys($r);
                    for($x = 0; $x < count($key); $x++){
                        // Sanitizes keys so only alphavalues are allowed
                        if(!is_int($key[$x])){
                            if($this->numResults >= 1){
                                $this->result[$i][$key[$x]] = $r[$key[$x]];
                            }else{
                                $this->result = null;
                            }
                        }
                    }
                }
                return true; // Query was successful
            }else{
                array_push($this->result,mysql_error());
                return false; // No rows where returned
            }
        }else{
            return false; // Table does not exist
        }
    }
    public function delete($table, $col, $where){
        $query = "DELETE FROM ? WHERE ? = ?";

        if(is_numeric($where)){
            $del_bind_param = 'ssi';
        }else{
            $del_bind_param = 'sss';
        }
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param($del_bind_param, $table, $col, $where);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    public function update($table, $data, $column, $field1){
        //Encaps in single quotes
        $encapsField = '\'' . $field1 . '\'';

        $newWhere = str_replace('?', $encapsField, $column);
        $_where = 'WHERE ' . $newWhere;

        foreach ($data as $field => $content){
            $colm[] .= $field." = ?<br>";

            switch (true){
                case  is_bool($content):
                    $content .= $content ? 'true' : 'false';
                    $value_type[] .= "b";
                    break;
                case  is_numeric($content):
                    $value_type[] .= "i";
                    break;
                default:
                    $value_type[] .= "s";
                    break;

            }

            $bind_values[] .= $content;
            $query = "UPDATE $table SET ".implode(",", $colm)." $_where = $field1";
        }
        switch (true){
            case  is_bool($field1):
                $content .= $content ? '1' : '2';
                $value_type[] .= "b";
                break;
            case  is_numeric($field1):
                $value_type[] .= "i";
                break;
            default:
                $value_type[] .= "s";
                break;

        }

        $stmt = $this->mysqli->prepare($query);

        $stmt->bind_param(implode(",",$value_type), implode(",", $content));
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    public function close(){

         if ($this->mysqli->close() == true){
             return true;
         }else{
             return false;
         }

    }
    public function escapeString($data){
        return $this->mysqli->real_escape_string($data);
    }
    //Pass the number of rows back
    public function numRows(){
        $val = $this->numResults;
        $this->numResults = array();
        return $val;
    }
}