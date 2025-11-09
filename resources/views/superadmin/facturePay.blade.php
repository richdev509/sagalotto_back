<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='https://www.w3.org/1999/xhtml'>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: #333333;
            background-color: #FFFFFF;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        #table-container {
            width: 595px;
            margin: 0 auto;
            border: 1px solid #e0e0e0;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: white;
        }
        .header-logo {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .header-logo h2 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #7f8c8d;
        }
        h3 {
            color: #2c3e50;
            margin: 10px 0;
            text-align: center;
            font-size: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #e0e0e0;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #f1f8fe;
        }
        .header-info {
            margin-bottom: 20px;
        }
        .payment-methods {
            margin-top: 30px;
        }
        .payment-methods td {
            border-top: 2px solid #e0e0e0;
            padding-top: 15px;
        }
        .btn {
            display: block;
            width: 250px;
            margin: 20px auto;
            padding: 10px;
            background-color: #3498db;
            color: white;
            text-align: center;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        .invoice-number {
            font-size: 18px;
            color: #e74c3c;
            text-align: center;
            margin: 15px 0;
        }
        .note {
            font-style: italic;
            color: #7f8c8d;
            padding: 10px 0;
            border-top: 1px dashed #e0e0e0;
            margin-top: 20px;
        }
        .currency {
            font-weight: bold;
            color: #27ae60;
        }
    </style>
</head>
<body>
    <?php
    // Month names in French
    $monthNames = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 
        4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
        7 => 'Juillet', 8 => 'Août', 9 => 'Septembre',
        10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ];

    // Always initialize $plan to the company's base plan to avoid undefined variable
    $plan = $compagnie->plan;

    if ($compagnie->plan == 10) {
        if ($vendeur >= 1 && $vendeur <10) {
            $plan = 10;
        } elseif ($vendeur >= 10 && $vendeur < 20) {
            $plan = 9;
        } elseif ($vendeur >= 20 && $vendeur <30 ) {
            $plan = 8;
        } elseif ($vendeur >= 30 && $vendeur <50) {
            $plan = 7;
        } elseif ($vendeur >= 50 && $vendeur < 10000) {
            $plan = 6;
        }
    }
    
    // Calculate dates with month names
    $currentDay = date('d', strtotime($date));
    $currentMonth = date('n', strtotime($date));
    $currentYear = date('Y', strtotime($date));
    
    $nextMonth = $currentMonth + 1;
    $nextYear = $currentYear;
    
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear++;
    }
    
    // Handle cases where the day doesn't exist in next month
    $lastDayNextMonth = date('t', mktime(0, 0, 0, $nextMonth, 1, $nextYear));
    $dayToUse = min($currentDay, $lastDayNextMonth);
    
    // Format dates with month names
    $formattedDate = $currentDay . ' ' . $monthNames[$currentMonth] . ' ' . $currentYear;
    $dateexpiration = $dayToUse . ' ' . $monthNames[$nextMonth] . ' ' . $nextYear;
    ?>
    
    <table id="table-container" align='center' border='0' cellpadding='0' cellspacing='0'>
        <tr>
            <td valign='top'>
                <!-- Header with sagaloto.com logo -->
                <div class="header-logo">
                    <h2>sagaloto.com</h2>
                </div>

                <div class="header-info">
                    <table width='100%' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td valign='top' width='60%'>
                                <span class="company-name">[{{ $compagnie->name }}]</span><br />
                                [{{ $compagnie->phone }}, {{ $compagnie->address }}]<br />
                            </td>
                            <td valign='top' width='40%' style='text-align: right;'>
                                <strong>Date de facturation:</strong><br> <?php echo $formattedDate; ?><br />
                                <strong>Période:</strong><br> <?php echo $formattedDate; ?> au <?php echo $dateexpiration; ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="invoice-number">
                    Facture #{{ $monthNames[$currentMonth] }}
                </div>

                <table width='100%' cellspacing='0' cellpadding='2' border='1' bordercolor='#e0e0e0'>
                    <tr>
                        <th width='35%'>Désignation</th>
                        <th>Quantité</th>
                        <th>Prix</th>
                        <th>Total</th>
                    </tr>

                    <tr style="display:none;">
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td valign='top'>Bank actif</td>
                        <td valign='top'>{{ $vendeur }}</td>
                        <td valign='top'>{{ $plan }} <span class="currency">$</span></td>
                        <td valign='top'>{{ $vendeur * $plan }} <span class="currency">$</span></td>
                    </tr>

                    <tr>
                        <td valign='top'>Taux: 1$ = 133 HTG</td>
                        <td valign='top'>&nbsp;</td>
                        <td valign='top'>&nbsp;</td>
                        <td valign='top'>{{ $vendeur * $plan * 133 }} <span class="currency">HTG</span></td>
                    </tr>

                    <!-- Empty rows for spacing -->
                    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                </table>

                <table width='100%' cellspacing='0' cellpadding='2' border='0'>
                    <tr>
                        <td style='width:50%;'></td>
                        <td>
                            <table width='100%' cellspacing='0' cellpadding='2' border='0'>
                                <tr>
                                    <td align='right'>Total</td>
                                    <td align='right'>{{ $vendeur * $plan }} <span class="currency">$</span> ou {{$vendeur * $plan * 133}} <span class="currency">HTG</span></td>
                                </tr>
                                <tr class="total-row">
                                    <td align='right'><b>Total à payer</b></td>
                                    <td align='right'><b>{{ $vendeur * $plan }} <span class="currency">$</span> ou {{$vendeur * $plan * 133}} <span class="currency">HTG</span></b></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <div class="note">
                    NB: Pour toutes méthodes de paiement, veuillez contacter l'administration.
                </div>

                <table width='100%' cellspacing='0' cellpadding='2' class="payment-methods">
                    <tr>
                        <td width='33%' valign='top'>
                            <b>[Moncash]</b><br />
                            48698274 (Rosalvo Ricardo)<br />
                        </td>
                        <td width='33%' align='center' valign='top'>
                            <b> [Natcash] </b><br />
                            55175521 (Rosalvo Ricardo)<br />
                        </td>
                        <td valign='top' width='34%' style='display: none;' align='right'>
                            [Nom de la banque]<br /> 
                            [Compte bancaire (IBAN)] <br />
                            SWIFT/BIC: [SWIFT/BIC] <br />
                        </td>
                    </tr>
                </table>

                <!-- Footer with copyright -->
                <div class="footer">
                    &copy; <?php echo date('Y'); ?> sagaloto.com - Tous droits réservés
                </div>
            </td>
        </tr>
    </table>

    <button onclick="captureTable()" class="btn">Télécharger la Facture en Image</button>

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