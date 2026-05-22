<?php
session_start();
if (!isset($_SESSION['owner_id']) || $_SESSION['user_role'] !== 'owner') { header("Location: owner_portal.php"); exit; }
$owner_name = $_SESSION['owner_name'] ?? 'Owner';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive Ease | Owner Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
        @font-face { font-family: 'Google Sans'; src: url('https://fonts.gstatic.com/s/productsans/v5/HYvgU2fE2nRJvZ5JFAumwegdm0LZdjqr5-oayXSOefg.woff2') format('woff2'); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Google Sans', 'Open Sans', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f7f6; color: #111827; overflow: hidden; }
        .sidebar { width: 260px; background: #111827; color: white; display: flex; flex-direction: column; height: 100%; z-index: 10; }
        .sidebar-header { padding: 30px 20px; font-size: 1.5rem; font-weight: 700; color: #d4af37; text-align: center; border-bottom: 1px solid #1f2937; }
        .nav-links { list-style: none; padding: 20px 0; flex-grow: 1; overflow-y: auto; }
        .nav-links li { padding: 16px 25px; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 15px; font-size: 1rem; color: #9ca3af; }
        .nav-links li:hover, .nav-links li.active { background: #1f2937; color: #d4af37; border-left: 4px solid #d4af37; }
        .main-content { flex-grow: 1; padding: 30px; overflow-y: auto; height: 100vh; }
        .section-container { display: none; animation: fadeIn 0.4s ease; }
        .section-container.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 30px; border: 1px solid #e5e7eb; }
        .card h2 { margin-bottom: 25px; color: #111827; border-bottom: 2px solid #f4f7f6; padding-bottom: 10px; font-size: 1.5rem; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
        input, select, textarea { width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 8px; outline: none; background: #f9fafb; transition: 0.3s; }
        input:focus, select:focus, textarea:focus { border-color: #d4af37; background: white; }
        .btn-group { display: flex; gap: 15px; margin-top: 10px; flex-wrap: wrap; }
        .btn { padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; color: white; transition: 0.3s; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.95rem; }
        .btn-primary { background: #111827; color: #d4af37; } .btn-primary:hover { background: #000; }
        .btn-update { background: #d4af37; color: #111827; } .btn-update:hover { background: #b5952f; }
        .btn-danger { background: #dc2626; color: white; } .btn-danger:hover { background: #b91c1c; }
        .table-responsive { overflow-x: auto; border-radius: 8px; border: 1px solid #e5e7eb; }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 0.95rem; }
        th { background: #f9fafb; color: #4b5563; font-weight: 600; white-space: nowrap; }
        .vehicle-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
        .vehicle-box { background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; transition: 0.3s; }
        .vehicle-box:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .vehicle-box img { width: 100%; height: 180px; object-fit: cover; border-bottom: 3px solid #d4af37; }
        .vehicle-box-info { padding: 20px; }
        .profile-img-lg { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #d4af37; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .hidden { display: none !important; }
        .tbl-img { width: 60px; height: 40px; object-fit: cover; border-radius: 4px; }
        .status-dropdown { padding: 6px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">Drive Ease</div>
        <ul class="nav-links">
            <li class="active" onclick="switchTab('dashboard')"><i class="fa-solid fa-house"></i> Dashboard</li>
            <li onclick="switchTab('vehicles')"><i class="fa-solid fa-car"></i> Vehicles</li>
            <li onclick="switchTab('bookings')"><i class="fa-solid fa-calendar-check"></i> Bookings</li>
            <li onclick="switchTab('track')"><i class="fa-solid fa-location-crosshairs"></i> Track</li>
            <li onclick="switchTab('availability')"><i class="fa-solid fa-clock"></i> Availability</li>
            <li onclick="switchTab('deals')"><i class="fa-solid fa-tags"></i> Deals</li>
            <li onclick="switchTab('profile')"><i class="fa-solid fa-user"></i> Profile</li>
            <li onclick="handleLogout()"><i class="fa-solid fa-right-from-bracket"></i> Logout</li>
        </ul>
    </div>

    <div class="main-content">
        <div id="dashboard" class="section-container active">
            <div class="card">
                <h2>Welcome, <?php echo $owner_name; ?> - Your Fleet</h2>
                <div class="vehicle-grid" id="dashboard-grid"></div>
            </div>
        </div>

        <div id="vehicles" class="section-container">
            <div class="card">
                <h2>Manage Vehicles</h2>
                <form id="vehicleForm" onsubmit="handleVehicleAction(event)" enctype="multipart/form-data">
                    <div class="form-grid">
                        <input type="text" name="vehical_id" placeholder="Vehicle ID (Leave blank to add)">
                        <input type="text" name="vehical_number" required placeholder="Vehicle Number">
                        <input type="text" name="vehical_type" required placeholder="Vehicle Type">
                        <input type="text" name="vehical_model" required placeholder="Vehicle Model">
                        <input type="text" name="engine_capacity" required placeholder="Engine Capacity">
                        <input type="number" name="price_per_day" required placeholder="Price Per Day (LKR)">
                    </div>
                    <textarea name="additional_details" rows="3" placeholder="Additional Details" style="margin-bottom: 15px;"></textarea>
                    <label style="font-size: 0.9rem; color: #6b7280; display: block; margin-bottom: 5px;">Upload Vehicle Image</label>
                    <input type="file" name="vehical_image" accept="image/*" style="margin-bottom: 15px;">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" onclick="setSubmitAction('addVehicle')"><i class="fa-solid fa-plus"></i> Add Vehicle</button>
                        <button type="submit" class="btn btn-update" onclick="setSubmitAction('updateVehicle')"><i class="fa-solid fa-pen"></i> Update Vehicle</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <h2>Delete Vehicle</h2>
                <form onsubmit="handleDelete(event, 'deleteVehicle', 'del_v_id', 'vehical_id', 'Vehical deleting!......', 'Vehical deleted suceessfully!')">
                    <div style="display: flex; gap: 15px; max-width: 500px;">
                        <input type="text" id="del_v_id" required placeholder="Enter Vehicle ID">
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead><tr><th>ID</th><th>Number</th><th>Type</th><th>Model</th><th>Price/Day</th><th>Details</th><th>Image</th></tr></thead>
                        <tbody id="vehicle-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="bookings" class="section-container">
            <div class="card">
                <h2>All Bookings</h2>
                <div class="table-responsive">
                    <table>
                        <thead><tr><th>ID</th><th>Booking No.</th><th>Customer</th><th>Mobile</th><th>Veh No.</th><th>Date</th><th>Status</th></tr></thead>
                        <tbody id="all-bookings-body"></tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <h2>Delete Booking</h2>
                <form onsubmit="handleDelete(event, 'deleteBooking', 'del_booking_id', 'booking_id', 'Booking details are deleting!.....', 'Booking details deleted successfully!')">
                    <div style="display: flex; gap: 15px; max-width: 500px;">
                        <input type="text" id="del_booking_id" required placeholder="Enter Booking ID">
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="track" class="section-container">
            <div class="card">
                <h2>Track Vehicle</h2>
                <form onsubmit="handleTracking(event)">
                    <div style="display: flex; gap: 15px; max-width: 500px;">
                        <input type="text" id="track_v_num" required placeholder="Enter Vehicle Number">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-location-crosshairs"></i> Track Now</button>
                    </div>
                </form>
            </div>
            <div class="card" id="tracking-results" style="display: none;">
                <h3>Tracking Updates</h3>
                <div id="tracking-data-container" style="padding: 15px; background: #f9fafb; border-radius: 8px;"></div>
            </div>
        </div>

        <div id="availability" class="section-container">
            <div class="card">
                <h2>Add Availability</h2>
                <form id="availForm" onsubmit="handleFormSubmit(event, 'addAvailability', 'Adding availability!......', 'Availability added successfully!')">
                    <div class="form-grid">
                        <input type="text" name="vehical_number" required placeholder="Vehicle Number">
                        <input type="text" name="vehical_type" required placeholder="Vehicle Type">
                        <input type="text" name="vehical_model" required placeholder="Vehicle Model">
                        <input type="date" name="available_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-calendar-plus"></i> Add Availability</button>
                </form>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead><tr><th>ID</th><th>Veh Number</th><th>Type</th><th>Model</th><th>Date</th><th>Action</th></tr></thead>
                        <tbody id="availability-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="deals" class="section-container">
            <div class="card">
                <h2>Add Deal</h2>
                <form id="dealForm" onsubmit="handleFormSubmit(event, 'addDeal', 'Deal is uploading!.....', 'Deal uploaded successfully!')" enctype="multipart/form-data">
                    <div class="form-grid" style="margin-bottom: 15px;">
                        <select name="deal_mode" id="deal_mode" required onchange="toggleDealFields()">
                            <option value="" disabled selected>Select Deal Mode</option>
                            <option value="Text">Text</option>
                            <option value="Image">Image</option>
                            <option value="Image with Text">Image with Text</option>
                        </select>
                    </div>
                    <div id="deal_dynamic_fields">
                        <input type="text" name="deal_title" id="deal_title" class="deal-text-input hidden" placeholder="Deal Title" style="margin-bottom: 15px;">
                        <input type="text" name="highlighted_text" id="deal_highlight" class="deal-text-input hidden" placeholder="Highlighted Text" style="margin-bottom: 15px;">
                        <textarea name="details" id="deal_details" class="deal-text-input hidden" rows="3" placeholder="Details" style="margin-bottom: 15px;"></textarea>
                        <div id="deal_image_input" class="hidden" style="margin-bottom: 15px;">
                            <label style="font-size: 0.9rem; color: #6b7280; display: block; margin-bottom: 5px;">Upload Deal Image</label>
                            <input type="file" name="deal_image" accept="image/*">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-tag"></i> Add Deal</button>
                </form>
            </div>
            <div class="card">
                <h2>Delete Deal</h2>
                <form onsubmit="handleDelete(event, 'deleteDeal', 'del_deal_id', 'deal_id', 'Deal is deleting!......', 'Deal is deleted successfully!')">
                    <div style="display: flex; gap: 15px; max-width: 500px;">
                        <input type="text" id="del_deal_id" required placeholder="Enter Deal ID">
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead><tr><th>ID</th><th>Mode</th><th>Title</th><th>Highlight</th><th>Details</th></tr></thead>
                        <tbody id="deals-body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="profile" class="section-container">
            <div class="card">
                <h2>Owner Profile</h2>
                <form id="profileForm" onsubmit="handleFormSubmit(event, 'updateProfileDetails', 'Updating profile...', 'Profile Details Updated!')" enctype="multipart/form-data">
                    
                    <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 30px;">
                        <img src="https://via.placeholder.com/150" id="prof_display" class="profile-img-lg" alt="Profile">
                        <div>
                            <h3 style="margin-bottom: 10px;" id="prof_display_name"><?php echo $owner_name; ?></h3>
                            <div class="btn-group">
                                <label class="btn btn-update" style="cursor: pointer;">
                                    <i class="fa-solid fa-upload"></i> Update Image
                                    <input type="file" name="profile_image" id="new_profile_img" style="display: none;" accept="image/*" onchange="previewProfileImage(event)">
                                </label>
                                <button type="button" class="btn btn-danger" onclick="removeProfileImage()">
                                    <i class="fa-solid fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid">
                        <input type="text" name="full_name" id="prof_name" required placeholder="Full Name">
                        <input type="email" name="email" id="prof_email" required placeholder="Email Address">
                        <input type="text" name="mobile" id="prof_mobile" required placeholder="Mobile Number">
                        <input type="text" name="address" id="prof_address" required placeholder="Address">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Profile Details</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        // Tab Logic
        function switchTab(tabId) {
            document.querySelectorAll('.section-container').forEach(sec => sec.classList.remove('active'));
            document.querySelectorAll('.nav-links li').forEach(li => li.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        let currentSubmitAction = '';
        function setSubmitAction(action) { currentSubmitAction = action; }

        // Fetch & Populate Data
        function loadDashboardData() {
            fetch('dashboard_actions.php?action=fetchAllData')
            .then(res => res.json())
            .then(data => {
                // Vehicles
                document.getElementById('vehicle-table-body').innerHTML = data.vehicles.map(v => `<tr><td>${v.id}</td><td>${v.vehical_number}</td><td>${v.vehical_type}</td><td>${v.vehical_model}</td><td>${v.price_per_day}</td><td>${v.additional_details}</td><td><img src="${v.vehical_image}" class="tbl-img"></td></tr>`).join('');
                document.getElementById('dashboard-grid').innerHTML = data.vehicles.map(v => `<div class="vehicle-box"><img src="${v.vehical_image}"><div class="vehicle-box-info"><h3>${v.vehical_model}</h3><p>${v.vehical_number}</p></div></div>`).join('');
                
                // Bookings
                document.getElementById('all-bookings-body').innerHTML = data.bookings.map(b => `<tr><td>${b.id}</td><td>${b.booking_number}</td><td>${b.customer_name}</td><td>${b.mobile}</td><td>${b.vehical_number}</td><td>${b.booking_date}</td><td><select class="status-dropdown" onchange="updateStatus(${b.id}, this.value)"><option value="Pending" ${b.status==='Pending'?'selected':''}>Pending</option><option value="Confirmed" ${b.status==='Confirmed'?'selected':''}>Confirmed</option><option value="Reject" ${b.status==='Reject'?'selected':''}>Reject</option><option value="Cancel" ${b.status==='Cancel'?'selected':''}>Cancel</option></select></td></tr>`).join('');
                
                // Availability
                document.getElementById('availability-body').innerHTML = data.availability.map(a => `<tr><td>${a.id}</td><td>${a.vehical_number}</td><td>${a.vehical_type}</td><td>${a.vehical_model}</td><td>${a.available_date}</td><td><button class="btn btn-danger" onclick="executeDelete('deleteAvailability', 'id', ${a.id}, 'Availability is deleting!.....', 'Availability deleted successfully!')"><i class="fa-solid fa-trash"></i></button></td></tr>`).join('');
                
                // Deals
                document.getElementById('deals-body').innerHTML = data.deals.map(d => `<tr><td>${d.id}</td><td>${d.deal_mode}</td><td>${d.deal_title}</td><td>${d.highlighted_text}</td><td>${d.details}</td></tr>`).join('');
                
                // Profile
                if(data.profile) {
                    document.getElementById('prof_name').value = data.profile.full_name;
                    document.getElementById('prof_email').value = data.profile.email;
                    document.getElementById('prof_mobile').value = data.profile.mobile;
                    document.getElementById('prof_address').value = data.profile.address;
                    document.getElementById('prof_display_name').innerText = data.profile.full_name;
                    document.getElementById('prof_display').src = (data.profile.profile_image && data.profile.profile_image !== "") ? data.profile.profile_image : 'https://via.placeholder.com/150';
                }
            });
        }

        // Form Submit Logic
        function handleFormSubmit(e, action, msg1, msg2) {
            e.preventDefault();
            let formData = new FormData(e.target);
            formData.append('action', action);
            
            Swal.fire({ title: msg1, allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            
            fetch('dashboard_actions.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Success!', text: msg2, confirmButtonColor: '#111827' });
                    e.target.reset(); 
                    loadDashboardData();
                } else Swal.fire('Error', data.message, 'error');
            });
        }

        function handleVehicleAction(e) {
            let msg1 = currentSubmitAction === 'addVehicle' ? 'Vehical adding!......' : 'Updating vehical details!......';
            let msg2 = currentSubmitAction === 'addVehicle' ? 'Vehical added suceessfully!' : 'Vehical updated successfully!';
            handleFormSubmit(e, currentSubmitAction, msg1, msg2);
        }

        // Delete Logic
        function handleDelete(e, action, inputId, paramName, msg1, msg2) {
            e.preventDefault();
            let id = document.getElementById(inputId).value;
            Swal.fire({ title: 'Are you sure?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#111827' })
            .then((result) => { if (result.isConfirmed) executeDelete(action, paramName, id, msg1, msg2); });
        }

        function executeDelete(action, paramName, id, msg1, msg2) {
            Swal.fire({ title: msg1, allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            let formData = new FormData(); formData.append('action', action); formData.append(paramName, id);
            fetch('dashboard_actions.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Deleted!', text: msg2, confirmButtonColor: '#111827' });
                    loadDashboardData();
                } else Swal.fire('Error', data.message, 'error');
            });
        }

        function updateStatus(id, status) {
            Swal.fire({ title: 'Status updating!......', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            let formData = new FormData(); formData.append('action', 'updateBookingStatus'); formData.append('booking_id', id); formData.append('status', status);
            fetch('dashboard_actions.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => { if(data.status === 'success') Swal.fire({ icon: 'success', title: 'Success!', text: 'Status updated successfully!', confirmButtonColor: '#111827' }); });
        }

        // Tracking Logic
        function handleTracking(e) {
            e.preventDefault();
            let v_num = document.getElementById('track_v_num').value;
            
            Swal.fire({ title: 'Tracking!....', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            
            let formData = new FormData(); 
            formData.append('action', 'trackVehicle'); 
            formData.append('vehical_number', v_num);
            
            fetch('dashboard_actions.php', { method: 'POST', body: formData })
            .then(res => res.text()) // Catch as text first to prevent JSON crashes
            .then(text => {
                Swal.close();
                document.getElementById('tracking-results').style.display = 'block';
                
                try {
                    const data = JSON.parse(text);
                    
                    if(data.status === 'success' && data.data) {
                        document.getElementById('tracking-data-container').innerHTML = `
                            <div style="border-left: 4px solid #3b82f6; padding-left: 15px;">
                                <p style="margin-bottom: 8px; font-size: 1.1rem;">
                                    <i class="fa-solid fa-car" style="color: #64748b; width: 25px;"></i> 
                                    <strong>Vehicle:</strong> ${data.data.vehical_number}
                                </p>
                                <p style="margin-bottom: 8px; font-size: 1.1rem;">
                                    <i class="fa-solid fa-location-dot" style="color:#dc2626; width: 25px;"></i> 
                                    <strong>Current Location:</strong> ${data.data.current_location}
                                </p>
                                <p style="margin-bottom: 8px;">
                                    <i class="fa-solid fa-clock" style="color: #64748b; width: 25px;"></i> 
                                    <strong>Time & Date:</strong> ${data.data.time_and_date}
                                </p>
                                <p style="margin-bottom: 8px;">
                                    <i class="fa-solid fa-id-badge" style="color: #64748b; width: 25px;"></i> 
                                    <strong>Driver Name:</strong> ${data.data.driver_name}
                                </p>
                                <p style="margin-bottom: 0; color: #475569;">
                                    <i class="fa-solid fa-route" style="color: #64748b; width: 25px;"></i> 
                                    <strong>Trip Details:</strong> ${data.data.trip_details}
                                </p>
                            </div>
                        `;
                    } else {
                        document.getElementById('tracking-data-container').innerHTML = `
                            <p style="color: #dc2626; font-weight: 600; margin: 0;">
                                <i class="fa-solid fa-circle-exclamation"></i> ${data.message}
                            </p>
                        `;
                    }
                } catch (error) {
                    console.error("Server crashed. PHP Error Output:", text);
                    document.getElementById('tracking-data-container').innerHTML = `
                        <p style="color: #dc2626; font-weight: 600; margin: 0;">
                            <i class="fa-solid fa-triangle-exclamation"></i> Server error occurred. Please check the browser console.
                        </p>
                    `;
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire('Error', 'Failed to fetch tracking data. Please check your connection.', 'error');
            });
        }

        function toggleDealFields() {
            const mode = document.getElementById('deal_mode').value;
            const textInputs = document.querySelectorAll('.deal-text-input');
            const imgInput = document.getElementById('deal_image_input');
            textInputs.forEach(el => el.classList.add('hidden')); imgInput.classList.add('hidden');
            if (mode === 'Text') { document.getElementById('deal_title').classList.remove('hidden'); document.getElementById('deal_highlight').classList.remove('hidden'); document.getElementById('deal_details').classList.remove('hidden'); }
            else if (mode === 'Image') { imgInput.classList.remove('hidden'); }
            else if (mode === 'Image with Text') { document.getElementById('deal_highlight').classList.remove('hidden'); document.getElementById('deal_details').classList.remove('hidden'); imgInput.classList.remove('hidden'); }
        }

        // Profile Image Logic
        function previewProfileImage(event) {
            const reader = new FileReader();
            reader.onload = function(){ document.getElementById('prof_display').src = reader.result; };
            if(event.target.files[0]) reader.readAsDataURL(event.target.files[0]);
        }

        function removeProfileImage() {
            Swal.fire({ title: 'Are you sure?', text: "This will remove your current profile image.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#111827', confirmButtonText: 'YES', cancelButtonText: 'NO' })
            .then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Removing image...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                    let formData = new FormData(); formData.append('action', 'removeProfileImage');
                    fetch('dashboard_actions.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                        if(data.status === 'success') {
                            Swal.fire({ icon: 'success', title: 'Removed!', text: 'Profile image removed successfully!', confirmButtonColor: '#111827' });
                            document.getElementById('prof_display').src = 'https://via.placeholder.com/150';
                            document.getElementById('new_profile_img').value = '';
                            loadDashboardData();
                        } else Swal.fire('Error', data.message, 'error');
                    });
                }
            });
        }

        function handleLogout() {
            Swal.fire({ title: 'Are you sure need to logout from the dashboard?', icon: 'question', showCancelButton: true, confirmButtonColor: '#111827', cancelButtonColor: '#dc2626', confirmButtonText: 'YES', cancelButtonText: 'NO' })
            .then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Logging out from the dashboard!.... Redirecting to the Login portal!', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                    setTimeout(() => window.location.href = 'owner_portal.php', 1500);
                }
            });
        }

        window.onload = loadDashboardData;
    </script>
</body>
</html>