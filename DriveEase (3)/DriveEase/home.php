<?php
require 'db.php'; 

// Fetch all vehicles with the owner's name using a JOIN query
$vehicles_sql = "SELECT v.*, o.full_name AS owner_name 
                 FROM owner_vehicals v 
                 LEFT JOIN owner_accounts o ON v.owner_id = o.id 
                 ORDER BY v.id DESC";
$vehicles_result = $conn->query($vehicles_sql);

// Fetch ALL deals (removed the filter that ignored text-only deals)
$deals_sql = "SELECT * FROM deals ORDER BY id DESC";
$deals_result = $conn->query($deals_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Ease | The Best Vehicle Rental Service</title>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
        @font-face {
            font-family: 'Google Sans';
            src: url('https://fonts.gstatic.com/s/productsans/v5/HYvgU2fE2nRJvZ5JFAumwegdm0LZdjqr5-oayXSOefg.woff2') format('woff2');
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Google Sans', 'Open Sans', sans-serif; }
        body { background-color: #f8fafc; color: #0f172a; overflow-x: hidden; }

        /* 1. Continuous Seamless Marquee (White Text) */
        .marquee-wrapper {
            background-color: #0f172a;
            color: #ffffff;
            padding: 10px 0;
            display: flex;
            overflow: hidden;
            position: relative;
            z-index: 100;
        }
        .marquee-content {
            display: flex;
            animation: scrollText 15s linear infinite;
        }
        .marquee-item {
            white-space: nowrap;
            padding: 0 30px;
            font-weight: 500;
            letter-spacing: 1px;
            font-size: 0.95rem;
        }
        @keyframes scrollText {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        /* Navbar */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 99;
            backdrop-filter: blur(10px);
        }
        .logo { font-size: 1.8rem; font-weight: 700; color: #0f172a; text-decoration: none; }
        .logo span { color: #3b82f6; }
        .nav-links { list-style: none; display: flex; gap: 30px; }
        .nav-links li a { text-decoration: none; color: #475569; font-weight: 600; transition: 0.3s; }
        .nav-links li a:hover { color: #3b82f6; }

        /* 2. Hero Slider (Fixed Overlapping) */
        .heroSwiper { width: 100%; height: 80vh; }
        .swiper-slide { width: 100%; height: 100%; }
        
        .hero-slide {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 0 20px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        /* Dark opacity overlay for the banner */
        .hero-slide::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.75);
            z-index: 1;
        }
        .hero-content { position: relative; z-index: 2; }
        .hero-content h1 { font-size: 4.5rem; font-weight: 700; margin-bottom: 10px; letter-spacing: 2px; }
        .hero-content h2 { font-size: 1.8rem; font-weight: 400; color: #d4af37; margin-bottom: 25px; }
        .hero-content p { max-width: 700px; font-size: 1.1rem; line-height: 1.8; color: #cbd5e1; margin: 0 auto; }

        /* 3. Vehicles Grid */
        .section-title { text-align: center; font-size: 2.5rem; font-weight: 700; margin: 60px 0 20px; color: #0f172a; }
        .vehicles-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; padding: 40px 5%; max-width: 1400px; margin: 0 auto; }
        .vehicle-card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.08); transition: 0.3s; border: 1px solid #e2e8f0; display: flex; flex-direction: column; }
        .vehicle-card:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(0,0,0,0.15); }
        .vehicle-img { width: 100%; height: 220px; object-fit: cover; }
        .vehicle-info { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
        .vehicle-info h3 { font-size: 1.4rem; font-weight: 700; color: #0f172a; margin-bottom: 15px; }
        .specs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; }
        .spec-item { font-size: 0.9rem; color: #475569; display: flex; align-items: center; gap: 8px; }
        .spec-item i { color: #3b82f6; width: 16px; }
        .price-tag { font-size: 1.5rem; font-weight: 700; color: #dc2626; margin-bottom: 20px; text-align: center; background: #fef2f2; padding: 10px; border-radius: 8px; }
        .btn-book { width: 100%; padding: 14px; background: #0f172a; color: white; border: none; border-radius: 8px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: 0.3s; margin-top: auto; }
        .btn-book:hover { background: #3b82f6; }

        /* 4. Deals Slider */
        .deals-section { background-color: #1e293b; padding: 60px 0; margin-top: 50px; }
        .deals-section .section-title { color: white; margin-top: 0; margin-bottom: 40px; }
        .dealsSwiper { width: 90%; max-width: 1200px; height: 400px; border-radius: 15px; }
        .dealsSwiper .swiper-slide { display: flex; justify-content: center; align-items: center; background: #0f172a; border-radius: 15px; overflow: hidden; position: relative; }
        .dealsSwiper .swiper-slide img { width: 100%; height: 100%; object-fit: cover; border-radius: 15px; }
        
        /* New Styles for Text and Overlay Deals */
        .deal-text-wrapper { padding: 40px; text-align: center; color: white; width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; background: linear-gradient(135deg, #0f172a, #1e293b); }
        .deal-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.75); display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 40px; color: white; border-radius: 15px; }
        .deal-title { font-size: 2.5rem; font-weight: 700; margin-bottom: 15px; color: #ffffff; }
        .deal-highlight { display: inline-block; background: #dc2626; color: white; padding: 8px 25px; border-radius: 30px; font-weight: 600; font-size: 1.1rem; margin-bottom: 20px; }
        .deal-details { font-size: 1.1rem; color: #cbd5e1; max-width: 800px; line-height: 1.6; }

        /* Professional Floating Chatbot */

        .chatbot-toggler {
        position: fixed; bottom: 30px; right: 30px; width: 65px; height: 65px;
        background: #3b82f6; color: white; border-radius: 50%;
        display: flex; justify-content: center; align-items: center;
        font-size: 1.8rem; cursor: pointer; z-index: 1000; transition: 0.3s;
        
        /* The new animation triggers */
        animation: chatFloat 3s ease-in-out infinite, chatPulse 2s infinite;
    }

    /* Hover effect stops the pulse so it feels responsive to the user */
    .chatbot-toggler:hover { 
        transform: scale(1.08); 
        background: #2563eb; 
        animation: none; /* Stops animation on hover */
        box-shadow: 0 15px 35px rgba(59, 130, 246, 0.5);
    }

    /* 1. Gentle Up and Down Floating */
    @keyframes chatFloat {
        0% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
        100% { transform: translateY(0); }
    }

    /* 2. Radar/Pulse Shadow Glow */
    @keyframes chatPulse {
        0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(59, 130, 246, 0); }
        100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
    

    .chatbot-window {
        position: fixed; bottom: 110px; right: 30px; width: 360px; height: 500px;
        background: white; border-radius: 15px; box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        display: flex; flex-direction: column; overflow: hidden; z-index: 1000;
        transform: scale(0); transform-origin: bottom right; transition: 0.3s ease-in-out;
        border: 1px solid #e2e8f0; font-family: 'Outfit', sans-serif;
    }
    .chatbot-window.active { transform: scale(1); }

    .chat-header {
        background: #0f172a; color: white; padding: 20px;
        display: flex; justify-content: space-between; align-items: center;
    }
    .chat-header h4 { font-size: 1.2rem; margin-bottom: 4px; font-weight: 600; }
    .chat-header p { font-size: 0.85rem; color: #94a3b8; margin: 0; }
    .chat-header button { background: none; border: none; color: white; font-size: 1.3rem; cursor: pointer; transition: 0.3s; }
    .chat-header button:hover { color: #ef4444; transform: rotate(90deg); }

    .chat-messages { flex-grow: 1; padding: 20px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 15px; }
    .message { max-width: 85%; padding: 12px 16px; border-radius: 12px; font-size: 0.95rem; line-height: 1.5; }
    .bot-message { background: white; color: #0f172a; border: 1px solid #e2e8f0; align-self: flex-start; border-bottom-left-radius: 2px; }
    .user-message { background: #3b82f6; color: white; align-self: flex-end; border-bottom-right-radius: 2px; }

    .chat-input { padding: 15px; background: white; border-top: 1px solid #e2e8f0; display: flex; gap: 10px; align-items: center; }
    .chat-input input { flex-grow: 1; padding: 14px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-family: 'Outfit', sans-serif; font-size: 0.95rem; transition: 0.3s;}
    .chat-input input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    .chat-input button { background: #0f172a; color: white; border: none; height: 100%; width: 50px; border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 1.1rem; }
    .chat-input button:hover { background: #3b82f6; }
    
    .typing-indicator { font-size: 0.85rem; color: #94a3b8; font-style: italic; align-self: flex-start; display: none; }

    /* Quick Reply Options */
    .chat-options { display: flex; flex-wrap: wrap; gap: 8px; margin-top: -5px; align-self: flex-start; max-width: 90%; }
    .chat-option-btn { 
        background: white; color: #3b82f6; border: 1px solid #3b82f6; 
        padding: 8px 14px; border-radius: 20px; font-size: 0.85rem; 
        cursor: pointer; transition: 0.3s; font-family: 'Outfit', sans-serif; font-weight: 500;
    }
    .chat-option-btn:hover { background: #3b82f6; color: white; }

        /* 5. Partner Portals & Professional Footer */
        .portal-section { background-color: #f1f5f9; padding: 50px 20px; text-align: center; border-top: 1px solid #e2e8f0; }
        .portal-section h3 { font-size: 1.8rem; color: #0f172a; margin-bottom: 25px; }
        .portal-buttons { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }
        .btn-portal { padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: 0.3s; display: inline-flex; align-items: center; gap: 10px; font-size: 1.05rem; border: none; cursor: pointer; }
        .btn-driver { background: #3b82f6; color: white; } .btn-driver:hover { background: #2563eb; transform: translateY(-2px); }
        .btn-owner { background: #d4af37; color: #0f172a; } .btn-owner:hover { background: #b5952f; transform: translateY(-2px); }
        .btn-admin { background: #dc2626; color: white; } .btn-admin:hover { background: #b91c1c; transform: translateY(-2px); }

        footer { background: #0f172a; color: #cbd5e1; padding: 50px 50px 20px; text-align: center; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; margin-bottom: 30px; text-align: left; max-width: 1200px; margin: 0 auto; }
        .footer-col h4 { color: white; font-size: 1.2rem; margin-bottom: 15px; font-weight: 600; }
        .footer-col p, .footer-col a { color: #94a3b8; text-decoration: none; display: block; margin-bottom: 10px; transition: 0.3s; }
        .footer-col a:hover { color: #3b82f6; }
        .copyright { border-top: 1px solid #1e293b; padding-top: 20px; font-size: 0.9rem; margin-top: 20px; }

        @media (max-width: 768px) {
            .hero-content h1 { font-size: 3rem; }
            .nav-links { display: none; }
            .navbar { padding: 15px 20px; }
        }
    </style>
</head>
<body>

    <div class="marquee-wrapper">
        <div class="marquee-content">
            <span class="marquee-item"><i class="fa-solid fa-star" style="color: #d4af37;"></i> The best vehicle rental service in Sri Lanka</span>
            <span class="marquee-item"><i class="fa-solid fa-star" style="color: #d4af37;"></i> The best vehicle rental service in Sri Lanka</span>
            <span class="marquee-item"><i class="fa-solid fa-star" style="color: #d4af37;"></i> The best vehicle rental service in Sri Lanka</span>
            <span class="marquee-item"><i class="fa-solid fa-star" style="color: #d4af37;"></i> The best vehicle rental service in Sri Lanka</span>
        </div>
        <div class="marquee-content" aria-hidden="true">
            <span class="marquee-item"><i class="fa-solid fa-star" style="color: #d4af37;"></i> The best vehicle rental service in Sri Lanka</span>
            <span class="marquee-item"><i class="fa-solid fa-star" style="color: #d4af37;"></i> The best vehicle rental service in Sri Lanka</span>
            <span class="marquee-item"><i class="fa-solid fa-star" style="color: #d4af37;"></i> The best vehicle rental service in Sri Lanka</span>
            <span class="marquee-item"><i class="fa-solid fa-star" style="color: #d4af37;"></i> The best vehicle rental service in Sri Lanka</span>
        </div>
    </div>

    <nav class="navbar">
        <a href="home.php" class="logo">Driver <span>Ease</span></a>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="reviews.php">Reviews & Feedbacks</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
    </nav>

    <div class="swiper heroSwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide hero-slide" style="background-image: url('https://plus.unsplash.com/premium_photo-1661775632324-d4d95c0e0099?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
                <div class="hero-content">
                    <h1>Driver Ease</h1>
                    <h2>The Best Vehicle Rental Service in Sri Lanka</h2>
                    <p>The best vehicle rental services in Sri Lanka offer reliable vehicles, flexible plans, and excellent customer support for a smooth and convenient travel experience.</p>
                </div>
            </div>
            <div class="swiper-slide hero-slide" style="background-image: url('https://images.unsplash.com/photo-1560958089-b8a1929cea89?q=80&w=1920&auto=format&fit=crop');">
                <div class="hero-content">
                    <h1>Luxury Fleet</h1>
                    <h2>Travel in Ultimate Comfort</h2>
                    <p>Explore our wide range of premium sedans and SUVs, ready to make your journey across Sri Lanka unforgettable.</p>
                </div>
            </div>
            <div class="swiper-slide hero-slide" style="background-image: url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=1920&auto=format&fit=crop');">
                <div class="hero-content">
                    <h1>Professional Drivers</h1>
                    <h2>Safe, Reliable, and Experienced</h2>
                    <p>Our dedicated team of professional drivers ensures you reach your destination safely and on time, every time.</p>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <h2 class="section-title">Explore Our Fleet</h2>
    <div class="vehicles-container">
        <?php
        if ($vehicles_result && $vehicles_result->num_rows > 0) {
            while ($vehicle = $vehicles_result->fetch_assoc()) {
                $imgSrc = !empty($vehicle['vehical_image']) ? $vehicle['vehical_image'] : 'https://via.placeholder.com/400x250?text=No+Image';
                $ownerName = !empty($vehicle['owner_name']) ? htmlspecialchars($vehicle['owner_name']) : 'DriveEase Partner';
                
                echo '
                <div class="vehicle-card">
                    <img src="' . $imgSrc . '" alt="Vehicle" class="vehicle-img">
                    <div class="vehicle-info">
                        <h3>' . htmlspecialchars($vehicle['vehical_model']) . '</h3>
                        <div class="specs-grid">
                            <div class="spec-item"><i class="fa-solid fa-car-side"></i> ' . htmlspecialchars($vehicle['vehical_type']) . '</div>
                            <div class="spec-item"><i class="fa-solid fa-gauge-high"></i> ' . htmlspecialchars($vehicle['engine_capacity']) . '</div>
                            <div class="spec-item"><i class="fa-solid fa-hashtag"></i> ' . htmlspecialchars($vehicle['vehical_number']) . '</div>
                            <div class="spec-item"><i class="fa-solid fa-user-tie"></i> ' . $ownerName . '</div>
                        </div>
                        <div class="price-tag">LKR ' . number_format($vehicle['price_per_day'], 2) . ' <span style="font-size:0.9rem; font-weight:400; color:#475569;">/ day</span></div>
                        <button class="btn-book" onclick="handleBooking()"><i class="fa-solid fa-calendar-check"></i> Book Now</button>
                    </div>
                </div>';
            }
        } else {
            echo '<p style="text-align:center; grid-column: 1/-1; font-size:1.2rem; color:#64748b;">No vehicles are currently available. Please check back later!</p>';
        }
        ?>
    </div>

    <section class="deals-section">
        <h2 class="section-title">Latest Deals & Offers</h2>
        <div class="swiper dealsSwiper">
            <div class="swiper-wrapper">
                <?php
                if ($deals_result && $deals_result->num_rows > 0) {
                    while ($deal = $deals_result->fetch_assoc()) {
                        $mode = $deal['deal_mode'];
                        $title = htmlspecialchars($deal['deal_title']);
                        $highlight = htmlspecialchars($deal['highlighted_text']);
                        $details = htmlspecialchars($deal['details']);
                        $imgSrc = $deal['deal_image'];

                        echo '<div class="swiper-slide">';
                        
                        if ($mode === 'Text') {
                            // Renders a sleek gradient background with text
                            echo '<div class="deal-text-wrapper">
                                    <h3 class="deal-title">' . $title . '</h3>
                                    ' . ($highlight ? '<div class="deal-highlight">' . $highlight . '</div>' : '') . '
                                    <p class="deal-details">' . $details . '</p>
                                  </div>';
                        } elseif ($mode === 'Image with Text') {
                            // Renders the image with a dark overlay and text on top
                            echo '<img src="' . $imgSrc . '" alt="Drive Ease Deal">
                                  <div class="deal-overlay">
                                    <h3 class="deal-title">' . $title . '</h3>
                                    ' . ($highlight ? '<div class="deal-highlight">' . $highlight . '</div>' : '') . '
                                    <p class="deal-details">' . $details . '</p>
                                  </div>';
                        } else {
                            // Default 'Image' mode
                            echo '<img src="' . $imgSrc . '" alt="Drive Ease Deal">';
                        }
                        
                        echo '</div>';
                    }
                } else {
                    // Fallback if no deals exist
                    echo '<div class="swiper-slide"><img src="https://images.unsplash.com/photo-1550355291-bbee04a92027?q=80&w=1200&auto=format&fit=crop" alt="Promo"></div>';
                }
                ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next" style="color: #d4af37;"></div>
            <div class="swiper-button-prev" style="color: #d4af37;"></div>
        </div>
    </section>

    <section class="portal-section">
        <h3>Portal Access</h3>
        <div class="portal-buttons">
            <a href="driver_portal.php" class="btn-portal btn-driver"><i class="fa-solid fa-id-badge"></i> Driver Login</a>
            <a href="owner_portal.php" class="btn-portal btn-owner"><i class="fa-solid fa-car"></i> Vehicle Owners Login</a>
            <a href="admin_login.php" class="btn-portal btn-admin"><i class="fa-solid fa-lock"></i> Admin Login</a>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <div class="logo" style="color:white; font-size:1.5rem; margin-bottom:15px;">Driver <span style="color:#3b82f6;">Ease</span></div>
                <p>Providing the most reliable, comfortable, and affordable vehicle rental services across Sri Lanka. Your journey starts here.</p>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <a href="home.php">Home</a>
                <a href="about.php">About Us</a>
                <a href="reviews.php">Reviews & Feedbacks</a>
                <a href="contact.php">Contact Us</a>
            </div>
            <div class="footer-col">
                <h4>Contact Info</h4>
                <p><i class="fa-solid fa-location-dot"></i> 123 Marine Drive, Colombo 03</p>
                <p><i class="fa-solid fa-phone"></i> +94 11 234 5678</p>
                <p><i class="fa-solid fa-envelope"></i> info@driverease.lk</p>
            </div>
        </div>
        <div class="copyright">
            &copy; 2026 Drive Ease. All Rights Reserved.
        </div>
    </footer>

    <div class="chatbot-toggler" onclick="toggleChat()">
    <i class="fa-solid fa-comments"></i>
</div>

<div class="chatbot-window" id="chatbot-window">
    <div class="chat-header">
        <div>
            <h4>DriveEase Assistant</h4>
            <p><i class="fa-solid fa-circle" style="color: #10b981; font-size: 0.6rem;"></i> Online | Replies instantly</p>
        </div>
        <button onclick="toggleChat()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="chat-messages" id="chat-messages">
        <div class="message bot-message">
            Hello there! Welcome to Drive Ease. What would you like to know about?
        </div>
        <div class="chat-options" id="initial-options">
            <button class="chat-option-btn" onclick="sendQuickReply('Price per day', this)">Price per day</button>
            <button class="chat-option-btn" onclick="sendQuickReply('Cars', this)">Cars</button>
            <button class="chat-option-btn" onclick="sendQuickReply('Payments', this)">Payments</button>
            <button class="chat-option-btn" onclick="sendQuickReply('Delivery', this)">Delivery</button>
            <button class="chat-option-btn" onclick="sendQuickReply('Driver', this)">Driver</button>
            <button class="chat-option-btn" onclick="sendQuickReply('Contact', this)">Contact</button>
            <button class="chat-option-btn" onclick="sendQuickReply('Book', this)">Book</button>
        </div>
    </div>
    <div class="chat-input">
        <input type="text" id="chat-input-field" placeholder="Type your question..." onkeypress="handleChatKeyPress(event)">
        <button onclick="sendMessage()"><i class="fa-solid fa-paper-plane"></i></button>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        // Hero Slider Initialization (Removed the fade effect to prevent overlapping)
        var heroSwiper = new Swiper(".heroSwiper", {
            spaceBetween: 0, 
            centeredSlides: true, 
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: ".swiper-pagination", clickable: true }, 
            loop: true
        });

        // Deals Slider Setup
        var dealsSwiper = new Swiper(".dealsSwiper", {
            spaceBetween: 30, 
            centeredSlides: true, 
            autoplay: { delay: 3500, disableOnInteraction: false },
            pagination: { el: ".swiper-pagination", clickable: true },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" }, 
            loop: true
        });

        // Book Now Button Logic
        function handleBooking() {
            Swal.fire({
                title: 'Redirecting to the login panel!.......',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            setTimeout(() => { window.location.href = 'index.php'; }, 1500);
        }


        // --- CHATBOT LOGIC ---
        function toggleChat() {
        document.getElementById('chatbot-window').classList.toggle('active');
    }

    function handleChatKeyPress(event) {
        if (event.key === 'Enter') sendMessage();
    }

    function sendQuickReply(text, btnElement) {
        btnElement.parentElement.style.display = 'none'; 
        document.getElementById('chat-input-field').value = text;
        sendMessage();
    }

    function sendMessage() {
        const inputField = document.getElementById('chat-input-field');
        const messageText = inputField.value.trim();
        if (!messageText) return;

        // Hide initial options if they are still there
        const initialOptions = document.getElementById('initial-options');
        if(initialOptions) initialOptions.style.display = 'none';

        // Show user message
        appendMessage(messageText, 'user-message');
        inputField.value = '';

        // Show typing indicator
        const messagesContainer = document.getElementById('chat-messages');
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'typing-indicator';
        typingIndicator.innerText = 'DriveEase Assistant is typing...';
        messagesContainer.appendChild(typingIndicator);
        typingIndicator.style.display = 'block';
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // Send question to backend API
        let formData = new FormData();
        formData.append('message', messageText);

        fetch('chatbot_logic.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            setTimeout(() => {
                typingIndicator.remove();
                appendMessage(data.reply, 'bot-message', data.options);
            }, 800); 
        }).catch(error => {
            typingIndicator.remove();
            appendMessage("Sorry, I'm having trouble connecting right now.", 'bot-message');
        });
    }

    function appendMessage(text, className, options = []) {
        const messagesContainer = document.getElementById('chat-messages');
        
        // Text Bubble
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${className}`;
        messageDiv.innerText = text;
        messagesContainer.appendChild(messageDiv);

        // Dynamic Options Buttons
        if (options && options.length > 0) {
            const optionsDiv = document.createElement('div');
            optionsDiv.className = 'chat-options';
            options.forEach(opt => {
                const btn = document.createElement('button');
                btn.className = 'chat-option-btn';
                btn.innerText = opt;
                btn.onclick = function() { sendQuickReply(opt, this); };
                optionsDiv.appendChild(btn);
            });
            messagesContainer.appendChild(optionsDiv);
        }

        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }




    </script>
</body>
</html>