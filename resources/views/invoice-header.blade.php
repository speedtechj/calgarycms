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
    table {
        width: 100%;
        margin:0px;
        padding:0px;
    }
    .logo {
        width: 150px;
        height: 50px;
        margin:0px;
        padding:0px;
    }
    p {
        margin:0px;
        padding:0px;
    }
    
    .heading-1{
       
        font-size: 20px;
        font-weight: bold;
        margin:0px;
        padding:0px;
        font-family: Arial, Helvetica, sans-serif;
        font-style: italic;
       
        
    }
    .horizontal-line {
        border-top: 1px solid black;
        margin:0px;
        padding:0px;
        font-size: 14px;
    }
</style>
    
</head>
<body>
    
 <table class="table">
    <tr>
        <td width="60%">
         <img class="logo" src="{{ public_path("/logo.png") }}" alt="logo"  />
         
        </td>
        <td width="40%" align="right">
            <p class="heading-1">Forex Cargo Travel & Tours</p>
            <p>328-39 Avenue SE, Calgary, AB T2G 1X6</p>
            <p>Phone: 403-873-6730</p>
            <p>www.forexcargodeals.com/calgary</p>
        </td>
    </tr>
 </table>
    
</body>
</html>