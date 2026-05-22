<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive Ease | Driver Portal</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
        @font-face { font-family: 'Google Sans'; src: url('https://fonts.gstatic.com/s/productsans/v5/HYvgU2fE2nRJvZ5JFAumwegdm0LZdjqr5-oayXSOefg.woff2') format('woff2'); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Google Sans', 'Open Sans', sans-serif; }
        body { height: 100vh; display: flex; background-color: #f8fafc; }
        
        .left-panel { flex: 1; background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.95)), url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=1920&auto=format&fit=crop') center/cover; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; padding: 40px; text-align: center; }
        .left-panel h1 { font-size: 3.5rem; font-weight: 700; margin-bottom: 15px; color: #3b82f6; }
        .left-panel p { font-size: 1.2rem; font-weight: 300; max-width: 450px; line-height: 1.6; color: #cbd5e1; }
        
        .right-panel { flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px; background: #ffffff; overflow-y: auto; }
        .form-container { width: 100%; max-width: 500px; transition: all 0.4s ease-in-out; }
        .form-container h2 { font-size: 2rem; color: #0f172a; margin-bottom: 8px; font-weight: 700; }
        .form-container p.subtitle { color: #64748b; margin-bottom: 30px; font-size: 0.95rem; }
        
        .input-group { position: relative; margin-bottom: 20px; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem; }
        .input-group input { width: 100%; padding: 14px 14px 14px 45px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 1rem; color: #334155; background: #f8fafc; transition: all 0.3s; }
        .input-group input:focus { border-color: #3b82f6; background: #ffffff; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); outline: none; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 15px; }
        
        .btn-primary { width: 100%; padding: 15px; background: #3b82f6; color: white; border: none; border-radius: 10px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; margin-top: 10px; }
        .btn-primary:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2); }
        .toggle-text { text-align: center; margin-top: 25px; color: #64748b; font-size: 0.95rem; }
        .toggle-text span { color: #3b82f6; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .toggle-text span:hover { color: #1d4ed8; text-decoration: underline; }
        #register-form { display: none; }
        
        @media (max-width: 900px) { body { flex-direction: column; } .left-panel { flex: none; padding: 60px 20px; } .right-panel { align-items: flex-start; padding: 40px 20px; } .form-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <div class="left-panel">
        <h1>Drive Ease Fleet</h1>
        <p>Join our professional driving team. Login to update your real-time tracking, manage trips, and stay connected on the road.</p>
    </div>

    <div class="right-panel">
        <div class="form-container" id="login-form">
            <h2>Driver Login</h2>
            <p class="subtitle">Securely access your tracking dashboard.</p>
            <form id="loginForm" onsubmit="handleLogin(event)">
                <div class="input-group"><i class="fa-solid fa-envelope"></i><input type="email" name="email" placeholder="Driver Email Address" required></div>
                <div class="input-group"><i class="fa-solid fa-lock"></i><input type="password" name="password" placeholder="Password" required></div>
                <button type="submit" class="btn-primary">Access Dashboard</button>
                <div class="toggle-text">New to the fleet? <span onclick="toggleForms()">Register Here</span></div>
            </form>
        </div>

        <div class="form-container" id="register-form">
            <h2>Driver Registration</h2>
            <p class="subtitle">Register to get your official Drive Ease Driver ID.</p>
            <form id="registerForm" onsubmit="handleRegister(event)">
                <div class="input-group"><i class="fa-solid fa-id-badge"></i><input type="text" name="full_name" placeholder="Full Name" required></div>
                <div class="form-grid">
                    <div class="input-group"><i class="fa-solid fa-envelope"></i><input type="email" name="email" placeholder="Email Address" required></div>
                    <div class="input-group"><i class="fa-solid fa-phone"></i><input type="text" name="mobile" placeholder="Mobile Number" required></div>
                </div>
                <div class="input-group"><i class="fa-solid fa-id-card"></i><input type="text" name="license_number" placeholder="Driving License Number" required></div>
                <div class="form-grid">
                    <div class="input-group"><i class="fa-solid fa-lock"></i><input type="password" id="reg_pass" name="password" placeholder="Password" required></div>
                    <div class="input-group"><i class="fa-solid fa-shield"></i><input type="password" id="reg_cpass" placeholder="Confirm Password" required></div>
                </div>
                <button type="submit" class="btn-primary">Register Account</button>
                <div class="toggle-text">Already a registered driver? <span onclick="toggleForms()">Login Here</span></div>
            </form>
        </div>
    </div>

<script>
    function toggleForms() {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        if (loginForm.style.display === 'none') {
            registerForm.style.display = 'none';
            loginForm.style.display = 'block';
        } else {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
        }
    }

    function handleRegister(e) {
        e.preventDefault();
        let pass = document.getElementById('reg_pass').value;
        let cpass = document.getElementById('reg_cpass').value;
        if(pass !== cpass) { Swal.fire({ icon: 'error', title: 'Error', text: 'Passwords do not match!', confirmButtonColor: '#3b82f6' }); return; }

        Swal.fire({ title: 'Driver account creating!......', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

        let formData = new FormData(e.target);
        setTimeout(() => {
            fetch('driver_register.php', { method: 'POST', body: formData })
            .then(res => res.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if(data.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Success!', text: 'Driver account created successfully!', confirmButtonColor: '#3b82f6' }).then(() => {
                            e.target.reset(); toggleForms();
                        });
                    } else Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#3b82f6' });
                } catch(error) {
                    console.error("Server crashed. PHP Error Output:", text);
                    Swal.fire({ icon: 'error', title: 'Server Error', text: 'Check the browser console (F12) for the exact PHP error.', confirmButtonColor: '#3b82f6' });
                }
            }).catch(error => Swal.fire('Error', 'Network connection failed!', 'error'));
        }, 1000);
    }

    function handleLogin(e) {
        e.preventDefault();
        Swal.fire({ title: 'Authenticating!......', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

        let formData = new FormData(e.target);
        setTimeout(() => {
            fetch('driver_login.php', { method: 'POST', body: formData })
            .then(res => res.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if(data.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Success!', text: 'Login success! Redirecting to the Dashboard!', confirmButtonColor: '#3b82f6' }).then(() => {
                            window.location.href = data.redirect;
                        });
                    } else Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#3b82f6' });
                } catch (error) {
                    console.error("Server crashed. PHP Error Output:", text);
                    Swal.fire({ icon: 'error', title: 'Server Error', text: 'Check the browser console (F12) for the exact PHP error.', confirmButtonColor: '#3b82f6' });
                }
            }).catch(error => Swal.fire('Error', 'Network connection failed!', 'error'));
        }, 1000);
    }
</script>
</body>
</html>