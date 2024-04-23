<?php
require('config/db_con.php');
include('security.php');
// Start the session

// Check if the user is logged in
if (isset($_SESSION['Username'])) {
    $loggedInName = $_SESSION['Username'];

    // Prepare the SQL statement with placeholders
    $query = "SELECT u.*, p.*, usr.*, m.ModuleName, m.ModuleID FROM privileges p
    INNER JOIN users u ON p.UserID = u.UserID
    INNER JOIN userroles usr ON u.UserRoleID = usr.UserRoleID
    INNER JOIN modules m ON p.ModuleID = m.ModuleID
    WHERE u.Username = ? AND p.Hide_Module = 1 AND p.Action_Add IN (1, 0) AND p.Action_Update IN (1, 0) AND p.Action_Delete IN (1, 0) AND p.Action_View IN (1, 0) AND p.Action_Lock IN (1, 0)  AND p.AssignModule_View IN (1, 0) AND p.AssignModule_Update IN (1, 0)";

    // Prepare the statement
    $stmt = $conn->prepare($query);
    
    // Bind the parameter
    $stmt->bind_param("s", $loggedInName);

    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    // Fetch the row
    if ($result) {
        $row = $result->fetch_assoc();
    } else {
        // Handle the case where the query fails
        echo "Error in fetching RoleID and UserID: " . $conn->error;
    }

    // Check if Action_Add is 1 (allowed) or 0 (not allowed)
    $actionAdd = $row['Action_Add'];
    $actionUpdate = $row['Action_Update'];
    $actionDelete = $row['Action_Delete'];
    $actionView = $row['Action_View'];
    $actionLock = $row['Action_Lock'];
    $actionUnlock = $row['Action_Unlock'];
    $actionModuleView = $row['AssignModule_View'];
    $actionModuleUpdate = $row['AssignModule_Update'];

    // Close the statement
    $stmt->close();
    
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

  <title>Account Management</title>
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  
 
  
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
 
  <style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500&display=swap');
  
  .container {
    width: 300px; /* Adjusted width */
     
    padding: 10px;
    background: #fff;
    border-radius: 20px;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
  }

  #dropArea{
    display: flex;
       
    align-items: center;
    justify-content: center;
    width: 280px;
    height: 200px;
    border-radius: 20px;
    border: 2px dashed #ccc;
    text-align: center;
    line-height: 200px;

  }
  .icons {
      color: blue; /* Change icon color to blue */
      font-size: 3rem; /* Increase icon size */
    }
    .pin-code-input input[type="text"] {
        text-align: center;
    }
    #otp-input {
    display: flex;
    column-gap: 8px;
}
    #otp-input input {
        text-align: center;
        padding: 10px 8px 10px 8px;
        border: 1px solid #adadad;
        border-radius: 4px;
        outline: none;
        height: 64px;
        width: 50px;
    }


</style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="home.php" class="logo d-flex align-items-center">
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
    <a class="nav-link collapsed" href="home.php">
      <i class="bi bi-grid"></i>
      <span>Dashboard</span>
    </a>
  </li><!-- End Dashboard Nav -->

  <?php
      if($row['ModuleName'] == 'Account Management'){
        echo '<li class="nav-item">';
        echo '<a class="nav-link" href="accounts.php">';
        echo '<i class="bi bi-person"></i>';
        echo '<span>Account Management</span>';
        echo '</a>';
        echo '</li>';
      }
  
  ?>



  <?php
      // Check if there are any modules to display
      if ($result->num_rows > 0) {
          // Define variables to hold sub-navigation items for Site Options
          $siteOptionsSubMenu = '';
          $pageOptionsSubMenu = '';
          $eventOptionsSubMenu = '';
          $logOptionsSubMenu = '';



          // Output data of each row
          while ($row = $result->fetch_assoc()) {
              // Check for specific module names and generate navigation items accordingly

          

              if ($row['ModuleName'] == 'Inbox') {
                  echo '<li class="nav-item">';
                  echo '<a class="nav-link collapsed" href="inbox.php">';
                  echo '<i class="bi bi-inbox"></i>';
                  echo '<span>Inbox</span>';
                  echo '</a>';
                  echo '</li>';
              } 

              // Check for Site Options module and construct sub-navigation items
              if ($row['ModuleName'] == 'Title & Slogan' || $row['ModuleName'] == 'Company Profile' || $row['ModuleName'] == 'Social Media' || $row['ModuleName'] == 'Copyright' || $row['ModuleName'] == 'Contact Information') {
                  $siteOptionsSubMenu .= '<li>';
                  $siteOptionsSubMenu .= '<a href="site_options/' . strtolower(str_replace(' ', '_', $row['ModuleName'])) . '.php">';
                  $siteOptionsSubMenu .= '<i class="bi bi-circle"></i><span>' . $row['ModuleName'] . '</span>';
                  $siteOptionsSubMenu .= '</a>';
                  $siteOptionsSubMenu .= '</li>';
              }

              // Check for Page Options module and construct sub-navigation items
              if ($row['ModuleName'] == 'Add New Page') {
                $pageOptionsSubMenu .= '<li>';
                $pageOptionsSubMenu .= '<a href="pages/' . strtolower(str_replace(' ', '_', $row['ModuleName'])) . '.php">';
                $pageOptionsSubMenu .= '<i class="bi bi-circle"></i><span>' . $row['ModuleName'] . '</span>';
                $pageOptionsSubMenu .= '</a>';
                $pageOptionsSubMenu .= '</li>';
            }

            if ($row['ModuleName'] == 'Event List' || $row['ModuleName'] == 'Event Request') {
              $eventOptionsSubMenu .= '<li>';
              $eventOptionsSubMenu .= '<a href="events/' . strtolower(str_replace(' ', '_', $row['ModuleName'])) . '.php">';
              $eventOptionsSubMenu .= '<i class="bi bi-circle"></i><span>' . $row['ModuleName'] . '</span>';
              $eventOptionsSubMenu .= '</a>';
              $eventOptionsSubMenu .= '</li>';
            }

            if ($row['ModuleName'] == 'Event Super Admin Logs' || $row['ModuleName'] == 'Event Admin Logs' || $row['ModuleName'] == 'Event User Logs' || $row['ModuleName'] == 'Event Doctors Logs') {
              $logOptionsSubMenu .= '<li>';
              $logOptionsSubMenu .= '<a href="logs/' . strtolower(str_replace(' ', '_', $row['ModuleName'])) . '.php">';
              $logOptionsSubMenu .= '<i class="bi bi-circle"></i><span>' . $row['ModuleName'] . '</span>';
              $logOptionsSubMenu .= '</a>';
              $logOptionsSubMenu .= '</li>';
            }


            if ($row['ModuleName'] == 'Category Options') {
              echo '<li class="nav-item">';
              echo '<a class="nav-link collapsed" href="category.php">';
              echo '<i class="bi bi-justify-left"></i>';
              echo '<span>Category Options</span>';
              echo '</a>';
              echo '</li>';
            } 

            if ($row['ModuleName'] == 'Department Options') {
              echo '<li class="nav-item">';
              echo '<a class="nav-link collapsed" href="department.php">';
              echo '<i class="bi bi-building"></i>';
              echo '<span>Department Options</span>';
              echo '</a>';
              echo '</li>';
            } 

            if ($row['ModuleName'] == 'Service Options') {
              echo '<li class="nav-item">';
              echo '<a class="nav-link collapsed" href="service.php">';
              echo '<i class="bi bi-life-preserver"></i>';
              echo '<span>Service Options</span>';
              echo '</a>';
              echo '</li>';
            } 

            
            if ($row['ModuleName'] == 'Doctors Options') {
              echo '<li class="nav-item">';
              echo '<a class="nav-link collapsed" href="doctor.php">';
              echo '<i class="bi bi-person-bounding-box"></i>';
              echo '<span>Doctors Options</span>';
              echo '</a>';
              echo '</li>';
            } 
            


        
          }

          // Output Site Options navigation section if there are relevant sub-navigation items
          if ($siteOptionsSubMenu !== '') {
              echo '<li class="nav-item">';
              echo '<a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">';
              echo '<i class="bi bi-gear"></i><span>Site Options</span><i class="bi bi-chevron-down ms-auto"></i>';
              echo '</a>';
              echo '<ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">';
              echo $siteOptionsSubMenu; // Output the constructed sub-navigation items
              echo '</ul>';
              echo '</li>';
          }

          if ($pageOptionsSubMenu !== '') {
            echo '<li class="nav-item">';
            echo '<a class="nav-link collapsed" data-bs-target="#page-nav" data-bs-toggle="collapse" href="#">';
            echo '<i class="bi bi-stickies"></i><span>Page</span><i class="bi bi-chevron-down ms-auto"></i>';
            echo '</a>';
            echo '<ul id="page-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">';
            echo $pageOptionsSubMenu; // Output the constructed sub-navigation items
            echo '</ul>';
            echo '</li>';
          }

          if ($eventOptionsSubMenu !== '') {
            echo '<li class="nav-item">';
            echo '<a class="nav-link collapsed" data-bs-target="#event-nav" data-bs-toggle="collapse" href="#">';
            echo '<i class="bi bi-calendar4"></i><span>Event Options</span><i class="bi bi-chevron-down ms-auto"></i>';
            echo '</a>';
            echo '<ul id="event-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">';
            echo $eventOptionsSubMenu; // Output the constructed sub-navigation items
            echo '</ul>';
            echo '</li>';
          }

          
          if ($logOptionsSubMenu !== '') {
            echo '<li class="nav-item">';
            echo '<a class="nav-link collapsed" data-bs-target="#log-nav" data-bs-toggle="collapse" href="#">';
            echo '<i class="bi bi-clipboard"></i><span>Logs</span><i class="bi bi-chevron-down ms-auto"></i>';
            echo '</a>';
            echo '<ul id="log-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">';
            echo $logOptionsSubMenu; // Output the constructed sub-navigation items
            echo '</ul>';
            echo '</li>';
          }


        

        

          // Add other conditions for different modules similarly
          // ...
      } else {
          echo "No modules available";
      }
  ?>
</ul>

</aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Account Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="home.php">Home</a></li>
          <li class="breadcrumb-item">Account Management</li>
       
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-end mb-3 pt-3">
               

                <?php
                    // Display the button if Action_Add is 1
                    if ($actionAdd == 1) {
                        echo ' <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUser">
                        <i class="bi bi-plus"></i> Add User
                      </button>';
                    }
                ?>
 

         



                    
                </div>
              <!-- Table with stripped rows -->
              <table class="table datatable" id="example">
              
                <thead>
                  <tr>
                    <!-- <th>Serial No.</th> -->
                    <th class="sorting">ID Number</th>
                    <th class="sorting">Name</th>
                    <th class="sorting">Username</th>
                    <th class="sorting">Email</th>
                    <th class="sorting">Department</th>
                    <th class="sorting">Role</th>
                    <th class="sorting">Action</th>
                  </tr>
                </thead>
                 
                <tbody>
                <?php
                    require('config/db_con.php');
                    $table = mysqli_query($conn, "SELECT 
                        u.UserID, 
                        u.IdNumber,
                        u.Fname, 
                        u.Lname, 
                        u.Gender,
                        u.Age,
                        u.Birthday,
                        u.Address,
                        u.ContactNumber,
                        u.is_Admin_Group,
                        u.is_Ancillary_Group,
                        u.is_Nursing_Group,
                        u.is_Outsource_Group,
                        u.Username, 
                        u.Password,
                        u.Email, 
                        u.UserRoleID,
                        u.ProfilePhoto,
                        bd.DepartmentName, 
                        u.BaypointeDepartmentID,
                        usr.UserRoleName,
                        p.PrivilegeID,
                        u.is_Lock,
                        COUNT(p.PrivilegeID) AS NumOfPrivileges
                    FROM 
                        users u
                    INNER JOIN 
                        userroles usr ON u.UserRoleID = usr.UserRoleID
                    INNER JOIN 
                        baypointedepartments bd ON u.BaypointeDepartmentID = bd.BaypointeDepartmentID
                    LEFT JOIN 
                        privileges p ON u.UserID = p.UserID
                    WHERE 
                        u.Active = 1 AND usr.UserRoleID NOT IN (0)
                    GROUP BY 
                        u.UserID, u.Fname, u.Lname,u.Gender, u.IdNumber,
                        u.Age,
                        u.Birthday,
                        u.Address,
                        u.ContactNumber,
                        u.is_Admin_Group,
                        u.is_Ancillary_Group,
                        u.is_Nursing_Group,
                        u.ProfilePhoto,
                        u.UserRoleID,
                        u.is_Lock,
                        u.BaypointeDepartmentID,
                        u.is_Outsource_Group, u.Username, u.Password,u.Email, bd.DepartmentName, usr.UserRoleName;");

                    $serialNo = 1;
                    while ($row = mysqli_fetch_assoc($table)) {
                      $lockAccountValue = $row['is_Lock'];
                        ?>
                        <tr>
                            <!-- <td><?php echo $serialNo++; ?></td> -->
                            <td><?php echo $row['IdNumber']; ?></td>
                            <td><?php echo $row['Fname'] . ' ' . $row['Lname']; ?></td>
                            <td><?php echo $row['Username']; ?></td>
                            <td><?php echo $row['Email']; ?></td>
                            <td><?php echo $row['DepartmentName']; ?></td>
                            <td><?php echo $row['UserRoleName']; ?></td>
                            <td>
                                <div class="d-inline-flex gap-3">
                                    <div data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    data-bs-title="View">
                                    
                                    <?php
                                        // Display the button if Action_Add is 1
                                        if ($actionView == 1) {
                                            echo '<button type="button" class="btn btn-primary"  
                                                data-bs-toggle="modal" data-bs-target="#viewUser" 
                                                data-user-id="' . $row['UserID'] . '"
                                                data-fname="' . $row['Fname'] . '"
                                                data-lname="' . $row['Lname'] . '"
                                                data-gender="' . $row['Gender'] . '"
                                                data-birthdate="' . $row['Birthday'] . '"
                                                data-address="' . $row['Address'] . '"
                                                data-cnumber="' . $row['ContactNumber'] . '"
                                                data-email="' . $row['Email'] . '"
                                                data-username="' . $row['Username'] . '"
                                                data-password="' . $row['Password'] . '"
                                                data-is-admin="' . $row['is_Admin_Group'] . '"
                                                data-is-ancillary="' . $row['is_Ancillary_Group'] . '"
                                                data-is-nursing="' . $row['is_Nursing_Group'] . '"
                                                data-is-outsource="' . $row['is_Outsource_Group'] . '"
                                                data-baypointe-department-id="' . $row['BaypointeDepartmentID'] . '"
                                                data-user-role-id="' . $row['UserRoleID'] . '"
                                                data-user-profile="' . $row['ProfilePhoto'] . '">
                                                <i class="bi bi-eye"></i>  
                                            </button>';
                                        }
                                    ?>

                                    </div>

                                    <div data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    data-bs-title="Edit">
                                        <?php
                                            // Display the button if Action_Add is 1
                                            if ($actionUpdate == 1) {
                                                echo '<button type="button" class="btn btn-info"  
                                                data-bs-toggle="modal" data-bs-target="#editUser">
                                                    <i class="bi bi-pencil"></i>  
                                                </button>';
                                            }
                                        ?>
                                    </div>
                                    
                                    <div data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    data-bs-title="Unlock Account">
                                        

                                    <?php
                                        // Display the button if Action_Add is 1
                                        if ($actionLock == 1 && $actionUnlock == 1) {
                                            echo '<button type="button" class="btn ' . ($lockAccountValue == 1 ? 'btn-danger' : 'btn-success') . '"  
                                            data-bs-toggle="modal" data-bs-target="#lockAccount">
                                                <i class="bi ' . ($lockAccountValue == 1 ? 'bi-lock' : 'bi-unlock') . '"></i>  
                                            </button>';
                                        }
                                    ?>

                                    </div>

                                 

                                    <div data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Assign Modules">
                                        

                                        <?php
                                            // Display the button if Action_Add is 1
                                            if ($actionModuleView == 1) {
                                                echo '<button type="button" class="btn btn-secondary" id="Assignmodule" data-bs-toggle="modal" data-bs-target="#priveleges" data-user-id="' .$row['UserID'] .'">
                                                <i class="bi bi-clipboard"></i>
                                            </button>';
                                            }
                                        ?>
                                    </div>
                                        
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- View modal -->
   <div class="modal fade" id="viewUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
      <div class="modal-dialog  modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title h4" id="exampleModalLgLabel"></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                  
              </div>
              <div class="modal-body" id="modalBody">
               

              </div>
          </div>
      </div>
  </div>

 


    <!-- Privileges modal -->
    <div class="modal fade" id="priveleges" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="exampleModalLgLabel">Modules</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody2">
                    <!-- Placeholder for the data -->
                    <div>

                    </div>
                </div>
            </div>
        </div>
    </div>

  <!-- Modal For User -->
  <div class="modal fade" id="addUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
      <div class="modal-dialog  modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title h4" id="exampleModalLgLabel">Add User</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <form class="row g-3 pt-3 needs-validation" novalidate enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-md-6">
                         

                      <div class="mb-3">
                            <label for="IdNumber" class="form-label">ID</label>
                            <input type="number" class="form-control" id="IdNumber" required name="IdNumber">
                            <div class="invalid-feedback">Please provide User ID.</div>
                        </div>
                      <div class="mb-3">
                          <label for="firstName" class="form-label">First Name</label>
                          <input type="text" class="form-control" id="firstName" required name="firstName">
                          <div class="invalid-feedback">Please provide a first name.</div>
                      </div>
                      <div class="mb-3">
                          <label for="lastName" class="form-label">Last Name</label>
                          <input type="text" class="form-control" id="lastName" required name="lastName">
                          <div class="invalid-feedback">Please provide a last name.</div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="mb-3">
                              <label for="gender" class="form-label">Gender</label>
                              <select class="form-select" id="gender" required name="gender">
                                  <option value="">Select Gender</option>
                                  <option value="male">Male</option>
                                  <option value="female">Female</option>
                              </select>
                              <div class="invalid-feedback">Please select a gender.</div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="mb-3">
                              <label for="birthday" class="form-label">Birthday</label>
                              <input type="date" class="form-control" id="birthday" required name="birthday">
                              <div class="invalid-feedback">Please input birthday.</div>
                          </div>
                        </div>
                      </div>

                    

                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <div class="d-flex justify-content-center">
                          <div class="container">
                            <div id="dropArea" class="text-center" ondrop="handleDrop(event)" ondragover="handleDragOver(event)">
                              <i class="bi bi-download icons"></i>
                              
                            </div>
                            <input type="file" id="fileInput" onchange="handleFileSelect(event)" name="profile" class="form-control mt-3">
                            
                          </div>
                        </div>

                      </div>
                     
                    </div>
                  </div>
                  <div class="mb-3">
                          <label for="validationCustom01" class="form-label">Address</label>
                          <div class="input-group">
                              <input type="text" class="form-control" id="houseNumber" placeholder="House Number" aria-label="House Number" name="houseNumber" required>
                              <input type="text" class="form-control" id="streetName" placeholder="Street Name" aria-label="Street Name" name="streetName" required>
                              <input type="text" class="form-control" id="barangay" placeholder="Barangay" aria-label="Barangay" name="barangay" required>
                              <div class="invalid-feedback"> 
                                  Please provide a complete address.
                              </div>
                          </div>
                          <br>
                          <div class="input-group"> 
                              <input type="text" class="form-control" id="cityMunicipality" placeholder="City/Municipality" aria-label="City/Municipality" name="city" required>
                              <input type="text" class="form-control" id="province" placeholder="Province" aria-label="Province" name="province" required>
                              <div class="invalid-feedback">
                                  Please provide a complete address.
                              </div>
                          </div>
                      </div>  
                  <div class="row">
                     <!-- Left side -->
                    <div class="col-md-6">
                       <!-- Contact Number -->
                       
                       <div class="mb-3">
                            <label for="contactNumber" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contactNum" required pattern="[0-9]{11}" placeholder="Enter a valid 11-digit contact number" name="contactNum">
                            <div class="invalid-feedback" id="contactNumError">Please provide a valid 11-digit contact number.</div>
                        </div>

                        
                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" required name="username">
                            <div class="invalid-feedback">Please provide a username.</div>
                        </div>
                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="passwordInput" required name="password"/>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    Please provide a valid password (at least 8 characters, containing at least one uppercase letter, one lowercase letter, and one digit).
                                </div>
                            </div>
                        </div>
                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" required name="password"/>
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            <div class="invalid-feedback">Please confirm your password.</div>
                            <div id="passwordMismatchError" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Right side -->
                    <div class="col-md-6">
                      <!-- Email -->
                      <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required name="email">
                            <div class="invalid-feedback">Please provide a valid email address.</div>
                        </div>
                      <div class="mb-3">
                      <label for="department" class="form-label">Department</label>
                        <select class="form-select" aria-label="Default select example" name="BaypointeDepartmentID" id="departmentSelect" required  >
                            <option value="">Select Department</option>
                            <?php
                            require('config/db_con.php');

                            // Fetch department data from the database
                            $query = "SELECT * FROM baypointedepartments";
                            $result = mysqli_query($conn, $query);

                            // Check if there are any departments
                            if (mysqli_num_rows($result) > 0) {
                                // Output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Output an option for each department
                                    echo '<option value="' . $row['BaypointeDepartmentID'] . '">' . $row['DepartmentName'] . '</option>';
                                }
                            } else {
                                echo '<option disabled>No departments found</option>';
                            }
                            ?>
                        </select>
                        <div class="invalid-feedback">Please select a department.</div>

                         
                        </div>

                      <!-- Department -->
                      <div class="mb-3">
                      <label for="group" class="form-label">Group</label>
                      <select class="form-select" aria-label="Default select example" name="group" id="groupSelect"  required>
                            <option value="">Select Group</option>
                            <option value="Admin">Admin</option>
                            <option value="Ancillary">Ancillary</option> 
                            <option value="Nursing">Nursing</option>
                            
                        </select>
                          <div class="invalid-feedback">Please select a group.</div>
                      </div>

                      <!-- User Role -->
                      <div class="mb-3">
                        <label for="userRole" class="form-label">User Role</label>
                        <select class="form-select" aria-label="Default select example" name="role" required id="roleSelect">
                            <option value="">Select User Role</option>
                            <option value="0">Super Admin</option>
                            <option value="1">Admin</option>
                            <option value="2">User</option>
                            <option value="3">Doctors</option>
                        </select>
                        <div class="invalid-feedback">Please select a user role.</div>
                        </div>
                      </div>
                    </div>                    
                  <!-- Submit Button -->
                  <div class="col-12">
                      <div class="d-flex gap-2 justify-content-end">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="button" id="addBtn" class="btn btn-primary">Add</button>
                      </div>
                  </div>
              </form>

              </div>
          </div>
      </div>
  </div>

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

  <!--For validdation of password  -->
    <script>    
        document.getElementById('passwordInput').addEventListener('input', function() {
            var password = this.value;
            var hasUpperCase = /[A-Z]/.test(password);
            var hasLowerCase = /[a-z]/.test(password);
            var hasDigit = /\d/.test(password);
            var isValidLength = password.length >= 8;

            var isValid = hasUpperCase && hasLowerCase && hasDigit && isValidLength;

            if (!isValid) {
                this.setCustomValidity("Please provide a valid password (at least 8 characters, containing at least one uppercase letter, one lowercase letter, and one digit).");
                this.parentNode.querySelector('.invalid-feedback').style.display = 'block';
            } else {
                this.setCustomValidity('');
                this.parentNode.querySelector('.invalid-feedback').style.display = 'none';
            }
        });

            
    </script>

    <!--Conact Number  -->
    <script>
        // Get the input element
        var inputElement = document.getElementById("contactNum");

        // Add event listener for input event
        inputElement.addEventListener("input", function(event) {
            // Get the value entered by the user
            var inputValue = event.target.value;

            // Remove non-numeric characters from the input value
            var numericValue = inputValue.replace(/\D/g, "");

            // Update the input field value with numeric-only value
            event.target.value = numericValue;
        });
    </script>

 
   
    <!-- Add data -->
    <script>
      $(document).ready(function () {
        $('#addBtn').click(function (e) {
            // e.preventDefault();
            var form = $('.needs-validation')[0];
            console.log(form);
            if (form.checkValidity()) {
                var formData = new FormData(form);
                var loading = Swal.fire({
                    title: 'Please wait',
                    html: 'Submitting your data...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    }
                });
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: 'DataAdd/test.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        response = JSON.parse(response);
                        toastr.clear(); // Clear all toastr messages
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.success,
                                // showConfirmButton: false,
                                // timer: 5000,
                            }).then(function() {
                                window.location.href = 'accounts.php';
                            });
                        } else if (response.error) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Warning',
                                text: response.error,
                            });
                        }
                    },
                    error: function () {
                        loading.close(); // Close loading animation
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while submitting data.',
                        });
                    },
                    complete: function() {
                        loading.close(); // Close loading animation regardless of success or failure
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Please fill in all required fields.'
                });
            }
            form.classList.add('was-validated'); // Add 'was-validated' class to show validation errors
        });
      });

    </script>
    
  <!--Show password and hide  -->
  <script>
    // Toggle Password Visibility for Password Field
    const togglePasswordButton = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');

    togglePasswordButton.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });

    // Toggle Password Visibility for Confirm Password Field
    const toggleConfirmPasswordButton = document.getElementById('toggleConfirmPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');

    toggleConfirmPasswordButton.addEventListener('click', function () {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });
</script>


  <!-- Filtering for Dep.
  
  <script>
    function updateDepartments() {
      var groupSelect = document.getElementById("groupSelect");
      var departmentSelect = document.getElementById("departmentSelect");
      var selectedGroup = groupSelect.value;

      // Clear existing options
      departmentSelect.innerHTML = '<option value="">Select Department</option>';

      // Add departments based on the selected group
      switch (selectedGroup) {
        case "Admin":
          addOptions(["Accounting", "Admitting", "Ambulance", "Billing", "Cashier", "Admitting/Comm. & Info.", "Dietary", "HMO", "HRDM", "IPCU", "Linen", "Medical Records", "MIS", "OHS", "PhilHealth", "Property", "Purchasing", "Quality Management", "Sales & Marketing", "Social Service", "TRICARE", "Board Secretary", "Engineering & Maintenance", "PCO"]);
          break;
        case "Ancillary":
          addOptions(["Blood Bank", "CSR", "Heart Station", "Laboratory", "OB-GYNE", "Pharmacy", "Pulmonary", "Radiology", "Diabetes", "Rehabilitation & Medicine"]);
          break;
        case "Nursing":
          addOptions(["CTU", "Dialysis", "ER", "ICU", "NICU", "Nurse Station 1", "Nurse Station 2", "NSO", "OPD", "ORDR", "EMG"]);
          break;
        case "Outsource":
          addOptions(["Eye Center", "Security"]);
          break;
        default:
          // No group selected, do nothing
          break;
      }
    }

    function addOptions(departments) {
      var departmentSelect = document.getElementById("departmentSelect");
      departments.forEach(function(department) {
        var option = document.createElement("option");
        option.text = department;
        option.value = department;
        departmentSelect.add(option);
      });
    }
  </script> -->
 
  <!-- Drag photo -->
  <script>
    function handleFileSelect(event) {
        const file = event.target.files[0];
        handleFile(file);
    }

    function handleDrop(event) {
        event.preventDefault();
        const file = event.dataTransfer.files[0];
        handleFile(file);

        const fileInput = document.getElementById('fileInput');
        fileInput.files = event.dataTransfer.files;
    }

    function handleDragOver(event) {
        event.preventDefault();
    }

    function handleFile(file) {
        const dropArea = document.getElementById('dropArea');
        const fileReader = new FileReader();

        fileReader.onload = () => {
            let fileURL = fileReader.result;
            let imgTag = document.createElement('img');
            imgTag.src = fileURL;
            imgTag.alt = 'profile';
            imgTag.name = 'profile';
            imgTag.width = 200;
            imgTag.height = 200;
            dropArea.innerHTML = '';
            dropArea.appendChild(imgTag);

            // Log the file to the console
            console.log("Selected File:", file);
            console.log("Image tag:", imgTag);
        };

        // Read the file as Data URL (base64 encoded)
        fileReader.readAsDataURL(file);
    }
</script>
 
<script>
  $(document).ready(function() {
    $('.btn-primary').click(function() {
      var userid = $(this).data('user-id');
      
      // Make an AJAX request to fetch data from the server
      $.ajax({
        url: 'DataGet/get_User.php', // PHP script to fetch data from the server
        method: 'POST',
        data: { userid: userid },
        success: function(response) {
          // Insert the HTML into the modal body
          $('#modalBody').html(response);
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    });
  });
</script>

 
<!-- Script to check authentication status and show privileges -->
<script>
  $(document).ready(function() {
    $('.btn-secondary').click(function() {
      var userid = $(this).data('user-id');
      
      // Make an AJAX request to fetch data from the server
      $.ajax({
        url: 'DataGet/userPrivileges.php', // PHP script to fetch data from the server
        method: 'POST',
        data: { userid: userid },
        success: function(response) {
          // Insert the HTML into the modal body
          $('#modalBody2').html(response);
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    });
  });
</script>
 

<!-- for hiding inspect and view page source -->
  <!-- <script>

    document.onkeydown = function(e) {
      if(e.keyCode == 123) {
      return false;
      }
      if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
      return false;
      }
      if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
      return false;
      }
      if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
      return false;
      }

      if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)){
      return false;
      }      
    }

    document.oncontextmenu = function(e) {
        e.preventDefault();
    }

  </script> -->

  <!-- Password matching -->
  <script>
    document.getElementById("confirmPassword").addEventListener("input", function () {
        var password = document.getElementById("passwordInput").value;
        var confirmPassword = document.getElementById("confirmPassword").value;
        var error = document.getElementById("passwordMismatchError");

        if (password !== confirmPassword) {
            error.textContent = "Passwords do not match.";
            document.getElementById("confirmPassword").setCustomValidity("Passwords do not match.");
        } else {
            error.textContent = "";
            document.getElementById("confirmPassword").setCustomValidity("");
        }
    });
</script>

<script>
    const contactNumInput = document.getElementById('contactNum');
    const contactNumError = document.getElementById('contactNumError');

    contactNumInput.addEventListener('input', function() {
        const isValid = this.validity.valid;
        if (!isValid) {
            contactNumError.style.display = 'block';
        } else {
            contactNumError.style.display = 'none';
        }
    });
</script>

<!-- Group based on department selected -->
<script>
    function updateGroups(){
        var departSelect = document.getElementById("departmentSelect");
        var groupSelect = document.getElementById("groupSelect");
        var selectedDep = departSelect.value;

        // Reset group dropdown
        groupSelect.selectedIndex = 0;

        // Enable all options
        for (var i = 0; i < groupSelect.options.length; i++){
            groupSelect.options[i].disabled = false;
        }

        switch(selectedDep){
            case "13": // MIS
            case "9": // HRDM
            case "19": // Sales & Marketing
                groupSelect.value = "Admin";
                groupSelect.options[2].style = 'none'; // Disable "Ancillary"
                groupSelect.options[3].style = 'none'; // Disable "Nursing"
                break;
            case "6": // Admitting/Comm. & Info.
            case "7": // Dietary
            case "25": // Blood Bank
            case "27": // Heart Station
            case "28": // Laboratory
            case "29": // OB-GYNE
            case "30": // Pharmacy
            case "31": // Pulmonary
            case "32": // Radiology
            case "33": // Diabetes
            case "34": // Rehabilitation & Medicine
                groupSelect.value = "Ancillary";
                groupSelect.options[1].style = 'none'; // Disable "Admin"
                groupSelect.options[3].style = 'none'; // Disable "Nursing"
                break;
            case "35": // CTU
            case "36": // Dialysis
            case "37": // ER
            case "39": // NICU
            case "43": // OPD
            case "45": // EMG
                groupSelect.value = "Nursing";
                groupSelect.options[1].style = 'none'; // Disable "Admin"
                groupSelect.options[2].style = 'none'; // Disable "Ancillary"
                break;
            default:
                groupSelect.selectedIndex = 0; // Default selection
                break;
        }
    }
</script>




<!-- User ROles based on department selected -->
<script>
    function updateUserRole() {
        var departmentSelect = document.getElementById("departmentSelect");
        var roleSelect = document.getElementById("roleSelect");
        var selectedDepartment = departmentSelect.value;

        // Reset role dropdown
        roleSelect.selectedIndex = 0;

        // Hide all options first
        for (var i = 1; i < roleSelect.options.length; i++) {
            roleSelect.options[i].style.display = "none";
        }

        // Update role options based on selected department
        switch (selectedDepartment) {
            case "13": // MIS
                roleSelect.value = "0"; // Super Admin
                break;
            case "9": // HRDM
            case "19": // Sales & Marketing
            case "43": // OPD
                roleSelect.options[1].style.display = "block"; // Admin
                break;
            case "6": // Admitting/Comm. & Info.
            case "7": // Dietary
            case "25": // Blood Bank
            case "27": // Heart Station
            case "28": // Laboratory
            case "29": // OB-GYNE
            case "30": // Pharmacy
            case "31": // Pulmonary
            case "32": // Radiology
            case "33": // Diabetes
            case "34": // Rehabilitation & Medicine
            case "35": // CTU
            case "36": // Dialysis
            case "37": // ER
            case "39": // NICU
            case "45": // EMG
                roleSelect.options[2].style.display = "block"; // User
                break;
            default:
                roleSelect.selectedIndex = 0; // Default selection
                break;
        }
    }
</script>

<script type="module">
            import {DataTable} from "../dist/module.js"
            window.dt = new DataTable("#demo-table", {
                perPageSelect: [10, 50, ["All", -1]],
                columns: [
                    {
                        select: 2,
                        sortSequence: ["desc", "asc"]
                    },
                    {
                        select: 3,
                        sortSequence: ["desc"]
                    }
                ],
                tableRender: (_data, table, type) => {
                    if (type === "print") {
                        return table
                    }
                    const tHead = table.childNodes[0]
                    const filterHeaders = {
                        nodeName: "TR",
                        childNodes: tHead.childNodes[0].childNodes.map(
                            (_th, index) => ({nodeName: "TH",
                                childNodes: [
                                    {
                                        nodeName: "INPUT",
                                        attributes: {
                                            class: "datatable-input",
                                            type: "search",
                                            "data-columns": `[${index}]`
                                        }
                                    }
                                ]})
                        )
                    }
                    tHead.childNodes.push(filterHeaders)
                    return table
                }
            })
        </script>

</body>

</html>