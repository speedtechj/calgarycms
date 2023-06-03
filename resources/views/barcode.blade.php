<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Generator</title>
</head>
<style>
div.page{
    position: relative;
    text-align:center;
 
}
#barcode1{
    position:absolute;
    width: 50%;
    left: -10px;
    height: 300px;
    top: 70px;
}
#barcode2{
    position:absolute;
    width: 50%;
    right: -30px;
    height: 300px;
    top:70px;
    
}
#barcode3{
    position:absolute;
    width: 50%;
    left: -10px;
    height: 300px;
    top:515px;
    
}
#barcode4{
    position:absolute;
    width: 50%;
    right: -30px;
    height: 300px;
    top:515px;
    
}
#barcode5{
    position:absolute;
    width: 50%;
    left: -10px;
    height: 300px;
    top:965px;
    
}
#barcode6{
    position:absolute;
    width: 50%;
    right: -30px;
    height: 300px;
    top:965px;
    
    
}
#barcode{

  position: absolute;
  left:80px;  
}
.page p{
    font-size: large;
    font-weight:bold;
    font-family: arial;
}




</style>
<body>
        <div class=page>
       
            <div id="barcode1">
                <p>WWW.FOREXCARGODEALS.COM</p>
                <P>{{ $record->booking_invoice}}</P>
                <div id="barcode">{!! DNS1D::getBarcodeHTML("1244243", 'PHARMA',3.7,160, 'black',true) !!}</div>            
            </div>
            <div id="barcode2">
                <p>WWW.FOREXCARGODEALS.COM</p>
                <P>{{ $record->booking_invoice}}</P>
                <div id="barcode">{!! DNS1D::getBarcodeHTML("1244243", 'PHARMA',3.7,160, 'black',true) !!}</div>            
            </div>
            <div id="barcode3">
                <p>WWW.FOREXCARGODEALS.COM</p>
                <P>{{ $record->booking_invoice}}</P>
                <div id="barcode">{!! DNS1D::getBarcodeHTML("1244243", 'PHARMA',3.7,160, 'black',true) !!}</div>            
            </div>
            <div id="barcode4">
                <p>WWW.FOREXCARGODEALS.COM</p>
                <P>{{ $record->booking_invoice}}</P>
                <div id="barcode">{!! DNS1D::getBarcodeHTML("1244243", 'PHARMA',3.7,160, 'black',true) !!}</div>            
            </div>
            <div id="barcode5">
                <p>WWW.FOREXCARGODEALS.COM</p>
                <P>{{ $record->booking_invoice}}</P>
                <div id="barcode">{!! DNS1D::getBarcodeHTML("1244243", 'PHARMA',3.7,160, 'black',true) !!}</div>            
            </div>
            <div id="barcode6">
                <p>WWW.FOREXCARGODEALS.COM</p>
                <P>{{ $record->booking_invoice}}</P>
                <div id="barcode">{!! DNS1D::getBarcodeHTML("1244243", 'PHARMA',3.7,160, 'black',true) !!}</div>            
            </div>
            
        </div>

</body>
</html>