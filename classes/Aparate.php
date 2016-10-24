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

    public function set_cititor_bit13($params, $page) {
        $idAparat    = $params['idAparat'];
        $seria = $params['seria'];
        $valoare = $params['valoare'];
        $aparatul = $this->db->getRows('stareaparate', 'bitiComanda', 'WHERE idAparat=?', array($idAparat));
        $bitiComanda = $aparatul[0]['bitiComanda'];
        $bitiComandaTo32bitiArray = $page->bitiComandaTo32bitiArray($bitiComanda);
        $bitiComandaTo32bitiArray[18] = $valoare;
        $bitiComandaTo32bitiArray[28] = 1;
        $bitComanda_nou = bindec(implode('', $bitiComandaTo32bitiArray));
        $updated = $this->db->updateRow('stareaparate', 'bitiComanda=?, stareRetur=1', 'WHERE idAparat=?', array($bitComanda_nou, $idAparat));
        $out = ($valoare == 1) ? 'ON cititor' : 'OFF cititor';
        $valoare_noua = ($valoare == 1) ? 0 : 1;
        $err = ($valoare == 1) ? 'btn-success' : 'btn-danger';
        return array($out, $valoare_noua, $err);
    }

    public function get_record_bit12($params, $page) {
        $idAparat    = $params['idAparat'];
        $seria = $params['seria'];
        $valoare = $params['valoare'];
        $aparatul = $this->db->getRows('stareaparate', 'bitiComanda', 'WHERE idAparat=?', array($idAparat));
        $bitiComanda = $aparatul[0]['bitiComanda'];
        $bitiComandaTo32bitiArray = $page->bitiComandaTo32bitiArray($bitiComanda);
        $bitiComandaTo32bitiArray[19] = 1;
        $bitComanda_nou = bindec(implode('', $bitiComandaTo32bitiArray));
        $updated = $this->db->updateRow('stareaparate', 'bitiComanda=?, stareRetur=1', 'WHERE idAparat=?', array($bitComanda_nou, $idAparat));
        $out = ($updated == 1) ? 'Descarcat cu succes!' : 'Eroare la descarcare!';
        return array($out, $out, $updated);
    }
}

