<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Slip</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <style>
        .card-bold-border {
            border-width: 1px;
            border-color: #000;
            /* Change the color if needed */
        }

        table {
            border-collapse: separate;
            /* Important for border-spacing to work */
            border-spacing: 0 10px;
            /* Adjust the second value for row spacing */
        }

        th,
        td {

            padding: 5px;
        }

        th {
            ;
            /* Example background color */
        }

        @media print {
            @page {
                size: portrait;
            }

        }
    </style>
</head>

<body>
    <div class="container" id="content">
        <div class="row mt-5">

            <div class="col-md-12">
                <div class="row mb-4 mt-5">
                    <div class="col-md-5 ">
                        <span> BVN Matching System: Verificatiom </span>
                    </div>
                    <div class="col-md-7">
                        @php
                            $now = new DateTime();

                            $date = $now->format('D M d,y h:i:s A');
                        @endphp

                        <span><b>Date of Issue:</b>{{ $date }}</span>
                    </div>
                </div>
                <div class="card card-bold-border mb-3" style="width: 45rem; ">
                    <ul class="list-group list-group-flush" style="font-weight:bold">
                        <li class="list-group-item border border-dark my-0 py-0">
                            <h5><b>Verification successful</b></h5>
                        </li>
                        <li class="list-group-item  border border-dark mb-0">
                            <center>
                                <img src="{{ asset('assets/images/thumbsup.png') }}" class="mb-2" width="20%">
                                <p class="fw-bold my-0 py-0">The Bank Verification Number {{ $veridiedRecord->idno }}
                                    has successfully been verified.</p>
                            </center>
                        </li>
                    </ul>
                </div>

                <div class="card card-bold-border" style="width: 45rem; ">
                    <ul class="list-group list-group-flush" style="font-weight:bold">
                        <li class="list-group-item border border-dark my-0 py-0">
                            <h5><b>Verification Data</b></h5>
                        </li>
                        <li class="list-group-item  border border-dark mb-0">
                            <table style=" font-size:16px; font-family: "Times New Roman", Times, serif;" width="80%"
                                class="mb-4">

                                <tr>
                                    <td>First Name</td>
                                    <td id="name1">{{ $veridiedRecord->first_name }}</td>
                                </tr>

                                <tr>
                                    <td>Middle Name</td>
                                    <td>

                                        @if (!empty($veridiedRecord->middle_name))
                                            {{ $veridiedRecord->middle_name }}
                                        @else
                                            <i class="bi bi-info-circle-fill"></i> Missing
                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <td>Surname</td>
                                    <td id="name2">{{ $veridiedRecord->last_name }}</td>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <td>{{ $veridiedRecord->gender }}</td>
                                </tr>
                                <tr>
                                    <td>Date of Birth</td>
                                    <td> {{ date('d/m/Y', strtotime($veridiedRecord->dob)) }}</td>
                                </tr>

                                <tr>
                                    <td>Phone Number</td>
                                    <td>{{ $veridiedRecord->phoneno }}</td>
                                </tr>
                            </table>
                            <img src="data:image/;base64,{{ $veridiedRecord->photo }}" width="400px" height="400px">


                        </li>
                    </ul>

                </div>

            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jspdf@2.4.0/dist/jspdf.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
        <script>
            window.onload = function() {
                const {
                    jsPDF
                } = window.jspdf;

                var names = document.getElementById("name1").innerHTML + " " + document.getElementById("name2").innerHTML;


                html2canvas(document.getElementById('content'), {
                    dpi: 300, // Set to 300 DPI
                    scale: 2, // Adjusts the scale of the screenshot
                    logging: true, // Enable logging (useful for debugging)
                    useCORS: true // Allow cross-origin images
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jsPDF('p', 'mm', 'a4');

                    // Determine screen size
                    const isSmallScreen = window.innerWidth < 768; // Example breakpoint for small screens

                    // PDF dimensions
                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const pageHeight = pdf.internal.pageSize.getHeight();

                    let imgWidth = isSmallScreen ? pageWidth - 20 : 250; // Smaller width for small screens
                    let imgHeight = (canvas.height * imgWidth) / canvas.width;

                    if (imgHeight > pageHeight) {
                        imgHeight = pageHeight - 20; // Adjust height if necessary
                        imgWidth = (canvas.width * imgHeight) / canvas.height;
                    }

                    // Center the image horizontally for small screens
                    const xOffset = isSmallScreen ? (pageWidth - imgWidth) / 2 : 10;

                    // Add image to PDF
                    pdf.addImage(imgData, 'PNG', xOffset, 10, imgWidth, imgHeight, '', 'FAST');

                    // For small screens, ensure it fits on one page
                    if (isSmallScreen) {
                        pdf.save(names + ' - Premium Slip.pdf');
                    } else {
                        let heightLeft = imgHeight;

                        while (heightLeft >= 0) {
                            if (heightLeft - imgHeight < 0) {
                                pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight, '', 'FAST');
                            } else {
                                // pdf.addPage();
                                pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight, '', 'FAST');
                            }
                            heightLeft -= pageHeight;
                        }

                        pdf.save(names + ' - Premium Slip.pdf');
                    }
                });
            };
        </script>
</body>

</html>
