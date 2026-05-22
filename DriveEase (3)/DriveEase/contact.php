<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Drive Ease</title>
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
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.7)), url('https://images.unsplash.com/photo-1596524430615-b46475ddff6e?q=80&w=1920&auto=format&fit=crop') center/cover; 
            display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; text-align: center;
        }
        .page-hero h1 { font-size: 4.5rem; font-weight: 800; margin-bottom: 10px; letter-spacing: 1px; }
        .page-hero p { font-size: 1.2rem; font-weight: 300; color: #cbd5e1; max-width: 600px; line-height: 1.6; }

        /* Contact Section Overlapping Card */
        .contact-wrapper { max-width: 1200px; margin: -80px auto 80px; padding: 0 20px; position: relative; z-index: 10; }
        .contact-container { display: flex; flex-wrap: wrap; background: white; border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.1); overflow: hidden; }
        
        /* Left Side: Info */
        .contact-info { flex: 1; background: #0f172a; color: white; padding: 60px 50px; min-width: 350px; position: relative; overflow: hidden; }
        /* Decorative circle in the background */
        .contact-info::before { content: ''; position: absolute; bottom: -50px; right: -50px; width: 200px; height: 200px; background: rgba(59, 130, 246, 0.1); border-radius: 50%; }
        
        .contact-info h3 { font-size: 2.2rem; color: white; margin-bottom: 20px; font-weight: 700; }
        .contact-info > p { color: #94a3b8; font-size: 1.1rem; line-height: 1.7; margin-bottom: 40px; }
        
        .info-item { display: flex; align-items: flex-start; gap: 20px; margin-bottom: 30px; font-size: 1.1rem; color: #cbd5e1; }
        .info-item i { color: #d4af37; font-size: 1.8rem; margin-top: 3px; }
        .info-item h4 { color: white; font-size: 1.2rem; margin-bottom: 5px; font-weight: 600; }
        .info-item p { font-size: 1rem; color: #94a3b8; }

        /* Right Side: Form */
        .contact-form { flex: 1.5; padding: 60px 50px; min-width: 350px; background: #ffffff; }
        .contact-form h3 { font-size: 2.2rem; margin-bottom: 30px; color: #0f172a; font-weight: 700; }
        
        .input-group { margin-bottom: 25px; }
        .input-group label { display: block; font-size: 0.95rem; font-weight: 500; color: #475569; margin-bottom: 8px; }
        .input-group input, .input-group textarea { 
            width: 100%; padding: 16px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 1.05rem; 
            outline: none; background: #f8fafc; transition: 0.3s; font-family: 'Outfit', sans-serif;
        }
        .input-group input:focus, .input-group textarea:focus { border-color: #3b82f6; background: white; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        
        .btn-submit { 
            padding: 16px 40px; background: #3b82f6; color: white; border: none; border-radius: 12px; 
            font-size: 1.15rem; font-weight: 600; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 10px;
        }
        .btn-submit:hover { background: #2563eb; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3); }

        /* Footer */
        footer { background: #0f172a; color: #cbd5e1; padding: 60px 50px 20px; text-align: center; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; text-align: left; max-width: 1200px; margin: 0 auto 40px; }
        .footer-col h4 { color: white; font-size: 1.3rem; margin-bottom: 20px; font-weight: 600; font-family: 'Outfit', sans-serif; }
        .footer-col p, .footer-col a { color: #94a3b8; text-decoration: none; display: block; margin-bottom: 12px; transition: 0.3s; font-size: 1.05rem; }
        .footer-col a:hover { color: #3b82f6; }
        .copyright { border-top: 1px solid #1e293b; padding-top: 25px; font-size: 0.95rem; }

        @media (max-width: 900px) {
            .contact-info, .contact-form { padding: 40px 30px; }
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
            <li><a href="about.php">About Us</a></li>
            <li><a href="reviews.php">Reviews & Feedbacks</a></li>
            <li><a href="contact.php" class="active">Contact Us</a></li>
        </ul>
    </nav>

    <div class="page-hero">
        <h1>Contact Us</h1>
        <p>Whether you need assistance with a booking or want to partner with our fleet, our team is here to help you 24/7.</p>
    </div>

    <div class="contact-wrapper">
        <div class="contact-container">
            
            <div class="contact-info">
                <h3>Contact Information</h3>
                <p>Fill out the form to send us a direct inquiry, or reach out to us using the details below. We guarantee a response within 24 hours.</p>
                
                <div class="info-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <h4>Head Office</h4>
                        <p>123 Marine Drive, Colombo 03<br>Western Province, Sri Lanka</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-phone-volume"></i>
                    <div>
                        <h4>Call Us</h4>
                        <p>+94 11 234 5678<br>+94 77 987 6543</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-envelope-open-text"></i>
                    <div>
                        <h4>Email Us</h4>
                        <p>support@driveease.lk<br>info@driveease.lk</p>
                    </div>
                </div>
            </div>

            <div class="contact-form">
                <h3>Send an Inquiry</h3>
                <form id="contactForm" onsubmit="handleContactSubmit(event)">
                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" placeholder="Enter your full name" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="input-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="input-group">
                            <label>Mobile Number</label>
                            <input type="text" name="mobile" placeholder="e.g., 07XXXXXXXX" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Your Message</label>
                        <textarea name="message" rows="5" placeholder="How can we help you today?" required></textarea>
                    </div>
                    <button type="submit" class="btn-submit">
                        Send Message <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
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
            &copy; 2026 Driver Ease. All Rights Reserved.
        </div>
    </footer>

    <script>
        function handleContactSubmit(e) {
            e.preventDefault();
            
            // First Pop-up
            Swal.fire({ 
                title: 'Inquiry sending!.....', 
                allowOutsideClick: false, 
                didOpen: () => { Swal.showLoading() } 
            });
            
            let formData = new FormData(e.target);
            
            // Simulate the slight network delay so the animation feels premium
            setTimeout(() => {
                fetch('contact_action.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        // Second Pop-up
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Success!', 
                            text: 'Successfully inquired Drive Ease, Thank You!', 
                            confirmButtonColor: '#3b82f6' 
                        });
                        e.target.reset(); // Clear the form
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => Swal.fire('Error', 'Network Error. Please try again.', 'error'));
            }, 1500);
        }
    </script>
</body>
</html>