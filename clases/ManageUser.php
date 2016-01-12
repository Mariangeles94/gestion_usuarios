<?php

class ManageUser {
    private $bd = null;
    private $tabla = "usuario";

    function __construct(DataBase $bd) {
        $this->bd = $bd;
    }

    function get($email) {
        //devuelve un objeto de la clase 
        $parametros = array();
        $parametros['email'] = $email;
        $this->bd->select($this->tabla, "*", "email=:email", $parametros);
        $fila = $this->bd->getRow();
        $user = new User();
        $user->set($fila);
        return $user;
    }

    function count($condicion = "1 = 1", $parametros = array()) {
        return $this->bd->count($this->tabla, $condicion, $parametros);
    }

    function delete($email) {
        $parametros = array();
        $parametros['email'] = $email;
        return $this->bd->delete($this->tabla, $parametros);
    }

    function deleteCities($parametros) {
        return $this->bd->delete($this->tabla, $parametros);
    }

    function erase(User $user) {
        return $this->delete($user->getEmail());
    }
    
   function set(User $user, $pkemail){
        //Update de todos los campos menos el id, el id se usara como el where para el update numero de filas modificadas
        $parametros =$user->getArray();
        $parametrosWhere = array();
        $parametrosWhere["email"]=$pkemail;
        return $this->bd->update($this->tabla, $parametros, $parametrosWhere);
    }
     function set2(User $user,$activo){
        //Update de todos los campos menos el id, el id se usara como el where para el update numero de filas modificadas
        $parametros =$user->getArray();
        $parametrosWhere = array();
        $parametrosWhere["activo"]=$activo;
        return $this->bd->update($this->tabla, $parametros, $parametrosWhere);
    }

    function insert(User $user) {
        //Se pasa un objeto city y se inserta, se devuelve el id del elemento con el que se ha insertado
        $parametrosSet = array();
        $parametrosSet['clave'] = $user->getClave();
        $parametrosSet['alias'] = $user->getAlias();
        $parametrosSet['fechaAlta'] = $user->getFechaAlta();
        $parametrosSet['activo'] = $user->getActivo();
        $parametrosSet['administrador'] = $user->getAdministrador();
        $parametrosSet['personal'] = $user->getPersonal();
        return $this->bd->insert($this->tabla, $parametrosSet);
    }

    function getList($pagina = 1, $order = "", $nrpp = Constant::NRPP, $condicion = "1=1", $parametros = array()) {
        /*$ordenPredeterminado = "$order, Name, CountryCode, ID";
        if ($order=== "" || $order === null) {
            $ordenPredeterminado = "Name, CountryCode, ID";
        }*/
        $resgistroInicial = ($pagina - 1) * $nrpp;
        $this->bd->select($this->tabla, "*", $condicion, $parametros, $ordenPredeterminado, "$resgistroInicial, $nrpp"); /* limite va desde 1 y saca 10 registros */
        $r = array();
        while ($fila = $this->bd->getRow()) {
            $user = new User();
            $user->set($fila);
            $r[] = $user;
        }
        return $r;
    }

    function getValuesSelectTodos() {
        $this->bd->query($this->tabla, "*", array());
        $array = array();
        while ($fila = $this->bd->getRow()) {
            $array[$fila[0]] = $fila[1];
        }
        return $array;
    }
    function getValuesSelectUsario() {
        $this->bd->query($this->tabla, "email, clave, alias", array(), "email");
        $array = array();
        while ($fila = $this->bd->getRow()) {
            $array[$fila[0]] = $fila[1];
        }
        return $array;
    }
    function getValuesSelectPersonal() {
        $this->bd->query($this->tabla, "clave, alias, fechaAlta", array(), "fechaAlta");
        $array = array();
        while ($fila = $this->bd->getRow()) {
            $array[$fila[0]] = $fila[1];
        }
        return $array;
    }
}
