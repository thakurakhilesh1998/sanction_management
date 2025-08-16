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
<div class="container">
        <h3 class="text-center">Sanction Order</h3>
        <p>
            The Governor of Himachal Pradesh under the scheme {{$data['sanction_head']}} for the Financial Year {{$data['financial_year']}}
            has sanctioned a total amount of Rs. {{$data['san_amount']}} to the District Panchayt Officer {{$data['district']}}. The sanction is provided 
            for the completion of {{ $data['sanction_purpose'] }} construction works under their jurisdiction.
            This expenditure will be debited from the head of account No. 4515-Other Rural Development Program, Sub-Head 32, Other rural Development works, under the schme for "01-Construction of New Panchayat Building" in the budget for year {{$data['financial_year']}}
        </p>
            <br>
        <p>
            The release of this sanctioned amount and expenditure will be subject to administrative approval and financial provision, as provided in the sanction order.
            The amount will be used only for the purpose for which it has been sanctioned, as per the provisions of the Himachal Pradesh Panchayati Raj Act Financial Rules 2002. The work must be carried out in accordance with the relevant standards and norms.
            The District Panchayats officers will ensure that the sanctioned work is completed in the assigned Panchayat.
        </p>
            <br>
        <p>
            The District Panchayat Officers are responsible for oberseeing the construction of Panchayat community centers (Panchayat Ghars) in their respective areas, and they must ensure that the 
            sanctioned amount is utilized for the approved work as per the approved drawings. Once the work is completed, a report along with the 
            drawings, completion certificates and utilization certificates must be submitted to the Directorate of Panchayati Ra, Himachal Pradesh 
            Shimla within six months of completion. 
        </p>
        <div class="text-right">
            <span class="text-right">-By Order-</span><br>
            <span>Secretary Panchayati Raj</span><br>
            <span>Himachal Pradesh Government.</span>
        </div>
    </div>
</html>
