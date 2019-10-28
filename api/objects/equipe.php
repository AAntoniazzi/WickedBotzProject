<?php
class Equipe
{

    private $conn;
    private $table_name = "equipe";

    public $id;
    public $nome;
    public $entidade;
    public $cidade;
    public $estado;

    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read() {

        // select all query
        $query = "SELECT * FROM $this->table_name";
    
        if (isset($this->id)) {
            $query .= " WHERE ID = :id"; 
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        // execute query
        $stmt->execute();

        $equipes = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $equipe = new Equipe($this->conn);
            $equipe->id = $ID;
            $equipe->nome = $NOME;
            $equipe->entidade = $ENTIDADE;
            $equipe->cidade = $CIDADE;
            $equipe->estado = $ESTADO;

            array_push($equipes, $equipe);
        }
    
        return $equipes;
    }

}
