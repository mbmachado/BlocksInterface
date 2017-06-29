<?php

require_once(__DIR__.'/../helpers/Connection.php');

Class Square {
    public $name;
    public $number;
    public $color;
    private $position;
    

    public function __construct($attributes) {
        $this->name = (empty($attributes['name']))? null: $attributes['name'];
        $this->number = (empty($attributes['number']))? null: $attributes['number'];
        $this->color = (empty($attributes['color']))? null: $attributes['color'];
        $this->position = (empty($attributes['position']))? null: $attributes['position'];
    }

    public function insert() {
        $connect = Connection::connect();
        $stm = $connect->prepare("INSERT INTO squares(name, number, color, position) VALUES(:name, :number, :color, :position)");
        $stm->BindValue(":name", $this->name, PDO::PARAM_STR);
        $stm->BindValue(":number", $this->number, PDO::PARAM_INT);
        $stm->BindValue(":color", $this->color, PDO::PARAM_STR);
        $stm->BindValue(":position", $this->position, PDO::PARAM_INT);
        return $stm->execute();
    }

    public function update($id) {
        $connect = Connection::connect();
        $stm = $connect->prepare("UPDATE squares SET name = :name, number = :number, color = :color, position = :position WHERE id = :id");
        $stm->BindValue(":name", $this->name, PDO::PARAM_STR);
        $stm->BindValue(":number", $this->number, PDO::PARAM_INT);
        $stm->BindValue(":color", $this->color, PDO::PARAM_STR);
        $stm->BindValue(":position", $this->position, PDO::PARAM_INT);
        $stm->BindValue(":id", $id, PDO::PARAM_INT);
        return $stm->execute();
    }

    public static function selectAll() {
        $connect = Connection::connect();
        $stm = $connect->query("SELECT * FROM squares ORDER BY id DESC");
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    public static function select($id) {
        $connect = Connection::connect();
        $stm = $connect->prepare("SELECT * FROM squares WHERE id = :id LIMIT 1");
        $stm->BindValue(":id",$id, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetch(PDO::FETCH_OBJ);
    }

    public static function delete($id) {
        $connect = Connection::connect();
        $stm = $connect->prepare("DELETE FROM squares WHERE id = :id");
        $stm->BindValue(":id",$id, PDO::PARAM_INT);
        return $stm->execute();
    }

    public static function getByPosition($position) {
        $connect = Connection::connect();
        $stm = $connect->prepare("SELECT * FROM squares WHERE position = :position LIMIT 1");
        $stm->BindValue(":position",$position, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetch(PDO::FETCH_OBJ);
    }

    public static function getByColor($color) {
        $connect = Connection::connect();
        $stm = $connect->prepare("SELECT * FROM squares WHERE color = :color");
        $stm->BindValue(":color",$color, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
    } 
}
