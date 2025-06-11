<?php
session_start();
if (! isset($_SESSION['email'])) {
    header('Location:login.php');
    exit;
}

if ($_SESSION['role'] == '1') {
    header('Location:agent/dashboard.php');
    exit;
}
if ($_SESSION['role'] == '3') {
    header('Location:admin/dashboard.php');
    exit;
}

include 'include/header.php'; ?>


<div class="container-fluid ">
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
    <h3 class="h3 mb-0  " style="color:black;font-size:15px"> <i class="fas fa-home"></i><a class="" style="text-decoration:none;color:black" href="dashboard.php"> Home </a> <i class="fas fa-angle-double-right"></i> BVN Verfication</h3>

  </div>
  <hr class="sidebar-divider ">

  <?php

  if (isset($_SESSION['alert2']) && $_SESSION['alert2'] != '') {
      ?>

    <div class="alert alert-danger alert-dismissible " role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
      </button>
      <?php echo $_SESSION['alert2']; ?>


    </div>
  <?php

        unset($_SESSION['alert2']);
  }
?>

  <div class="row">


    <!-------------------------------------1st-------------------------------------->
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 mb-4 " >
      <div class="card  shadow h-100 py-2" style="background: linear-gradient(90deg, rgba(10,203,159,1) 30%, rgba(14,138,76,1) 96%);border:none;" data-toggle="modal" data-target="#exampleModal">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col-lg mr-2">
              <h5 class="text-light col-sm-5  col-md-10 col-lg-12 " style="text-align:center">BVN Slip</h5>
              <p class="text-light" style="text-align:center">You will be charge <Small class="text-warning"><span>&#8358;</span> 0</Small> if record Not found</p>
              <h6 class="text-light" style="text-align:center">Price: <Small class="text-danger font-weight-bold"><span>&#8358;</span> 150</Small></h6>
              <div class="col-auto">

              </div>
            </div>
            <img src="img/BVN.jpg" class="float-start img img-reponsive  pd-4 col-sm-3 col-md-3 col-lg-3" alt="regular">
            

          </div>
        </div>
      </div>
    </div>






  </div>





  <!-------------------------------------BVN Modal------------------------------------------>
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Print BVN Slip</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="verifyBvn.php" method="POST">
        <div class="modal-body">
          
          <div class="form-group">
            <input type="text" name="bvn" class="form-control"  placeholder="Enter BVN Number" required>
          </div>
        </div>
        <div class="modal-footer">
         
          <button type="submit" name="bvnbtn" class="btn text-light" style="background-color:rgb(40,28,68)">Verify <span>&#8358;</span> 150</button>
        </div>
      </form>
    </div>
  </div>
</div>
  <script type="text/javascript">
    
  </script>
  <?php
include 'include/init/config.php';
$userid = $_SESSION['userid'];
$select_query = "SELECT * FROM bvn_services WHERE userID = '$userid'";
$result = $conn->query($select_query);
$rows = $result->fetch_all(MYSQLI_ASSOC);
?>

  <div class="card shadow mb-4 ">
    <div class="card-header py-3  text-light" style="background: linear-gradient(90deg, rgba(10,203,159,1) 30%, rgba(14,138,76,1) 96%);border:none;">
      <h6 class="m-0 font-weight-bold text-light">All Recent Service Perform</h6>
    </div>
    <div class="card-body bg-light text-dark">
      <div class="table-responsive">
        <table class="table table-bordered table-light" id="dataTable" width="100%" cellspacing="0">

          <thead>
            <tr>
              <th class="table-center">#</th>
              <th>Service Type</th>
              <th>Amount</th>
              
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>

          <tbody>
            <?php
          if (! empty($rows)) {
              // Use a for loop to iterate through the array
              for ($i = 0; $i < count($rows); $i++) {
                  $row = $rows[$i];
                  ?>
                <tr>
                  <td><?php echo $i + 1; ?></td>
                  <td><?php echo $row['service_type']; ?></td>
                  <td><?php echo $row['amount']; ?></td>
                  <td><?php echo $row['status']; ?></td>
                  <td><?php echo $row['date']; ?></td>
                </tr>
            <?php
              }
          } else {
              echo "<tr><td colspan='7'>No records found</td></tr>";
          }
?>
          </tbody>
        </table>

        <?php
        // Close the database connection
        $conn->close();
?>
      </div>
    </div>
  </div>




</div>






<?php include 'include/footer.php'; ?>