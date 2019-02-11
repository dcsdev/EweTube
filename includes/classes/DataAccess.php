<?php

    class DataAccess {

        public static function getDataArray($con,$commandText,$params, $orderBy) {
            $queryText  = $commandText . DataAccess::BuildWhereFromParamArray($params) . $orderBy;

            $query      = $con->prepare($queryText);
            $query->execute();

            $returnArray = array();

            while ($row = $query->fetch(PDO::FETCH_ASSOC) ) {
                $returnArray[] = $row;
            }
            
            $input      = $query->fetch(PDO::FETCH_ASSOC);
            
            return $returnArray;
        }

        public static function getDataObject($con,$commandText,$params, $orderBy) {
            $queryText  = $commandText . DataAccess::BuildWhereFromParamArray($params) . $orderBy;
            $query      = $con->prepare($queryText);
            $query->execute();

            $input      = $query->fetch(PDO::FETCH_ASSOC);
            
            return $input;
        }

        public static function getSingleColumn($con, $commandText, $params) {
            $queryText  = $commandText . DataAccess::BuildWhereFromParamArray($params);
            $query      = $con->prepare($queryText);
            $query->execute();
            return $query->fetchColumn();
        }
        
        public static function Delete($con, $commandText, $params) {
            $queryText  = $commandText . DataAccess::BuildWhereFromParamArray($params);            
            $query      = $con->prepare($queryText);
            $query->execute();
            return $query;
        }

        public static function Update($con, $commandText, $columns, $whereParams) {
            $queryText  =  DataAccess::BuildUpdateStatment($commandText, $columns, $whereParams);
            $query      = $con->prepare($queryText);
            return $query->execute();
        }

        public static function Insert($con, $commandText, $values) {
            $queryText  = DataAccess::BuildInsertStatement($commandText,$values);

            $query      = $con->prepare($queryText);
            $query->execute();
            return $con->lastInsertId();
        }

        private static function BuildInsertStatement($tableName, $values) {
            $i = 0;
            $and;
            $query = "INSERT INTO $tableName (";

            foreach ($values as $key => $value) {
                $i = $i + 1; 
                if(sizeof($values) == 1) {
                    $and = "";
                } else if ( $i < sizeof($values) ) {
                    $and = " , ";
                } else {
                    $and = "";
                }
                 $query .= "$key $and";      
            }
        
            $query .= ") Values ( "; 

            $i = 0;

            foreach ($values as $key => $value) {
                $i = $i + 1; 
                if(sizeof($values) == 1) {
                    $and = "";
                } else if ( $i < sizeof($values) ) {
                    $and = ",";
                } else {
                    $and = "";
                }

                 $query .= "'$value'$and";      
            }

            $query .= ")";

            return $query;
         }

         public static function BuildUpdateStatment($tableName, $columns, $whereParams) {

            $i = 0;
            $and;
            $query = "UPDATE $tableName SET ";

            foreach ($columns as $key => $value) {
                $i = $i + 1; 
                if(sizeof($columns) == 1) {
                    $and = "";
                } else if ( $i < sizeof($columns) ) {
                    $and = " , ";
                } else {
                    $and = "";
                }

                 $query .= "$key='$value' $and"; 
     
            }

            $i = 0;
            $query .= " WHERE "; 

            foreach ($whereParams as $key => $value) {
                $i = $i + 1; 
                if(sizeof($whereParams) == 1) {
                    $and = "";
                } else if ( $i < sizeof($whereParams) ) {
                    $and = " AND ";
                } else {
                    $and = "";
                }

                 $query .= "$key='$value' $and"; 
     
            }

            return $query;

         }

        private static function BuildWhereFromParamArray($params) {
            $i = 0;
            $and;
            $query = " WHERE ";

            if (!isset($params)) {
                return;
            }

            foreach ($params as $key => $value) {
                $i = $i + 1; 
                if(sizeof($params) == 1) {
                    $and = "";
                } else if ( $i < sizeof($params) ) {
                    $and = " AND ";
                } else {
                    $and = "";
                }

                 $query .= "$key='$value' $and"; 
     
            }

            return $query;
        }

        public static function SelectRowCount($con, $commandText, $params) {
            $queryText = $commandText . DataAccess::BuildWhereFromParamArray($params);    
            $query = $con->prepare($queryText);
            $query->execute();
            return $query->rowCount();
        }
    }
?>