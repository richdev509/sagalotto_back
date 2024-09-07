<!DOCTYPE html
    PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='https://www.w3.org/1999/xhtml'>

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

<body style='font-family:Tahoma;font-size:12px;color: #333333;background-color:#FFFFFF;'>
    <table align='center' border='0' cellpadding='0' cellspacing='0' style='height:842px; width:595px;font-size:12px;'>
        <tr>
            <td valign='top'>
                <table width='100%' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td valign='bottom' width='25%' height='25'>
                            <div align='left'><img
                                    src='https://sagaloto.com/assets/mages/logo.png' />
                            </div><br />
                        </td>

                        <td width='50%'>&nbsp;</td>
                    </tr>
                </table><h3>Sagaloto.com</h3><br /><br />
                <table width='100%' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td valign='top' width='35%' style='font-size:12px;'> <strong>[{{$compagnie->name}}]</strong><br />
                            [{{$compagnie->phone}}, {{$compagnie->address}}] <br />

                        </td>
                        <td valign='top' width='35%'>
                        </td>
                        <td valign='top' width='30%' style='font-size:12px;'>Date de facturation: {{$date}} <br />
                             


                        </td>

                    </tr>
                </table>
                <table width='100%' height='100' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td>
                            <div align='center' style='font-size: 14px;font-weight: bold;'>Facture № 553 </div>
                        </td>
                    </tr>
                </table>
                <table width='100%' cellspacing='0' cellpadding='2' border='1' bordercolor='#CCCCCC'>
                    <tr>

                        <td width='35%' bordercolor='#ccc' bgcolor='#f2f2f2' style='font-size:12px;'>
                            <strong>Désignation </strong></td>
                        <td bordercolor='#ccc' bgcolor='#f2f2f2' style='font-size:12px;'><strong>Quantité</strong></td>
                        <td bordercolor='#ccc' bgcolor='#f2f2f2' style='font-size:12px;'><strong>Prix</strong></td>
                        <td bordercolor='#ccc' bgcolor='#f2f2f2' style='font-size:12px;'><strong>Total</strong></td>

                    </tr>
                    <tr style="display:none;">
                        <td colspan="*">
                    <tr>

                        <td valign='top' style='font-size:12px;'>Bank actif</td>
                        <td valign='top' style='font-size:12px;'>{{$vendeur}}</td>
                        <td valign='top' style='font-size:12px;'>{{$compagnie->plan}} $</td>
                        <td valign='top' style='font-size:12px;'>{{ $vendeur * $compagnie->plan}} $</td>

                    </tr>
                  
                  
                 
               
                    <tr>

                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>

                    </tr>
                    <tr>

                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>

                    </tr>
                    <tr>

                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>

                    </tr>
                    <tr>

                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>

                    </tr>
                    <tr>

                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>
                        <td valign='top' style='font-size:12px;'>&nbsp;</td>

                    </tr>
                   
            </td>
        </tr>
    </table>
    <table width='100%' cellspacing='0' cellpadding='2' border='0'>
        <tr>
            <td style='font-size:12px;width:50%;'><strong> </strong></td>
            <td>
                <table width='100%' cellspacing='0' cellpadding='2' border='0'>
                    <tr>
                        <td align='right' style='font-size:12px;'>Total</td>
                        <td align='right' style='font-size:12px;'>{{ $vendeur * $compagnie->plan}} $
                        <td>
                    </tr>
                    <tr>

                        <td align='right' style='font-size:12px;'><b>Total</b></td>
                        <td align='right' style='font-size:12px;'><b>{{ $vendeur * $compagnie->plan}} $</b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table width='100%' height='50'>
        <tr>
            <td style='font-size:12px;text-align:justify;'>NB: Pour toutes methode de paiement, contactez l'adminisation
            </td>
        </tr>
    </table>
    <table width='100%' cellspacing='0' cellpadding='2'>
        <tr>
            <td width='33%' style='border-top:double medium #CCCCCC;font-size:12px;' valign='top'><b>[Moncash]</b><br />
                48698274 (Rosalvo Ricardo)<br />

            </td>
            <td width='33%' style='border-top:double medium #CCCCCC; font-size:12px;' align='center'
                valign='top'>
               <b> [Natcash] </b><br />
                55175521(Rosalvo Ricardo)<br />
            </td>

            <td valign='top' width='34%' style='border-top:double medium #CCCCCC;font-size:12px; display: none;'
                align='right'>[Nom de la banque]<br /> [Compte bancaire (IBAN)] <br />SWIFT/BIC: [SWIFT/BIC] <br />
            </td>
        </tr>
    </table>
    </td>
    </tr>
    </table>
</body>

</html>
