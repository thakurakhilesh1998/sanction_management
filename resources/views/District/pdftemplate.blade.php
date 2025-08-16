<!DOCTYPE html>
<html>
<head>
    <title>Sanction Order</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin: 20px;
        }
        .content {
            margin-top: 20px;
        }
        p{
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center">Sanction Order</h3>
        <p>A sanction order was issued by the international regulatory body to curtail the illicit financial 
            transactions of the conglomerate. This order aims to freeze assets and impose travel bans on 
            individuals involved. The stringent measures are expected to halt the organization’s 
            economic leverage, rendering them incapable of continuing their operations. The financial
             sector is on high alert as the enforcement of these sanctions may ripple through global
              markets. Key stakeholders are advised to comply with the directives to avoid further penalties.
               This marks a significant step in the crackdown on economic malpractices.<br>
               The sanction order, detailed in a 30-page document, outlines specific prohibitions and 
               conditions for lifting the sanctions. Governments worldwide are coordinating to ensure the 
               effective implementation of these measures. The sanctioned entity has been linked to various
                violations, prompting a swift and decisive response. As part of the sanctions, all assets
                 are to be monitored closely, and any attempts at circumvention will be met with severe 
                 consequences. The economic community is urged to remain vigilant and report any suspicious 
                 activities related to the sanctioned party.
            </p>
        <div class="content">
            <p><strong>Financial Year:</strong> {{ $data['financial_year'] }}</p>    
            <p><strong>District Name:</strong>{{$data['district']}}</p>
            <p><strong>Block Name:</strong>{{$data['block']}}</p>
            <p><strong>Gram Panchayat Name:</strong>{{$data['gp']}}</p>
            <p><strong>Sanction Amount:</strong>{{$data['san_amount']}}</p>
            <p><strong>Sanction Head:</strong>{{$data['sanction_head']}}</p>
        </div>
        <div class="text-right">
            <span class="text-right">Sanction Authority</span><br>
            <span>Panchayati Raj Deparment</span><br>
            <span>Himachal Pradesh Shimla-09.</span>
        </div>
    </div>
</body>
</html>
