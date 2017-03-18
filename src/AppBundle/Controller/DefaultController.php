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
            $idCommercant = intval(rtrim($this->decrypter($item['idCommercant']), "\0"));
            $transaction = new Transaction();
            $transaction->setId(intval(rtrim($this->decrypter($item['id']), "\0")));
            $transaction->setDate(rtrim($this->decrypter($item['date'])), "\0");
            $transaction->setMontant(floatval(rtrim($this->decrypter($item['montant']), "\0")));
            $transaction->setNomCommercant(rtrim($this->decrypter($item['nomCommercant'])), "\0");
            $transaction->setIbanCommercant(rtrim($this->decrypter($item['ibanCommercant'])), "\0");
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
        $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $ciphertext_dec = base64_decode($data);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);
        return $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
    }
}
