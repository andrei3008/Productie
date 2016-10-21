<?php
/**
 * Class Aparate
 */
class Aparate {
    private $db;
    public function __construct($databFull) {
        $this->db = $databFull;
    }

    public function updatePozitie($params) {
        $idAparat    = $params['idAparat'];
        $pozitieNoua = $params['pozitieNoua'];
        $updated = $this->db->updateRow('aparate', 'pozitieLocatie=?', 'WHERE idAparat=?', array($pozitieNoua, $idAparat));
        return $updated;
    }

}

