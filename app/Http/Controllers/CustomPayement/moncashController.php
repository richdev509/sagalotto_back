<?php

namespace App\Http\Controllers\CustomPayement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services\CustomPaymentService;


class moncashController extends Controller
{

    public function configMoncash($amount,$orderId) {
    
        
        $getMoncash = new CustomPaymentService;
        $getMoncash->clientId = env('CUSTOM_PAYMENT_API_KEY');;
        $getMoncash->clientSecret =env('CUSTOM_PAYMENT_API_SECRET');
        $getMoncash->isLive = true;
        
        $getMoncash->orderId  = $orderId;
        $getMoncash->amount = $amount;
        
        $getMoncash->generateToken();
        $sendPayment = $getMoncash->postPayment();
        
        return redirect($sendPayment);
        
        }

       public  function returnUrl($transactionId) {
           
           
            $getMoncash = new CustomPaymentService;
            $getMoncash->clientId = env('CUSTOM_PAYMENT_API_KEY');;
            $getMoncash->clientSecret =env('CUSTOM_PAYMENT_API_SECRET');
            $getMoncash->isLive = true;
        
        
            $getToken = $getMoncash->generateToken();
            $returnDetails =  $getMoncash->getDetailsOfTransaction($transactionId);
            $getStatut = $returnDetails["status"];
            $getReference=$returnDetails["payment"]["reference"];
            $getTel=$returnDetails["payment"]["payer"];
            $getCost=$returnDetails["payment"]["cost"]; 
        
            $code_14 = substr($getReference, 0, 14);
            $compagnieid=$code_14;
            $referencea=uniqid();
            
            $maji=$referencea;
            $resulto=$this->save($getTel,$getCost,$referencea,$transactionId,$compagnieid,$getStatut,session('id'));
             if(!$resulto){
               //redirection
               notify()->success('Abonnement effectuer success');
             //return redirect()->route('tankyou');
             }
        
        }
        
        
       public  function save($payer,$montant,$referencea,$transactionId,$compagnieid,$statut,$user){
            $id=uniqid();
            
            
        }




    
}
