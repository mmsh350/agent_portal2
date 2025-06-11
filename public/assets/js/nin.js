$("#verifyNIN").on("click", function (event) {

    event.preventDefault();

    let data = new FormData(this.form);
    let validationInfo = document.getElementById("validation-info");
    let download = document.getElementById("download");
    $("#errorMsg").hide();

    var preloader = $('.page-loading');

    function showLoader() {
        preloader.addClass('active').show();
    }

    function hideLoader() {
        preloader.removeClass('active');
        setTimeout(function () {
            preloader.hide();
        }, 1000);
    }

    $.ajax({
        type: "post",
        url: "/user/nin-retrieve",
        dataType: "json",
        data,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

            showLoader();

            $("#download").addClass("d-none");

        },
        success: function (result) {
            $("#loader").hide();

            if (result && result.data) {
validationInfo.innerHTML = `
<div class="border border-light p-3 rounded">
  <div class="row">
    <!-- Image Section -->
    <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
      <img class="img-fluid rounded" src="data:image/;base64, ${result.data.image}" alt="User Image" style="max-width: 250px; height: auto;">
    </div>

    <!-- Data Section -->
    <div class="col-12 col-md-8">
      <div class="table-responsive">
        <table class="table table-sm ">
          <tbody>
            <tr>
              <th style="width: 40%;">NIN</th>
              <td><span id="nin_no" >${result.data.nin}</span></td>
            </tr>
            <tr>
              <th>Tracking ID</th>
              <td>${result.data.trackingId}</td>
            </tr>
            <tr>
              <th>First Name</th>
              <td>${result.data.firstname}</td>
            </tr>
            <tr>
              <th>Surname</th>
              <td>${result.data.surname}</td>
            </tr>
            <tr>
              <th>Middle Name</th>
              <td>${result.data.middlename ?? '—'}</td>
            </tr>

             <tr>
                     <th>Date of Birth</th>
                     <td>${result.data.birthdate}</td>
            </tr>
            <tr>
              <th>Phone No</th>
              <td>${result.data.telephoneno}</td>
            </tr>
            <tr>
              <th>Gender</th>
              <td>${result.data.gender}</td>
            </tr>
            <tr>
              <th>Residence State</th>
              <td>${result.data.residence_state ?? ''}</td>
            </tr>
            <tr>
              <th>Residence LGA / town </th>
              <td>${result.data.residence_lga ?? ''} / ${result.data.residence_town ?? ''}</td>
            </tr>
            <tr>
              <th>Birth State</th>
              <td>${result.data.self_origin_state ?? ''}</td>
            </tr>
            <tr>
              <th>Birth LGA / town </th>
              <td>${result.data.self_origin_lga ?? ''} / ${result.data.self_origin_place ?? ''}</td>
            </tr>
            <tr>
              <th>Address</th>
              <td>${result.data.residence_AdressLine1 ?? ''}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
`;

$("#download").removeClass("d-none"); // show
            } else {
                hideLoader();

                $("#errorMsg").show();
                $("#message").html("Invalid Response");

                setTimeout(function () {
                    $("#errorMsg").fadeOut();
                }, 30000);
            }
        },
        error: function (data) {
            hideLoader();
            $.each(data.responseJSON.errors, function (key, value) {
                $("#errorMsg").show();
                $("#message").html(value);
            });
            setTimeout(function () {
                $("#errorMsg").fadeOut();
            }, 30000);
        },
    });
});

$("#regularSlip").on("click", function (event) {
    let getNIN = $("#nin_no").html();

    fetch("/user/regularSlip/" + getNIN, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
    })
        .then((response) => {
            if (response.ok) {
                // Extract filename from Content-Disposition header
                const contentDisposition = response.headers.get(
                    "Content-Disposition"
                );
                let filename = "document.pdf";
                if (
                    contentDisposition &&
                    contentDisposition.indexOf("attachment") !== -1
                ) {
                    const filenameRegex =
                        /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    const matches = filenameRegex.exec(contentDisposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, "");
                    }
                }
                return response.blob().then((blob) => ({ blob, filename }));
            } else {
                return response.json().then((data) => {
                    // Handle errors
                    $.each(data.errors, function (key, value) {
                        $("#errorMsg2").show();
                        $("#message2").html(value);
                    });
                    setTimeout(function () {
                        $("#errorMsg2").hide();
                    }, 5000);
                });
            }
        })
        .then(({ blob, filename }) => {
            if (blob) {
                // Create a link element, use it to download the blob with the extracted filename
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = filename; // Use the extracted filename
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            // Handle errors
            $.each(data.errors, function (key, value) {
                $("#errorMsg2").show();
                $("#message2").html(value);
            });
            setTimeout(function () {
                $("#errorMsg2").hide();
            }, 5000);
        });
});

$("#standardSlip").on("click", function (event) {
    let getNIN = $("#nin_no").html();

    fetch("/user/standardSlip/" + getNIN, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
    })
        .then((response) => {
            if (response.ok) {
                // Extract filename from Content-Disposition header
                const contentDisposition = response.headers.get(
                    "Content-Disposition"
                );
                let filename = "document.pdf"; // Default filename if not found in headers
                if (
                    contentDisposition &&
                    contentDisposition.indexOf("attachment") !== -1
                ) {
                    const filenameRegex =
                        /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    const matches = filenameRegex.exec(contentDisposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, "");
                    }
                }
                return response.blob().then((blob) => ({ blob, filename }));
            } else {
                return response.json().then((data) => {
                    // Handle errors
                    $.each(data.errors, function (key, value) {
                        $("#errorMsg2").show();
                        $("#message2").html(value);
                    });
                    setTimeout(function () {
                        $("#errorMsg2").hide();
                    }, 5000);
                });
            }
        })
        .then(({ blob, filename }) => {
            if (blob) {
                // Create a link element, use it to download the blob with the extracted filename
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = filename; // Use the extracted filename
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            // Handle errors
            $.each(data.errors, function (key, value) {
                $("#errorMsg2").show();
                $("#message2").html(value);
            });
            setTimeout(function () {
                $("#errorMsg2").hide();
            }, 5000);
        });
});

$("#premiumSlip").on("click", function (event) {
    let getNIN = $("#nin_no").html();

    fetch("/user/premiumSlip/" + getNIN, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
    })
        .then((response) => {
            if (response.ok) {
                // Extract filename from Content-Disposition header
                const contentDisposition = response.headers.get(
                    "Content-Disposition"
                );
                let filename = "document.pdf"; // Default filename if not found in headers
                if (
                    contentDisposition &&
                    contentDisposition.indexOf("attachment") !== -1
                ) {
                    const filenameRegex =
                        /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    const matches = filenameRegex.exec(contentDisposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, "");
                    }
                }
                return response.blob().then((blob) => ({ blob, filename }));
            } else {
                return response.json().then((data) => {
                    // Handle errors
                    $.each(data.errors, function (key, value) {
                        $("#errorMsg2").show();
                        $("#message2").html(value);
                    });
                    setTimeout(function () {
                        $("#errorMsg2").hide();
                    }, 5000);
                });
            }
        })
        .then(({ blob, filename }) => {
            if (blob) {
                // Create a link element, use it to download the blob with the extracted filename
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = filename; // Use the extracted filename
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            // Handle errors
            $.each(data.errors, function (key, value) {
                $("#errorMsg2").show();
                $("#message2").html(value);
            });
            setTimeout(function () {
                $("#errorMsg2").hide();
            }, 5000);
        });
});


$("#basicSlip").on("click", function (event) {
    let getNIN = $("#nin_no").html();

    fetch("/user/basicSlip/" + getNIN, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
    })
        .then((response) => {
            if (response.ok) {
                // Extract filename from Content-Disposition header
                const contentDisposition = response.headers.get(
                    "Content-Disposition"
                );
                let filename = "document.pdf"; // Default filename if not found in headers
                if (
                    contentDisposition &&
                    contentDisposition.indexOf("attachment") !== -1
                ) {
                    const filenameRegex =
                        /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    const matches = filenameRegex.exec(contentDisposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, "");
                    }
                }
                return response.blob().then((blob) => ({ blob, filename }));
            } else {
                return response.json().then((data) => {
                    // Handle errors
                    $.each(data.errors, function (key, value) {
                        $("#errorMsg2").show();
                        $("#message2").html(value);
                    });
                    setTimeout(function () {
                        $("#errorMsg2").hide();
                    }, 5000);
                });
            }
        })
        .then(({ blob, filename }) => {
            if (blob) {
                // Create a link element, use it to download the blob with the extracted filename
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = filename; // Use the extracted filename
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            // Handle errors
            $.each(data.errors, function (key, value) {
                $("#errorMsg2").show();
                $("#message2").html(value);
            });
            setTimeout(function () {
                $("#errorMsg2").hide();
            }, 5000);
        });
});


$('.dropdown-option').click(function (e) {
        e.preventDefault();

        let recordId = $(this).data('id');
        let selectedType = $(this).data('value');
        let url = '';

        switch (selectedType) {
            case 'Basic':
                url = '/user/basicSlip/';
                break;
            case 'Premium':
                url = '/user/premiumSlip/';
                break;
            case 'Standard':
                url = '/user/standardSlip/';
                break;
            case 'Regular':
                url = '/user/regularSlip/';
                break;
            default:
                alert('Invalid selection type');
                return;
        }

        fetch(url + recordId, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
        })
            .then((response) => {
                if (response.ok) {
                    // Extract filename from Content-Disposition header
                    const contentDisposition = response.headers.get(
                        "Content-Disposition"
                    );
                    let filename = "document.pdf"; // Default filename if not found in headers
                    if (
                        contentDisposition &&
                        contentDisposition.indexOf("attachment") !== -1
                    ) {
                        const filenameRegex =
                            /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        const matches = filenameRegex.exec(contentDisposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, "");
                        }
                    }
                    return response.blob().then((blob) => ({ blob, filename }));
                } else {
                    return response.json().then((data) => {
                        // Handle errors
                        $.each(data.errors, function (key, value) {
                            $("#errorMsg2").show();
                            $("#message2").html(value);
                        });
                        setTimeout(function () {
                            $("#errorMsg2").hide();
                        }, 5000);
                    });
                }
            })
            .then(({ blob, filename }) => {
                if (blob) {
                    // Create a link element, use it to download the blob with the extracted filename
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement("a");
                    a.href = url;
                    a.download = filename; // Use the extracted filename
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                // Handle errors
                $.each(data.errors, function (key, value) {
                    $("#errorMsg2").show();
                    $("#message2").html(value);
                });
                setTimeout(function () {
                    $("#errorMsg2").hide();
                }, 5000);
            });
    });


