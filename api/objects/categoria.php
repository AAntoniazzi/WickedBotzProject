<?php
class Categoria
{

    private $conn;
    private $table_name = "categoria";

    public $id;
    public $nome;


    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read() {

        // select all query
        $query = "SELECT * FROM $this->table_name";
    
        if (isset($this->id)) {
            $query .= " WHERE ID = :categoriaid"; 
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoriaid', $this->id);

        // execute query
        $stmt->execute();

        $categorias = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $categoria = new Categoria($this->conn);
            $categoria->id = $ID;
            $categoria->nome = $nome;

            array_push($categorias, $categoria);
        }
    
        return $categorias;
    }

}
