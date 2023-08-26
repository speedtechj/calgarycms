<table>
    <thead>
        <tr>
            <th>Batch #</th>
            <th>Manual Invoice #</th>
            <th>Invoice</th>
            <th>Sender</th>
            <th>Destination</th>
            @foreach ($boxtype as $boxtypes)
                <th>{{ $boxtypes->description }}</th>
            @endforeach
            <th>Discount</th>
            <th>Extra Charge</th>
            <th>Total Amount</th>
            @foreach ($paymenttype as $paymenttypes)
                <th> {{ $paymenttypes->name }}</th>
            @endforeach
            
        </tr>
    </thead>
    <tbody>

        @foreach ($booking as $booking)
            <tr>
                <td>{{ $booking->batch->batchno }}</td>
                <td>{{ $booking->manual_invoice }}</td>
                <td>{{ $booking->booking_invoice }}</td>
                <td>{{ $booking->sender->full_name }}</td>
                <td>{{$booking->receiveraddress->provincephil->name}}</td>
                @foreach ($boxtype as $boxtypes)
                        @php
                            $boxtypecnt = 0;
                        @endphp
                        @if ($booking->boxtype_id == $boxtypes->id)
                            @php
                                $boxtypecnt = 1;
                            @endphp
                            @if ($booking->discount_id == null || $booking->discount_id == 0)
                                <td>{{ $booking->total_price }}</td>
                                
                            @else
                            <td>{{$booking->total_price + $booking->discount->discount_amount}}</td>
                            @endif
                            
                        @endif
                       @if ($boxtypecnt == 0)
                            <td></td>
                            
                        @endif 
                @endforeach
                @if ($booking->discount_id !== null)
                <td>{{ $booking->discount->discount_amount }}</td>
            @else
                <td></td>
            @endif
            @if ($booking->extracharge_amount !== null)
                <td>{{ $booking->extracharge_amount }}</td>
            @else
                <td></td>
            @endif
            <td>{{$booking->total_price}}</td>
                @foreach ($paymenttype as $paymenttypes)
                    @php
                        $paymenttypecnt = 0;
                    @endphp
                    @foreach ($booking->bookingpayment as $bookingpayment)
                        @if ($bookingpayment->paymenttype_id == $paymenttypes->id)
                            @php
                                $paymenttypecnt = 1;
                            @endphp
                            <td>{{ $bookingpayment->payment_amount }}</td>
                        @endif
                    @endforeach
                    @if ($paymenttypecnt == 0)
                        <td></td>
                    @endif
                @endforeach
               
            </tr>   
        @endforeach
    </tbody>
</table>
