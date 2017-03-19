<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transaction;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Post("/transactions")
     */
    public function indexAction(Request $request) {
        $transactions = $request->request->all()['entry'];
        $transactionsArray = [];
        $idsCommercantsArray = [];
        foreach ($transactions as $item) {
            $idCommercant = intval($this->decrypter($item['idCommercant']));
            $transaction = new Transaction();
            $transaction->setId(intval($this->decrypter($item['id'])));
            $transaction->setDate($this->decrypter($item['date']));
            $transaction->setMontant(floatval($this->decrypter($item['montant'])));
            $transaction->setNomCommercant($this->decrypter($item['nomCommercant']));
            $transaction->setIbanCommercant($this->decrypter($item['ibanCommercant']));
            $transaction->setIdCommercant($idCommercant);
            $transactionsArray[] = $transaction;
            if (!in_array($idCommercant, $idsCommercantsArray)) {
                $idsCommercantsArray[] = $idCommercant;
            }
        }
        $returnedTransactions = [];
        $i = 0;
        foreach ($idsCommercantsArray as $idCommercant) {
            $returnedTransactions[$i] = ["id" => $idCommercant, "solde" => 0];
            foreach ($transactionsArray as $transaction) {
                if ($transaction->getIdCommercant() == $idCommercant) {
                    $returnedTransactions[$i]["solde"] += $transaction->getMontant();
                }
            }
            $i++;
        }
        return $returnedTransactions;
    }

    private function decrypter($data) {
        $maCleDeCryptage = md5("bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
        $letter = -1;
        $newstr = "";
        $maChaineCrypter = base64_decode($data);
        $strlen = strlen($maChaineCrypter);
        for ($i = 0; $i < $strlen; $i++) {
            $letter++;
            if ($letter > 31) {
                $letter = 0;
            }
            $neword = ord($maChaineCrypter{$i}) - ord($maCleDeCryptage{$letter});
            if ($neword < 1) {
                $neword += 256;
            }
            $newstr .= chr($neword);
        }
        return $newstr;
    }
}
