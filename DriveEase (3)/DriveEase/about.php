<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Drive Ease</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Import Outfit Font */
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background-color: #f8fafc; color: #0f172a; overflow-x: hidden; }
        
        /* Navbar */
        .navbar { background-color: rgba(255, 255, 255, 0.98); padding: 20px 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 20px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 99; backdrop-filter: blur(10px); }
        .logo { font-size: 2rem; font-weight: 800; color: #0f172a; text-decoration: none; letter-spacing: -0.5px; } 
        .logo span { color: #3b82f6; }
        .nav-links { list-style: none; display: flex; gap: 35px; } 
        .nav-links li a { text-decoration: none; color: #475569; font-weight: 500; font-size: 1.05rem; transition: 0.3s; }
        .nav-links li a:hover, .nav-links li a.active { color: #3b82f6; font-weight: 600; }

        /* Page Hero */
        .page-hero { 
            height: 55vh; 
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.7)), url('https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover; 
            display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; text-align: center;
        }
        .page-hero h1 { font-size: 4.5rem; font-weight: 800; margin-bottom: 10px; letter-spacing: 1px; }
        .page-hero p { font-size: 1.3rem; font-weight: 300; color: #d4af37; letter-spacing: 2px; text-transform: uppercase; }

        /* About Content Layout */
        .about-section { max-width: 1200px; margin: 80px auto; padding: 0 20px; }
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; margin-bottom: 80px; }
        .about-text h2 { font-size: 3rem; font-weight: 700; color: #0f172a; margin-bottom: 25px; line-height: 1.2; }
        .about-text p { font-size: 1.15rem; line-height: 1.8; color: #475569; margin-bottom: 20px; }
        .about-image { position: relative; }
        .about-image img { width: 100%; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .about-image::after { content: ''; position: absolute; bottom: -20px; right: -20px; width: 100%; height: 100%; border: 3px solid #3b82f6; border-radius: 20px; z-index: -1; }

        /* Features Cards */
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px; margin-top: 40px; }
        .feature-card { background: white; padding: 50px 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); transition: 0.4s; border: 1px solid #f1f5f9; text-align: center; position: relative; overflow: hidden; }
        .feature-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: #3b82f6; transform: scaleX(0); transition: 0.4s; transform-origin: left; }
        .feature-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .feature-card:hover::before { transform: scaleX(1); }
        .feature-card i { font-size: 3.5rem; color: #d4af37; margin-bottom: 25px; }
        .feature-card h3 { font-size: 1.8rem; font-weight: 700; color: #0f172a; margin-bottom: 15px; }
        .feature-card p { color: #64748b; line-height: 1.7; font-size: 1.05rem; }

        /* Footer */
        footer { background: #0f172a; color: #cbd5e1; padding: 60px 50px 20px; margin-top: 80px; text-align: center; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; text-align: left; max-width: 1200px; margin: 0 auto 40px; }
        .footer-col h4 { color: white; font-size: 1.3rem; margin-bottom: 20px; font-weight: 600; font-family: 'Outfit', sans-serif; }
        .footer-col p, .footer-col a { color: #94a3b8; text-decoration: none; display: block; margin-bottom: 12px; transition: 0.3s; font-size: 1.05rem; }
        .footer-col a:hover { color: #3b82f6; }
        .copyright { border-top: 1px solid #1e293b; padding-top: 25px; font-size: 0.95rem; }

        @media (max-width: 900px) {
            .about-grid { grid-template-columns: 1fr; gap: 40px; }
            .page-hero h1 { font-size: 3.5rem; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="home.php" class="logo">Driver<span> Ease</span></a>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="about.php" class="active">About Us</a></li>
            <li><a href="reviews.php">Reviews & Feedbacks</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
    </nav>

    <div class="page-hero">
        <h1>About Us</h1>
        <p>Your Trusted Travel Partner in Sri Lanka</p>
    </div>

    <div class="about-section">
        
        <div class="about-grid">
            <div class="about-text">
                <h2>Redefining Mobility<br>Across <span style="color: #3b82f6;">Sri Lanka</span></h2>
                <p>Drive Ease was founded on a simple principle: to provide the most reliable, comfortable, and professional vehicle rental experience in Sri Lanka. From the bustling streets of Colombo to the serene coastlines, we ensure your journey is flawless.</p>
                <p>Whether you are a tourist exploring the island's beauty or a corporate professional needing seamless transport, our dedicated fleet and rigorously vetted drivers are here to serve you 24/7.</p>
            </div>
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1560958089-b8a1929cea89?q=80&w=1200&auto=format&fit=crop" alt="Luxury Car Rental">
            </div>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <i class="fa-solid fa-shield-halved"></i>
                <h3>Our Mission</h3>
                <p>To deliver safe, high-quality, and transparent transportation solutions that exceed our clients' expectations at every turn, ensuring a stress-free travel experience.</p>
            </div>
            <div class="feature-card">
                <i class="fa-solid fa-eye"></i>
                <h3>Our Vision</h3>
                <p>To become the undisputed leader in fleet management and vehicle rentals across Sri Lanka by innovating and prioritizing world-class customer care.</p>
            </div>
            <div class="feature-card">
                <i class="fa-solid fa-award"></i>
                <h3>Why Choose Us?</h3>
                <p>We combine an elite, well-maintained fleet of vehicles with flexible rental plans and a deeply vetted network of professional drivers dedicated to your safety.</p>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <div class="logo" style="color:white; font-size:1.8rem; margin-bottom:15px; font-family:'Outfit', sans-serif;">Driver<span style="color:#3b82f6;"> Ease</span></div>
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
                <p><i class="fa-solid fa-location-dot" style="color:#d4af37; margin-right:8px;"></i> 123 Marine Drive, Colombo 03</p>
                <p><i class="fa-solid fa-phone" style="color:#d4af37; margin-right:8px;"></i> +94 11 234 5678</p>
                <p><i class="fa-solid fa-envelope" style="color:#d4af37; margin-right:8px;"></i> support@driveease.lk</p>
            </div>
        </div>
        <div class="copyright">
            &copy; 2026 Drive Ease. All Rights Reserved.
        </div>
    </footer>

</body>
</html>