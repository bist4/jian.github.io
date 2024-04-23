<?php
require('config/db_con.php');
include('security.php');
// Start the session

// Check if the user is logged in
if (isset($_SESSION['Username'])) {
    $loggedInName = $_SESSION['Username'];

    $query = "SELECT u.UserRoleID,u.ProfilePhoto, usr.UserRoleName, u.is_Lock, u.Fname, u.Lname, u.BaypointeDepartmentID, bd.DepartmentName, u.Age, u.Address, u.ContactNumber, u.Email, u.Gender, u.Birthday,
    u.is_Admin_Group, u.is_Ancillary_Group, u.is_Nursing_Group, u.is_Outsource_Group FROM users u
    INNER JOIN userroles usr ON u.UserRoleID = usr.UserRoleID
    INNER JOIN baypointedepartments bd ON u.BaypointeDepartmentID = bd.BaypointeDepartmentID
    WHERE Username = '$loggedInName'";
    // Execute the query
    $result = $conn->query($query);
    
    if ($result) {
        $row = $result->fetch_assoc();

        // Close the result set
        $result->close();

      
    } else {
        // Handle the case where the query fails
        echo "Error in fetching RoleID and UserID: " . $conn->error;
    }
    
    // Close the database connection
    $conn->close();
}
if ($row['is_Lock'] == 1) {
    // User account is locked
    header("Location: lock_account.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Profile</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/logo2.png" rel="icon">
  <link href="assets/img/logo2.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo2.png" alt="">
        <span class="d-none d-lg-block">    </span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

 

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="DataAdd/uploads/<?php echo $row['ProfilePhoto'];?>" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">
            <?php
              // Assuming $row['Fname'] contains the first name and $row['Lname'] contains the last name

              // Get the first letter of Fname
              $firstLetter = strtoupper(substr($row['Fname'], 0, 1));

              echo $firstLetter;
              ?>.
             <?php echo $row['Lname']?>
           </span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6> <?php
              // Assuming $row['Fname'] contains the first name and $row['Lname'] contains the last name

              // Get the first letter of Fname
              $firstLetter = strtoupper(substr($row['Fname'], 0, 1));

              echo $firstLetter;
              ?>.
             <?php echo $row['Lname']?></h6>
              <span><?php echo $row['UserRoleName']?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
          
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="home.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="accounts.php">
          <i class="bi bi-person"></i>
          <span>Account Management</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="inbox.php">
          <i class="bi bi-inbox"></i>
          <span>Inbox</span>
        </a>
      </li><!-- End Account Management -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gear"></i><span>Site Options</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="site_options/title_slogan.php">
              <i class="bi bi-circle"></i><span>Title & Slogan</span>
            </a>
          </li>
          <li>
            <a href="site_options/company_profile.php">
              <i class="bi bi-circle"></i><span>Company Profile</span>
            </a>
          </li>
          <li>
            <a href="site_options/social_media.php">
              <i class="bi bi-circle"></i><span>Social Media</span>
            </a>
          </li>
          <li>
            <a href="site_options/copyright.php">
              <i class="bi bi-circle"></i><span>Copyright</span>
            </a>
          </li>
          <li>
            <a href="site_options/contact_info.php">
              <i class="bi bi-circle"></i><span>Contact Informartion</span>
            </a>
           
        </ul>
      </li><!-- End Site options Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-stickies"></i><span>Pages</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="pages/new_page.php">
              <i class="bi bi-circle"></i><span>Add New Page</span>
            </a>
          </li>
          <li>
            <a href="pages/about_us.php">
              <i class="bi bi-circle"></i><span>About Us</span>
            </a>
          </li>
          <li>
            <a href="pages/contact.php">
              <i class="bi bi-circle"></i><span>Contact</span>
            </a>
          </li>
          
        </ul>
      </li><!-- End pages Nav -->


      <li class="nav-item">
        <a class="nav-link collapsed" href="category.php">
          <i class="bi bi-justify-left"></i>
          <span>Category Options</span>
        </a>
      </li><!-- End Category-->

      
      <li class="nav-item">
        <a class="nav-link collapsed" href="department.php">
          <i class="bi bi-building"></i>
          <span>Department Options</span>
        </a>
      </li><!-- End Department-->

      <li class="nav-item">
        <a class="nav-link collapsed" href="service.php">
          <i class="bi bi-life-preserver"></i>
          <span>Service Options</span>
        </a>
      </li><!-- End Service-->

      <li class="nav-item">
        <a class="nav-link collapsed" href="doctors.php">
          <i class="bi bi-person-bounding-box"></i>
          <span>Doctors Options</span>
        </a>
      </li><!-- End Service-->

      
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#events" data-bs-toggle="collapse" href="#">
          <i class="bi bi-calendar4"></i><span>Events Options</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="events" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="events/events.php">
              <i class="bi bi-circle"></i><span>Even List</span>
            </a>
          </li>
          <li>
            <a href="events/event_request.php">
              <i class="bi bi-circle"></i><span>Event Request</span>
            </a>
          </li>
          <li>
            <a href="events/events_logs.php">
              <i class="bi bi-circle"></i><span>Event Logs</span>
            </a>
          </li>
          
        </ul>
      </li><!-- End pages Nav -->

      

    </ul>

  </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                <img src="DataAdd/uploads/<?php echo $row['ProfilePhoto'];?>" alt="Profile" class="rounded-circle">
                
                <h2><?php echo $row['Fname']?> <?php echo $row['Lname']?></h6></h6></h2>
                <h3><?php echo $row['UserRoleName']?></h6></h3>
                <div class="social-links mt-2">
                    <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                </div>
                </div>
            </div>

            </div>

            <div class="col-xl-8">

            <div class="card">
                <div class="card-body pt-3">
                <!-- Bordered Tabs -->
                <ul class="nav nav-tabs nav-tabs-bordered">

                    <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                    </li>

                    <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                    </li>

                    <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                    </li>

                    <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                    </li>

                </ul>
                <div class="tab-content pt-2">

                    <div class="tab-pane fade show active profile-overview" id="profile-overview">
                     
                    <h5 class="card-title">Profile Details</h5>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label ">Full Name</div>
                        <div class="col-lg-9 col-md-8"><?php echo $row['Fname']?> <?php echo $row['Lname']?></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Gender</div>
                        <div class="col-lg-9 col-md-8"><?php echo $row['Gender']?></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Age</div>
                        <div class="col-lg-9 col-md-8"><?php echo $row['Age']?></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Birthday</div>
                        <div class="col-lg-9 col-md-8">
                            <?php echo date('F j, Y', strtotime($row['Birthday'])); ?>
                        </div>
                    </div>

                  
                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Group</div>
                        <div class="col-lg-9 col-md-8">
                            <?php echo $row['is_Admin_Group'] == 1 ? 'Admin' : ''; ?>
                            <?php echo $row['is_Ancillary_Group'] == 1 ? 'Ancillary' : ''; ?>
                            <?php echo $row['is_Nursing_Group'] == 1 ? 'Nursing' : ''; ?>
                            <?php echo $row['is_Outsource_Group'] == 1 ? 'Outsource' : ''; ?>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Department</div>
                        <div class="col-lg-9 col-md-8"><?php echo $row['DepartmentName']?></div>
                    </div>

                   

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Address</div>
                        <div class="col-lg-9 col-md-8"><?php echo $row['Address']?></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Phone</div>
                        <div class="col-lg-9 col-md-8"><?php echo $row['ContactNumber']?></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Email</div>
                        <div class="col-lg-9 col-md-8"><?php echo $row['Email']?></div>
                    </div>

                    </div>

                    <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                    <!-- Profile Edit Form -->
                    <form>
                        <div class="row mb-3">
                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                        <div class="col-md-8 col-lg-9">
                            <img src="DataAdd/uploads/<?php echo $row['ProfilePhoto'];?>" alt="Profile">
                            <div class="pt-2">
                            <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                            <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                            </div>
                        </div>
                        </div>

                        <div class="row mb-3">
                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="fullName" type="text" class="form-control" id="fullName" value="<?php echo $row['Fname']?> <?php echo $row['Lname']?>">
                        </div>
                        </div>

                        

                        <div class="row mb-3">
                        <label for="company" class="col-md-4 col-lg-3 col-form-label">Group</label>
                        <div class="col-md-8 col-lg-9">
                            <select class="form-select" aria-label="Default select example" name="group" id="groupSelect" onchange="updateDepartments()" required>

                                <option value="Admin" <?php echo $row['is_Admin_Group'] == 1 ? 'selected' : ''; ?>>Admin</option>
                                <option value="Ancillary" <?php echo $row['is_Ancillary_Group'] == 1 ? 'selected' : ''; ?>>Ancillary</option>
                                <option value="Nursing" <?php echo $row['is_Nursing_Group'] == 1 ? 'selected' : ''; ?>>Nursing</option>
                                <option value="Outsource" <?php echo $row['is_Outsource_Group'] == 1 ? 'selected' : ''; ?>>Outsource</option>
                            </select>

                        </div>
                        </div>

                        <div class="row mb-3">
                        <label for="Job" class="col-md-4 col-lg-3 col-form-label">Department</label>
                        <div class="col-md-8 col-lg-9">
             
                        <select class="form-select" aria-label="Default select example" name="BaypointeDepartmentID" id="departmentSelect" required>
                            <?php
                            // Assuming $conn is your database connection
                            require('config/db_con.php');
                            $query = "SELECT * FROM baypointedepartments";
                            $result = mysqli_query($conn, $query);

                            // Check if the query was successful
                            if ($result) {
                                // Loop through the result set
                                while ($department = mysqli_fetch_assoc($result)) {
                                    // Check if the current department ID matches the one from the database row
                                    $selected = $department['BaypointeDepartmentID'] == $row['BaypointeDepartmentID'] ? 'selected' : '';
                                    // Output the option with the department name and set 'selected' attribute if matched
                                    echo '<option value="' . $department['BaypointeDepartmentID'] . '" ' . $selected . '>' . $department['DepartmentName'] . '</option>';
                                }
                            } else {
                                // Handle error if query fails
                                echo '<option value="">Error retrieving departments</option>';
                            }
                            ?>
                        </select>


                        </div>
                        </div>

                        

                        <div class="row mb-3">
                        <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="address" type="text" class="form-control" id="Address" value="<?php echo $row['Address']?>">
                        </div>
                        </div>

                        <div class="row mb-3">
                        <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Contact Number</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="phone" type="text" class="form-control" id="Phone" value="<?php echo $row['ContactNumber']?>">
                        </div>
                        </div>

                        <div class="row mb-3">
                        <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="email" type="email" class="form-control" id="Email" value="<?php echo $row['Email']?>">
                        </div>
                        </div>

                        <div class="text-center">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form><!-- End Profile Edit Form -->

                    </div>

                    <div class="tab-pane fade pt-3" id="profile-settings">

                    <!-- Settings Form -->
                    <form>

                        <div class="row mb-3">
                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                        <div class="col-md-8 col-lg-9">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="changesMade" checked>
                            <label class="form-check-label" for="changesMade">
                                Changes made to your account
                            </label>
                            </div>
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="newProducts" checked>
                            <label class="form-check-label" for="newProducts">
                                Information on new products and services
                            </label>
                            </div>
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="proOffers">
                            <label class="form-check-label" for="proOffers">
                                Marketing and promo offers
                            </label>
                            </div>
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                            <label class="form-check-label" for="securityNotify">
                                Security alerts
                            </label>
                            </div>
                        </div>
                        </div>

                        <div class="text-center">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form><!-- End settings Form -->

                    </div>

                    <div class="tab-pane fade pt-3" id="profile-change-password">
                    <!-- Change Password Form -->
                    <form>

                        <div class="row mb-3">
                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="password" type="password" class="form-control" id="currentPassword">
                        </div>
                        </div>

                        <div class="row mb-3">
                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="newpassword" type="password" class="form-control" id="newPassword">
                        </div>
                        </div>

                        <div class="row mb-3">
                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                        </div>
                        </div>

                        <div class="text-center">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form><!-- End Change Password Form -->

                    </div>

                </div><!-- End Bordered Tabs -->

                </div>
            </div>

            </div>
        </div>
        </section>

    </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span> Allied Care Experts (ACE) Medical Center - Baypointe 2024</span></strong>
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>