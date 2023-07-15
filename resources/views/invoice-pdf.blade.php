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

        table.table-0 {
            width: 100%;
            margin: 0px;
            padding: 0px;
        }

        .logo {
            width: 150px;
            height: 50px;
            margin: 0px;
            padding: 0px;
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
            font-family: Verdana, Geneva, Tahoma, sans-serif;
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
            font-size: 12px;
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
            font-size: 20px;
            font-weight: bold;
            margin: 0px;
            padding-top: 10px;
            font-family: Arial, Helvetica, sans-serif;
            font-style: italic;
            text-decoration: underline;
        }

        .table-0 p {
            margin: 0px;
            padding: 0px;
            font-size: 15px;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bolder;
        }

        .page-break {
            page-break-after: always;

        }

        .li-1 {
            margin: 0px;
            padding: 0px;
            font-size: 13px;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bolder;
        }

        .sub-span {
            font-size: 11px;
            font-family: Arial, Helvetica, sans-serif;

        }

        .span-text {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
    </style>

</head>

<body>
    <table class="table-0">
        <tr>
            <td width="40%">
                <img class="logo" src="{{ public_path('/logo.png') }}" alt="logo" />

            </td>
            <td width="20%">
                {!! DNS2D::getBarcodeHTML('https://forexcargodeals.com/calgary', 'QRCODE', 3, 3, 'black', true) !!}
            </td>
            <td width="40%" align="right">
                <p class="heading-1">Forex Cargo Travel & Tours</p>
                <p>328-39 Avenue SE, Calgary, AB T2G 1X6</p>
                <p>Phone: 403-873-6730</p>
                <p>www.forexcargodeals.com</p>
            </td>
        </tr>
    </table>
    <table class="table-1">
        <tr>
            <td align="center">
                <p class="heading-2">Tracking Number: {{ $record->booking_invoice }}</p>
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
            <td width="40%"><span class="span-text">First Name</span> <br> {{ $record->sender->first_name }}</td>
            <td colspan="2" width="40%"><span class="span-text">Last Name</span> <br>
                {{ $record->sender->last_name }}</td>
            <td align="right"><span class="span-text">Mobile Number </span><br> {{ $record->sender->mobile_no }}</td>

        </tr>
        <tr>
            <td colspan="3" width="50%"><span class="span-text">House/Unit/Apt. Number/Street Name</span> <br>
                {{ $record->senderaddress->address }}</td>
            <td width="25%" align="right"><span class="span-text">Phone Number</span><br>
                {{ $record->sender->home_no }}</td>

        </tr>
        <tr>
            <td><span class="span-text">City </span><br>{{ $record->senderaddress->citycan->name }}</td>
            <td><span class="span-text">Province </span><br> {{ $record->senderaddress->provincecan->name }}</td>
            <td><span class="span-text">Postal Code </span><br> {{ $record->senderaddress->postal_code }}</td>
            <td align="right"><span class="span-text">Email </span><br> {{ $record->sender->email }}</td>

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
            <td colspan="" width="40%"><span class="span-text">First Name </span><br>
                {{ $record->receiver->first_name }}</td>
            <td colspan="2" width="40%"><span class="span-text">Last Name </span><br>
                {{ $record->receiver->last_name }}</td>
            <td align="right"><span class="span-text">Mobile Number</span> <br> {{ $record->receiver->mobile_no }}
            </td>

        </tr>
        <tr>
            <td colspan="2" width="20%"><span class="span-text">House/Unit/Apt Number/Street Name</span><br>
                {{ $record->receiveraddress->address }}</td>
            <td width="30%"><span class="span-text">Barangay </span><br>
                {{ $record->receiveraddress->barangayphil->name }}</td>
            <td width="25%" align="right"><span class="span-text">Phone Number</span><br>
                {{ $record->receiver->home_no }}</td>

        </tr>
        <tr>
            <td width="25%"><span class="span-text">City </span><br> {{ $record->receiveraddress->cityphil->name }}
            </td>
            <td width="25%"><span class="span-text">Province </span><br>
                {{ $record->receiveraddress->provincephil->name }}</td>
            <td width="25%"><span class="span-text">Zip Code </span><br> {{ $record->receiveraddress->zip_code }}
            </td>
            <td width="25%" align="right"><span class="span-text">Email </span><br> {{ $record->receiver->email }}
            </td>
        </tr>
    </table>

    <table class="table-3">
        <tr>
            <td>
                <span>DESCRIPITON OF PACKAGES AND GOODS</span>
            </td>

        </tr>
    </table>
    <table class="table-5" border>
        <tr>
            <th>QUANTITY</th>
            <th>DESCRIPTION</th>
            <th>AMOUNT</th>
        </tr>
        @if ($packinglist->count() !== 0)
            <tr>
                @foreach ($packinglist as $packinglist)
            <tr>
                <td>{{ $packinglist->quantity }}</td>
                <td>{{ $packinglist->packlistitem->itemname }}</td>
                <td>{{ $packinglist->price }}</td>
                {{-- <td>{{ $packinglist->price ?? 0}}</td> --}}
            </tr>
        @endforeach
        </tr>
    @else
        @for ($i = 0; $i < 3; $i++)
            <tr>
                <td style="padding:10px"></td>
                <td></td>
                <td></td>
            </tr>
        @endfor
        @endif
    </table>

    <table class="table-3">
        <tr>
            <td>
                <span>BOX SIZE AND SHIPPING COST</span>
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
                    {{ '$' . $record->total_price + $record->discount->discount_amount }}
                </td>
            @elseif(isset($record->agentdiscount_id))
                <td>
                    {{ '$' . $record->total_price + $record->agentdiscount->discount_amount }}
                </td>
            @else
                <td>{{ '$' . $record->total_price }}</td>
            @endif
            @if (isset($record->discount_id))
                <td>
                    {{ '$' . $record->total_price + $record->discount->discount_amount }}
                </td>
            @elseif(isset($record->agentdiscount_id))
                <td>
                    {{ '$' . $record->total_price + $record->agentdiscount->discount_amount }}
                </td>
            @else
                <td>{{ '$' . $record->total_price }}</td>
            @endif
        </tr>
        @if ($record->discount_id !== null or $record->agentdiscount_id !== null)
            <tr>
                <td colspan="2" align="right">Discount</td>
                @if ($record->discount_id !== null)
                    <td>
                        {{ '$' . $record->discount->discount_amount ?? 0 }}
                    </td>
                @else
                    <td>
                        {{ '$' . $record->agentdiscount->discount_amount ?? 0 }}
                    </td>
                @endif
            </tr>

        @endif
        <tr>
            <td colspan="2" align="right">Balance Due</td>
            <td>{{ "$" . $record->payment_balance }}</td>
        </tr>
    </table>
    <table class="table-3" width="100%">
        <tr>
            <td align="center">
                <span>DECLARATION</span>
            </td>

        </tr>
        <table>
            <tr>
                <td style="font-size:10px">
                    I HERE BY CERTIFY AND DECLARE that the contents of the above sealed package(s) are goods without
                    commercial value or purpose whatsoever. I FURTHER CERTIFY that are no contraband goods as defined by
                    the laws of Canada and the Republic of the Philippines: I take full legal responsibility for any
                    erroneous declaration or omission in the packing list attached to this document. I FINALLY CERTIFY
                    that I am endorsing this
                    Invoice to Forex Cargo Travel and Tours or door-delivery of my package(s) to
                    my consignee at the address herein; that I have read, understood and agree to the terms and
                    conditions printed on the reverse.
                </td>
            <tr>
        </table>
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
            @foreach ($paymenttype as $paymenttypes)
            @php $paymenttypescount = 100 @endphp
                @foreach ($record->bookingpayment as $typepayments)
                    @if ($typepayments->paymenttype_id == $paymenttypes->id)
                        @php $paymenttypescount = $paymenttypes->id @endphp
                        <td> <input type="checkbox" {{ $paymenttypes->id ? 'checked' : '' }} />
                            <label>{{ $paymenttypes->name }}</label>
                        </td>
                    @else
                     
                    @endif
                @endforeach
                    
                    @if ($paymenttypes->id != $paymenttypescount)
                    <td> <input type="checkbox"  />
                        <label>{{ $paymenttypes->name }}</label>
                    </td>
                @endif
                 
                  
               
            @endforeach
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td align="center" style="font-size:12px">
                <h4>OWNER’S/SHIPPER’S RISK FORM</h4>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="50%" align="left" style="font-size:10px">
                <span style="font-family: Arial, Helvetica, sans-serif;"> <span style="font-weight: bold;">Please be
                        advised that BREAKABLE ITEMS, LIQUID ITEMS OR
                        ELECTRONIC ITEMS inside the box per above reference Tracking/Invoice
                        Number are accepted for transport under OWNER/SHIPPER’S risk.</span><br><br>
                    Notwithstanding the Terms and Conditions of the covering Sea Waybill or Bill of
                    Lading, shipper and/or shipper’s representative, by signing on this form, Shipper
                    agreed and understood that <span style="font-weight: bold;">FOREX CARGO TRAVEL &amp; TOURS INC.,
                        WILL
                        NOT BE LIABLE FOR ANY SPILLAGE, BREAKEAGE AND/OR
                        DAMAGES, RELATED TO THIS TRANSACTION, HOWEVER CAUSED.</span><br>
                    I/WE FURTHER, declared that my box(es) has no commercial goods (more
                    than a dozen in any kind) No currency, No Firearms/Ammunition/Explosives
                    and Toy Guns, No Precious Metals /Stones, No Money Order and Travelers’
                    Check, No Drugs and Perishable goods, and other prohibited items.<br>
                    Shipper, as stated at the face of this Owner’s/Shipper’s Risk Form who is of legal
                    age, with address stated as the corresponding FOREX CARGO TRAVEL &amp;
                    TOURS INC., reference do hereby remise, release, acquit and forever discharge
                    and agree to hold harmless FOREX CARGO TRAVEL &amp; TOURS. INC., its
                    parent, affiliate or subsidiary companies, their stockholders, officers, directors,
                    claims for sum of money, demands, complaints, liabilities, obligations, suits,</span>

            </td>
            <td width="50%" align="left" style="font-size:10px;">
                <span style="font-family: Arial, Helvetica, sans-serif;">agents or employees and their
                    successors-in-interest from any and all actions,
                    rights or causes of actions whatsoever (for indemnity, damages or otherwise) at
                    law or in equity that now exists or may hereafter exists (collectively, the
                    “Claims”), arising out of or in connection with the non-perishable shipment
                    covered under the corresponding INVOICE which, considering such spillage,
                    breakable or fragile in nature of the shipment, is accepted under
                    shipper/owner’s risk.</span>
                Shipper acknowledges that no action will be instituted whether civil, criminal, or
                administrative against <span style="font-weight: bold;">FOREX CARGO TRAVEL &amp; TOURS INC.</span>, This
                Form
                may be pleaded in bar or any suit or proceeding which Shipper may have taken or
                may take in connection with the non-perishable breakable shipment. Suits arising
                from or in relation to this document or the shipment, including violations of the
                waiver herein, shall be brought before the courts.<br>
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
    <div class="page-break"></div>
    <table width="100%">
        <tr>
            <td align="center">
                <h4>THIS FORM OF A CONSOLIDATION SHIPMENT COVERED BY A DELIVERY ORDER DECLARED HEREIN</h5>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="50%" style="vertical-align:top; font-size:10px">
                <span>By tendering goods and personal effects for shipment via Forex Cargo Travel
                    Tours (“Company”). The shipper agrees to the terms and conditions stated
                    herein and the declaration of the shipper made in the invoice which are
                    incorporated herein by reference. No agent or employee of “company” or the
                    shipper may alter these terms and condition.</span><br>
                <ol>
                    <li class="li-1"> THE INVOICE</li>
                    <span class="sub-span">The “Company” invoice is non-negotiable, and the shipper acknowledges
                        that it has been prepared by the shipper or by the “Company” goods
                        transported hereunder, or it is the authorized agent of the owner of the goods,
                        and that it hereby accepts the “Company’s” terms and conditions for itself and
                        as agent for and behalf of any other person having interest in the shipment.</span>
                    <li class="li-1"> SHIPPER’S OBLIGATIONS AND ACKNOWLEDGEMENTS</li>
                    <span class="sub-span">The shippers warrants that each article in the shipment is properly
                        described on this invoice and has not been declared by the “Company” to be
                        unacceptable for transport, and that the shipment is properly marked and
                        addressed and packed to ensure safe transportation with ordinary care in
                        handling.
                        The shipper hereby acknowledges that the “Company” may abandon
                        and/or release any item consigned by the shipper to the “Company” which the
                        “Company” has declared unacceptable or which the Shipper has undervalued
                        for Customs’ purposes or wrongful description heron, whether intentionally or
                        otherwise, without incurring any liability whatsoever to the Shipper or the
                        Shipper will save and defend, indemnify and hold the “Company” harmless for
                        all claims, damages, fines and expenses arising there from.
                        The shipper shall be liable for all costs and expenses related to the
                        shipment for all costs incurred in either returning the Shipment to the Shipper
                        or warehousing the shipment pending disposition.</span>
                    <li class="li-1"> THE RIGHT OF INSPECTION OF SHIPMENT</li>
                    <span class="sub-span">The “Company” has the right, but not the obligation to inspect any
                        shipment including, without limitation, opening the shipment.</span>
                    <li class="li-1"> LIEN ON GOODS SHIPPED</li>
                    <span class="sub-span">The “Company” shall have a lien on any goods shipped for all freight
                        charges, customs duties, advances, or other charges of any kind arising out of
                        the transportation hereunder and may refuse to surrender possession of the
                        goods until such charges are paid.</span>
                    <li class="li-1"> LIMITATION OF LIABILITY</li>
                    <span class="sub-span">The liability of the “Company” for lost shipment(s), under this invoice, is
                        limited to:</span>
                    <ol type="a">
                        <li> CDN $100 per package (for regular box, TV (regardless of size),
                            irregular box/shipment which dimension is equivalent to or bigger
                            than regular box).</li>
                        <li>CDN $75 per package (for bagahe box or irregular box/shipment
                            which dimension is equivalent to bagahe box).</li>
                        <li>CDN $50 per package (for bulilit box) excluding those which are
                            shipped under “Company” promotions or deals.</li>
                    </ol>

                    <li class="li-1">CONSEQUENTIAL DAMAGES EXCLUDED</li>
                    <span class="sub-span">The “Company” shall not be liable, in any event, for any consequential or
                        special damages or other indirect loss, however arising whether or not the
                        “Company” had knowledge that such damage might be incurred, including but
                        not limited to, loss of income, profits interest, utility or loss of market.</span>

                </ol>
            </td>
            <td width="50%" vertical-align: top;>
                <ol start="7">
                    <li class="li-1">LIABILITIES NOT ASSUMED</li>
                    <span class="sub-span">While the “Company” endeavors to exercise its best efforts to provide
                        expeditious delivery in accordance with regular delivery schedules. The
                        “Company” WILL NOT, UNDER ANY CIRCUMSTANCES, BE LIABLE FOR DELAY
                        IN PICKUP, TRANSPORTATION OR DELIVERY OF ANY SHIPMENT REGARDLESS
                        OF THE CAUSES OF SUCH DELAY. Further, the “Company” shall not be liable
                        for any loss, damage, mis delivery or non-delivery:</span>
                    <ol type="a">
                        <li>Due to act of nature force majeure occurrence or any cause
                            reasonably beyond the control of the “Company”</li>
                        <li>Caused by:</li>
                        <ol type="1" class="sub-span">
                            <li>The act, default or omission of the shipper, the consignee or any
                                other party who claims an interest in the shipment (including
                                violation of any term or condition hereof) or of any person other
                                than the “Company” or of any Customs or other Government
                                officials or of any postal service, forwarded or other entity or
                                person to whom a shipment is tendered by the “Company” for
                                transportation to any location not regularly served by the
                                “Company” regardless of whether the shipper requests or had no
                                knowledge of such third-party delivery arrangement.</li>
                            <li>The nature of the shipment or any defect, characteristics, or
                                inherent vice thereof.</li>
                            <li>Electrical or magnetic injury, erasure, or other such damage to
                                electronic or photographic images or recordings in any form. </li>
                        </ol>
                        <li>Value goods and personal effects not declared in invoice</li>
                        <li>The “Company” is not liable for accidental breakage and/or
                            leakage of items inside the box. It is the responsibility of the
                            shipper to pack the items properly and securely.</li>
                    </ol>

                    <li class="li-1">CLAIMS</li>
                    <ol class="sub-span">
                        <li>Claims or complaint should be advised by the sender within 24 to
                            48 hours. From the date of delivery to destination through email,
                            phone or text message (SMS). The “Company” will then request for
                            proof of delivery (POD) and pictures from its counterpart in the
                            destination and send Complaint Form to sender. The sender has 20
                            days to fill up and submit the complaint form to the “Company”
                            through any means of communication or personal visit to any of
                            the “Company “offices or agent. The “Company” will not accept or
                            assist any claim beyond 20 days from the date of delivery of cargo
                            to the destination.</li>
                        <li> No claim will be accepted and/or entertained until all
                            transportation and shipping charges have been paid to the
                            “Company”.</li>
                        <li>See section 5-LIMITATION OF LIABILITY</li>
                    </ol>

                    <li class="li-1">APPLICABILITY</li>
                    <span class="sub-span">These terms and conditions shall apply to and inure to the benefit of the
                        “Company” and its authorized agents and affiliated companies, and the
                        officers, directors, and employees.</span>
                    <li class="li-1">MATERIALS NOT ACCEPTABLE FOR TRANSPORT</li>
                    <span class="sub-span">The “Company” will not accept commercial goods (more than a dozen of
                        any kind) and will not carry
                        Currency Precious Metals Drugs
                        Traveler’s Checks Precious Stones Firearms/ammunition
                        Money Orders Jewelleries Explosive/Toy Guns
                        Checks Pirated products (i.e. DVD, CD)
                        Plant seeds Plant Materials
                        Any food stuff that are not in cans, sealed packages, or in bottles
                        Negotiable instruments in bearer form; electrical Appliance; lewd,
                        obscene, or pornographic materials; Gambling Paraphernalia; Industrial
                        carbons or diamonds; communication equipment and computers;
                        combustible materials; motor vehicle parts, microwave ovens; property
                        carriage of which is prohibited by law, regulation, or statue of any federal,
                        state or local government of any country from to or through which the
                        shipment may be carried.</span><br>
                    <li class="li-1">Any expenses incurred by the” Company” on behalf of Shippers including, but
                        not limited to, taxes interests’ penalties, fines surcharges, duties, etc, arising
                        from non declaration or misdirection shall be reimbursed or refunded by
                        shipper upon submission by the “Company” of proper proof or evidence for
                        such expenses. In such event, the company is entitled to hold, retain or
                        impound the shipment as surely for payment until said refund or
                        reimbursement is fully satisfied.</li>
                    </p>
                    <ol>
            </td>
        </tr>
    </table>

</body>

</html>
