<?php
class IncasareEntity {
    private $serie_aparat;
    private $indexInceputI;
    private $indexInceputEJ;
    private $indexInceputEI;
    private $indexSfarsitI;
    private $indexSfarsitEJ;
    private $indexSfarsitEI;
    private $diferentaI;
    private $diferentaEJ;
    private $diferentaEI;
    private $sold;
    private $pretImpuls;
    private $taxa;
    private $totalPlati;
    private $incasari;


    public function get_serie_aparat() {
        return $this->serie_aparat;
    }

    public function get_indexInceputI() {
        return $this->indexInceputI;
    }

    public function get_indexInceputEJ() {
        return $this->indexInceputEJ;
    }

    public function get_indexInceputEI() {
        return $this->indexInceputEI;
    }

    public function get_indexSfarsitI() {
        return $this->indexSfarsitI;
    }

    public function get_indexSfarsitEJ() {
        return $this->indexSfarsitEJ;
    }

    public function get_indexSfarsitEI() {
        return $this->indexSfarsitEI;
    }

    public function get_diferentaI() {
        return ($this->get_indexSfarsitI() - $this->get_indexInceputI());
    }

    public function get_diferentaEJ() {
        return ($this->get_indexSfarsitEJ() - $this->get_indexInceputEJ());
    }

    public function get_diferentaEI() {
        return ($this->get_indexSfarsitEI() - $this->get_indexInceputEI());
    }
    
    public function get_sold() {
        return ($this->get_diferentaI() - $this->get_indexSfarsitEJ() - $this->get_diferentaEI() );
    }

    public function set_pretImpuls($impuls) {
        $this->pretImpuls = $impuls;
    }

    public function get_pretImpuls($impuls) {
        return $this->pretImpuls;
    }

    public function get_taxa() {
        return ($this->get_diferentaI() * $this->get_pretImpuls());
    }

    public function get_totalPlati() {
        return ($this->get_diferentaEI() * $this->get_pretImpuls());
    }

    public function get_incasari() {
        return ($this->get_sold() * $this->get_pretImpuls());
    }

    public function exchangeArray($data){
        $this->serie_aparat     = isset($data['serie_aparat']) ? $data['serie_aparat'] : NULL;
        $this->indexInceputI    = isset($data['indexInceputI']) ? $data['indexInceputI'] : NULL;
        $this->indexInceputEJ   = isset($data['indexInceputEJ']) ? $data['indexInceputEJ'] : NULL;
        $this->indexInceputEI   = isset($data['indexInceputEI']) ? $data['indexInceputEI'] : NULL;
        $this->indexSfarsitI    = isset($data['indexSfarsitI']) ? $data['indexSfarsitI'] : NULL;
        $this->indexSfarsitEJ   = isset($data['indexSfarsitEJ']) ? $data['indexSfarsitEJ'] : NULL;
        $this->indexSfarsitEI   = isset($data['indexSfarsitEI']) ? $data['indexSfarsitEI'] : NULL;
        $this->factorI          = isset($data['factorI']) ? $data['factorI'] : NULL;
        $this->factorEJ         = isset($data['factorEJ']) ? $data['factorEJ'] : NULL;
        $this->factorEI         = isset($data['factorEI']) ? $data['factorEI'] : NULL;
        $this->diferentaI       = $this->get_diferentaI();
        $this->diferentaEJ      = $this->get_diferentaEJ();
        $this->diferentaEI      = $this->get_diferentaEI();
        $this->sold             = $this->get_sold();
        $this->pretImpuls       = isset($data['pretImpuls']) ? $data['pretImpuls'] : NULL;
        $this->sold             = $this->get_taxa();
        $this->sold             = $this->get_totalPlati();
        $this->sold             = $this->get_incasari();
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }
}