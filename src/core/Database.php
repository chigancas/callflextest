<?php

namespace src\core;
use PDO;
use PDOException;
use src\core\Database;

class Database extends PDO
{

    private $db_Host = "localhost";
    private $db_User = "root";
    private $db_Pass = "";
    private $db_Name = "Callflex";
    private $db_Con;

    public function __construct() {

        try {

            $this->db_Con = new PDO("mysql:host=$this->db_Host", $this->db_User, $this->db_Pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->db_Con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->db_Name = "`".str_replace("`","``",$this->db_Name)."`";
            $this->db_Con->exec("CREATE DATABASE IF NOT EXISTS $this->db_Name CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
            $this->db_Con->exec("use $this->db_Name");

        } catch (PDOException $e) {

            return $e;

        }
        
    }


    public function generateDb(){

        try{

            $queries = [

                "CREATE TABLE IF NOT EXISTS `roles` (
                    `id_role` INT NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(45) NOT NULL,
                    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    UNIQUE KEY(`name`),
                    PRIMARY KEY (`id_role`))
                  ENGINE = InnoDB;",
                
                "CREATE TABLE IF NOT EXISTS `users` (
                    `id_user` INT NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(45) NOT NULL,
                    `lastname` VARCHAR(100) NOT NULL,
                    `email` VARCHAR(100) NOT NULL,
                    `alias` VARCHAR(45) NOT NULL,
                    `password` VARCHAR(255) NOT NULL,
                    `role` INT NOT NULL,
                    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id_user`),
                    INDEX `fk_user_role_idx` (`role` ASC),
                    CONSTRAINT `fk_user_role`
                      FOREIGN KEY (`role`)
                      REFERENCES `roles` (`id_role`)
                      ON DELETE CASCADE
                      ON UPDATE CASCADE)
                  ENGINE = InnoDB;",

                "CREATE TABLE IF NOT EXISTS `products` (
                    `id_product` INT NOT NULL AUTO_INCREMENT,
                    `description` VARCHAR(255) NOT NULL,
                    `code` VARCHAR(50) NOT NULL,
                    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id_product`),
                    CONSTRAINT code_un UNIQUE (`code`)
                )
                ENGINE = InnoDB;",

                "INSERT INTO `roles` (`name`) 
                VALUES ('Administrator'), ('User')
                ON DUPLICATE KEY UPDATE
                `name` = VALUES(`name`)"

            ];

            foreach($queries as $key=>$value)
            {

                $this->db_Con->exec($value);

            }

            $auth = new Authentication;

            $auth->addUser("Alan","Chiganças","teste@gmail.com","admin", "mudar@123", 1);

            return true;

        }catch (PDOException $e)
        {

            return $e->getMessage();

        }

    }

    private function mb_ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)).mb_strtolower(mb_substr($string, 1));
    }

    public function select(string $table, int $limit = 0, Array $columns = ["*"], Array $filters = null, string $order = null){

        try
        {

            $get_Columns = "";
            $get_Filters = array();

            foreach($columns as $column)
            {
                $get_Columns .= "$column" . (strpos($column, "JOIN") ? " " : ", ");
            }

            $get_Columns = rtrim($get_Columns, ", ");

            if(!is_null($filters))
            {

                $get_Filters[0] = "";
                $get_Filters[1] = array();
                
                foreach($filters["column"] as $key=>$filter)
                {
                    $get_Filters[0] .= (strpos($filter, "GROUP BY") ? $filter : $filter ." = :f" . $filter . " AND");

                    if(!strpos($filter, "GROUP BY"))
                    {
                        $get_Filters[1]["f". $filter] = $filters["value"][$key];
                    }
                    
                } 

                $get_Filters[0] = rtrim($get_Filters[0], " AND");

            }

            $stmt = $this->db_Con->prepare("SELECT $get_Columns FROM $table" . (is_null($filters) ? "" : (substr($get_Filters[0], 0, 8) === " GROUP BY" ? " $get_Filters[0]" : " WHERE $get_Filters[0]")) . (is_null($order) ? "" : " ORDER BY $order"). ($limit === 0 ? "" : " LIMIT ". ($limit - 10) ." , $limit"));

            $stmt->execute((is_null($filters) ? array() : $get_Filters[1]));

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            if(count($result))
            {

                return json_encode(array("result" => $result, "total" => count($result)));

            }else
            {

                return json_encode(array("result" => "Nenhum resultado encontrado", "total" => count($result)));

            }

            $stmt = null;
            $this->db_Con = null;

        } catch (PDOException $e)
        {

            return 'ERROR: ' . $e->getMessage();

        }
        
    }

    public function insert(string $table, Array $values){

        try
        {

            $get_Values = array();

            $get_Values[0] = "";
            $get_Values[1] = "";
            $get_Values[2] = array();
            $get_Values[3] = "";
            
            foreach($values["column"] as $key=>$value)
            {
                $get_Values[0] .= $value . ",";
                $get_Values[1] .= ":" .$value. ",";
                $get_Values[2][$value] = $values["value"][$key];
                $get_Values[3] .= "$value = :$value,";
            } 

            $get_Values[0] = rtrim($get_Values[0], " ,");
            $get_Values[1] = rtrim($get_Values[1], " ,");
            $get_Values[3] = rtrim($get_Values[3], " ,");

            $stmt = $this->db_Con->prepare("INSERT INTO $table ($get_Values[0]) VALUES ($get_Values[1]) ON DUPLICATE KEY UPDATE $get_Values[3]");
            
                $result = $stmt->execute($get_Values[2]);
            
            if($stmt->rowCount())
            {

                return json_encode(array("result" => $result, "total" => $stmt->rowCount()));

            }else
            {

                return json_encode(array("result" => "Nenhum registro foi alterado", "total" => $stmt->rowCount()));

            }

            $stmt = null;
            $this->db_Con = null;

        } catch (PDOException $e)
        {

            return 'ERROR: ' . $e->getMessage();

        }
        
    }

    public function update(string $table, Array $values, Array $filters){

        try
        {

            $get_Values = array();

            $get_Values[0] = "";
            $get_Values[1] = array();
            
            foreach($values["column"] as $key=>$value)
            {
                $get_Values[0] .= "$value = :$value,";
                $get_Values[1][$value] = (is_null($type) ? $values["value"][$key] : $values["value"][$key]);
            } 

            $get_Filters[0] = "";
            
            foreach($filters["column"] as $key=>$filter)
            {
                $get_Filters[0] .= $filter ." = :f" .$filter. " AND ";
                $get_Values[1]["f" . $filter] = $filters["value"][$key];
            } 

            $get_Filters[0] = rtrim($get_Filters[0], " AND ");

            $get_Values[0] = rtrim($get_Values[0], " ,");

            $stmt = $this->db_Con->prepare("UPDATE $table SET $get_Values[0] WHERE $get_Filters[0]");

            $result = $stmt->execute($get_Values[1]);

            if($stmt->rowCount())
            {

                return json_encode(array("result" => $result, "total" => $stmt->rowCount()));

            }else
            {

                return json_encode(array("result" => "Nenhum registro foi alterado", "total" => $stmt->rowCount()));

            }

            $stmt = null;
            $this->db_Con = null;

        } catch (PDOException $e)
        {

            return 'ERROR: ' . $e->getMessage();

        }
        
    }

    public function delete(string $table, Array $filters){

        try
        {

            $get_Filters[0] = "";
            $get_Filters[1] = array();
            
            foreach($filters["column"] as $key=>$filter)
            {
                $get_Filters[0] .= $filter ." = :f" .$filter. " AND ";
                $get_Filters[1]["f" . $filter] = $filters["value"][$key];
            } 

            $get_Filters[0] = rtrim($get_Filters[0], " AND ");

            $stmt = $this->db_Con->prepare("DELETE FROM $table WHERE $get_Filters[0]");

            $result = $stmt->execute($get_Filters[1]);

            if($stmt->rowCount())
            {

                return json_encode(array("result" => $result, "total" => $stmt->rowCount()));

            }else
            {

                return json_encode(array("result" => "Nenhum registro foi alterado", "total" => $stmt->rowCount()));

            }

            $stmt = null;
            $this->db_Con = null;

        } catch (PDOException $e)
        {

            return 'ERROR: ' . $e->getMessage();

        }
        
    }

    public function query(string $sql)
    {

        try
        {
            $result = $this->db_Con->query($sql);

            return json_encode($result->fetchAll(PDO::FETCH_OBJ));

        } catch (PDOException $e)
        {

            return 'ERROR: ' . $e->getMessage();

        }
        
    }

}

?>