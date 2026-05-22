<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive Ease | Admin Portal</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background-color: #f8fafc; color: #0f172a; display: flex; height: 100vh; overflow: hidden; }
        
        /* Sidebar */
        .sidebar { width: 260px; background: #0f172a; color: white; display: flex; flex-direction: column; height: 100%; z-index: 10; transition: 0.3s; }
        .sidebar-header { padding: 25px 20px; font-size: 1.8rem; font-weight: 800; color: #ffffff; text-align: center; border-bottom: 1px solid #1e293b; letter-spacing: -0.5px; }
        .sidebar-header span { color: #dc2626; }
        .nav-links { list-style: none; padding: 15px 0; flex-grow: 1; overflow-y: auto; }
        .nav-links li { padding: 14px 25px; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 15px; font-size: 1.05rem; color: #94a3b8; font-weight: 500; }
        .nav-links li i { width: 20px; text-align: center; }
        .nav-links li:hover, .nav-links li.active { background: #1e293b; color: #ffffff; border-left: 4px solid #dc2626; }
        
        /* Main Layout */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
        
        /* Top Header */
        .top-header { background-color: #ffffff; padding: 15px 30px; display: flex; justify-content: flex-end; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.03); z-index: 5; }
        .btn-logout { padding: 10px 20px; background: #ef4444; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; font-size: 1rem; display: flex; align-items: center; gap: 8px; }
        .btn-logout:hover { background: #dc2626; }

        /* Content Area */
        .content-area { padding: 30px; overflow-y: auto; flex-grow: 1; }
        .section-view { display: none; animation: fadeIn 0.4s ease; }
        .section-view.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Dashboard Grid */
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 25px; margin-bottom: 30px; }
        .stat-box { background: white; padding: 30px 25px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; display: flex; align-items: center; gap: 20px; transition: 0.3s; }
        .stat-box:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.08); }
        .stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 1.8rem; color: white; }
        .stat-info h4 { color: #64748b; font-size: 1rem; font-weight: 500; margin-bottom: 5px; }
        .stat-info h2 { color: #0f172a; font-size: 1.8rem; font-weight: 800; }

        /* Cards & Forms */
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; margin-bottom: 30px; }
        .card h2 { font-size: 1.5rem; margin-bottom: 20px; color: #0f172a; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; font-weight: 700; }
        
        .delete-form { display: flex; gap: 15px; max-width: 500px; }
        .delete-form input { flex-grow: 1; padding: 12px 15px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-family: 'Outfit'; font-size: 1rem; }
        .delete-form input:focus { border-color: #dc2626; }
        .btn-danger { padding: 12px 25px; background: #ef4444; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; font-size: 1rem; }
        .btn-danger:hover { background: #dc2626; }

        /* Tables */
        .table-responsive { overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0; }
        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 0.95rem; }
        th { background: #f8fafc; color: #475569; font-weight: 600; white-space: nowrap; }
        tr:hover { background-color: #f1f5f9; }
        
        .tbl-img { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; }
        .btn-sm-danger { background: #fee2e2; color: #ef4444; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; transition: 0.3s; font-size: 0.9rem; }
        .btn-sm-danger:hover { background: #ef4444; color: white; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">Drive<span>Ease</span></div>
        <ul class="nav-links">
            <li class="active" onclick="switchView('dashboard')"><i class="fa-solid fa-chart-pie"></i> Dashboard</li>
            <li onclick="switchView('owners')"><i class="fa-solid fa-car-side"></i> Vehicle Owners</li>
            <li onclick="switchView('drivers')"><i class="fa-solid fa-id-badge"></i> Drivers</li>
            <li onclick="switchView('users')"><i class="fa-solid fa-users"></i> Users</li>
            <li onclick="switchView('bookings')"><i class="fa-solid fa-calendar-check"></i> Bookings</li>
            <li onclick="switchView('vehicles')"><i class="fa-solid fa-car"></i> Vehicles</li>
            <li onclick="switchView('deals')"><i class="fa-solid fa-tags"></i> Deals</li>
            <li onclick="switchView('inquiries')"><i class="fa-solid fa-envelope"></i> Inquiries</li>
            <li onclick="switchView('feedbacks')"><i class="fa-solid fa-star"></i> Feedbacks</li>
            <li onclick="switchView('trackings')"><i class="fa-solid fa-location-crosshairs"></i> Trackings</li>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-header">
            <button class="btn-logout" onclick="handleLogout()"><i class="fa-solid fa-power-off"></i> Logout</button>
        </div>

        <div class="content-area">
            
            <div id="dashboard" class="section-view active">
                <div class="dashboard-grid">
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #10b981;"><i class="fa-solid fa-money-bill-wave"></i></div>
                        <div class="stat-info"><h4>Total Revenue</h4><h2 id="stat-costs">LKR 0</h2></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #3b82f6;"><i class="fa-solid fa-car"></i></div>
                        <div class="stat-info"><h4>Total Vehicles</h4><h2 id="stat-vehicles">0</h2></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #f59e0b;"><i class="fa-solid fa-calendar-check"></i></div>
                        <div class="stat-info"><h4>Total Bookings</h4><h2 id="stat-bookings">0</h2></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #8b5cf6;"><i class="fa-solid fa-users"></i></div>
                        <div class="stat-info"><h4>Total Users</h4><h2 id="stat-users">0</h2></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #f43f5e;"><i class="fa-solid fa-user-tie"></i></div>
                        <div class="stat-info"><h4>Total Owners</h4><h2 id="stat-owners">0</h2></div>
                    </div>
                </div>
            </div>

            <div id="owners" class="section-view">
                <div class="card">
                    <h2>Manage Vehicle Owners</h2>
                    <div class="table-responsive">
                        <table>
                            <thead><tr><th>ID</th><th>Image</th><th>Full Name</th><th>Email</th><th>Mobile</th><th>Address</th><th>NIC</th><th>Gender</th><th>Nationality</th><th>Created At</th><th>Action</th></tr></thead>
                            <tbody id="tb-owners"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="drivers" class="section-view">
                <div class="card">
                    <h2>Manage Drivers</h2>
                    <div class="table-responsive">
                        <table>
                            <thead><tr><th>ID</th><th>Full Name</th><th>Email</th><th>Mobile</th><th>License Number</th><th>Created At</th><th>Action</th></tr></thead>
                            <tbody id="tb-drivers"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="users" class="section-view">
                <div class="card">
                    <h2>Manage Users</h2>
                    <div class="table-responsive">
                        <table>
                            <thead><tr><th>ID</th><th>Image</th><th>Full Name</th><th>Email</th><th>Mobile</th><th>Address</th><th>NIC</th><th>Gender</th><th>Created At</th><th>Action</th></tr></thead>
                            <tbody id="tb-users"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="bookings" class="section-view">
                <div class="card">
                    <h2>Manage Bookings</h2>
                    <div class="table-responsive">
                        <table>
                            <thead><tr><th>ID</th><th>Booking No.</th><th>Customer</th><th>Email</th><th>Mobile</th><th>NIC</th><th>Veh Type</th><th>Veh Model</th><th>Veh Num</th><th>Booking Date</th><th>Taking Date</th><th>Handover Date</th><th>Status</th></tr></thead>
                            <tbody id="tb-bookings"></tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <h2>Delete Booking</h2>
                    <form class="delete-form" onsubmit="handleBookingDelete(event)">
                        <input type="text" id="del_booking_num" required placeholder="Enter Booking Number (e.g., B-XXXXX)">
                        <button type="submit" class="btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
                    </form>
                </div>
            </div>

            <div id="vehicles" class="section-view">
                <div class="card">
                    <h2>Vehicle Availability Records</h2>
                    <div class="table-responsive">
                        <table>
                            <thead><tr><th>ID</th><th>Vehicle Number</th><th>Vehicle Type</th><th>Vehicle Model</th><th>Available Date</th><th>Created At</th></tr></thead>
                            <tbody id="tb-vehicles"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="deals" class="section-view">
                <div class="card">
                    <h2>Manage Deals</h2>
                    <div class="table-responsive">
                        <table>
                            <thead><tr><th>ID</th><th>Image</th><th>Mode</th><th>Title</th><th>Highlighted Text</th><th>Details</th><th>Created At</th><th>Action</th></tr></thead>
                            <tbody id="tb-deals"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="inquiries" class="section-view">
                <div class="card">
                    <h2>Customer Inquiries</h2>
                    <div class="table-responsive">
                        <table>
                            <thead><tr><th>ID</th><th>Full Name</th><th>Email</th><th>Mobile</th><th>Message</th><th>Created At</th><th>Action</th></tr></thead>
                            <tbody id="tb-inquiries"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="feedbacks" class="section-view">
                <div class="card">
                    <h2>Customer Feedbacks</h2>
                    <div class="table-responsive">
                        <table>
                            <thead><tr><th>ID</th><th>Customer Name</th><th>Rating</th><th>Review Text</th><th>Created At</th><th>Action</th></tr></thead>
                            <tbody id="tb-feedbacks"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="trackings" class="section-view">
                <div class="card">
                    <h2>Live Tracking Records</h2>
                    <div class="table-responsive">
                        <table>
                            <thead><tr><th>ID</th><th>Vehicle Number</th><th>Trip Details</th><th>Current Location</th><th>Time and Date</th><th>Driver Name</th><th>Created At</th></tr></thead>
                            <tbody id="tb-trackings"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // --- ROUTING LOGIC ---
        function switchView(viewId) {
            document.querySelectorAll('.section-view').forEach(sec => sec.classList.remove('active'));
            document.querySelectorAll('.nav-links li').forEach(li => li.classList.remove('active'));
            
            document.getElementById(viewId).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        // --- FETCH & POPULATE DATA ---
        function loadAdminData() {
            fetch('admin_actions.php?action=fetchAllData')
            .then(res => res.json())
            .then(resData => {
                if(resData.status === 'success') {
                    const d = resData.data;

                    // Stats
                    document.getElementById('stat-costs').innerText = 'LKR ' + parseFloat(d.stats.costs).toLocaleString('en-US', {minimumFractionDigits: 2});
                    document.getElementById('stat-vehicles').innerText = d.stats.vehicles;
                    document.getElementById('stat-bookings').innerText = d.stats.bookings;
                    document.getElementById('stat-users').innerText = d.stats.users;
                    document.getElementById('stat-owners').innerText = d.stats.owners;

                    // Tables Data
                    populateTable('tb-owners', d.owners, (r) => `<tr><td>${r.id}</td><td><img src="${r.profile_image || 'https://via.placeholder.com/50'}" class="tbl-img"></td><td>${r.full_name}</td><td>${r.email}</td><td>${r.mobile}</td><td>${r.address}</td><td>${r.nic}</td><td>${r.gender}</td><td>${r.nationality || 'N/A'}</td><td>${r.created_at || 'N/A'}</td><td><button class="btn-sm-danger" onclick="triggerDelete('deleteOwner', ${r.id}, 'Are you sure need to delete this Vehicle Owner?', 'Vehicle owner is deleting!....', 'Vehicle Owner deleted successfully!')"><i class="fa-solid fa-trash"></i></button></td></tr>`);
                    
                    populateTable('tb-drivers', d.drivers, (r) => `<tr><td>${r.id}</td><td>${r.full_name}</td><td>${r.email}</td><td>${r.mobile}</td><td>${r.license_number || 'N/A'}</td><td>${r.created_at || 'N/A'}</td><td><button class="btn-sm-danger" onclick="triggerDelete('deleteDriver', ${r.id}, 'Are you sure need to delete this Driver?', 'Driver is deleting!......', 'Driver deleted successfully!')"><i class="fa-solid fa-trash"></i></button></td></tr>`);
                    
                    populateTable('tb-users', d.users, (r) => `<tr><td>${r.id}</td><td><img src="${r.profile_image || 'https://via.placeholder.com/50'}" class="tbl-img"></td><td>${r.full_name}</td><td>${r.email}</td><td>${r.mobile}</td><td>${r.address}</td><td>${r.nic}</td><td>${r.gender}</td><td>${r.created_at || 'N/A'}</td><td><button class="btn-sm-danger" onclick="triggerDelete('deleteUser', ${r.id}, 'Are you sure need to delete this user?', 'User is deleting!.....', 'User deleted successfully!')"><i class="fa-solid fa-trash"></i></button></td></tr>`);
                    
                    populateTable('tb-bookings', d.bookings, (r) => `<tr><td>${r.id}</td><td style="color:#dc2626; font-weight:600;">${r.booking_number}</td><td>${r.customer_name}</td><td>${r.email}</td><td>${r.mobile}</td><td>${r.nic}</td><td>${r.vehical_type}</td><td>${r.vehical_model}</td><td>${r.vehical_number}</td><td>${r.booking_date}</td><td>${r.taking_date || 'N/A'}</td><td>${r.handover_date || 'N/A'}</td><td>${r.status}</td></tr>`);
                    
                    populateTable('tb-vehicles', d.vehicles, (r) => `<tr><td>${r.id}</td><td style="font-weight:600;">${r.vehical_number}</td><td>${r.vehical_type}</td><td>${r.vehical_model}</td><td>${r.available_date}</td><td>${r.created_at || 'N/A'}</td></tr>`);
                    
                    populateTable('tb-deals', d.deals, (r) => `<tr><td>${r.id}</td><td><img src="${r.deal_image || 'https://via.placeholder.com/50'}" class="tbl-img" style="border-radius:4px;"></td><td>${r.deal_mode}</td><td>${r.deal_title}</td><td>${r.highlighted_text}</td><td>${r.details}</td><td>${r.created_at || 'N/A'}</td><td><button class="btn-sm-danger" onclick="triggerDelete('deleteDeal', ${r.id}, 'Are you sure need to delete this deal?', 'Deal is deleting!.....', 'Successfully deal is deleted!')"><i class="fa-solid fa-trash"></i></button></td></tr>`);
                    
                    populateTable('tb-inquiries', d.inquiries, (r) => `<tr><td>${r.id}</td><td>${r.full_name}</td><td>${r.email}</td><td>${r.mobile}</td><td>${r.message}</td><td>${r.created_at || 'N/A'}</td><td><button class="btn-sm-danger" onclick="triggerDelete('deleteInquiry', ${r.id}, 'Are you sure need to delete this Inquiry?', 'Inquiry is deleting!....', 'Inquiry is deleted successfully!')"><i class="fa-solid fa-trash"></i></button></td></tr>`);
                    
                    populateTable('tb-feedbacks', d.feedbacks, (r) => `<tr><td>${r.id}</td><td>${r.customer_name}</td><td>${r.rating} Stars</td><td>${r.review_text}</td><td>${r.created_at || 'N/A'}</td><td><button class="btn-sm-danger" onclick="triggerDelete('deleteFeedback', ${r.id}, 'Are you sure need to delete this feedback?', 'Feedback is deleting!....', 'Feedback is deleted successfully!')"><i class="fa-solid fa-trash"></i></button></td></tr>`);
                    
                    populateTable('tb-trackings', d.trackings, (r) => `<tr><td>${r.id}</td><td style="font-weight:600;">${r.vehical_number}</td><td>${r.trip_details}</td><td>${r.current_location}</td><td>${r.time_and_date}</td><td>${r.driver_name}</td><td>${r.created_at || 'N/A'}</td></tr>`);
                }
            }).catch(err => console.error(err));
        }

        function populateTable(tbodyId, dataArray, rowTemplate) {
            const tbody = document.getElementById(tbodyId);
            if (!dataArray || dataArray.length === 0) {
                tbody.innerHTML = '<tr><td colspan="15" style="text-align:center; color:#94a3b8;">No records found.</td></tr>';
            } else {
                tbody.innerHTML = dataArray.map(rowTemplate).join('');
            }
        }

        // --- GLOBAL DELETE LOGIC ---
        function triggerDelete(action, id, msgWarning, msgLoading, msgSuccess) {
            Swal.fire({
                title: msgWarning, icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Yes', cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: msgLoading, allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                    
                    let fd = new FormData();
                    fd.append('action', action);
                    fd.append('id', id);

                    fetch('admin_actions.php', { method: 'POST', body: fd })
                    .then(res => res.json())
                    .then(data => {
                        if(data.status === 'success') {
                            Swal.fire({ icon: 'success', title: 'Deleted!', text: msgSuccess, confirmButtonColor: '#dc2626' });
                            loadAdminData();
                        } else Swal.fire('Error', data.message, 'error');
                    }).catch(() => Swal.fire('Error', 'Network Error.', 'error'));
                }
            });
        }

        // --- SPECIFIC BOOKING DELETE FORM ---
        function handleBookingDelete(e) {
            e.preventDefault();
            let bNum = document.getElementById('del_booking_num').value;
            
            Swal.fire({ title: 'Booking is deleting!.....', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            
            let fd = new FormData();
            fd.append('action', 'deleteBooking');
            fd.append('booking_number', bNum);

            fetch('admin_actions.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                setTimeout(() => {
                    if(data.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Booking is deleted successfully!', confirmButtonColor: '#dc2626' });
                        e.target.reset();
                        loadAdminData();
                    } else Swal.fire('Error', data.message, 'error');
                }, 1000);
            }).catch(() => Swal.fire('Error', 'Network Error.', 'error'));
        }

        // --- LOGOUT LOGIC ---
        function handleLogout() {
            Swal.fire({
                title: 'Are you sure need to logout?', icon: 'question', showCancelButton: true,
                confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Yes', cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Logging out from the panel!.....', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                    setTimeout(() => window.location.href = 'admin_logout.php', 1500);
                }
            });
        }

        // Initialize Data on Load
        window.onload = loadAdminData;
    </script>
</body>
</html>