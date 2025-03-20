<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #ffffff; /* Keeps the background clean */
        }

        @page {
            background: white;
            background-color: white;
            margin: 20mm 0; /* Adjusted for better print layout */
        }

        .invoice-container {
            width: 100%;
            max-width: 21cm;
            min-height: auto; /* Allow content to define height */
            padding: 2cm;
            box-sizing: border-box;
            background-color: #ffffff;
            margin: 0 auto;
            margin-top: -40px;
            position: relative;
        }

        .header {
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .details-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .customer-details, .shop-details {
            width: 48%;
        }

        .shop-details {
            text-align: right;
        }

        .invoice-details {
            margin-bottom: 30px;
        }

        .invoice-details h2 {
            margin-bottom: 10px;
        }

        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            table-layout: fixed;
        }

        .invoice-items th, .invoice-items td {
            border: 1px solid #ccc;
            padding: 10px;
        }

        .invoice-items th {
            background-color: #f2f2f2;
        }

        .invoice-items td:nth-child(2),
        .invoice-items td:nth-child(3),
        .invoice-items td:nth-child(4),
        .invoice-items td:nth-child(5),
        .invoice-items td:nth-child(6),
        .invoice-items td:nth-child(7) {
            text-align: right; /* Aligns numeric values for better readability */
        }

        table {
            page-break-inside: auto; /* Allows table to break across pages */
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            display: table-header-group; /* Ensures header repeats on each new page */
        }

        tfoot {
            display: table-row-group; /* Prevents `tfoot` from repeating on every page */
        }

        tr {
            page-break-inside: avoid; /* Prevents row breaking in half */
        }

        .footer {
            text-align: center;
            font-size: 12px;
            padding: 20px 0;
            border-top: 1px solid #ccc;
            margin-top: 30px;
            position: relative;
        }

    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <strong>Invoice</strong><br>
            <span>{{ $invoice->invoice_number }}</span><br>
            <span>{{ $invoice->created_at->format('F d, Y') }}</span>
        </div>

        <!-- Customer & Shop Details in One Line -->
        <div class="details-container">
            <div class="customer-details">
                <h2>Bill To:</h2>
                <p><strong>{{ $invoice->customer->name }}</strong></p>
                <p>Phone: {{ $invoice->customer->mobile }}</p>
                <p>Email: {{ $invoice->customer->email ?? 'N/A' }}</p>
            </div>

            <div class="shop-details">
                <h2>Shop Details:</h2>
                <p><strong>{{SHOP_NAME}}</strong></p>
                <p>{{ SHOP_ADDRESS }}</p>
                <p>Phone: {{ SHOP_PHONE }}</p>
                <p>Email: {{ SHOP_EMAIL }}</p>
            </div>
        </div>
        <table class="invoice-items">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Sub Total</th>
                    <th>Tax</th>
                    <th>Tax Amount</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $quantity = 0;
                    $total_tax = 0.00;
                    $total_sub_total = 0.00;
                @endphp
                @foreach ($invoice->items as $item)
                    @php
                        $quantity += $item->quantity;
                        $tax = $item->variant->product->category->tax;
                
                        // Calculate Base Price (Price before tax)
                        $base_price = $item->unit_price / (1 + ($tax / 100));
                
                        // Calculate subtotal (Base Price * Quantity)
                        $sub_total = $base_price * $item->quantity;

                        $total_sub_total += $sub_total;
                
                        // Calculate tax amount per unit
                        $tax_amount = $base_price * ($tax / 100);

                        $total_tax += $tax_amount;
                
                        // Calculate total amount (Subtotal + Total Tax Amount)
                        $total_amount = $sub_total + ($tax_amount * $item->quantity);
                    @endphp
                    <tr>
                        <td>{{ $item->variant->product->name }} - {{ $item->variant->quantity }}g</td>
                        <td>{{ number_format($base_price, 2) }}</td>  <!-- Price Before Tax -->
                        <td>{{ $item->quantity }}</td>  <!-- Quantity -->
                        <td>{{ number_format($total_sub_total, 2) }}</td>  <!-- Subtotal (Base Price * Quantity) -->
                        <td>{{ number_format($tax, 2) }}%</td>  <!-- Tax Percent -->
                        <td>{{ number_format($tax_amount, 2) }}</td>  <!-- Tax Amount Per Unit -->
                        <td>{{ number_format($total_amount, 2) }}</td>  <!-- Final Amount -->
                    </tr>
                @endforeach
            
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total</td>
                    <td>{{ $quantity }}</td>
                    <td>{{ number_format($total_sub_total, 2) }}</td>
                    <td>Tax</td>
                    <td>{{ number_format($total_tax, 2) }}</td>
                    <td>{{ number_format($invoice->sub_total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <table class="invoice-items">
            <thead>
                <th>SubTotal</th>
                <th>Tax</th>
                <th>Total</th>
                <th>Final Price</th>
                <th>Paid Amount</th>
                <th>Status</th>
            </thead>
            <tbody>
                <tr>
                    <td>{{ number_format($total_sub_total, 2) }}</td>
                    <td>{{ number_format($total_tax, 2) }}</td>
                    <td>{{ $invoice->sub_total }}</td>
                    <td>{{ $invoice->final_price }}</td>
                    <td>{{ $invoice->amount_paid }}</td>
                    <td>{{ $invoice->balance_amount == 0 ? 'PAID' : 'NOT PAID' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>If you have any questions, feel free to contact us.</p>
        </div>
    </div>
</body>

</html>
