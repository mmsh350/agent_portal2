<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Meta Data -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Easy Verifications for your Business" />
    <meta name="keywords" content="NIMC, BVN, ZEPA, Verification, Airtime,Bills, Identity">
    <meta name="author" content="Zepa Developers">
    <title>ZEPA Solutions - @yield('title') </title>
    <!-- fav icon -->
    <link rel="icon" href="{{ asset('assets/home/images/favicon/favicon.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .receipt-container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #dfe3e8;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        #receipt {
            margin: 5px auto;
            padding: 3px auto;
            /* Internal padding of 15px for content spacing */
            overflow: hidden;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 12px;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 25px;
            color: #28a745;
        }

        .receipt-header i {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .receipt-header h2 {
            font-weight: bold;
            font-size: 24px;
            color: #333333;
        }

        .receipt-header p {
            font-size: 16px;
            color: #666666;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .receipt-table th,
        .receipt-table td {
            padding: 12px 15px;
            text-align: left;
            vertical-align: middle;
            font-size: 15px;
        }

        .receipt-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #444444;
            border-bottom: 2px solid #dfe3e8;
            width: 35%;
        }

        .receipt-table td {
            background-color: #ffffff;
            font-weight: normal;
            color: #333333;
            border-bottom: 1px solid #eceeef;
        }

        .total-amount {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            padding: 15px;
            background-color: #eafce8;
            color: #218838;
            border-top: 2px solid #dfe3e8;
            border-radius: 5px;
        }

        .receipt-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #777777;
        }

        .buttons-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            /* Adds spacing between buttons */
            margin-top: 20px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            /* Smaller font size */
            padding: 8px 14px;
            /* Compact padding for smaller buttons */
            border: none;
            border-radius: 50px;
            /* Rounded buttons for a modern look */
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            /* Removes underline for anchor-like buttons */
            color: #fff;
            font-weight: 500;
            /* Slightly bold for emphasis */
        }

        .btn i {
            margin-right: 6px;
            /* Adds spacing between icon and text */
            font-size: 16px;
        }

        /* Primary Button: Blue Gradient */
        .btn-primary {
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            box-shadow: 0 4px 6px rgba(79, 172, 254, 0.4);
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
            box-shadow: 0 6px 8px rgba(0, 123, 255, 0.5);
            transform: scale(1.05);
        }

        /* Secondary Button: Gray with Neutral Gradient */
        .btn-secondary {
            background: linear-gradient(90deg, #adb5bd 0%, #6c757d 100%);
            box-shadow: 0 4px 6px rgba(173, 181, 189, 0.4);
        }

        .btn-secondary:hover {
            background: linear-gradient(90deg, #6c757d 0%, #495057 100%);
            box-shadow: 0 6px 8px rgba(108, 117, 125, 0.5);
            transform: scale(1.05);
        }

        /* Success Button: Green Gradient */
        .btn-success {
            background: linear-gradient(90deg, #42e695 0%, #3bb2b8 100%);
            box-shadow: 0 4px 6px rgba(66, 230, 149, 0.4);
        }

        .btn-success:hover {
            background: linear-gradient(90deg, #28a745 0%, #218838 100%);
            box-shadow: 0 6px 8px rgba(40, 167, 69, 0.5);
            transform: scale(1.05);
        }

        /* Active State: Pressed Effect */
        .btn:active {
            transform: scale(0.95);
        }

        .buttons-container.hidden {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            /* Smooth hide/show effect */
        }



        @media (max-width: 768px) {
            #receipt {
                transform: scale(0.85);
            }
        }

        @media (max-width: 576px) {
            #receipt {
                transform: scale(0.85);
            }
        }
    </style>

</head>

<body>
    @yield('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        const transactionId = '{{ strtoupper($transaction->referenceId) }}';

        function printReceipt() {
            const buttonsContainer = document.querySelector('.buttons-container');

            // Add the 'hidden' class to hide buttons
            buttonsContainer.classList.add('hidden');

            // Print the receipt
            window.print();

            // Remove the 'hidden' class to show buttons back
            buttonsContainer.classList.remove('hidden');
        }

        const shareButton = document.getElementById('shareButton');
        const downloadButton = document.getElementById('downloadButton');
        const receiptElement = document.getElementById('receipt');
        const buttonsContainer = document.querySelector('.buttons-container');

        shareButton.addEventListener('click', async () => {
            receiptElement.style.width = "600px";
            receiptElement.style.height = "700px";
            try {

                buttonsContainer.classList.add('hidden');

                const canvas = await html2canvas(receiptElement, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                });

                const imageData = canvas.toDataURL('image/png', 1.0);

                buttonsContainer.classList.remove('hidden');

                const receiptName = `receipt_${transactionId}_${new Date().toISOString().split('T')[0]}.png`;

                if (navigator.canShare && navigator.canShare({
                        files: [new File([], '')]
                    })) {

                    const file = new File([await (await fetch(imageData)).blob()], receiptName, {
                        type: 'image/png',
                    });

                    await navigator.share({
                        files: [file],
                        title: 'Transaction Receipt',
                        text: 'Here is the transaction receipt.',
                    });
                }
            } catch (error) {
                console.error('Error sharing the receipt:', error);
            }
        });

        downloadButton.addEventListener('click', async () => {
            receiptElement.style.width = "600px";
            receiptElement.style.height = "700px";

            try {

                buttonsContainer.classList.add('hidden');

                const receiptName = `receipt_${transactionId}_${new Date().toISOString().split('T')[0]}.png`;

                const canvas = await html2canvas(receiptElement, {

                    scale: 2,
                    useCORS: true,
                    logging: false,
                });


                const imageData = canvas.toDataURL('image/png', 1.0);

                buttonsContainer.classList.remove('hidden');

                const downloadLink = document.createElement('a');
                downloadLink.href = imageData;
                downloadLink.download = receiptName;
                downloadLink.click();
            } catch (error) {
                console.error('Error downloading the receipt:', error);
            }
        });
    </script>
</body>

</html>
