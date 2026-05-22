<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive Ease | Vehicle Owner Portal</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
        @font-face {
            font-family: 'Google Sans';
            src: url('https://fonts.gstatic.com/s/productsans/v5/HYvgU2fE2nRJvZ5JFAumwegdm0LZdjqr5-oayXSOefg.woff2') format('woff2');
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Google Sans', 'Open Sans', sans-serif; }
        
        body { height: 100vh; display: flex; background-color: #f8f9fa; }

        /* Owner Portal specific styling */
        .left-panel {
            flex: 1;
            /* Using a different premium background for the owner side */
            background: linear-gradient(rgba(17, 24, 39, 0.8), rgba(17, 24, 39, 0.95)), url('https://images.unsplash.com/photo-1560958089-b8a1929cea89?q=80&w=1920&auto=format&fit=crop') center/cover;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            color: white; padding: 40px; text-align: center;
        }

        .left-panel h1 { font-size: 3.5rem; font-weight: 700; margin-bottom: 15px; color: #d4af37; /* Gold accent */ }
        .left-panel p { font-size: 1.2rem; font-weight: 300; max-width: 450px; line-height: 1.6; }

        .right-panel {
            flex: 1.2; display: flex; justify-content: center; align-items: center;
            padding: 40px; background: #ffffff; overflow-y: auto;
        }

        .form-container { width: 100%; max-width: 600px; transition: all 0.4s ease-in-out; }
        .form-container h2 { font-size: 2rem; color: #111827; margin-bottom: 8px; }
        .form-container p.subtitle { color: #6b7280; margin-bottom: 30px; font-size: 0.95rem; }

        .input-group { position: relative; margin-bottom: 20px; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 1.1rem; }
        .input-group input, .input-group select {
            width: 100%; padding: 14px 14px 14px 45px; border: 1px solid #d1d5db; border-radius: 10px;
            font-size: 1rem; color: #1f2937; background: #f9fafb; transition: all 0.3s;
        }
        .input-group input:focus, .input-group select:focus {
            border-color: #d4af37; background: #ffffff; box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1); outline: none;
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 15px; }

        .file-upload-wrapper { margin-bottom: 20px; }
        .file-upload-wrapper label { display: block; font-size: 0.9rem; color: #6b7280; margin-bottom: 8px; }
        .file-upload-wrapper input[type="file"] {
            width: 100%; padding: 10px; border: 1px dashed #9ca3af; border-radius: 10px;
            background: #f9fafb; color: #1f2937; cursor: pointer;
        }

        .btn-primary {
            width: 100%; padding: 15px; background: #111827; color: #d4af37;
            border: none; border-radius: 10px; font-size: 1.1rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s; margin-top: 10px; text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-primary:hover { background: #000000; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(17, 24, 39, 0.2); }

        .toggle-text { text-align: center; margin-top: 25px; color: #6b7280; font-size: 0.95rem; }
        .toggle-text span { color: #d4af37; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .toggle-text span:hover { color: #b5952f; text-decoration: underline; }

        #register-form { display: none; }

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
        <h1>Drive Ease Partners</h1>
        <p>Turn your fleet into a revenue stream. Join our exclusive network of vehicle owners and manage your rentals with ease.</p>
    </div>

    <div class="right-panel">
        <div class="form-container" id="login-form">
            <h2>Owner Portal Access</h2>
            <p class="subtitle">Securely login to manage your vehicles and earnings.</p>
            
            <form id="loginForm" onsubmit="handleLogin(event)">
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="log_email" placeholder="Owner Email Address" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="log_pass" placeholder="Password" required>
                </div>
                
                <button type="submit" class="btn-primary">Access Dashboard</button>
                <div class="toggle-text">Want to list your vehicle? <span onclick="toggleForms()">Register as Owner</span></div>
            </form>
        </div>

        <div class="form-container" id="register-form">
            <h2>Partner Registration</h2>
            <p class="subtitle">Provide your details to start listing your vehicles on Drive Ease.</p>
            
            <form id="registerForm" onsubmit="handleRegister(event)" enctype="multipart/form-data">
                
                <div class="input-group">
                    <i class="fa-solid fa-user-tie"></i>
                    <input type="text" name="full_name" placeholder="Owner Full Name" required>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" placeholder="Owner Email" required>
                    </div>
                    <div class="input-group">
                        <i class="fa-solid fa-phone"></i>
                        <input type="text" name="mobile" placeholder="Owner Mobile Number" required>
                    </div>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-building"></i>
                    <input type="text" name="address" placeholder="Owner Address" required>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <i class="fa-solid fa-id-card"></i>
                        <input type="text" name="nic" placeholder="Owner NIC Number" required>
                    </div>
                    <div class="input-group">
                        <i class="fa-solid fa-earth-americas"></i>
                        <input type="text" name="nationality" placeholder="Nationality" required>
                    </div>
                </div>
                
                <div class="input-group">
                    <i class="fa-solid fa-venus-mars"></i>
                    <select name="gender" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
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
                    <label><i class="fa-solid fa-image"></i> Upload Owner Profile Image</label>
                    <input type="file" name="profile_image" accept="image/*" required>
                </div>

                <button type="submit" class="btn-primary">Register Account</button>
                <div class="toggle-text">Already a registered partner? <span onclick="toggleForms()">Login Here</span></div>
            </form>
        </div>
    </div>

<script>
    function toggleForms() {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        
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
            Swal.fire({ icon: 'error', title: 'Error', text: 'Passwords do not match!', confirmButtonColor: '#111827' });
            return;
        }

        Swal.fire({
            title: 'Owner account creating!......',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        let formData = new FormData(document.getElementById('registerForm'));

        setTimeout(() => {
            fetch('owner_register.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Success!', text: 'Owner account created successfully!', confirmButtonColor: '#111827' }).then(() => {
                        document.getElementById('registerForm').reset();
                        toggleForms();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#111827' });
                }
            })
            .catch(error => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong!', confirmButtonColor: '#111827' });
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
            fetch('owner_login.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Success!', text: 'Login success! Redirecting to the Dashboard!', confirmButtonColor: '#111827' }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#111827' });
                }
            })
            .catch(error => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong!', confirmButtonColor: '#111827' });
            });
        }, 1500);
    }
</script>
</body>
</html>