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
            margin-top: -75px;
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
            font-size: 12px;
        }

        .invoice-items td {
            font-size: 12px;
        }

        .invoice-items td:nth-child(2),
        .invoice-items td:nth-child(3),
        .invoice-items td:nth-child(4),
        .invoice-items td:nth-child(5),
        .invoice-items td:nth-child(6),
        .invoice-items td:nth-child(7) {
            text-align: center; /* Aligns numeric values for better readability */
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
            <strong>Invoice No : </strong>
            <span>{{ $invoice->invoice_number }}</span><br>
            <strong>Date : </strong>
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
        <table class="invoice-items" width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th width="30%">Product</th>
                    <th width="10%">Unit Price</th>
                    <th width="10%">Tax</th>
                    <th width="10%">Quantity</th>
                    <th width="15%">Sub Total</th>
                    <th width="10%">Total Tax</th>
                    <th width="15%">Final Price</th>
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
                        $tax = $item->variant->product->category->tax; // Tax Percentage

                        // ✅ Extract Correct Base Price (Before Tax)
                        $basic_price = round($item->unit_price / (1 + ($tax / 100)), 2);

                        // ✅ Subtotal Calculation (Rounded)
                        $sub_total = round($basic_price * $item->quantity, 2);
                        $total_sub_total += $sub_total;

                        // ✅ Correct Total Tax Calculation (Rounded)
                        $total_tax_for_item = round(($item->unit_price - $basic_price) * $item->quantity, 2);
                        $total_tax += $total_tax_for_item;

                        // ✅ Final Total (Rounded)
                        $total_amount = round($sub_total + $total_tax_for_item, 2);
                    @endphp
                    <tr>
                        <td>{{ $item->variant->product->name }} - {{ $item->variant->quantity }}g</td>
                        <td>{{ number_format($basic_price, 2) }}</td>  <!-- Base Price (Before Tax) -->
                        <td>{{ number_format($tax, 2) }}%</td>  <!-- Tax Percentage -->
                        <td>{{ $item->quantity }}</td>  <!-- Quantity -->
                        <td>{{ number_format($sub_total, 2) }}</td>  <!-- Subtotal -->
                        <td>{{ number_format($total_tax_for_item, 2) }}</td>  <!-- Total Tax -->
                        <td>{{ number_format($total_amount, 2) }}</td>  <!-- Final Price -->
                    </tr>
                @endforeach
            
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total</td>
                    <td>{{ $quantity }}</td>
                    <td>{{ number_format($total_sub_total, 2) }}</td>
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
                    <td style="text-align: center; word-wrap: break-word; white-space: normal;">{{ number_format($total_sub_total, 2) }}</td>
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
