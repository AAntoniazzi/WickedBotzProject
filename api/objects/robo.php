<?php
class Robo
{

    private $conn;
    private $table_name = "robo";

    public $id;
    public $nome;
    public $categoriaid;
    public $equipeid;
    public $armaid;
    public $qtdarmas;
    public $qtdroda;
    public $qtdmotor;
    public $tipomotor;
    public $tiporoda;
    public $carenagem;

    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read() {

        // select all query
        $query = "SELECT * FROM $this->table_name";

        $query_where = array();
    
        if (isset($this->id)) {
            array_push($query_where, "ID = :id");
        }

        if (isset($this->equipeid)) {
            array_push($query_where, "EQUIPEID = :equipeid");
        }

        if (count($query_where)) {
            $query .= " WHERE ";
            $query .= implode(" AND ", $query_where);
        }


        $stmt = $this->conn->prepare($query);
        if (isset($this->id)) {
            $stmt->bindParam(':id', $this->id);
        }
        if (isset($this->equipeid)) {
            $stmt->bindParam(':equipeid', $this->equipeid);
        }

        // execute query
        $stmt->execute();

        $robos = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $robo = new Robo($this->conn);
            $robo->id = $ID;
            $robo->nome = $NOME;
            $robo->categoriaid = $CATEGORIAID;
            $robo->equipeid = $EQUIPEID;
            $robo->armaid = $ARMAID;
            $robo->qtdarmas = $QTDARMAS;
            $robo->qtdroda = $QTDRODA;
            $robo->qtdmotor = $QTDMOTOR;
            $robo->tipomotor = $TIPOMOTOR;
            $robo->tiporoda = $TIPORODA;
            $robo->carenagem = $CARENAGEM;

            array_push($robos, $robo);
        }

        return $robos;
    }

    // update the product
    function update($email){
        // sanitize
        $this->categoriaid=htmlspecialchars(strip_tags($this->categoriaid));
        $this->armaid=htmlspecialchars(strip_tags($this->armaid));
        $this->qtdarmas = $this->qtdarmas ? htmlspecialchars(strip_tags($this->qtdarmas)) : 0;
        $this->qtdroda = $this->qtdroda ? htmlspecialchars(strip_tags($this->qtdroda)) : 0;
        $this->qtdmotor= $this->qtdmotor ? htmlspecialchars(strip_tags($this->qtdmotor)) : 0;
        $this->tipomotor = htmlspecialchars(strip_tags($this->tipomotor));
        $this->tiporoda = htmlspecialchars(strip_tags($this->tiporoda));
        $this->carenagem = htmlspecialchars(strip_tags($this->carenagem));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $email = htmlspecialchars(strip_tags($email));

        //INSERE HISTORICO NA TABELA DE HISTORICO
        $sql = "INSERT INTO
                    robo_historico
                    (roboid, nome, categoriaid, equipeid, armaid,
                    qtdarmas, qtdroda, qtdmotor, tipomotor,
                    tiporoda, carenagem, email) (SELECT robo.*, :email from robo WHERE ID = :roboid)";
        
        $stmt= $this->conn->prepare($sql);

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':roboid', $this->id);

        if(!$stmt->execute()) {
            throw new Exception(var_dump($stmt->errorInfo()));
        }

        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    CATEGORIAID = :categoriaid,
                    ARMAID = :armaid,
                    QTDARMAS = :qtdarmas,
                    QTDRODA = :qtdroda,
                    QTDMOTOR = :qtdmotor,
                    TIPOMOTOR = :tipomotor,
                    TIPORODA = :tiporoda,
                    CARENAGEM = :carenagem
                WHERE
                    id = :id";

    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind new values
        $stmt->bindParam(':categoriaid', $this->categoriaid);
        $stmt->bindParam(':armaid', $this->armaid);
        $stmt->bindParam(':qtdarmas', $this->qtdarmas);
        $stmt->bindParam(':qtdroda', $this->qtdroda);
        $stmt->bindParam(':qtdmotor', $this->qtdmotor);
        $stmt->bindParam(':tipomotor', $this->tipomotor);
        $stmt->bindParam(':tiporoda', $this->tiporoda);
        $stmt->bindParam(':carenagem', $this->carenagem);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if(!$stmt->execute()) {
            throw new Exception($stmt->debugDumpParams());
        }
    }
}
