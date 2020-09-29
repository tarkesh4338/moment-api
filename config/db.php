<?php
    class DBConnection{
        private $url = "localhost";
        private $username = "root";
        private $password = "root";
        private $database = "mymoment";

        public $connection;

        // Getting DB Connection

        public function getConnection(){
            $this->connection = null;

            try{
                $this->connection = new PDO("mysql:host=".$this->url.";dbname=".$this->database, $this->username, $this->password);
                $this->connection->exec("set names utf8");
                return $this->connection;
            }catch(PDOException $e){
                echo "Error : ". $e->getMessage();
            }
        }
    }

?>