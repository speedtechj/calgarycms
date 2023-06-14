<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}

    <style>
        body{
    background-color:white;
    font-family: Arial;
}
.page{

    display: block;
    margin: 0 auto;
    position: relative;
    box-shadow: gray;
    background-color: white;
    font-style:arial;

}


.page[size="A4"]{

    width: 21cm;
    height: 34.4cm;  

}

#logo img{

    position: absolute;
    height: 100px;
    width: 180px;
    top: -30px;
    align-items:center;
}
#top-right-content{
    position: absolute;
    top: -15px;
    right: 10px;

}
#top-right-content p{
    font-weight: bold;
    font-size: medium;
}
#head{

    text-align: center;
    position: relative;
    top: 70px;
}
#head p{
    font-size: small;
    font-weight: bold;
}

#table1{

    border: 1px solid;
    position: relative;
    top:  52px;
    width: 49%;
    height: 252px;
    left: 12px;
    text-align: center;
    border-collapse:collapse;
}
#table2{
    border: 1px solid;
    position: relative;
    top:  -200px;
    width: 49%;
    height: 252px;
    left: 399px;
    text-align: center;
    border-collapse:collapse;
}
.table{

    font-size: small;
    border-collapse:collapse;

}
#itemize{

    top: -200px;
    left: 10px;
    position: relative;

}
#itemize p{
    font-size: medium;
    font-weight: bold;
}
#table3{

    border: 1px solid;
    position: relative;
    top:  -210px;
    width: 98%;
    left: 10px;
    font-size: small;
    text-align: center;
    border-collapse:collapse;
    

}

#table3,th,td{
    border:  1px solid;
    
}
#itemize2{

top: -200px;
left: 10px;
position: relative;

}
#itemize2 p{
font-size: medium;
font-weight: bold;
}
#table4{

border: 1px solid;
position: relative;
top:  -210px;
width: 98%;
left: 10px;
font-size: small;
text-align: center;
border-collapse:collapse;

}


#right-content{
   display: block;
   position: relative;
   width: 47%;
   float: left;
   right: -10px;
   top: -80px;
   
   
}
#right-content p{
    font-size:11px;
    font-weight: normal;
}
#right-content h4{
    font-weight: bold;
    text-align: center;
    
    
}
#right{
   display: block;
   position: relative;
   width: 47%;
   float: right;
   right: -10px;
   top: -80px;
   font-size:11px;
   
}

.page-break{

    display: block;
    margin: 0 auto;
    position: relative;
    box-shadow: gray;
    background-color: white;
    font-style:arial;

}
.page-break[size="A4"]{

    width: 21cm;
    height: 29.7cm;

}


#left-content2{
    display: block;
    position: relative;
    width: 55%;
    left: -5%;
    font-size: 11px;
    top: 143px;
    text-align: left;
 
 }
 #right-content2{

    display: block;
    position: absolute;
    width: 55%;
    float: right;
   right: -7%;
   top: 133px;
   font-size: 11px;
   text-align: left;

 }
 #footer{
font-size:large;
font-weight: bold;
position: relative;
text-align: center;
top: 165px;
 }
#footer p{
    font-weight: bold;
    font-size: 11px;
}

#page1-footer{
    position: absolute;
    bottom: 80px;
    left: 0px;
   

}
#page1-footer p{
    font-weight: bold;
}
#page1-footer2{
    position: absolute;
    bottom: 80px;
    left:200px;   

}
#page1-footer2 p{
    font-weight: bold;
}
#sender{
    position: absolute;
    top: 20px;
    font-size: 11px;

}
#date{
    position: absolute;
    top: 20px;
    font-size: small;
}
.page-break h4{
    position: absolute;
    text-align: center;
    top: 20PX;
    font-size: x-large;
}

#payment-mode{
    position:absolute;
    top:580px;
    font-size:medium;
    left: 10px;
    font-family:arial;
    
}
  
  </style>
    
</head>
<body>
    
    <div class="page" size="A4">

        
         <div id="logo">
         <img src="{{ public_path("/image002.png") }}" alt="logo"  />
         </div>
         <div id="top-right-content">
           <p>INVOICE#. {{$record->booking_invoice}}</p>
           <p>DROPOFF. {{ $record->booking_date }}</p>
        </div>
        <div id="head">
            <p>THIS INVOICE IS SUBJECT TO THE TERMS AND CONDITIONS PRINTED ON THE REVERSE</p>
        </div>
    
        <div class="table">
           
              <table id="table1">
                <tr>
                  <th colspan="3">SENDER INFORMATION</th> 
                </tr>
                <tr>
                  <th>COMPLETE NAME</th>
                  <th>ADDRESS</th>
                  <th>POSTAL CODE</th>
                </tr>
                <tr>
                   
                        
                    <td>{{ $record->sender->full_name}}</td>
                    <td>{{ $record->senderaddress->address }}</td>
                    <td>{{$record->senderaddress->postal_code}}</td>
                
                </tr>
                <tr>
                  <th>CITY</th>
                  <th>PROVINCE</th>
                  <th>MOBILE NUMBER 1</th>
                  
                </tr>
                <tr>
                    <td>{{ $temp->name }}</td>
                    <td>{{ $temp->provincecan->name }}</td>
                    <td>{{ $record->sender->mobile_no }}</td> 
                    
                </tr>
                <tr>
                            
                  <th>LANDLINE NUMBER</th>
                  <th colspan="2">EMAIL</th> 
                </tr>
                <tr>
                            
                    <td>{{ $record->sender->home_no }}</td>
                    <td colspan="2">{{ $record->sender->email }}</td>
                  </tr>
              
              </table>
        </div>
              <div class="table">
              <table id="table2">
                <tr>
                  <th colspan="4">RECEIVER INFORMATION</th> 
                </tr>
                <tr>
                  <th>COMPLETE NAME</th>
                  <th>ADDRESS</th>
                  <th>BRGY</th>  
                  <th>CITY</th>

                </tr>
                <tr>
                    <td>{{ $record->receiver->full_name}}</td>
                    <td>{{ $record->receiveraddress->address}}</td>
                    <td>{{ $record->receiveraddress->barangayphil->name}}</td>  
                    <td>{{ $temp2->name }}</td>
  
                  </tr>
                <tr>
                  
                  <th>PROVINCE</th>
                  <th>ZIP CODE</th>
                  <th>MOBILE NUMBER 1</th>
                  <th>LANDLINE NUMBER</th>
                </tr>
                <tr>
                    
                    <td>{{ $temp2->provincephil->name }}</td>
                    <td>{{ $record->receiveraddress->zip_code}}</td> 
                    <td>{{$record->receiver->mobile_no}}</td> 
                    <td>{{$record->receiver->home_no}}</td>
                  </tr>
                <tr>
                  
                 
                  <th colspan="4">EMAIL</th>  
                  
                </tr>
                <tr>
                   
                <td colspan="4">{{ $record->receiver->email}}</td> 
                    
                  </tr>
               
              </table>


            </div>
            <div id="itemize">
                    <P>ITEMIZED DESCRIPTION OF GOODS</P>
            </div>
             <table id="table3">
                       
                            <tr>
                              <th>Quantity</th>
                              <th>Goods <br>Description</th>
                             
                            </tr>
                            
                            <tr>
                               <td>1</td> 
                               <td>electronic</td>
                             </tr>
                            <tr>
                            <tr>
                               <td>1</td> 
                               <td>electronic</td>
                             </tr>
                            <tr>
                            <tr>
                               <td>1</td> 
                               <td>electronic</td>
                             </tr>
                             
                            <tr>
                              <td style="font-weight: bold;">Total Value: </td><td>100</td>
                            </tr>
                            
                            
                 
             </table>
             <div id="itemize2">
                    <P> DESCRIPTION OF PACKAGES AND GOODS</P>
            </div>
            <div class="table">
             <table id="table4">
                       
                            <tr>
                              <th>PRODUCT CODE</th>
                              <th>DESCRIPTION</th>
                              <th>PRICE</th>
                              <th>TOTAL </th> 
                            </tr>
                            
                            <tr>
                                <td>{{ $record->booking_invoice }}</td>
                                <td>{{ $record->servicetype->description}}</td>
                                <td>{{ $record->total_price }}</td>
                                <td>{{ $record->total_price }} </td>
                               
                              </tr>
                           
                            
                 
             </table>
             </div>

            <div id="right-content">
                <h4>OWNER’S/SHIPPER’S RISK FORM</h4>
                <p> <span style="font-weight: bold;">Please be advised that BREAKABLE ITEMS, LIQUID ITEMS OR
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
                    age, with address stated as the corresponding 
                    </p>
            </div>
            <div id="payment-mode">
            <h3>MODE OF PAYMENT</h3>
             <label class="container">CASH
             <input type="checkbox" >
             <span class="checkmark"></span>
             </label>
            <label class="container">DEBIT
            <input type="checkbox">
            <span class="checkmark"></span>
                </label>
            <label class="container">CHEQUE
                <input type="checkbox">
            <span class="checkmark"></span>
                </label>
                    <label class="container">MASTER CARD
                <input type="checkbox">
                <span class="checkmark"></span>
              </label>
              <label class="container">VISA CARD
                <input type="checkbox">
                <span class="checkmark"></span>
              </label><br>
              <label class="container">E TRANSFER to calgary@forexcargodeals.com <input type="checkbox">
                <span class="checkmark"></span>
                
              </label>
            </div>
            <div id="right">
                <p><span style="font-weight: bold;">FOREX CARGO TRAVEL &amp;
                    TOURS INC., reference do hereby remise, release, acquit and forever discharge
                    and agree to hold harmless FOREX CARGO TRAVEL &amp; TOURS. INC., its
                    parent, affiliate or subsidiary companies, their stockholders, officers, directors,
                    agents or employees and their successors-in-interest from any and all actions,
                    claims for sum of money, demands, complaints, liabilities, obligations, suits,
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
                    and is binding upon Shipper and its successors and assigns.</p>
            </div>

            <div id="page1-footer">
                <P id="line">_____________________</P>
                <p id="sender">SENDER SIGNATER</p>
                
            </div>
            <div id="page1-footer2">

              <P id="line2" >_______________________</P>
              <p id="date">DATE</p>
                
            </div>
          
          
     
    </div>
    <div class="page-break" size="A4">
        
            <h4>THIS FORM OF A CONSOLIDATION SHIPMENT COVERED BY A DELIVERY ORDER DECLARED HEREIN</h4>
      
        <div id="left-content2">
            <p>By tendering goods and personal effects for shipment via Forex Cargo Travel
                &amp; Tours (“Company”). The shipper agrees to the terms and conditions stated
                herein and the declaration of the shipper made in the invoice which are
                incorporated herein by reference. No agent or employee of “company” or the
                shipper may alter these terms and condition.<br>
                <span style="font-weight: bold;">1. THE INVOICE</span><br>
                The “Company” invoice is non-negotiable, and the shipper acknowledges
                that it has been prepared by the shipper or by the “Company” goods
                transported hereunder, or it is the authorized agent of the owner of the goods,
                and that it hereby accepts the “Company’s” terms and conditions for itself and
                as agent for and behalf of any other person having interest in the shipment.<br>
                <span style="font-weight: bold;">2. SHIPPER’S OBLIGATIONS AND ACKNOWLEDGEMENTS</span><br>
                The shippers warrants that each article in the shipment is properly
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
                or warehousing the shipment pending disposition.<br>
                <span style="font-weight: bold;">3. THE RIGHT OF INSPECTION OF SHIPMENT</span><br>
                The “Company” has the right, but not the obligation to inspect any
                shipment including, without limitation, opening the shipment.<br>
                <span style="font-weight: bold;">4. LIEN ON GOODS SHIPPED</span><br>
                The “Company” shall have a lien on any goods shipped for all freight
                charges, customs duties, advances, or other charges of any kind arising out of
                the transportation hereunder and may refuse to surrender possession of the
                goods until such charges are paid.<br>
                <span style="font-weight: bold;">.5. LIMITATION OF LIABILITY</span><br>
                The liability of the “Company” for lost shipment(s), under this invoice, is
                limited to:<br>
                <ul>a) CDN $100 per package (for regular box, TV (regardless of size),
                irregular box/shipment which dimension is equivalent to or bigger
                than regular box).<br>
                b) CDN $75 per package (for bagahe box or irregular box/shipment
                which dimension is equivalent to bagahe box).<br>
                c) CDN $50 per package (for bulilit box) excluding those which are
                shipped under “Company” promotions or deals.
                </ul><br>
                <span style="font-weight: bold; ">6. CONSEQUENTIAL DAMAGES EXCLUDED</span><br>
                The “Company” shall not be liable, in any event, for any consequential or
                special damages or other indirect loss, however arising whether or not the
                “Company” had knowledge that such damage might be incurred, including but
                not limited to, loss of income, profits interest, utility or loss of market.<br>
                <span style="font-weight: bold;">7. LIABILITIES NOT ASSUMED</span><br>
                While the “Company” endeavors to exercise its best efforts to provide
                expeditious delivery in accordance with regular delivery schedules. The
                “Company” WILL NOT, UNDER ANY CIRCUMSTANCES, BE LIABLE FOR DELAY
                IN PICKUP, TRANSPORTATION OR DELIVERY OF ANY SHIPMENT REGARDLESS
                OF THE CAUSES OF SUCH DELAY. Further, the “Company” shall not be liable
                for any loss, damage, mis delivery or non-delivery:<br>
                <ul>a) Due to act of nature force majeure occurrence or any cause
                reasonably beyond the control of the “Company”<br>
                b) Caused by:</ul></p>
        </div>
        <div id="right-content2">
            <p><ul>1. The act, default or omission of the shipper, the consignee or any
                other party who claims an interest in the shipment (including
                violation of any term or condition hereof) or of any person other
                than the “Company” or of any Customs or other Government
                officials or of any postal service, forwarded or other entity or
                person to whom a shipment is tendered by the “Company” for
                transportation to any location not regularly served by the
                “Company” regardless of whether the shipper requests or had no
                knowledge of such third-party delivery arrangement.<br>
                2. The nature of the shipment or any defect, characteristics, or
                inherent vice thereof.<br>
                3. Electrical or magnetic injury, erasure, or other such damage to
                electronic or photographic images or recordings in any form.<br>
                c) Value goods and personal effects not declared in invoice<br>
                d) The “Company” is not liable for accidental breakage and/or
                leakage of items inside the box. It is the responsibility of the
                shipper to pack the items properly and securely.</ul><br>
                
                <span style="font-weight: bold;">8. CLAIMS</span><br>
               <ul> a) Claims or complaint should be advised by the sender within 24 to
                48 hours. From the date of delivery to destination through email,
                phone or text message (SMS). The “Company” will then request for
                proof of delivery (POD) and pictures from its counterpart in the
                destination and send Complaint Form to sender. The sender has 20
                days to fill up and submit the complaint form to the “Company”
                through any means of communication or personal visit to any of
                the “Company “offices or agent. The “Company” will not accept or
                assist any claim beyond 20 days from the date of delivery of cargo
                to the destination.<br>
                b) No claim will be accepted and/or entertained until all
                transportation and shipping charges have been paid to the
                “Company”.<br>
                c) See section 5-LIMITATION OF LIABILITY</ul><br>
                <span style="font-weight: bold;">9. APPLICABILITY</span><br>
                These terms and conditions shall apply to and inure to the benefit of the
                “Company” and its authorized agents and affiliated companies, and the
                officers, directors, and employees.
                <span style="font-weight: bold;">10. MATERIALS NOT ACCEPTABLE FOR TRANSPORT</span><br>
                The “Company” will not accept commercial goods (more than a dozen of
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
                shipment may be carried.<br>
               11. Any expenses incurred by the” Company” on behalf of Shippers including, but
                not limited to, taxes interests’ penalties, fines surcharges, duties, etc, arising
                from non declaration or misdirection shall be reimbursed or refunded by
                shipper upon submission by the “Company” of proper proof or evidence for
                such expenses. In such event, the company is entitled to hold, retain or
                impound the shipment as surely for payment until said refund or
                reimbursement is fully satisfied.</p>
        </div>
        <div id="footer">
            <p>DROP YOUR BOX HERE<br>
                CALGARY 328 39AVE SE AB T2G 1X6 Tel 403-873-6739<br>
                EDMONTON 16722-113 Ave., N.W., AB T5M 2X3   Tel. 780-710-6739</p>
        </div>
    </div>
    
</body>
</html>