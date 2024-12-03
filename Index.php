<?php include 'navbar.php'; ?>
<?php
if (isset($_GET['message'])) {
    header("Location: index.php");
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">Hotel Booking</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class ="nav-link" href="rooms.php">Rooms</a>
            </li>
            <li class="nav-item">
                <a class ="nav-link" href="services.php">Services</a>
            </li>
            <li class="nav-item">
                <a class ="nav-link" href="about.php">About us</a>
            </li>
        </ul>
        <div class="d-flex">
            <button type="button" class="btn btn-outline-dark me-lg-4" data-bs-toggle="modal" data-bs-target="#loginbutton">
            Login
            </button>
            <button type="button" class="btn btn-outline-dark me-lg-4" data-bs-toggle="modal" data-bs-target="#adminbutton">
            Admin Login
            </button>
            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#regbutton">
            Register
            </button>
        </div>
      </div>
    </div>
    </nav>
    <!-- user login-->
    <div class="modal fade" id="loginbutton" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Login Form -->
            <form method="POST" action="login_handler.php">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person"></i> User Login
                    </h5>
                    <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Feedback Placeholder -->
                    <div id="loginFeedback" class="text-danger mb-3" style="display: none;"></div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="username" class="form-label">username</label>
                        <input type="text" id="username" name="username" class="form-control shadow-none" required>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control shadow-none" required>
                    </div>

                    <!-- Login Button -->
                    <div class="d-flex align-items-center justify-content-between">
                        <button type="submit" class="btn btn-primary">Login</button>
                        <a href="#" class="text-decoration-none">Forgot Password?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



        <!--admin login-->
        <div class="modal fade" id="adminbutton" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Login Form -->
            <form method="POST" action="admin_login_handler.php">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person"></i> Admin Login
                    </h5>
                    <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Feedback Placeholder -->
                    <div id="loginFeedback" class="text-danger mb-3" style="display: none;"></div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" id="username" name="username" class="form-control shadow-none" required>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control shadow-none" required>
                    </div>

                    <!-- Login Button -->
                    <div class="d-flex align-items-center justify-content-between">
                        <button type="submit" class="btn btn-primary">Login</button>
                        <a href="#" class="text-decoration-none">Forgot Password?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    <div>
        <!-- register-->

        <div class="modal fade" id="regbutton" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form method="POST" action="register_handler.php">


                
            <div class="modal-header">
                <h5 class="modal-title">
                <i class="bi bi-person-vcard"></i> User Registration
                </h5>
                <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <span class="badge bg-light text-dark mb-3 text-wrap lh-base">
                Note: Your details must match with your ID (passport, Driving License,etc) that will be required during your check-in. 
            </span>
            <div class="container-fluid">
                <div class="row">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Full Name" required>

                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Username" required>

                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" placeholder="Phone (optional)">

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>

                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                </div> 
            <div class="text-center">
               <button type="submit" class="btn btn-dark">Register</button> 
            </div>

            </div>
            </form>
           </div>
         </div>
        </div>
        
        </div>

        <!-- Hotel -->
  <div class="swiper mySwiper" px-lg-4 mt-4>
    <div class="swiper-wrapper">
    <div class="swiper-slide">
        <img src="images/Hotel/Hotel6.jpg" class="w-100 d-block" />
      </div>
      <div class="swiper-slide">
        <img src="images/Hotel/Hotel-1.jpg" class="w-100 d-block"/>
      </div>
      <div class="swiper-slide">
        <img src="images/Hotel/Hotel-2.jpg" class="w-100 d-block"/>
      </div>
      <div class="swiper-slide">
        <img src="images/Hotel/Hotel-3.jpg" class="w-100 d-block"/>
      </div>
      <div class="swiper-slide">
        <img src="images/Hotel/Hotel-4.jpg" class="w-100 d-block"/>
      </div>
    </div>
    
    <div class="swiper-pagination"></div>
  </div>



   

    <!-- Rooms-->
     <h2 class="mt-4 pt-4 mb-4 text-center fw-bold h-font">Our Rooms</h2>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin:auto;">
                <img src="images/rooms/room-1.jpg" class="card-img-top" alt="room">
                <div class="card-body">
                    <h5 >Single Room</h5>
                    <div class="features mb-4">
                    <h6 class="mb-1">Features</h6>
                    <span class="badge bg-light text-dark text-wrap">
                    1 Rooms
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    1 Bathrooms
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    1 Balcony
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    2 sofa
                    </span>
                    </div>
                    <div class="facilities mb-4">
                    <h6 class="mb-1">Facilities</h6>
                    <span class="badge bg-light text-dark text-wrap">
                    Television
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    Wi-Fi
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    AC
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    Heater
                    </span>
                    </div>
                    <div class="rating mb-4">
                    <h6 class="mb-1">Ratings</h6>
                    <span>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    </span>
                    </div>
                    
                    <div class="d-flex" justify-content-evenly></div>
                    <a href="#" class="btn btn-sm btn-outline-dark rounded-0 fw-bold">Book Now</a>
                </div>
            </div>
            </div>
            <div class="col-lg-4 col-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin:auto;">
                <img src="images/rooms/room-3.jpg" class="card-img-top" alt="room">
                <div class="card-body">
                    <h5 >Double bedroom </h5>
                    <div class="features mb-4">
                    <h6 class="mb-1">Features</h6>
                    <span class="badge bg-light text-dark text-wrap">
                    1 Rooms
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    1 Bathrooms
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    1 Balcony
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    3 sofa
                    </span>
                    </div>
                    <div class="facilities mb-4">
                    <h6 class="mb-1">Facilities</h6>
                    <span class="badge bg-light text-dark text-wrap">
                    Television
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    Wi-Fi
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    AC
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    Heater
                    </span>
                    </div>
                    <div class="rating mb-4">
                    <h6 class="mb-1">Ratings</h6>
                    <span>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    </span>
                    </div>
                    
                    <div class="d-flex" justify-content-evenly></div>
                    <a href="#" class="btn btn-sm btn-outline-dark rounded-0 fw-bold">Book Now</a>
                </div>
            </div>
            </div>
            </span>
            <div class="col-lg-4 col-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin:auto;">
                <img src="images/rooms/room-4.jpg" class="card-img-top" alt="room">
                <div class="card-body">
                    <h5 >Family Room</h5>
                    <div class="features mb-4">
                    <h6 class="mb-1">Features</h6>
                    <span class="badge bg-light text-dark text-wrap">
                    1 Rooms
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    2 Bathrooms
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    1 Balcony
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    4 sofa
                    </span>
                    </div>
                    <div class="facilities mb-4">
                    <h6 class="mb-1">Facilities</h6>
                    <span class="badge bg-light text-dark text-wrap">
                    Television
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    Wi-Fi
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    AC
                    </span>
                    <span class="badge bg-light text-dark text-wrap">
                    Heater
                    </span>
                    </div>
                    <div class="rating mb-4">
                    <h6 class="mb-1">Ratings</h6>
                    <span>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    </span>
                    </div>
                    
                    <div class="d-flex" justify-content-evenly></div>
                    <a href="index.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold">Book Now</a>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!--facilities-->
    <h2 class="mt-4 pt-4 mb-4 text-center fw-bold h-font">Our Facilities</h2>
        <div class="container">
            <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
                <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                    <img src="images/features/wifi.png" alt="wifi" width="80 px">
                <h6 class="mt-3">Wi-Fi<h6>
                </div>
                <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                    <img src="images/features/Swimmingpool.png" alt="wifi" width="80 px">
                <h6 class="mt-3">Swimming Pool<h6>
                </div>
                <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                    <img src="images/features/gym.png" alt="wifi" width="80 px">
                <h6 class="mt-3">Gym<h6>
                </div>
                <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                    <img src="images/features/spa.png" alt="wifi" width="80 px">
                <h6 class="mt-3">Spa<h6>
                </div>
                <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                    <img src="images/features/parking.jpg" alt="wifi" width="80 px">
                <h6 class="mt-3">Parking<h6>
                </div>
            </div>
        </div>
        
</div>
<!-- Reviews Section -->
<h2 class="mt-4 pt-4 mb-4 text-center fw-bold h-font">Reviews</h2>
<div class="container" style="max-width: 600px;"> <!-- Restrict container width -->
    <div class="swiper swiper-testimonials" style="height: 200px;"> <!-- Limit Swiper height -->
        <div class="swiper-wrapper">
            <?php
            // Path to the reviews file
            $review_file = 'reviews.json';

            // Load reviews from the file
            if (file_exists($review_file)) {
                $reviews = json_decode(file_get_contents($review_file), true) ?? [];

                // Filter for only "visible" reviews
                $visible_reviews = array_filter($reviews, fn($review) => $review['status'] === 'visible');

                if (!empty($visible_reviews)) {
                    $visible_reviews = array_slice(array_reverse($visible_reviews), 0, 5); // Limit to 5 reviews
                    foreach ($visible_reviews as $review) {
                        echo '<div class="swiper-slide bg-light p-2 rounded shadow-sm" style="height: 150px;">'; // Limit slide height
                        echo '<p class="small text-muted mb-2">' . htmlspecialchars($review['review']) . '</p>';
                        echo '<div class="rating mt-1">';
                        echo '<i class="bi bi-star-fill text-warning small"></i>';
                        echo '<i class="bi bi-star-fill text-warning small"></i>';
                        echo '<i class="bi bi-star-fill text-warning small"></i>';
                        echo '<i class="bi bi-star-fill text-warning small"></i>';
                        echo '<i class="bi bi-star-fill text-warning small"></i>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="swiper-slide bg-light p-2 rounded shadow-sm">';
                    echo '<p class="small text-muted">No reviews available. Be the first to leave a review!</p>';
                    echo '</div>';
                }
            } else {
                echo '<div class="swiper-slide bg-light p-2 rounded shadow-sm">';
                echo '<p class="small text-muted">No reviews available. Be the first to leave a review!</p>';
                echo '</div>';
            }
            ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
</div>







     <br><br><br>
     <br><br><br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

   
  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
        delay: 1000,
        disableOnInteraction: false,
      }
      
      
    });

    var swiper = new Swiper(".swiper-testimonials", {
      pagination: {
        el: ".swiper-pagination",
        type: "fraction",
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  </script>
    


</body>
</html>