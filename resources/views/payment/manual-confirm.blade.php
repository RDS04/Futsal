<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manual Payment Confirmation - Testing</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-50">
    <div class="min-h-screen py-10 px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Warning Banner -->
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-6 rounded">
                <strong>⚠️ Development Only:</strong> This page is only available in development mode for testing payment webhook locally.
            </div>

            <!-- Booking Details -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Confirmation Test</h1>
                <p class="text-gray-600 mb-6">Manually trigger webhook to simulate Midtrans payment notification</p>

                <div class="space-y-4 bg-gray-50 p-4 rounded">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order ID</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $orderId }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Status</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $booking->status }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lapangan</label>
                        <p class="text-gray-900">{{ $booking->lapangan }} ({{ $booking->jam_mulai }} - {{ $booking->jam_selesai }})</p>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
                <h2 class="font-bold text-blue-900 mb-3">How This Works:</h2>
                <ol class="list-decimal list-inside space-y-2 text-blue-900 text-sm">
                    <li>This page simulates the Midtrans webhook notification process</li>
                    <li>Click "Confirm Payment" to trigger the webhook handler</li>
                    <li>The booking status should update to "paid" in the database</li>
                    <li>A payment record will be created in the payments table</li>
                    <li>Check the database to verify the changes</li>
                </ol>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('show.payment') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-bold py-3 px-4 rounded-lg text-center transition">
                    Back to Payment
                </a>
                <button id="confirm-btn" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition">
                    ✓ Confirm Payment (Trigger Webhook)
                </button>
            </div>

            <!-- Result Section (hidden by default) -->
            <div id="result" class="hidden mt-6 p-4 rounded-lg border-l-4">
            </div>
        </div>
    </div>

    <script>
        document.getElementById('confirm-btn').addEventListener('click', async () => {
            const btn = document.getElementById('confirm-btn');
            btn.disabled = true;
            btn.textContent = 'Processing...';

            try {
                const response = await fetch('/midtrans/test-webhook?order_id={{ $orderId }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                const data = await response.json();
                const resultDiv = document.getElementById('result');
                
                if (response.ok) {
                    resultDiv.className = 'bg-green-50 border-green-500 text-green-900';
                    resultDiv.innerHTML = `
                        <h3 class="font-bold mb-2">✓ Payment Confirmed Successfully!</h3>
                        <p class="mb-2">Order ID: <strong>${data.order_id}</strong></p>
                        <p class="mb-2">Status Code: <strong>${data.status_code}</strong></p>
                        <p class="text-sm mb-3">${data.instruction}</p>
                        <div class="bg-green-100 p-3 rounded text-sm">
                            The booking status in the database should now be "paid" and a payment record has been created.
                            You can verify by checking the bokings and payments tables.
                        </div>
                    `;
                } else {
                    resultDiv.className = 'bg-red-50 border-red-500 text-red-900';
                    resultDiv.innerHTML = `
                        <h3 class="font-bold mb-2">❌ Error</h3>
                        <p>${data.error || 'Unknown error occurred'}</p>
                    `;
                }
                resultDiv.classList.remove('hidden');

                // Refresh page after 3 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            } catch (error) {
                const resultDiv = document.getElementById('result');
                resultDiv.className = 'bg-red-50 border-red-500 text-red-900';
                resultDiv.innerHTML = `
                    <h3 class="font-bold mb-2">❌ Error</h3>
                    <p>${error.message}</p>
                `;
                resultDiv.classList.remove('hidden');
                btn.disabled = false;
                btn.textContent = '✓ Confirm Payment (Trigger Webhook)';
            }
        });
    </script>
</body>
</html>
