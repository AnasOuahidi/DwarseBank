<?php

namespace AppBundle\Entity;

class Transaction {

    private $id;

    private $date;

    private $montant;

    private $nomCommercant;

    private $idCommercant;

    private $ibanCommercant;

    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    public function getDate() {
        return $this->date;
    }

    public function setMontant($montant) {
        $this->montant = $montant;

        return $this;
    }

    public function getMontant() {
        return $this->montant;
    }

    public function setNomCommercant($nomCommercant) {
        $this->nomCommercant = $nomCommercant;

        return $this;
    }

    public function getNomCommercant() {
        return $this->nomCommercant;
    }

    public function setIdCommercant($idCommercant) {
        $this->idCommercant = $idCommercant;

        return $this;
    }

    public function getIdCommercant() {
        return $this->idCommercant;
    }

    public function setIbanCommercant($ribCommercant) {
        $this->ibanCommercant = $ribCommercant;

        return $this;
    }

    public function getIbanCommercant() {
        return $this->ibanCommercant;
    }
}
