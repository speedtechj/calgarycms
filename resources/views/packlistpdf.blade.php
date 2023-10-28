<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packinglist</title>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}
    <style>
        .logo {
            width: 250px;
            height: 100px;
            margin: 0px;
            padding: 0px;
        }
        .heading1{
            font-size: 20px;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
            margin-bottom: 10px;
            margin-top: 10px;
        }
       .table2,  th, td{
            font-size: 15px;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
           border:1px solid black;
           padding:10px;
        }
        .table1{
            border-collapse: collapse;
           
        }
        
    </style>

</head>

<body>
    <table width="100%"  >
        <tr>
            <td align="center" style="border:none">
                <img class="logo" src="{{ public_path('/logo.png') }}" alt="logo" />

            </td>

        </tr>
    </table>
    
    <div class="container">
        <table width="100%" class="heading1">
            <tr >
                <td width="50%"  style="border:none; padding:none; padding:0px">SENDER INFORMATION</td>
                <td width="50%" align="right" style="border:none; padding:0px;">INVOICE NUMBER<span style="margin-left:2px">{{$record->booking_invoice}}</span></td>
            </tr>
        </table>
        <table width="100%" class="table1">
            <tr>
                <td>Family Name:<span class="ml-2">{{$record->sender->first_name}}</span></td>
                <td>Givien Name: <span class="ml-2">{{$record->sender->last_name}}</span></td>
                <td>Middle Name:</td>
                <td>Suffix:</td>
            </tr>
            <tr>

                <td>Contact No: <span class="ml-2">{{$record->sender->mobile_no}}</span></td>
                <td colspan="3">Address:<span class="ml-2">
                    {{$record->senderaddress->address}}
                    {{$record->senderaddress->citycan->name}}
                    {{$record->senderaddress->provincecan->name}}
                    {{$record->senderaddress->postal_code}}
                </span> </td>
               
            </tr>
    </table>
    <table width="100%" class="heading1">
            <tr>
                <td style="border:none">RECEIVER INFORMATION</td>
                
            </tr>
        </table>
    <table width="100%" class="table1">
        <tr>
            <td>Family Name:<span class="ml-2">{{$record->receiver->first_name}}</span></td>
                <td>Givien Name: <span class="ml-2">{{$record->receiver->last_name}}</span></td>
                <td>Middle Name:</td>
                <td>Suffix:</td>
        </tr>
        <tr>
            <td>Contact No: <span class="ml-2">{{$record->receiver->mobile_no}}</span></td>
                <td colspan="3">Address:<span class="ml-2">
                    {{$record->receiveraddress->address}}
                    {{$record->receiveraddress->cityphil->name}}
                    {{$record->receiveraddress->provincephil->name}}
                    
                </span> </td>
        </tr>
</table>
    <table class="table1">
        <thead>
          <th>Quantity</th>
          <th>Unit of Measure</th>
          <th>New</th>
          <th>Used</th>
          <th>Actual Estiamted Value(Canadian Dollar)</th>
        </thead>
        <tbody>
        <tr>
          <td></td>
          <td></td>
          <td>CANNED GOODS</td>
          <td></td>
          <td></td>

        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>CANDIES/CHOCOLATE/NUTS</td>
            <td></td>
            <td></td>

          </tr>
          <tr>
            <td></td>
            <td></td>
            <td>BISCUITS/CRACKERS/SNACKS</td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td>CEREALS/OATMEALS/PASTA/MACARONI</td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td>SEASONING/CONDIMENTS</td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td>TOILETRIES</td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td>CHILDREN CLOTHES & ACCESSORIES</td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td>ADULTS CLOTES & ACCESSORIES</td>
            <td></td>
            <td></td>
          </tr>
        </tbody>
      </table>
      <div style="font-size:20px; font-weight:bold">
        <p>Declaration</p>
        <span>I declare, under the penalties falsificatio, that this information.
            Sheet has been in good faith and to the best of my knowledge and belief, is true
            and correct pursuant to the provision of the Custom Moderniation and Tariff Act of the
            Philippines and its impplementing rules and regulations.
    </div>
    <center>
        <div style="font-size:20px; font-weight:bold">
        <p>_____________________________________</p>
        <p>Sender Signature over Printed Name</p>
        <p>Date Accomplished _____/______/______
        </div>
    </center>
    </div>
</body>

</html>
