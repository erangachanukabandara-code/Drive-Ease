<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Driver Ease</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Import Outfit Font */
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { height: 100vh; display: flex; background-color: #f8fafc; overflow: hidden; }

        /* Left Panel - Admin Theme */
        .left-panel {
            flex: 1;
            /* Using a darker, more authoritative background for the Admin side */
            background: linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.75)), url('https://images.unsplash.com/photo-1506015391300-4802dc74de2e?q=80&w=1259&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            color: white; padding: 40px; text-align: center;
        }

        .left-panel h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 15px; color: #dc2626; letter-spacing: 1px; }
        .left-panel p { font-size: 1.2rem; font-weight: 300; max-width: 450px; line-height: 1.6; color: #cbd5e1; }

        /* Right Panel - Form */
        .right-panel {
            flex: 1; display: flex; justify-content: center; align-items: center;
            padding: 40px; background: #ffffff;
        }

        .form-container { width: 100%; max-width: 450px; }
        .form-container h2 { font-size: 2.2rem; color: #0f172a; margin-bottom: 8px; font-weight: 800; letter-spacing: -0.5px; }
        .form-container p.subtitle { color: #64748b; margin-bottom: 40px; font-size: 1.05rem; }

        .input-group { position: relative; margin-bottom: 25px; }
        .input-group i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.2rem; }
        .input-group input {
            width: 100%; padding: 16px 16px 16px 50px; border: 1px solid #e2e8f0; border-radius: 12px;
            font-size: 1.05rem; color: #0f172a; background: #f8fafc; transition: all 0.3s; font-family: 'Outfit', sans-serif;
        }
        .input-group input:focus {
            border-color: #dc2626; background: #ffffff; box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1); outline: none;
        }

        .btn-primary {
            width: 100%; padding: 16px; background: #0f172a; color: white;
            border: none; border-radius: 12px; font-size: 1.15rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s; margin-top: 10px; display: flex; justify-content: center; align-items: center; gap: 10px;
        }
        .btn-primary:hover { background: #dc2626; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(220, 38, 38, 0.2); }

        .back-link { display: block; text-align: center; margin-top: 30px; color: #64748b; text-decoration: none; font-weight: 500; transition: 0.3s; }
        .back-link:hover { color: #0f172a; }

        @media (max-width: 900px) {
            body { flex-direction: column; overflow-y: auto; }
            .left-panel { flex: none; padding: 80px 20px; }
            .right-panel { align-items: flex-start; padding: 60px 20px; }
        }
    </style>
</head>
<body>

    <div class="left-panel">
        <h1>System Admin</h1>
        <p>Driver Ease Central Management Console. Restricted access for authorized administrators only.</p>
    </div>

    <div class="right-panel">
        <div class="form-container">
            <h2>Admin Authorization</h2>
            <p class="subtitle">Please enter your master credentials to proceed.</p>
            
            <form id="adminLoginForm" onsubmit="handleAdminLogin(event)">
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="text" name="email" placeholder="Admin Email / ID" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Master Password" required>
                </div>
                
                <button type="submit" class="btn-primary">
                    Secure Login <i class="fa-solid fa-shield-halved"></i>
                </button>

                <a href="home.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Return to Public Site</a>
            </form>
        </div>
    </div>

<script>
    function handleAdminLogin(e) {
        e.preventDefault();

        // 1st Popup: Loading
        Swal.fire({
            title: 'Login to admin panel!.....',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        let formData = new FormData(e.target);

        // Send credentials to backend
        setTimeout(() => {
            fetch('admin_auth.php', { 
                method: 'POST', 
                body: formData 
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // 2nd Popup: Success
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Success!', 
                        text: 'Login success! Redirecting to the admin panel!....', 
                        confirmButtonColor: '#dc2626' 
                    }).then(() => {
                        window.location.href = data.redirect; 
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Access Denied', text: data.message, confirmButtonColor: '#0f172a' });
                }
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Server connection failed!', confirmButtonColor: '#0f172a' });
            });
        }, 1500); // Slight delay to make the animation feel secure and processing
    }
</script>
</body>
</html>