<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>SAE 401</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <style>
    a {
      text-decoration: none;
    }

    html,
    body {
      min-height: 100%;
      height: 100vh;
    }
    
    .baniere {
    background-image: url("baniere.jpg");
    height: 95vh;
    background-size: cover;

    background-position: center;
    }
  </style>
</head>
<body class="d-flex flex-column">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <div class="container">
            <a class="navbar-brand me-2 fw-bold" href="http://bikestore.wuaze.com/index.php?action=home">
                BikeStores
            </a>
            <button data-mdb-collapse-init class="navbar-toggler" type="button" data-mdb-target="#navbarButtonsExample"
                aria-controls="navbarButtonsExample" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarButtonsExample">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link" href="http://bikestore.wuaze.com/index.php?action=home">Products</a>
                        <a href="http://bikestore.wuaze.com/index.php?action=LegalNotices" class="nav-link">Legal Notices</a>
                        <?php
                        // PHP logic to dynamically generate navigation links based on user role
                        if(isset($_COOKIE['role'])) {
                            // Decoding user role from the cookie
                            $cookieValue = urldecode($_COOKIE['role']);
                            $parts = explode('&', $cookieValue);
                            if(count($parts) >= 2) {
                                $role = base64_decode($parts[0]);
                                // Checking user role and displaying appropriate links
                                if($role == 'employee' || $role == "chief" || $role == 'it') {
                                    // Displaying additional links for certain roles
                                    echo '<a href="http://bikestore.wuaze.com/index.php?action=Add" class="nav-link">Add</a>';
                                    echo '<a href="http://bikestore.wuaze.com/index.php?action=Delete" class="nav-link">Delete</a>';
                                    echo '<div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                                        <ul class="navbar-nav">
                                            <li class="nav-item dropdown">
                                                <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Modify
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-dark">
                                                    <li><a class="dropdown-item" href="http://bikestore.wuaze.com/index.php?action=ModifyCategory">Category</a></li>
                                                    <li><a class="dropdown-item" href="http://bikestore.wuaze.com/index.php?action=ModifyBrand">Brand</a></li>
                                                    <li><a class="dropdown-item" href="http://bikestore.wuaze.com/index.php?action=ModifyStores">Stores</a></li>
                                                    <li><a class="dropdown-item" href="http://bikestore.wuaze.com/index.php?action=ModifyStock">Stock</a></li>
                                                    <li><a class="dropdown-item" href="http://bikestore.wuaze.com/index.php?action=ModifyProduct">Product</a></li>
                                                    <li><a class="dropdown-item" href="http://bikestore.wuaze.com/index.php?action=ModifyLogin">Login</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>';
                                    // Displaying additional links for certain roles
                                    if($role == "chief") {
                                        echo '<a href="http://bikestore.wuaze.com/index.php?action=ShowEmployeesBystore" class="nav-link">Show Employees</a>';
                                        echo '<a href="http://bikestore.wuaze.com/index.php?action=AddEmployeesBystore" class="nav-link">Add Employee</a>';
                                    } else if($role == 'it') {
                                        echo '<a href="http://bikestore.wuaze.com/index.php?action=ShowEmployees" class="nav-link">Show Employees</a>';
                                        echo '<a href="http://bikestore.wuaze.com/index.php?action=AddEmployee" class="nav-link">Add Employee</a>';
                                    }
                                echo '</div>';
                                }
                            }
                        }
                        ?>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <?php
                     if(isset($_COOKIE['role']) && $_COOKIE['role'] != '') {
                      // If the 'role' cookie is set and not empty, display a "Log out" button
                      echo '<button data-mdb-ripple-init type="button" class="btn btn-primary me-3" id="boutonDeconnexion">
                             Log out
                            </button>';
                      } else {
                          // If the 'role' cookie is not set or empty, display a "Login" button
                          echo '<button data-mdb-ripple-init type="button" class="btn btn-primary me-3">
                                  <a href="http://bikestore.wuaze.com/index.php?action=login" class="text-light">Login</a>
                                </button>';
                      }
                    ?>
                </div>
            </div>
        </div>
    </nav>
    <script>
     document.addEventListener("DOMContentLoaded", function() {
    // Selecting the "Log out" button by its id
    var boutonDeconnexion = document.querySelector("#boutonDeconnexion");
    // Checking if the "Log out" button exists
    if (boutonDeconnexion) {
        // Adding a click event listener to the "Log out" button
        boutonDeconnexion.addEventListener("click", function() {
            // Clearing the 'role' and 'id' cookies by setting their expiration date to a past time
            document.cookie = "role=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            // Redirecting the user to the home page after logout
            window.location.href = "http://bikestore.wuaze.com/index.php?action=home";
        });
    }
    });
    </script>
    <main class="flex-grow-1 flex-shrink-1 mb-4">
        


    

