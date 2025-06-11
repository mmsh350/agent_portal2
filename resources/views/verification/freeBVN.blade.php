<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Standard Slip</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
  <style>
    .card-bold-border {
      border-width: 1px;
      border-color: #000;
      /* Change the color if needed */
    }

    small-table.small-table {
      width: 300px;
      /* Adjust the width to make the table smaller */
      border-collapse: collapse;
    }

    .small-table td {
      border: 1px solid #e6e7e8;
      padding: 1px;
    }

    .small-table th {
      border: 1px solid #e6e7e8;
      padding: 1px;
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
      <div class="col-md-9">
        <div class="row mb-3 border border-dark">
          <div class="col-md-3 pb-2 pt-2 ">
            <img src="{{ asset('assets/images/bvn.jpg') }}" alt="Logo" width="150px">
          </div>
          <div class="col-md-8 pb-2 pt-2">
            <center>
              <p class="mt-3">The Bank Verification Number has successfuly been verified.</p>
            </center>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="row">
          <div class="col">
            @php
            // Set the desired timezone if needed
            date_default_timezone_set('Europe/London');

            // Get the current date and time
            $dateTime = new DateTime();

            // Format the date as desired
            $formattedDate = $dateTime->format(DateTime::ATOM);
            @endphp
            <p class="float-right">Date: {{ $formattedDate }}</p>
          </div>
        </div>

      </div>
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-4">
            <img src="data:image/;base64,{{$veridiedRecord->photo}}" alt="Logo" width="280px" height="378">
          </div>
          <div class="col-md-8">
            <table class="small-table" width="100%">
              <thead>
                <tr>
                  <th colspan="2" class="text-center"> Personal Information </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td width="35%">BVN</td>
                  <td> {{ $veridiedRecord->idno}}</td>
                </tr>
                <tr>
                  <td width="35%">NIN</td>
                  <td> {{$veridiedRecord->nin}}</td>
                </tr>
                <tr>
                  <td width="35%">First Name</td>
                  <td id="name1">{{$veridiedRecord->first_name}}</td>
                </tr>
                <tr>
                  <td width="35%">Last Name</td>
                  <td id="name2">{{$veridiedRecord->last_name}}</td>
                </tr>
                <tr>
                  <td width="35%">Middle Name</td>
                  <td>{{$veridiedRecord->middle_name}}</td>
                </tr>
                <tr>
                  <td width="35%">Phone</td>
                  <td>{{$veridiedRecord->phoneno}}</td>
                </tr>
                <tr>
                  <td width="35%">Email</td>
                  <td>{{$veridiedRecord->email}}</td>
                </tr>
                <tr>
                  <td width="35%">Date of Birth</td>
                  <td>{{date("d-M-Y", strtotime($veridiedRecord->dob))}}</td>
                </tr>
                <tr>
                  <td width="35%">Gender</td>
                  <td>{{$veridiedRecord->gender}}</td>
                </tr>
                <tr>
                  <td width="35%">Enrollment Bank</td>
                  <td>{{$veridiedRecord->enrollment_bank}}</td>
                </tr>
                <tr>
                  <td width="35%">Enrollment Branch</td>
                  <td>{{$veridiedRecord->enrollment_branch}}</td>
                </tr>
                <tr>
                  <td width="35%">Registration Date</td>
                  <td></td>
                </tr>
                <tr>
                  <td>Address</td>
                  <td></td>

                </tr>
              </tbody>
            </table>
          </div>

        </div>

      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jspdf@2.4.0/dist/jspdf.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
  <script>
    window.onload = function () {
    const { jsPDF } = window.jspdf;
    
    var names = document.getElementById("name1").innerHTML+" "+document.getElementById("name2").innerHTML;
    
    
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
            pdf.save(names + ' - Standard Slip.pdf');
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
    
            pdf.save(names + ' - Standard Slip.pdf');
        } 
    });
    };
  </script>
</body>

</html>