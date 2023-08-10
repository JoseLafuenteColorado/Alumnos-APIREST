<?php

namespace App\Routes;

class AlumnoGateway {

    private $db = null;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll() {
        $statement = "
            SELECT 
                id, dni, nombre, apellidos, telefono, foto
            FROM
                alumnos;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id) {
        $statement = "
            SELECT 
            id, dni, nombre, apellidos, telefono, foto
            FROM
                alumnos
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function insert(Array $input) {
        $statement = "
            INSERT INTO alumnos 
                (dni, nombre, apellidos, telefono)
            VALUES
                (:dni, :nombre, :apellidos, :telefono);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'dni' => $input['dni'],
                'nombre'  => $input['nombre'],
                'apellidos' => $input['apellidos'],
                'telefono' => $input['telefono'],
                // 'foto' => $input['foto'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
        
    }
    
    public function update($id, Array $input) {
        $statement = "
            UPDATE alumnos
            SET dni = :dni,
                nombre  = :nombre,
                apellidos = :apellidos,
               telefono = :telefono
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'dni' => $input['dni'],
                'nombre'  => $input['nombre'],
                'apellidos' => $input['apellidos'],
                'telefono' => $input['telefono'],
                //'foto' => $input['foto'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function updateImage($id, Array $input) {
        $statement = "
            UPDATE alumnos
            SET foto = :foto
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'foto' => $input['foto'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id) {
        $statement = "
            DELETE FROM alumnos
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}

?>