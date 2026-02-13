<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $booking->order_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
        }

        .invoice-wrapper {
            padding: 40px;
            background: white;
            border: 1px solid #ddd;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2c3e50;
        }

        .company-info h1 {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .company-info p {
            color: #666;
            font-size: 13px;
        }

        .invoice-details {
            text-align: right;
        }

        .invoice-details .invoice-number {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            font-family: 'Courier New', monospace;
            margin-bottom: 5px;
        }

        .invoice-details p {
            font-size: 12px;
            color: #666;
        }

        .status-badge {
            display: inline-block;
            background: #27ae60;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            margin-top: 10px;
        }

        .content {
            margin-bottom: 30px;
        }

        .row {
            display: flex;
            gap: 40px;
            margin-bottom: 30px;
        }

        .column {
            flex: 1;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }

        .info-item {
            font-size: 13px;
            margin-bottom: 8px;
            line-height: 1.8;
        }

        .info-label {
            color: #666;
            font-weight: normal;
        }

        .info-value {
            color: #2c3e50;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }

        table thead {
            background-color: #2c3e50;
            color: white;
        }

        table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        table tbody tr:last-child td {
            border-bottom: 2px solid #2c3e50;
        }

        .details-table {
            width: 100%;
            margin-bottom: 30px;
        }

        .details-table tr {
            border-bottom: 1px solid #eee;
        }

        .details-table td {
            padding: 10px 0;
            font-size: 13px;
        }

        .details-table td:first-child {
            color: #666;
        }

        .details-table td:last-child {
            text-align: right;
            color: #2c3e50;
            font-weight: bold;
        }

        .summary {
            margin-top: 40px;
            border-top: 2px solid #2c3e50;
            border-bottom: 2px solid #2c3e50;
            padding: 20px 0;
        }

        .summary-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .summary-row-label {
            color: #666;
            margin-right: 20px;
            min-width: 150px;
            text-align: right;
        }

        .summary-row-value {
            color: #2c3e50;
            font-weight: bold;
            min-width: 150px;
            text-align: right;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            padding-top: 20px;
            font-size: 18px;
            border-top: 2px solid #2c3e50;
        }

        .total-label {
            color: #2c3e50;
            font-weight: bold;
            margin-right: 20px;
            min-width: 150px;
            text-align: right;
        }

        .total-value {
            color: #27ae60;
            font-weight: bold;
            min-width: 150px;
            text-align: right;
            font-size: 20px;
        }

        .payment-status {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 4px;
            margin-top: 30px;
            border-left: 4px solid #27ae60;
        }

        .payment-status-item {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .payment-status-item:last-child {
            margin-bottom: 0;
        }

        .payment-status-label {
            color: #666;
        }

        .payment-status-value {
            color: #2c3e50;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #999;
        }

        .footer-text {
            margin-bottom: 8px;
        }

        .terms {
            font-size: 10px;
            color: #999;
            line-height: 1.6;
        }

        @media print {
            body {
                background-color: white;
            }
            .invoice-wrapper {
                border: none;
                box-shadow: none;
            }
        }

        .divider {
            height: 1px;
            background-color: #eee;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="invoice-wrapper">
            <!-- Header -->
            <div class="header">
                <div class="company-info">
                    <h1>INVOICE</h1>
                    <p>{{ config('app.name', 'Futsal Booking') }}</p>
                </div>
                <div class="invoice-details">
                    <div class="invoice-number">{{ $booking->order_id }}</div>
                    <p>Invoice</p>
                    <div class="status-badge">✓ PAID</div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="content">
                <!-- Customer & Payment Info -->
                <div class="row">
                    <div class="column">
                        <div class="section-title">Customer Information</div>
                        <div class="info-item">
                            <span class="info-value">{{ $booking->nama }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span><br>
                            <span class="info-value">{{ Auth::user()->email ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone:</span><br>
                            <span class="info-value">{{ Auth::user()->phone ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Region:</span><br>
                            <span class="info-value" style="text-transform: capitalize;">{{ $booking->region }}</span>
                        </div>
                    </div>

                    <div class="column">
                        <div class="section-title">Payment Information</div>
                        @if($booking->payment)
                            <div class="info-item">
                                <span class="info-label">Payment Date:</span><br>
                                <span class="info-value">{{ $booking->payment->payment_at ? \Carbon\Carbon::parse($booking->payment->payment_at)->format('d M Y H:i') : '-' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Payment Method:</span><br>
                                <span class="info-value" style="text-transform: capitalize;">{{ $booking->payment->payment_method ?? '-' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Transaction ID:</span><br>
                                <span class="info-value" style="font-size: 11px;">{{ $booking->payment->transaction_id ?? '-' }}</span>
                            </div>
                        @else
                            <p style="color: #999;">No payment data found</p>
                        @endif
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Booking Details -->
                <div class="section-title">Booking Details</div>
                <table class="details-table">
                    <tr>
                        <td>Lapangan (Field)</td>
                        <td>{{ $booking->lapangan }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal (Date)</td>
                        <td>{{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Jam Bermain (Time)</td>
                        <td>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                    </tr>
                    <tr>
                        <td>Durasi (Duration)</td>
                        <td>{{ $booking->durasi }} jam</td>
                    </tr>
                    @if($booking->catatan)
                    <tr>
                        <td>Catatan (Notes)</td>
                        <td>{{ $booking->catatan }}</td>
                    </tr>
                    @endif
                </table>

                <div class="divider"></div>

                <!-- Pricing Summary -->
                <div class="section-title">Pricing Summary</div>
                <div class="summary">
                    <div class="summary-row">
                        <span class="summary-row-label">Price per Hour:</span>
                        <span class="summary-row-value">Rp {{ number_format($booking->harga_per_jam, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-row-label">Duration:</span>
                        <span class="summary-row-value">{{ $booking->durasi }} hours</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">TOTAL PAYMENT:</span>
                        <span class="total-value">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="payment-status">
                    <div class="payment-status-item">
                        <span class="payment-status-label">Booking Status:</span>
                        <span class="payment-status-value" style="text-transform: capitalize;">{{ str_replace('_', ' ', $booking->status) }}</span>
                    </div>
                    <div class="payment-status-item">
                        <span class="payment-status-label">Invoice Date:</span>
                        <span class="payment-status-value">{{ $booking->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-text">
                    Thank you for your booking! Terima kasih telah melakukan pemesanan.
                </div>
                <div class="footer-text">
                    Please keep this invoice for your records. Silakan simpan invoice ini untuk referensi Anda.
                </div>
                <div class="terms">
                    <p>
                        Kebijakan Pembatalan (Cancellation Policy):<br>
                        • Pembatalan hingga 24 jam sebelum jam booking: 100% refund<br>
                        • Pembatalan 1-24 jam sebelum jam booking: 50% refund<br>
                        • Pembatalan kurang dari 1 jam: Tidak ada refund
                    </p>
                </div>
                <div class="footer-text" style="margin-top: 15px;">
                    Generated on {{ now()->format('d M Y H:i:s') }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
