<?php
namespace MyChat\Database;
use \PDO;
class Db{

    private $host = 'localhost' ;
    private $user = 'root' ;
    private $pass = '' ;
    private $dbname = 'chat' ;
    private $connection;
    private $query;
    private $error;
    private static $instance;
    private $lastInsertId;



    /**
     * @return Db
     */
    public static function getInstance(){
        if(!self::$instance) { // If no instance then make one
            self::$instance = new Db();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->connectDB();

    }


    /**
     * @return bool|PDO
     */
    private function connectDB(){
        try {
            $this->connection = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname,$this->user,$this->pass,array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8',PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            return $this->connection;

        } catch (PDOexception $e) {
            echo "connection Error";
            return false;

        }

    }

    /**
     * @param $sql
     * @param array $args
     * @return $this|bool
     */

    public function query($sql,$args=[]){

        if(	$this->query  =  $this->connection->prepare($sql)){

            if(count($args)){
                $i = 1;
                foreach ($args as $arg) {

                    if(!is_array($arg)){

                        $this->query->bindValue($i,$arg);
                        $i++;
                    }
                }
            }


            try{
                $this->query->execute();

                $this->lastInsertId = $this->connection->lastInsertId();
            } catch (\PDOException  $e) {
                if ($e->errorInfo[1] == 1062) {
                    $this->error ="Alerday Exist ";

                    return false;
                } else {
                    //throw $e;
         
                    return false;
                }
            }


        }else{
            $this->error = "Invalid query";

            return false;
        }
        return $this;

    }

    /**
     * @param $query
     * @param $obj
     * @return |null
     */
    public function select($query,$obj){
        $req = $this->connection->query($query);

        $req->execute();

        $exist = $req->rowCount();

        if($exist>0){
            $obj = $req->fetchObject($obj);

            return $obj;
        }
        else{

            return null;
        }


    }


    /**
     * @param $table
     * @param $object
     * @return bool
     */
    public function insert($table,$object,$fields=[]){

        if(count($fields)){
            $fields = implode(",",$fields);
            $query ="Insert Into ".$table."(".$fields.") Values(";
        }else{
            $query = "Insert Into ".$table." Values(";
        }
        foreach ($object as $key => $value) {
            if(empty($value) && !($value==0)){
                $query .= "NULL,";
            }else{
                $query .= "'".$value."',";
            }


        }
        $query = rtrim($query,',');

        $query.=")";


        $req = $this->connection->prepare($query);
        try{
            $res = $req->execute();
        } catch (PDOException  $e) {
            if ($e->errorInfo[1] == 1062) {
                $this->error ="Alerday Exist";
                return false;
            } else {
                echo $e;
                throw $e;
            }
        }

        if($res){

            return $this->connection->lastInsertId();
        }else{
            return false;
        }

    }


    /**
     * @param $table
     * @param $fields
     * @param array $condts
     * @return bool
     */
    public function update($table,$fields,$condts=[]){
        $sql = "UPDATE ".$table." SET ";
        $values = [];
        foreach ($fields as $key => $value) {
            $sql.= $key."=?,";

        }
        $sql = rtrim($sql,',');
        if(count($condts)){
            $sql = $this->where($sql,$condts);
        }
        $values = array_merge($fields, $condts);


        if($this->query($sql,$values)!=false){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $table
     * @param array $fields
     * @param array $condts
     * @return mixed
     */

    public function findAll($table,$fields=[],$condts=[],$order_by=''){
        $sql = "select ";
        if(count($fields)){
            $sql .= implode(",",$fields);
            $sql = rtrim($sql,',');

        }else{
            $sql .=" * ";
        }
        $sql.=" from ".$table;
        if(count($condts)){
            $sql = $this->where($sql,$condts);
        }
        if($order_by){

            $sql.=" ORDER BY ".$order_by;
        }

        $results = $this->query($sql,$condts)->results();
        return $results;


    }

    public function joinTwoTbales($tables,$values=[],$array=false){
        if(isset($tables['fields'])){
            $fields = implode($tables['fields'],',');
        }else{
            $fields = "*";
        }

        $sql = "SELECT ".$fields." from ${tables[0]} JOIN ${tables[1]} ON ${tables['on']} ";
        if(isset($tables['condts'])){
            $sql .="where ${tables['condts']}";
        }



        return $this->query($sql,$values)->Customresult($array);


    }




    public function delete($table,$condt,$values=[]){
        $sql = "DELETE from {$table} where ${condt}";
        if($this->query($sql,$values)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $sql
     * @param $condts
     * @return string
     */

    private function where($sql,$condts){
        $sql .= " where ";
        foreach ($condts as $key => $value) {

            if(is_array($value)){
                foreach ($value as $k => $v) {
                    $sql .= " ".$key."='".$v."' OR";
                }
                $sql = rtrim($sql,'OR');

            }else{
                $sql .= " ".$key."=? AND";
            }


        }
        $sql = rtrim($sql,'AND');

        return $sql;
    }


    /**
     * @return mixed
     */
    public function results(){
        return $this->query->fetchAll(PDO::FETCH_OBJ);
    }

    public function result(){
        return $this->query->fetchObject();
    }

    public function Customresult($array=true){
        if($array){
            return $this->query->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return $this->query->fetch(PDO::FETCH_ASSOC);
        }

    }
    /**
     * @return mixed
     */
    public function RowCount(){
        return $this->query->rowCount();
    }

    /**
     * @return mixed
     */
    public function getError(){
        return $this->error;
    }
    public function getLastInsertId(){
        return $this->lastInsertId;
    }

}