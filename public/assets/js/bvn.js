$("#verifyBVN").on("click", function (event) {

    event.preventDefault();

    let data = new FormData(this.form);
    let validationInfo = document.getElementById("validation-info");
    let download = document.getElementById("download");

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
        url: "/user/bvn-retrieve",
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

validationInfo.innerHTML = `
<div class="border border-light p-3">
    <div class="row align-items-start">
        <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
            <img class="rounded img-fluid" src="data:image/;base64, ${result.data.image}" alt="User Image" style="max-width: 100%; height: auto;">
        </div>
        <div class="col-12 col-md-8">
            <div class="table-responsive">
                <table class="table  table-sm ">
                    <tbody>
                        <tr>
                            <th style="text-align: right;">BVN</th>
                            <td style="text-align: left;">
                                <span id="bvnno">${result.data.idNumber}</span>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: right;">First Name</th>
                            <td style="text-align: left;">${result.data.firstName}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;">Surname</th>
                            <td style="text-align: left;">${result.data.lastName}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;">Middle Name</th>
                            <td style="text-align: left;">${result.data.middleName}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;">Phone No</th>
                            <td style="text-align: left;">${result.data.mobile}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;">Gender</th>
                            <td style="text-align: left;">${result.data.gender}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
`;

            $("#download").removeClass("d-none"); // show
        },
        error: function (data) {
            $("#loader").hide();
            $.each(data.responseJSON.errors, function (key, value) {
                $("#errorMsg").show();
                $("#message").html(value);
            });
            setTimeout(function () {
                $("#errorMsg").hide();
            }, 5000);
        },
    });
});

$("#freeSlip").on("click", function (event) {
    let getBVN = $("#bvnno").html();
    $.ajax({
        type: "get",
        url: "/user/standardBVN/" + getBVN,
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            if (response.view) {
                var newWindow = window.open("", "_blank");
                newWindow.document.write(response.view);
                newWindow.document.close();
            } else {
                console.error("No view content received");
            }
        },
        error: function (data) {
            $.each(data.responseJSON.errors, function (key, value) {
                $("#errorMsg2").show();
                $("#message2").html(value);
            });
            setTimeout(function () {
                $("#errorMsg2").hide();
            }, 5000);
        },
    });
});

$("#paidSlip").on("click", function (event) {
    let getBVN = $("#bvnno").html();
    $.ajax({
        type: "get",
        url: "/user/premiumBVN/" + getBVN,
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            if (response.view) {
                var newWindow = window.open("", "_blank");
                newWindow.document.write(response.view);
                newWindow.document.close();
            } else {
                console.error("No view content received");
            }
        },
        error: function (data) {
            $.each(data.responseJSON.errors, function (key, value) {
                $("#errorMsg2").show();
                $("#message2").html(value);
            });
            setTimeout(function () {
                $("#errorMsg2").hide();
            }, 5000);
        },
    });
});
$("#plasticSlip").on("click", function (event) {
    let getBVN = $("#bvnno").html();

    fetch("/user/plasticBVN/" + getBVN, {
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
