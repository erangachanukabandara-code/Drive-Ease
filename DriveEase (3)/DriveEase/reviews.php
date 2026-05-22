<?php
require 'db.php';
$sql = "SELECT * FROM feedbacks ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews | Drive Ease</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background-color: #f8fafc; color: #0f172a; }
        
        .navbar { background-color: white; padding: 20px 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .logo { font-size: 1.8rem; font-weight: 700; color: #0f172a; text-decoration: none; } .logo span { color: #3b82f6; }
        .nav-links { list-style: none; display: flex; gap: 30px; } .nav-links li a { text-decoration: none; color: #475569; font-weight: 600; }

        .page-hero { height: 50vh; background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)), url('https://plus.unsplash.com/premium_photo-1682309674226-fa47fc54568e?q=80&w=1212&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; }
        .page-hero h1 { font-size: 4rem; font-weight: 700; margin-bottom: 10px; }

        .reviews-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; padding: 60px 5%; max-width: 1400px; margin: 0 auto; }
        .review-box { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; display: flex; flex-direction: column; }
        .stars { color: #d4af37; margin-bottom: 15px; font-size: 1.2rem; }
        .review-text { color: #475569; font-size: 1.05rem; line-height: 1.6; font-style: italic; margin-bottom: 20px; flex-grow: 1; }
        .reviewer { display: flex; align-items: center; gap: 15px; border-top: 1px solid #f1f5f9; padding-top: 20px; }
        .reviewer i { font-size: 2rem; color: #cbd5e1; }
        .reviewer-name { font-weight: 700; color: #0f172a; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="home.php" class="logo">Driver <span>Ease</span></a>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="reviews.php" style="color: #3b82f6;">Reviews & Feedbacks</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
    </nav>

    <div class="page-hero"><h1>Reviews & Feedbacks</h1></div>

    <div class="reviews-container">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Generate Star Ratings
                $starsHTML = '';
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $row['rating']) {
                        $starsHTML .= '<i class="fa-solid fa-star"></i>';
                    } else {
                        $starsHTML .= '<i class="fa-regular fa-star"></i>';
                    }
                }

                echo '
                <div class="review-box">
                    <div class="stars">' . $starsHTML . '</div>
                    <p class="review-text">"' . htmlspecialchars($row['review_text']) . '"</p>
                    <div class="reviewer">
                        <i class="fa-solid fa-circle-user"></i>
                        <span class="reviewer-name">' . htmlspecialchars($row['customer_name']) . '</span>
                    </div>
                </div>';
            }
        } else {
            echo '<p style="grid-column: 1/-1; text-align: center; color: #64748b; font-size: 1.2rem;">No reviews have been posted yet.</p>';
        }
        ?>
    </div>
</body>
</html>