<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive Ease | Professional Vehicle Rental</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Import a web-safe version of Google Sans (Product Sans) */
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
        @font-face {
            font-family: 'Google Sans';
            src: url('https://fonts.gstatic.com/s/productsans/v5/HYvgU2fE2nRJvZ5JFAumwegdm0LZdjqr5-oayXSOefg.woff2') format('woff2');
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Google Sans', 'Open Sans', sans-serif; }
        
        body { 
            height: 100vh; 
            display: flex; 
            background-color: #f4f7f6;
        }

        /* Split Layout */
        .left-panel {
            flex: 1;
            background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.9)), url('https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?q=80&w=1920&auto=format&fit=crop') center/cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 40px;
            text-align: center;
        }

        .left-panel h1 { font-size: 3.5rem; font-weight: 700; margin-bottom: 15px; letter-spacing: 1px; }
        .left-panel p { font-size: 1.2rem; font-weight: 300; max-width: 400px; line-height: 1.6; }

        .right-panel {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background: #ffffff;
            overflow-y: auto;
        }

        .form-container {
            width: 100%;
            max-width: 550px;
            transition: all 0.4s ease-in-out;
        }

        .form-container h2 {
            font-size: 2rem;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .form-container p.subtitle {
            color: #64748b;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .input-group input, .input-group select {
            width: 100%;
            padding: 14px 14px 14px 45px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            color: #334155;
            background: #f8fafc;
            transition: all 0.3s;
        }

        .input-group input:focus, .input-group select:focus {
            border-color: #3b82f6;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 15px;
        }

        /* File Upload Styling */
        .file-upload-wrapper {
            margin-bottom: 20px;
        }
        .file-upload-wrapper label {
            display: block; font-size: 0.9rem; color: #64748b; margin-bottom: 8px;
        }
        .file-upload-wrapper input[type="file"] {
            width: 100%; padding: 10px; border: 1px dashed #cbd5e1; border-radius: 10px;
            background: #f8fafc; color: #334155; cursor: pointer;
        }

        .btn-primary {
            width: 100%; padding: 15px; background: #0f172a; color: white;
            border: none; border-radius: 10px; font-size: 1.1rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s; margin-top: 10px;
        }
        .btn-primary:hover { background: #1e293b; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(15, 23, 42, 0.15); }

        .toggle-text { text-align: center; margin-top: 25px; color: #64748b; font-size: 0.95rem; }
        .toggle-text span { color: #3b82f6; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .toggle-text span:hover { color: #2563eb; text-decoration: underline; }

        #register-form { display: none; }

        /* Responsive */
        @media (max-width: 900px) {
            body { flex-direction: column; }
            .left-panel { flex: none; padding: 60px 20px; }
            .right-panel { align-items: flex-start; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="left-panel">
        <h1>Drive Ease</h1>
        <p>Experience the ultimate vehicle rental management system. Premium fleet, seamless booking, and professional service.</p>
    </div>

    <div class="right-panel">
        <div class="form-container" id="login-form">
            <h2>Welcome Back</h2>
            <p class="subtitle">Please enter your details to access your dashboard.</p>
            
            <form id="loginForm" onsubmit="handleLogin(event)">
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="log_email" placeholder="Email Address" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="log_pass" placeholder="Password" required>
                </div>
                
                <button type="submit" class="btn-primary">Secure Login</button>
                <div class="toggle-text">Don't have an account? <span onclick="toggleForms()">Create Account</span></div>
            </form>
        </div>

        <div class="form-container" id="register-form">
            <h2>Create an Account</h2>
            <p class="subtitle">Join Drive Ease to manage your rentals efficiently.</p>
            
            <form id="registerForm" onsubmit="handleRegister(event)" enctype="multipart/form-data">
                
                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="full_name" placeholder="Full Name" required>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="input-group">
                        <i class="fa-solid fa-phone"></i>
                        <input type="text" name="mobile" placeholder="Mobile Number (e.g., 07XXXXXXXX)" required>
                    </div>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-location-dot"></i>
                    <input type="text" name="address" placeholder="Home Address" required>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <i class="fa-solid fa-id-card"></i>
                        <input type="text" name="nic" placeholder="NIC Number" required>
                    </div>
                    <div class="input-group">
                        <i class="fa-solid fa-venus-mars"></i>
                        <select name="gender" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="reg_pass" name="password" placeholder="Password" required>
                    </div>
                    <div class="input-group">
                        <i class="fa-solid fa-shield"></i>
                        <input type="password" id="reg_cpass" placeholder="Confirm Password" required>
                    </div>
                </div>

                <div class="file-upload-wrapper">
                    <label><i class="fa-solid fa-image"></i> Upload Profile Image</label>
                    <input type="file" name="profile_image" accept="image/*" required>
                </div>

                <button type="submit" class="btn-primary">Register Account</button>
                <div class="toggle-text">Already have an account? <span onclick="toggleForms()">Sign In</span></div>
            </form>
        </div>
    </div>

<script>
    function toggleForms() {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        
        // Simple fade effect
        if (loginForm.style.display === 'none') {
            registerForm.style.opacity = 0;
            setTimeout(() => {
                registerForm.style.display = 'none';
                loginForm.style.display = 'block';
                setTimeout(() => loginForm.style.opacity = 1, 50);
            }, 300);
        } else {
            loginForm.style.opacity = 0;
            setTimeout(() => {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                setTimeout(() => registerForm.style.opacity = 1, 50);
            }, 300);
        }
    }

    function handleRegister(e) {
        e.preventDefault();
        
        let pass = document.getElementById('reg_pass').value;
        let cpass = document.getElementById('reg_cpass').value;
        if(pass !== cpass) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Passwords do not match!', confirmButtonColor: '#0f172a' });
            return;
        }

        Swal.fire({
            title: 'User creating!.....',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        let formData = new FormData(document.getElementById('registerForm'));

        setTimeout(() => {
            fetch('register.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Success!', text: 'User created successfully!', confirmButtonColor: '#0f172a' }).then(() => {
                        document.getElementById('registerForm').reset();
                        toggleForms();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#0f172a' });
                }
            })
            .catch(error => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong!', confirmButtonColor: '#0f172a' });
            });
        }, 1500);
    }

    function handleLogin(e) {
        e.preventDefault();
        
        let email = document.getElementById('log_email').value;
        let pass = document.getElementById('log_pass').value;

        let formData = new FormData();
        formData.append('email', email);
        formData.append('password', pass);

        Swal.fire({
            title: 'Authenticating!......',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        setTimeout(() => {
            fetch('login.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Success!', text: 'Login success! Redirecting to the dashboard!.', confirmButtonColor: '#0f172a' }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#0f172a' });
                }
            })
            .catch(error => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong!', confirmButtonColor: '#0f172a' });
            });
        }, 1500);
    }
</script>
</body>
</html>