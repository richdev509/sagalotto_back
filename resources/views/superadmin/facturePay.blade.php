<!DOCTYPE html
    PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='https://www.w3.org/1999/xhtml'>

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

<body style='font-family:Tahoma;font-size:12px;color: #333333;background-color:#FFFFFF;'>
    <?php
    if ($compagnie->plan == 10) {
        if ($vendeur >= 1 && $vendeur <= 10) {
            $plan = 10;
        } elseif ($vendeur >= 11 && $vendeur <= 20) {
            $plan = 9;
        } elseif ($vendeur >= 21 && $vendeur <= 30) {
            $plan = 8;
        } elseif ($vendeur >= 31 && $vendeur <= 50) {
            $plan = 7;
        } elseif ($vendeur >= 51 && $vendeur <= 10000) {
            $plan = 6;
        }
    } else {
        $plan = $compagnie->plan;
    }
    
    ?>
    <table  id="table-container" align='center' border='1' cellpadding='0' cellspacing='0'
        style='height:500px;padding: 3px; width:595px;font-size:12px;'>
        <tr>
            <td valign='top'>
                <table width='100%' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td valign='bottom' width='25%' height='25'>
                            <div align='left'>
                            </div><br />
                        </td>

                        <td width='50%'>&nbsp;</td>
                    </tr>
                </table>
                <h3>Sagaloto.com</h3><br /><br />
                <table width='100%' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td valign='top' width='35%' style='font-size:12px;'>
                            <strong>[{{ $compagnie->name }}]</strong><br />
                            [{{ $compagnie->phone }}, {{ $compagnie->address }}] <br />

                        </td>
                        <td valign='top' width='35%'>
                        </td>
                        <td valign='top' width='30%' style='font-size:12px;'>Date de facturation:
                            {{ $date }} <br />



                        </td>

                    </tr>
                </table>
                <table width='100%' height='100' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td>
                            <div align='center' style='font-size: 14px;font-weight: bold;'>Facture №
                                {{ rand(1000, 99999) }} </div>
                        </td>
                    </tr>
                </table>
                <table width='100%' cellspacing='0' cellpadding='2' border='1' bordercolor='#CCCCCC'>
                    <tr>

                        <td width='35%' bordercolor='#ccc' bgcolor='#f2f2f2' style='font-size:12px;'>
                            <strong>Désignation </strong>
                        </td>
                        <td bordercolor='#ccc' bgcolor='#f2f2f2' style='font-size:12px;'><strong>Quantité</strong></td>
                        <td bordercolor='#ccc' bgcolor='#f2f2f2' style='font-size:12px;'><strong>Prix</strong></td>
                        <td bordercolor='#ccc' bgcolor='#f2f2f2' style='font-size:12px;'><strong>Total</strong></td>

                    </tr>

                    <tr style="display:none;">
                        <td colspan="*">
                    <tr>

                        <td valign='top' style='font-size:12px;'>Bank actif</td>
                        <td valign='top' style='font-size:12px;'>{{ $vendeur }}</td>
                        <td valign='top' style='font-size:12px;'>{{ $plan }} $</td>
                        <td valign='top' style='font-size:12px;'>{{ $vendeur * $plan }} $</td>

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
                        <td align='right' style='font-size:12px;'>{{ $vendeur * $plan }} $
                        <td>
                    </tr>
                    <tr>

                        <td align='right' style='font-size:12px;'><b>Total</b></td>
                        <td align='right' style='font-size:12px;'><b>{{ $vendeur * $plan }} $</b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table width='100%' height='50'>
        <tr>
            <td style='font-size:12px;text-align:justify;'>NB: Pour toutes méthodes de paiement, veuillez contacter l'administration.
            </td>
        </tr>
    </table>
    <table width='100%' cellspacing='0' cellpadding='2'>
        <tr>
            <td width='33%' style='border-top:double medium #CCCCCC;font-size:12px;' valign='top'>
                <b>[Moncash]</b><br />
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
    <button onclick="captureTable()" class="btn btn-primary" style="background-color: rgb(20, 20, 246); color:#fff;font-weight:bold;">Download Facture en Image</button>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function captureTable() {
            html2canvas(document.getElementById('table-container')).then(canvas => {
                let link = document.createElement('a');
                link.href = canvas.toDataURL("image/png");
                link.download = "{{ $compagnie->name.'-'.$date}}.png";
                link.click();
            });
        }
    </script>
</body>

</html>
