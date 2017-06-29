<?php

require_once(__DIR__.'/../models/Square.php');

Class SquaresController {
    public static function create(){
        if(!empty($_POST['square'])) {
            try {
                $square = new Square($_POST['square']);
                $square->insert();
                $_SESSION['msg'] = 'success"><i class="fa fa-check" fa-3x aria-hidden="true"></i>&nbsp Ação realizado com sucesso.';
            } catch (PDOException $e) {
                $_SESSION['msg'] = 'fail"><i class="fa fa-times" fa-3x aria-hidden="true"></i>&nbsp Erro ao realizar ação.';
                var_dump($e);
                die();
            }
        } else {
            $_SESSION['msg'] = 'fail"><i class="fa fa-times" fa-3x aria-hidden="true"></i>&nbsp Erro ao realizar ação.';
        }
        header('Location:../views/index.php');
    }

    public static function update() {
        if (!empty($_POST['square'])) {
            try {
                if($_POST['changeallname']) {
                    $squares = self::getByColor($_POST['square']['color']);
                    foreach ($squares as $square) {
                        $array = (array) $square;
                        $obj = new Square($array);
                        $obj->name = $_POST['square']['name'];
                        $obj->update($array['id']);
                    }
                }
                $square = new Square($_POST['square']);
                $square->update($_POST['square']['id']);
                
                $_SESSION['msg'] = 'success"><i class="fa fa-check" fa-3x aria-hidden="true"></i>&nbsp Ação ralizada com sucesso.';
            } catch (PDOException $e) {
                $_SESSION['msg'] = 'fail"><i class="fa fa-times" fa-3x aria-hidden="true"></i>&nbsp Não foi possível realizar esta ação.';
                var_dump($e);
                die();
            }
        } else {
            $_SESSION['msg'] = 'fail"><i class="fa fa-times" fa-3x aria-hidden="true"></i>&nbsp Não foi possível realizar esta ação.';
        }
        header('Location: ../views/index.php');
    }

    public static function updatePosition() {
        if (!empty($_GET['position'])) {
            try {
                $obj = self::select($_GET['id']);
                $array = (array)$obj;
                $array['position'] = $_GET['position'];

                $square = new Square($array);
                $square->update($_GET['id']);
                $_SESSION['msg'] = 'success"><i class="fa fa-check" fa-3x aria-hidden="true"></i>&nbsp Ação ralizada com sucesso.';
                return "Sucesso";
            } catch (PDOException $e) {
                $_SESSION['msg'] = 'fail"><i class="fa fa-times" fa-3x aria-hidden="true"></i>&nbsp Não foi possível realizar esta ação.';
                return $e;
            }
        } else {
            $_SESSION['msg'] = 'fail"><i class="fa fa-times" fa-3x aria-hidden="true"></i>&nbsp Não foi possível realizar esta ação.';
        }
        header('Location: ../views/index.php');
    }

    public static function remove() {
        if (!empty($_GET['remove'])) {
            try {
                Square::delete($_GET['remove']);
                $_SESSION['msg'] = 'success"><i class="fa fa-check" fa-3x aria-hidden="true"></i>&nbsp Ação ralizada com sucesso.';
            } catch (PDOException $e) {
                 $_SESSION['msg'] = 'fail"><i class="fa fa-times" fa-3x aria-hidden="true"></i>&nbsp Não foi possível realizar esta ação.';
            }
        } else {
            $_SESSION['msg'] = 'fail"><i class="fa fa-times" fa-3x aria-hidden="true"></i>&nbsp Não foi possível realizar esta ação.';
        }
        header('Location:../views/index.php');
    }

    public static function selectAll() {
        return Square::selectAll();
    }

    public static function select($id) {
        return Square::select($id);
    }

    public static function getByPosition($position) {
        return Square::getByPosition($position);
    }

    public static function getByColor($color) {
        return Square::getByColor($color);
    }
}

$postActions = ['create','update'];
$getActions = ['remove'];

if (!empty($_POST['action']) && in_array($_POST['action'], $postActions)) {
    $action = $_POST['action'];
    SquaresController::$action();
} else if (!empty(key($_GET)) &&  key($_GET) == "remove") {
    SquaresController::remove();
} else if(isset($_GET['position'])) {
    echo json_encode(SquaresController::updatePosition());
}
