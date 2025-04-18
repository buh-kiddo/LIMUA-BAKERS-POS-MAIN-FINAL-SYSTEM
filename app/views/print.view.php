<?php 

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{

 		$WshShell = new COM("WScript.Shell");
 		///$obj = $WshShell->Run("cmd /c wscript.exe www/public/file.vbs",0, true); 
 		$obj = $WshShell->Run("cmd /c wscript.exe ".ABSPATH."/file.vbs",0, true); 
 		
 		$WshShell = new COM("WScript.Shell");
 		///$obj = $WshShell->Run("cmd /c wscript.exe www/public/file.vbs",0, true); 
 		$obj = $WshShell->Run("cmd /c wscript.exe ".ABSPATH."/file.vbs",0, true); 
  
 	 
	}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=esc(APP_NAME)?> - Receipt</title>

    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/all.min.css">
    <style>
        @page {
            margin: 0;
            size: 80mm auto;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .receipt-container {
            width: 80mm;
            margin: 0 auto;
            padding: 8px;
            background: #fff;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px dashed #000;
        }

        .company-logo {
            max-width: 120px;
            height: auto;
            margin-bottom: 8px;
        }

        .receipt-header h1 {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
            padding: 3px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .receipt-header .tagline {
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin: 2px 0 4px 0;
        }

        .receipt-header .slogan {
            font-size: 12px;
            color: #666;
            font-style: italic;
            margin: 0 0 8px 0;
        }

        .receipt-header .contact-details {
            font-size: 11px;
            color: #555;
            margin: 5px 0;
            line-height: 1.4;
        }

        .receipt-header .contact-details i {
            width: 14px;
            color: #666;
            margin-right: 3px;
        }

        .receipt-header .receipt-title {
            font-size: 16px;
            font-weight: bold;
            margin: 12px 0 8px 0;
            text-transform: uppercase;
            background: #f8f9fa;
            padding: 5px;
            border-radius: 3px;
        }

        .receipt-header .receipt-info {
            font-size: 12px;
            margin: 3px 0;
            color: #444;
        }

        .receipt-body {
            margin: 15px 0;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .receipt-table th {
            text-align: left;
            padding: 5px;
            border-bottom: 2px solid #ddd;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #666;
        }

        .receipt-table td {
            padding: 8px 5px;
            font-size: 12px;
            border-bottom: 1px dotted #eee;
        }

        .receipt-table .amount {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        .receipt-table .quantity {
            text-align: center;
            font-weight: bold;
        }

        .receipt-totals {
            margin-top: 15px;
            border-top: 2px dashed #000;
            padding-top: 10px;
        }

        .receipt-totals table {
            width: 100%;
            margin-top: 5px;
        }

        .receipt-totals td {
            padding: 4px 5px;
        }

        .receipt-totals .label {
            text-align: right;
            font-weight: normal;
            color: #666;
            font-size: 11px;
            text-transform: uppercase;
        }

        .receipt-totals .value {
            text-align: right;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }

        .grand-total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px dashed #000;
            border-bottom: 2px dashed #000;
            padding: 8px 0;
            margin: 8px 0;
            background: #f8f9fa;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px dashed #000;
        }

        .receipt-footer .thank-you {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .receipt-footer .contact-info {
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .receipt-footer .contact-info p {
            margin: 5px 0;
            font-size: 11px;
            color: #444;
        }

        .receipt-footer .contact-info i {
            width: 20px;
            color: #666;
        }

        .receipt-footer .social-info {
            margin: 10px 0;
            font-size: 11px;
            color: #666;
        }

        .receipt-footer .policy {
            font-size: 10px;
            color: #888;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dotted #ddd;
        }

        .barcode {
            text-align: center;
            margin: 15px 0;
            padding: 10px 0;
            border-top: 1px dotted #ddd;
            border-bottom: 1px dotted #ddd;
        }

        .barcode img {
            max-width: 100%;
            height: auto;
        }

        .receipt-meta {
            font-size: 10px;
            color: #999;
            text-align: center;
            margin-top: 10px;
        }

        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
            }
            
            .receipt-container {
                padding: 5mm;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <?php 
        $vars = $_GET['vars'] ?? "";
        $obj = json_decode($vars,true);
    ?>
    <?php if(is_array($obj)):?>
    <div class="receipt-container">
        <div class="receipt-header">
            <?php if(isset($obj['logo']) && !empty($obj['logo'])): ?>
            <img src="<?=$obj['logo']?>" alt="<?=$obj['company']?>" class="company-logo">
            <?php endif; ?>
            <h1>LIMUA BAKERS</h1>
            <div class="tagline">Professional Cake & Pastry Services</div>
            <div class="slogan">You Crave, We Deliver</div>
            <div class="contact-details">
                <p><i class="fas fa-phone"></i> +254 769 094 030</p>
                <p><i class="fas fa-envelope"></i> info@limuabakers.com</p>
                <p><i class="fas fa-map-marker-alt"></i> Wote, Makueni</p>
            </div>
            <div class="receipt-title">SALES RECEIPT</div>
            <div class="receipt-info">
                <div><strong>Receipt No:</strong> <?=isset($obj['receipt_no']) ? $obj['receipt_no'] : date('Ymd').rand(1000,9999)?></div>
                <div><strong>Date:</strong> <?=date("jS F, Y")?></div>
                <div><strong>Time:</strong> <?=date("h:i A")?></div>
                <?php if(isset($obj['cashier'])): ?>
                <div><strong>Served By:</strong> <?=$obj['cashier']?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="receipt-body">
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th class="quantity">Qty</th>
                        <th>Description</th>
                        <th class="amount">Price</th>
                        <th class="amount">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($obj['data'] as $row):?>
                    <tr>
                        <td class="quantity"><?=$row['qty']?></td>
                        <td><?=$row['description']?></td>
                        <td class="amount">KES <?=number_format($row['amount'],2)?></td>
                        <td class="amount">KES <?=number_format($row['qty'] * $row['amount'],2)?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>

            <div class="receipt-totals">
                <table>
                    <tr>
                        <td colspan="2"></td>
                        <td class="label">Subtotal</td>
                        <td class="value">KES <?=number_format(floatval(str_replace(['KES', ','], '', $obj['gtotal'])),2)?></td>
                    </tr>
                    <?php if(isset($obj['tax']) && $obj['tax'] > 0): ?>
                    <tr>
                        <td colspan="2"></td>
                        <td class="label">VAT (16%)</td>
                        <td class="value">KES <?=number_format($obj['tax'],2)?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if(isset($obj['discount']) && $obj['discount'] > 0): ?>
                    <tr>
                        <td colspan="2"></td>
                        <td class="label">Discount</td>
                        <td class="value">KES <?=number_format($obj['discount'],2)?></td>
                    </tr>
                    <?php endif; ?>
                    <tr class="grand-total">
                        <td colspan="2"></td>
                        <td class="label">TOTAL</td>
                        <td class="value">KES <?=number_format(floatval(str_replace(['KES', ','], '', $obj['gtotal'])),2)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td class="label">Amount Paid</td>
                        <td class="value">KES <?=number_format(floatval(str_replace(['KES', ','], '', $obj['amount'])),2)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td class="label">Change</td>
                        <td class="value">KES <?=number_format(floatval(str_replace(['KES', ','], '', $obj['change'])),2)?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="receipt-footer">
            <div class="thank-you">Thank You for Your Business!</div>
            
            <div class="contact-info">
                <?php if(isset($obj['phone'])): ?>
                <p><i class="fas fa-phone"></i> <?=$obj['phone']?></p>
                <?php endif; ?>
                
                <?php if(isset($obj['address'])): ?>
                <p><i class="fas fa-map-marker-alt"></i> <?=$obj['address']?></p>
                <?php endif; ?>
                
                <?php if(isset($obj['email'])): ?>
                <p><i class="fas fa-envelope"></i> <?=$obj['email']?></p>
                <?php endif; ?>
            </div>

            <?php if(isset($obj['website']) || isset($obj['social'])): ?>
            <div class="social-info">
                <?php if(isset($obj['website'])): ?>
                <p><i class="fas fa-globe"></i> <?=$obj['website']?></p>
                <?php endif; ?>
                <?php if(isset($obj['social'])): ?>
                <p><?=$obj['social']?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if(isset($obj['barcode'])): ?>
            <div class="barcode">
                <img src="<?=$obj['barcode']?>" alt="Receipt Barcode">
            </div>
            <?php endif; ?>

            <div class="policy">
                <p>All prices include VAT where applicable</p>
                <p>Goods once sold cannot be returned</p>
                <p>This receipt is valid for 30 days from the date of purchase</p>
            </div>

            <div class="receipt-meta">
                <?=date('Y')?> <?=isset($obj['company']) ? $obj['company'] : ''?> | Receipt generated on <?=date('Y-m-d H:i:s')?>
            </div>
        </div>
    </div>
    <?php endif;?>

    <script>
        window.print();
        var ajax = new XMLHttpRequest();
        ajax.addEventListener('readystatechange',function(){
            if(ajax.readyState == 4) {
                //console.log(ajax.responseText);
            }
        });
        ajax.open('POST','',true);
    </script>
</body>
</html>