 <!-- Page Wrapper -->
 <div id="wrapper">

     <!-- Sidebar -->
     <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: radial-gradient(circle, rgba(0,0,0,1) 0%, rgba(0,0,0,.9) 100%);">

         <!-- Sidebar - Brand -->
         <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
             <div class="sidebar-brand-icon ">
                 <img class=" img img-reponsive " src="../../img/nicon.png" style="width:50px" alt="icon">


             </div>
           
             
             
         </a>

         <!-- Divider -->
         <hr class="sidebar-divider my-0">
         
         <!-- Nav Item - Dashboard -->
         <li class="nav-item active">
             <a class="nav-link" href="../dashboard.php">
                 <i class="fas fa-fw fa-tachometer-alt"></i>
                 <span>Dashboard</span></a>
         </li>

         <!-- Divider -->
         <hr class="sidebar-divider">

         <!-- Heading -->


         <!-- Nav Item - Pages Collapse Menu -->
         <li class="nav-item">
             <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                 <i class="fas fa-laptop"></i>
                 <span>Our Services</span>
             </a>
             <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                 <div class="bg-dark text-light py-2 collapse-inner rounded">
                     <h6 class="collapse-header text-light">Services:</h6>
                     <a class="collapse-item text-light" href="../nin.php">NIN Services</a>
                     <a class="collapse-item text-light" href="../nin_v2/">NIN Services v2</a>
                     
                     <a class="collapse-item text-light" href="../vnin.php">VNIN Search</a>
                     <a class="collapse-item text-light" href="../bvn.php">BVN Search</a>
                     <a class="collapse-item text-light" href="../jambServices.php">JAMB Services</a>
                     <a class="collapse-item text-light" href="#">JAMB Pin Vebding</a>
                 </div>
             </div>
         </li>

         <hr class="sidebar-divider">
         <li class="nav-item">
             <a class="nav-link collapsed" href="../wallet.php">
                 <i class="fas fa-wallet"></i>
                 <span>wallet</span>
             </a>

         </li>

         <!-- Divider -->
         <hr class="sidebar-divider">
         <li class="nav-item">
             <a class="nav-link collapsed" href="../transaction.php">
                 <i class="fas fa-history"></i>
                 <span>Transactions</span>
             </a>

         </li>


         <!-- Divider -->
         <hr class="sidebar-divider d-none d-md-block">
         <li class="nav-item">
             <a class="nav-link collapsed" href="../../logout.php">
                 <i class="fas fa-sign-out-alt"></i>
                 <span>Logout</span>
             </a>

         </li>
         <small class="text-light mx-2 my-2 pd-5 " style="padding:5px;text-align:center;font-size:15px;border-radius:5px;background-color:rgb(0,0,0)">
             Balance: <br>
             <strong style="font-size: 14px;">
                <span>&#8358;</span>
                 <?php echo $_SESSION['balance']; ?>
            </strong>
         </small>

         <!-- Sidebar Toggler (Sidebar) -->
         <div class="text-center d-none d-sm-inline" >
             <button class="rounded-circle border-0 " id="sidebarToggle"></button>
         </div>



     </ul>