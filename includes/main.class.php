<?php
	/**
	* 	CLASA pentru HOMEPAGE
	*/
	class Home {
		private $db;
		function __construct($datab) {
			$this->db = $datab;
		}
		public function get_istoric_aparate($params) {
			$idAparat = $params['idAparat'];
			$query = "SELECT 
					a.idAparat,
				    a.idLocVechi,
				    a.idLocatie,
				    a.pozitieLocatie,
				    a.idxInM,
				    a.idxOutM,
				    a.tipJocMetrologii,
				    a.dtActivare,
				    a.dtBlocare,
				    l_v.denumire AS loc_vechi_denumire,
				    l_v.regiune AS loc_vechi_reg,
				    l_v.localitate AS loc_vechi_loc,
				    l_v.adresa AS loc_vechi_adresa,
				    l_n.denumire AS loc_nou_denumire,
				    l_n.regiune AS loc_nou_reg,
				    l_n.localitate AS loc_nou_loc,
				    l_n.adresa AS loc_nou_adresa
				FROM
				    aparate a
				        INNER JOIN
				    locatii l_v ON l_v.idlocatie = a.idLocVechi
				        INNER JOIN
				    locatii l_n ON l_n.idlocatie = a.idLocatie
				WHERE
				    a.seria = ?
				ORDER BY a.idAparat DESC";
			$stmt = $this->db->datab->prepare($query);
			$stmt->execute(array($idAparat));
            $rows = $stmt->fetchAll();  
            return $rows;
		}
	}
?>