<!DOCTYPE html>
<html>
<body>
<div class="container">
    <div style="background: #354adf;display: flex;justify-content: space-around;margin: 20px">
        <div style="font-size: 48px">Awesome Shop</div>
        <div style="">
            <ul style="list-style-type: none">
                <li>Awesome Shop Inc.</li>
                <li>(123) 123-456789</li>
                <li><a href="#" style="color: black;text-underline: none">Awesomeshop@example.com</a></li>
                <li>awesomeshop.com</li>
            </ul>
        </div>
    </div>
    <div style="margin: 20px">
        <p>UTILITY BILL</p>
    </div>
    <div style="display: flex;justify-content: space-around;">
        <div style="width: 100%">
            <h5>Account No.</h5>
            <p>123456789</p>
            <h5>Account Name</h5>
            <p>{{$name}}</p>
            <h5>Address</h5>
            <p>{{$address}}</p>
        </div>
        <div style="background: #df625c;width: 100%">
            <h5>Statement Date</h5>
            <p>{{$date}}</p>
            <h5>Statement Date</h5>
            <p>{{$date}}</p>
            <h5>Statement Date</h5>
            <p>{{$date}}</p>
        </div>
    </div>
    <div>
        <p>Product(s) Information</p>
        <table style="width: 100%">
            <tr style="border-bottom: 1px solid black">
                <th style="text-align: center;padding: 5px">Product Name</th>
                <th style="text-align: center;padding: 5px">Price</th>
                <th style="text-align: center;padding: 5px">Quantity</th>
            </tr>
            @foreach ($items as $item)
                <tr style="border-bottom: 1px solid black">
                    <td style="text-align: center;padding: 5px">{{ $item->product->name }}</td>
                    <td style="text-align: center;padding: 5px">{{ $item->price }}</td>
                    <td style="text-align: center;padding: 5px">{{ $item->quantity }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div style="float: right">
        <p>Total Amount</p>
        {{$total}}
    </div>
</div>
</body>
</html>
