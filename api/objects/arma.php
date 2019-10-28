<?php
class Arma
{
    private $conn;
    private $table_name = "arma";

    public $id;
    public $nome;

    public function __construct($db){
        $this->conn = $db;
    }

    //ler armas
    function read(){

        //select * from arma
        $query = "SELECT * FROM $this->table_name";

        if (isset($id)){
            $query .= " WHERE ID = :armaid";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':armaid', $this->id);

        // execute query
        $stmt->execute();

        $armas = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            
            $arma = new Arma($this->conn);
            $arma->id = $ID;
            $arma->nome = $NOME;

            array_push($armas, $arma);
        }

        return $armas;
    }
}