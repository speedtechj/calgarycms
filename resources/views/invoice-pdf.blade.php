<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>

    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}
    <style>
        body {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }

        table.table-1 {
            width: 100%;
            margin: 0px;
            padding: 0px;

        }

        table.table-2 {
            width: 100%;
            margin: 0px;
            padding: 0px;

        }

        table.table-3 {
            width: 100%;
            margin: 0px;
            padding: 0px;
            border-collapse: collapse;
            font-size: 15px;
            font-weight: bold;
            background-color: rgb(83, 86, 89);
            color: white;

        }

        .table-3 td {
            /* border: 1px solid black; */
            font-family: Arial, Helvetica, sans-serif;
            padding: 8px;
        }

        table.table-4 {
            width: 100%;
            margin: 0px;
            font-size: 15px;
            font-weight: bold;
            border-collapse: collapse;

        }

        .table-4 td {
            border: 1px solid black;
            padding: 5px;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;

        }

        table.table-5 {
            width: 100%;
            margin: 0px;
            font-size: 15px;
            font-weight: bold;
            border-collapse: collapse;

        }

        .table-5 td {
            border: 1px solid black;
            padding: 5px;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;

        }
        

        td.align-right {
            text-align: right;
            padding-top: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        td.align-center {
            text-align: center;
            padding-top: 10px;

        }

        td.align-span {
            text-align: center;

        }

        .heading-2 {
            font-size: 15px;
            font-weight: bold;
            margin: 0px;
            padding: 4px;
            font-family: Arial, Helvetica, sans-serif;
            font-style: italic;
        }
    </style>

</head>

<body>
    <table class="table-1">
        <tr>
            <td align="right">
                <p class="heading-2">Tracking Number: {{ $record->booking_invoice }}</p>
                <span>{!! DNS1D::getBarcodeHTML($record->booking_invoice, 'C39', 2, 30) !!}</span>
            </td>
        </tr>
    </table>
    <table class="table-2">
        <tr>
            <td class="align-center">
                <p>THIS INVOICE IS SUBJECT TO THE TERMS AND CONDITIONS PRINTED ON THE REVERSE</p>
            </td>
        </tr>
    </table>
    <table class="table-3">
        <tr>
            <td>
                <span>SENDER INFORMATION</span>
            </td>

        </tr>
    </table>
    <table class="table-4" border>
        <tr>
            <td width="25%">First Name <br> {{ $record->sender->first_name }}</td>
            <td width="25%">Last Name <br> {{ $record->sender->last_name }}</td>
            <td width="50%">Address <br> {{ $record->senderaddress->address }}</td>

        </tr>
        <tr>
            <td width="25%">Province <br> {{ $record->senderaddress->provincecan->name }}</td>
            <td width="25%">City <br> {{ $record->senderaddress->citycan->name }}</td>
            <td width="50%">Postal Code <br> {{ $record->senderaddress->postal_code }}</td>

        </tr>
        <tr>
            <td width="25%">Mobile Number <br> {{ $record->sender->mobile_no }}</td>
            <td width="25%">Phoneer Numb<br> {{ $record->sender->home_no }}</td>
            <td width="50%">Email <br> {{ $record->sender->email }}</td>

        </tr>
    </table>
    <table class="table-3">
        <tr>
            <td>
                <span>RECEIVER INFORMATION</span>
            </td>

        </tr>
    </table>
    <table class="table-4" border>
        <tr>
            <td width="25%">First Name <br> {{ $record->receiver->first_name }}</td>
            <td width="25%">Last Name <br> {{ $record->receiver->last_name }}</td>
            <td colspan="2" width="25%">Address <br> {{ $record->receiveraddress->address }}</td>


        </tr>
        <tr>
            <td width="25%">Province <br> {{ $record->receiveraddress->provincephil->name }}</td>
            <td width="25%">City <br> {{ $record->receiveraddress->cityphil->name }}</td>
            <td width="25%">Barangay <br> {{ $record->receiveraddress->barangayphil->name }}</td>
            <td width="25%">Postal Code <br> {{ $record->receiveraddress->zip_code }}</td>

        </tr>
        <tr>
            <td width="25%">Mobile Number <br> {{ $record->receiver->mobile_no }}</td>
            <td width="25%">Phoneer Numb<br> {{ $record->receiver->home_no }}</td>
            <td colspan="2"width="25%">Email <br> {{ $record->receiver->email }}</td>
        </tr>
    </table>
    <table class="table-3">
        <tr>
            <td>
                <span>PACKINGLIST INFORMATION</span>
            </td>

        </tr>
    </table>
    <table class="table-5" border>
        <tr>
            <th>QUANTITY</th>
            <th>DESCRIPTION</th>
            <th>AMOUNT</th>
        </tr>
        <tr>
            @foreach ( $record->packinglist as $packinglist)
            <tr>
            <td>{{ $packinglist->quantity ?? 0 }}</td>
            <td>{{ $packinglist->description ?? "none" }}</td>
            <td>{{ $packinglist->price ?? 0}}</td>
            </tr>
            @endforeach
        </tr>
    </table>
    <table class="table-3">
        <tr>
            <td>
                <span>DESCRIPTION OF PACKAGES AND GOODS</span>
            </td>
        </tr>
    </table>
    <table class="table-5" border>
        <tr>
            <th>DESCRIPTION</th>
            <th>PRICE</th>
            <th>AMOUNT</th>
        </tr>
        <tr>
            <td>{{ $record->boxtype->description }}</td>
            @if (isset($record->discount_id))
                <td>
                    {{'$'. $record->total_price + $record->discount->discount_amount }}
                </td>
            @elseif(isset($record->agentdiscount_id))
                <td>
                    {{'$'. $record->total_price + $record->agentdiscount->discount_amount }}
                </td>
            @else
                <td>{{ '$'.$record->total_price }}</td>
            @endif
            @if (isset($record->discount_id))
                <td>
                    {{'$'. $record->total_price + $record->discount->discount_amount }}
                </td>
            @elseif(isset($record->agentdiscount_id))
                <td>
                    {{'$'. $record->total_price + $record->agentdiscount->discount_amount }}
                </td>
            @else
                <td>{{'$'. $record->total_price }}</td>
            @endif
        </tr>
        @if ($record->discount_id !== null or $record->agentdiscount_id !== null)
            <tr>
                <td colspan="2" align="right">Discount</td>
                @if ($record->discount_id !== null)
                    <td>
                        {{'$'. $record->discount->discount_amount ?? 0 }}
                    </td>
                @else
                    <td>
                        {{'$'. $record->agentdiscount->discount_amount ?? 0 }}
                    </td>
                @endif
            </tr>

        @endif
        <tr>
            <td colspan="2" align="right">Balance Due</td>
            <td>{{"$".$record->payment_balance}}</td>
        </tr>
    </table>
    <table class="table-3">
      <tr>
          <td>
              <span>PAYMENT INFORMATION</span>
          </td>

      </tr>
  </table>
  <table class="table-5" border>
    <tr> 
      @if($record->is_paid !== 0)
            @foreach ( $record->bookingpayment as $paymenttype)
                
            <td width="25%"> <input type="checkbox" name="status" {{$paymenttype->paymenttype_id == $paymenttype->paymenttype_id ? 'checked': '' }}/>
              <label>{{$paymenttype->paymenttype->name}}</label>
            </td>
        
          @endforeach
      @else
      <td>"No Payment Made"</td>
      @endif
    </tr>
</table>
<table width="100%">
  <tr>
    <td align="center"> <h4>OWNER’S/SHIPPER’S RISK FORM</h4></td>
  </tr>
</table>
<table width="100%">
  <tr>
    <td width="50%" align="left">
                <span style="font-family: Arial, Helvetica, sans-serif; font-size:10px"> <span style="font-weight: bold;">Please be advised that BREAKABLE ITEMS, LIQUID ITEMS OR
                    ELECTRONIC ITEMS inside the box per above reference Tracking/Invoice
                    Number are accepted for transport under OWNER/SHIPPER’S risk.</span><br><br>
                    Notwithstanding the Terms and Conditions of the covering Sea Waybill or Bill of
                    Lading, shipper and/or shipper’s representative, by signing on this form, Shipper
                    agreed and understood that <span style="font-weight: bold;">FOREX CARGO TRAVEL &amp; TOURS INC., WILL
                    NOT BE LIABLE FOR ANY SPILLAGE, BREAKEAGE AND/OR
                    DAMAGES, RELATED TO THIS TRANSACTION, HOWEVER CAUSED.</span><br><br>
                    I/WE FURTHER, declared that my box(es) has no commercial goods (more
                    than a dozen in any kind) No currency, No Firearms/Ammunition/Explosives
                    and Toy Guns, No Precious Metals /Stones, No Money Order and Travelers’
                    Check, No Drugs and Perishable goods, and other prohibited items.<br><br>
                    Shipper, as stated at the face of this Owner’s/Shipper’s Risk Form who is of legal
                    age, with address stated as the corresponding FOREX CARGO TRAVEL &amp;
                    TOURS INC., reference do hereby remise, release, acquit and forever discharge
                    and agree to hold harmless FOREX CARGO TRAVEL &amp; TOURS. INC., its
                    parent, affiliate or subsidiary companies, their stockholders, officers, directors,
                    claims for sum of money, demands, complaints, liabilities, obligations, suits,</span>
                    
    </td>
    <td width="50%" align="left">
      <span style="font-family: Arial, Helvetica, sans-serif; font-size:10px">agents or employees and their successors-in-interest from any and all actions,
        rights or causes of actions whatsoever (for indemnity, damages or otherwise) at
        law or in equity that now exists or may hereafter exists (collectively, the
        “Claims”), arising out of or in connection with the non-perishable shipment
        covered under the corresponding INVOICE which, considering such spillage,
        breakable or fragile in nature of the shipment, is accepted under
        shipper/owner’s risk.</span>
        Shipper acknowledges that no action will be instituted whether civil, criminal, or
        administrative against <span style="font-weight: bold;">FOREX CARGO TRAVEL &amp; TOURS INC.</span>, This Form
        may be pleaded in bar or any suit or proceeding which Shipper may have taken or
        may take in connection with the non-perishable breakable shipment. Suits arising
        from or in relation to this document or the shipment, including violations of the
        waiver herein, shall be brought before the courts.<br><br>
        It is agreed that Shipper have read this entire document, the contents of which have
        been explained in a language that is known and which the Shipper acknowledge to
        have signed, and the entire form, release, waiver and quitclaim hereby given is made
        by Shipper willingly, voluntary and with full knowledge of the rights under the law
        and is binding upon Shipper and its successors and assigns.</p><span>
    </td>
  </tr>
</table>
<table width="100%" style="margin-top:10px">
    <tr>
      <td>_________________________</td>
      <td>_________________________</td>
    </tr>
    <tr>
      <td>Signature</td>
      <td>Date</td>
    </tr>
</table>
</body>

</html>
