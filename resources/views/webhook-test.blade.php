<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhook Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">ToyyibPay Webhook Test</h1>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Current Configuration</h2>
                <div class="bg-gray-100 p-4 rounded-md">
                    <p class="text-sm text-gray-600 mb-2">Webhook URL:</p>
                    <p class="font-mono text-blue-600 break-all">
                        {{ url('/toyyibpay/callback') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        This is the URL you should configure in your ToyyibPay dashboard
                    </p>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Test Form</h2>
                <form id="webhookForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bill Code
                        </label>
                        <input type="text" id="billCode" name="billcode"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="e.g., qxzxzdki" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Status
                        </label>
                        <select id="paymentStatus" name="billpaymentStatus"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1">1 - Paid</option>
                            <option value="2">2 - Pending</option>
                            <option value="3">3 - Failed</option>
                            <option value="4">4 - Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Order ID (optional)
                        </label>
                        <input type="number" id="orderId" name="order_id"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="e.g., 34">
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">
                        Send Test Webhook
                    </button>
                </form>
            </div>

            <div id="result" class="hidden">
                <h2 class="text-xl font-semibold mb-4">Response</h2>
                <div class="bg-gray-100 p-4 rounded-md">
                    <pre id="responseJson" class="text-sm"></pre>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4">ToyyibPay Dashboard Configuration Steps</h2>
                <div class="space-y-4">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <h3 class="font-semibold text-blue-800 mb-2">Step 1: Find Your Merchant ID</h3>
                        <p class="text-sm text-blue-700">
                            Your Merchant ID is: <code class="bg-blue-100 px-2 py-1 rounded text-xs">qmik8n4d-lpb8-i5rt-98lr-ye5dntxbqz3r</code>
                        </p>
                        <p class="text-xs text-blue-600 mt-1">
                            This ID is already provided in your ToyyibPay dashboard
                        </p>
                    </div>

                    <div class="bg-green-50 border-l-4 border-green-400 p-4">
                        <h3 class="font-semibold text-green-800 mb-2">Step 2: Create a Bill</h3>
                        <p class="text-sm text-green-700">
                            Create a test bill using your test merchant ID to get a bill code for testing.
                        </p>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <h3 class="font-semibold text-yellow-800 mb-2">Step 3: Configure Webhook URL</h3>
                        <p class="text-sm text-yellow-700">
                            In your ToyyibPay dashboard, go to Settings > Webhook URL. Set it to:
                        </p>
                        <p class="font-mono text-sm text-yellow-800 break-all mt-1">
                            {{ url('/toyyibpay/callback') }}
                        </p>
                    </div>

                    <div class="bg-purple-50 border-l-4 border-purple-400 p-4">
                        <h3 class="font-semibold text-purple-800 mb-2">Step 4: Enable Webhook</h3>
                        <p class="text-sm text-purple-700">
                            Make sure the webhook is enabled and configured for the correct payment statuses:
                        </p>
                        <ul class="text-sm text-purple-700 list-disc list-inside mt-2">
                            <li>Status 1: Paid</li>
                            <li>Status 2: Pending</li>
                            <li>Status 3: Failed</li>
                            <li>Status 4: Cancelled</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('webhookForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            // Add additional required fields
            data.msg = 'ok';
            data.status_id = '1';

            try {
                const response = await fetch('{{ url("/webhook/test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                document.getElementById('result').classList.remove('hidden');
                document.getElementById('responseJson').textContent = JSON.stringify(result, null, 2);

                if (response.ok) {
                    document.getElementById('responseJson').parentElement.classList.add('bg-green-50');
                } else {
                    document.getElementById('responseJson').parentElement.classList.add('bg-red-50');
                }

            } catch (error) {
                document.getElementById('result').classList.remove('hidden');
                document.getElementById('responseJson').textContent = error.message;
                document.getElementById('responseJson').parentElement.classList.add('bg-red-50');
            }
        });
    </script>
</body>
</html>