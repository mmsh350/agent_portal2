<?php

if (! isset($_SESSION['email']) && $_SESSION['email'] != true && $_SESSION['role'] != 0 || $_SESSION['role'] == false) {
    header('Location:login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Inventures</title>
    <link rel="icon" type="image/x-icon" href="../../img/nicon.png">
    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    

    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    

<!-- Custom styles for this page -->
<link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-8HYV393VBP"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-8HYV393VBP');
</script>

    
  

</head>

<body id="page-top" style="background-image: url('../../img/verification.jpg');filter:.6;">
<?php include 'sidebar.php'; ?>
   
        <!-- End of Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column" style="background-color:rgba(255,255,255,.6)">

<!-- Main Content -->
<div id="content">

    <!-- Topbar -->
    <nav class="navbar  navbar-expand  topbar mb-4 static-top shadow" style="background:  linear-gradient(90deg, rgba(203,10,59,1) 50%, rgba(255,0,0,1) 96%);">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn text-light d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Topbar Search -->
        <form
            class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search " >
            <div class="input-group" >
            <marquee behavior="" direction=""><p class="font-weight-bold py-2 px-4 text-light">Wellcome to Inventures.com.ng</p></marquee>
                
            </div>
        </form>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

          
            <!-- Nav Item - Alerts -->
            

                       

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline font-weight-bold text-light small"><?php echo $_SESSION['name']; ?></span>
                                <img class="img-profile rounded-circle" src="../../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                               
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../../logout.php" >
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
                


























