<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: index.php");
    exit;
}
$customer_name = $_SESSION['full_name'] ?? 'Customer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive Ease | Customer Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background-color: #f8fafc; color: #0f172a; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
        
        /* Top Header */
        .top-header { background-color: #ffffff; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); z-index: 100; }
        .header-left { display: flex; align-items: center; gap: 40px; }
        .logo { font-size: 1.8rem; font-weight: 800; color: #0f172a; text-decoration: none; letter-spacing: -0.5px; cursor: pointer; } 
        .logo span { color: #3b82f6; }
        .nav-links { display: flex; gap: 20px; }
        .nav-links a { text-decoration: none; color: #475569; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .nav-links a:hover, .nav-links a.active { color: #3b82f6; }
        
        .header-right { display: flex; align-items: center; gap: 20px; }
        .customer-name { font-weight: 600; color: #0f172a; font-size: 1.1rem; }
        .icon-btn { background: none; border: none; font-size: 1.4rem; color: #64748b; cursor: pointer; transition: 0.3s; }
        .icon-btn:hover { color: #3b82f6; transform: rotate(30deg); }
        .btn-logout { padding: 8px 20px; background: #ef4444; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-logout:hover { background: #dc2626; }

        /* Main Layout */
        .main-layout { display: flex; flex-grow: 1; overflow: hidden; }
        
        /* Sidebar Filter */
        .sidebar { width: 280px; background: #ffffff; padding: 30px 20px; border-right: 1px solid #e2e8f0; overflow-y: auto; }
        .sidebar h3 { margin-bottom: 20px; font-size: 1.3rem; color: #0f172a; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; }
        .filter-group { margin-bottom: 20px; }
        .filter-group label { display: block; font-size: 0.9rem; color: #64748b; margin-bottom: 8px; font-weight: 500; }
        .filter-group select, .filter-group input { width: 100%; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; background: #f8fafc; font-family: 'Outfit'; }
        .filter-group select:focus, .filter-group input:focus { border-color: #3b82f6; }
        .btn-reset { width: 100%; padding: 12px; background: #e2e8f0; color: #475569; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-reset:hover { background: #cbd5e1; }

        /* Content Area */
        .content-area { flex-grow: 1; padding: 30px; overflow-y: auto; }
        .section-view { display: none; animation: fadeIn 0.4s ease; }
        .section-view.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Vehicle Grid */
        .vehicles-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
        .vehicle-card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); transition: 0.3s; border: 1px solid #f1f5f9; display: flex; flex-direction: column; }
        .vehicle-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .vehicle-img { width: 100%; height: 200px; object-fit: cover; }
        .vehicle-info { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
        .vehicle-info h3 { font-size: 1.3rem; font-weight: 700; color: #0f172a; margin-bottom: 10px; }
        .vehicle-info p { color: #64748b; font-size: 0.95rem; margin-bottom: 5px; }
        .price-highlight { font-size: 1.4rem; font-weight: 800; color: #3b82f6; margin: 15px 0; background: #eff6ff; padding: 10px; border-radius: 8px; text-align: center; }
        .btn-book-now { width: 100%; padding: 12px; background: #0f172a; color: white; border: none; border-radius: 8px; font-size: 1.05rem; font-weight: 600; cursor: pointer; transition: 0.3s; margin-top: auto; }
        .btn-book-now:hover { background: #3b82f6; }

        /* Forms & Cards */
        .card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 900px; margin: 0 auto; border: 1px solid #f1f5f9; }
        .card h2 { font-size: 1.8rem; margin-bottom: 25px; color: #0f172a; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-size: 0.95rem; font-weight: 500; color: #475569; margin-bottom: 8px; }
        .input-group input, .input-group select, .input-group textarea { width: 100%; padding: 14px; border-radius: 8px; boarder: 1px solid #cbd5e1; font-size: 1rem; outline: none; background: #f8fafc; font-family: 'Outfit', sans-serif; transition: 0.3s; resize: vertical; }
        .input-group input:focus, .input-group select:focus, .input-group textarea:focus { border-color: #3b82f6; background: white; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .btn-action { padding: 15px 30px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; font-size: 1.05rem; width: 100%; }
        .btn-action:hover { background: #2563eb; }

        /* Booking Flow Dynamic Sections */
        .hidden-section { display: none; margin-top: 30px; padding-top: 30px; border-top: 2px dashed #e2e8f0; animation: fadeIn 0.5s ease; }
        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 25px; margin-bottom: 25px; }
        .summary-box p { margin-bottom: 10px; font-size: 1.05rem; color: #475569; }
        .summary-box h3 { font-size: 1.6rem; color: #dc2626; margin-top: 15px; border-top: 1px solid #cbd5e1; padding-top: 15px; }
        
        .bank-details-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 20px; margin-bottom: 20px; color: #1e3a8a; }
        .bank-details-box p { margin-bottom: 5px; font-weight: 500; }

        /* Tables */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; color: #475569; font-weight: 600; }

        /* Profile Specific */
        .profile-header { display: flex; align-items: center; gap: 30px; margin-bottom: 30px; }
        .profile-img-lg { width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 4px solid #3b82f6; }

        /* Star Rating System */
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; font-size: 2rem; gap: 10px; }
        .star-rating input { display: none; }
        .star-rating label { color: #cbd5e1; cursor: pointer; transition: 0.2s; }
        .star-rating input:checked ~ label { color: #d4af37; }
        .star-rating label:hover, .star-rating label:hover ~ label { color: #d4af37; }

    </style>
</head>
<body>

    <div class="top-header">
        <div class="header-left">
            <div class="logo" onclick="switchView('home-view')">Drive<span>Ease</span></div>
            <div class="nav-links">
                <a onclick="switchView('home-view')" id="nav-home" class="active">Vehicles</a>
                <a onclick="switchView('my-bookings-view')" id="nav-bookings">My Bookings</a>
                <a onclick="switchView('review-view')" id="nav-review">Make a Review</a>
            </div>
        </div>
        <div class="header-right">
            <span class="customer-name">Welcome, <?php echo htmlspecialchars($customer_name); ?></span>
            <button class="icon-btn" onclick="switchView('profile-view')" title="Settings"><i class="fa-solid fa-gear"></i></button>
            <button class="btn-logout" onclick="handleLogout()"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
        </div>
    </div>

    <div class="main-layout">
        
        <div class="sidebar" id="filter-sidebar">
            <h3><i class="fa-solid fa-filter" style="color: #3b82f6;"></i> Filter Vehicles</h3>
            <div class="filter-group">
                <label>Vehicle Type</label>
                <select id="filter_type" onchange="applyFilters()">
                    <option value="All">All Types</option>
                    <option value="Sedan">Sedan</option>
                    <option value="SUV">SUV</option>
                    <option value="Hatchback">Hatchback</option>
                    <option value="Van">Van</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Vehicle Model</label>
                <input type="text" id="filter_model" placeholder="e.g., Mercedes" onkeyup="applyFilters()">
            </div>
            <div class="filter-group">
                <label>Engine Capacity</label>
                <input type="text" id="filter_engine" placeholder="e.g., 1500cc" onkeyup="applyFilters()">
            </div>
            <button class="btn-reset" onclick="resetFilters()">Reset Filters</button>
        </div>

        <div class="content-area">
            
            <div id="home-view" class="section-view active">
                <div class="vehicles-container" id="vehicles-grid">
                    </div>
            </div>

            <div id="profile-view" class="section-view">
                <div class="card">
                    <h2><i class="fa-solid fa-user" style="color: #3b82f6;"></i> My Profile Details</h2>
                    <form id="profileForm" onsubmit="handleProfileUpdate(event)" enctype="multipart/form-data">
                        <div class="profile-header">
                            <img src="https://via.placeholder.com/150" id="prof_display" class="profile-img-lg" alt="Profile">
                            <div>
                                <h3 style="margin-bottom: 10px; font-size: 1.5rem;" id="prof_display_name"><?php echo htmlspecialchars($customer_name); ?></h3>
                                <label style="cursor: pointer; color: #3b82f6; font-weight: 600; border: 1px solid #3b82f6; padding: 8px 15px; border-radius: 8px;">
                                    <i class="fa-solid fa-camera"></i> Change Image
                                    <input type="file" name="profile_image" style="display: none;" accept="image/*" onchange="previewProfileImage(event)">
                                </label>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="input-group"><label>Customer ID</label><input type="text" id="prof_id" readonly style="background-color: #e2e8f0; cursor: not-allowed;"></div>
                            <div class="input-group"><label>Full Name</label><input type="text" name="full_name" id="prof_name" required></div>
                            <div class="input-group"><label>Email Address</label><input type="email" name="email" id="prof_email" required></div>
                            <div class="input-group"><label>Mobile Number</label><input type="text" name="mobile" id="prof_mobile" required></div>
                            <div class="input-group"><label>NIC Number</label><input type="text" name="nic" id="prof_nic" required></div>
                            <div class="input-group">
                                <label>Gender</label>
                                <select name="gender" id="prof_gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="input-group"><label>Address</label><input type="text" name="address" id="prof_address" required></div>
                        <div class="form-grid">
                            <div class="input-group"><label>New Password (Leave blank to keep current)</label><input type="password" id="prof_pass" name="password"></div>
                            <div class="input-group"><label>Confirm New Password</label><input type="password" id="prof_cpass"></div>
                        </div>
                        <button type="submit" class="btn-action">Update details</button>
                    </form>
                </div>
            </div>

            <div id="my-bookings-view" class="section-view">
                <div class="card" style="max-width: 100%;">
                    <h2><i class="fa-solid fa-list-check" style="color: #3b82f6;"></i> My Bookings History</h2>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Booking No.</th>
                                    <th>Vehicle</th>
                                    <th>Taking Date</th>
                                    <th>Handover Date</th>
                                    <th>Type</th>
                                    <th>Total Cost</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="bookings-table-body">
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="booking-flow-view" class="section-view">
                <div class="card">
                    <h2><i class="fa-solid fa-calendar-plus" style="color: #3b82f6;"></i> Book Vehicle</h2>
                    
                    <form id="bookingForm">
                        <input type="hidden" id="book_price_per_day">
                        
                        <div class="form-grid">
                            <div class="input-group"><label>Customer Full Name</label><input type="text" name="customer_name" id="book_name" readonly style="background:#e2e8f0;"></div>
                            <div class="input-group"><label>Email</label><input type="email" name="email" id="book_email" readonly style="background:#e2e8f0;"></div>
                            <div class="input-group"><label>Mobile Number</label><input type="text" name="mobile" id="book_mobile" readonly style="background:#e2e8f0;"></div>
                            <div class="input-group"><label>NIC Number</label><input type="text" name="nic" id="book_nic" readonly style="background:#e2e8f0;"></div>
                            
                            <div class="input-group"><label>Vehicle Type</label><input type="text" name="vehical_type" id="book_v_type" readonly style="background:#e2e8f0;"></div>
                            <div class="input-group"><label>Vehicle Model</label><input type="text" name="vehical_model" id="book_v_model" readonly style="background:#e2e8f0;"></div>
                            <div class="input-group"><label>Vehicle Number</label><input type="text" name="vehical_number" id="book_v_num" readonly style="background:#e2e8f0;"></div>
                        </div>

                        <div class="form-grid" style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                            <div class="input-group"><label>Taking Date</label><input type="date" id="book_taking_date" name="taking_date"></div>
                            <div class="input-group"><label>Handover Date</label><input type="date" id="book_handover_date" name="handover_date"></div>
                            <div class="input-group">
                                <label>Vehicle Handover Type</label>
                                <select id="book_handover_type" name="handover_type">
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="Deliver to the address">Deliver to the address</option>
                                    <option value="Pick-up from the branch">Pick-up from the branch</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="button" id="btn-proceed" class="btn-action" onclick="handleProceedBooking()">Proceed Booking</button>

                        <div id="booking-summary-section" class="hidden-section">
                            <div class="summary-box">
                                <h3 style="margin-top:0; border:none; padding:0; color:#0f172a; margin-bottom:15px;">Booking Summary</h3>
                                <p><strong>Vehicle:</strong> <span id="sum_vehicle"></span></p>
                                <p><strong>Duration:</strong> <span id="sum_days"></span></p>
                                <h3>Total Cost: LKR <span id="sum_total_cost">0.00</span></h3>
                                <input type="hidden" id="final_total_cost" name="total_cost">
                            </div>
                            <button type="button" class="btn-action" onclick="handleMakePayment()" style="background: #10b981;">Make Payment</button>
                        </div>

                        <div id="payment-portal-section" class="hidden-section">
                            <h3>Payment Details</h3>
                            <div class="input-group">
                                <label>Select Payment Method</label>
                                <select id="payment_method" name="payment_method" onchange="togglePaymentMethods()">
                                    <option value="" disabled selected>Select Method</option>
                                    <option value="Card Payment">Card Payment</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>

                            <div id="card-payment-form" style="display: none;">
                                <div class="form-grid">
                                    <div class="input-group"><label>Debit/Credit Card Number</label><input type="text" maxlength="12" placeholder="12 Digit Card Number"></div>
                                    <div class="input-group"><label>Card Holder Name</label><input type="text" placeholder="Name on Card"></div>
                                    <div class="input-group"><label>MM/YY</label><input type="text" placeholder="MM/YY" maxlength="5"></div>
                                    <div class="input-group"><label>CVV</label><input type="text" maxlength="3" placeholder="3 Digits"></div>
                                </div>
                            </div>

                            <div id="bank-payment-form" style="display: none;">
                                <div class="bank-details-box">
                                    <p><strong>Bank:</strong> Commercial Bank</p>
                                    <p><strong>Branch:</strong> Kadawatha</p>
                                    <p><strong>A/C Name:</strong> Driver Ease Private Limited</p>
                                    <p><strong>A/C No.:</strong> 8012083786</p>
                                </div>
                                <div class="input-group">
                                    <label>Upload Payment Slip</label>
                                    <input type="file" name="payment_slip" id="payment_slip" accept="image/*,application/pdf">
                                </div>
                            </div>

                            <button type="button" id="btn-final-book" class="btn-action" style="display:none; background:#0f172a; margin-top: 20px;" onclick="submitFinalBooking()">Book Now</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="review-view" class="section-view">
                <div class="card" style="max-width: 650px;">
                    <h2><i class="fa-solid fa-star" style="color: #d4af37;"></i> Share Your Experience</h2>
                    <form id="reviewForm" onsubmit="handleReviewSubmit(event)">
                        <div class="input-group">
                            <label>Customer Full Name</label>
                            <input type="text" name="customer_name" id="review_name" required readonly style="background:#e2e8f0; cursor: not-allowed;">
                        </div>
                        
                        <div class="input-group" style="margin: 30px 0;">
                            <label>Rate Your Experience</label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 stars"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star"><i class="fa-solid fa-star"></i></label>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Your Review</label>
                            <textarea name="review_text" rows="5" required placeholder="Tell us about your experience with Drive Ease..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn-action" style="background-color: #0f172a;">Submit Review <i class="fa-solid fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        let allVehicles = [];
        let customerProfile = {};

        // --- NAVIGATION LOGIC ---
        function switchView(viewId) {
            document.querySelectorAll('.section-view').forEach(sec => sec.classList.remove('active'));
            document.getElementById(viewId).classList.add('active');
            
            document.querySelectorAll('.nav-links a').forEach(a => a.classList.remove('active'));
            if(viewId === 'home-view') document.getElementById('nav-home').classList.add('active');
            if(viewId === 'my-bookings-view') document.getElementById('nav-bookings').classList.add('active');
            
            // Highlight the new nav link and autofill the name
            if(viewId === 'review-view') {
                document.getElementById('nav-review').classList.add('active');
                document.getElementById('review_name').value = customerProfile.full_name;
            }

            document.getElementById('filter-sidebar').style.display = (viewId === 'home-view') ? 'block' : 'none';
            
            if(viewId !== 'booking-flow-view') {
                document.getElementById('booking-summary-section').classList.remove('active');
                document.getElementById('booking-summary-section').style.display = 'none';
                document.getElementById('payment-portal-section').classList.remove('active');
                document.getElementById('payment-portal-section').style.display = 'none';
                document.getElementById('btn-proceed').style.display = 'block';
                document.getElementById('bookingForm').reset();
            }
        }

        // --- FETCH DATA ---
        function loadDashboardData() {
            fetch('customer_actions.php?action=fetchData')
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    allVehicles = data.data.vehicles;
                    customerProfile = data.data.profile;
                    renderVehicles(allVehicles);
                    populateProfileForm();
                    renderBookings(data.data.bookings);
                }
            }).catch(err => console.error("Error fetching data:", err));
        }

        // --- VEHICLES & FILTERS ---
        function renderVehicles(vehicles) {
            const container = document.getElementById('vehicles-grid');
            if(vehicles.length === 0) {
                container.innerHTML = '<p style="grid-column: 1/-1; text-align:center; color:#64748b;">No vehicles found.</p>';
                return;
            }
            container.innerHTML = vehicles.map(v => `
                <div class="vehicle-card">
                    <img src="${v.vehical_image || 'https://via.placeholder.com/400x250?text=No+Image'}" class="vehicle-img">
                    <div class="vehicle-info">
                        <h3>${v.vehical_model}</h3>
                        <p><i class="fa-solid fa-car-side"></i> ${v.vehical_type} | <i class="fa-solid fa-gauge-high"></i> ${v.engine_capacity}</p>
                        <p><i class="fa-solid fa-hashtag"></i> ${v.vehical_number}</p>
                        <div class="price-highlight">LKR ${v.price_per_day} <span style="font-size:0.9rem; color:#475569; font-weight:500;">/ day</span></div>
                        <button class="btn-book-now" onclick="startBooking('${v.vehical_number}')">Book Now</button>
                    </div>
                </div>
            `).join('');
        }

        function applyFilters() {
            const fType = document.getElementById('filter_type').value.toLowerCase();
            const fModel = document.getElementById('filter_model').value.toLowerCase();
            const fEngine = document.getElementById('filter_engine').value.toLowerCase();

            const filtered = allVehicles.filter(v => {
                const matchType = (fType === 'all') || v.vehical_type.toLowerCase().includes(fType);
                const matchModel = v.vehical_model.toLowerCase().includes(fModel);
                const matchEngine = v.engine_capacity.toLowerCase().includes(fEngine);
                return matchType && matchModel && matchEngine;
            });
            renderVehicles(filtered);
        }

        function resetFilters() {
            document.getElementById('filter_type').value = 'All';
            document.getElementById('filter_model').value = '';
            document.getElementById('filter_engine').value = '';
            renderVehicles(allVehicles);
        }

        // --- PROFILE ---
        function populateProfileForm() {
            if(customerProfile.id) {
                document.getElementById('prof_id').value = customerProfile.id;
                document.getElementById('prof_name').value = customerProfile.full_name;
                document.getElementById('prof_email').value = customerProfile.email;
                document.getElementById('prof_mobile').value = customerProfile.mobile;
                document.getElementById('prof_nic').value = customerProfile.nic;
                document.getElementById('prof_gender').value = customerProfile.gender;
                document.getElementById('prof_address').value = customerProfile.address;
                if(customerProfile.profile_image) {
                    document.getElementById('prof_display').src = customerProfile.profile_image;
                }
            }
        }

        function previewProfileImage(event) {
            const reader = new FileReader();
            reader.onload = function(){ document.getElementById('prof_display').src = reader.result; };
            if(event.target.files[0]) reader.readAsDataURL(event.target.files[0]);
        }

        function handleProfileUpdate(e) {
            e.preventDefault();
            let pass = document.getElementById('prof_pass').value;
            let cpass = document.getElementById('prof_cpass').value;
            if(pass !== '' && pass !== cpass) { Swal.fire('Error', 'Passwords do not match!', 'error'); return; }

            Swal.fire({ title: 'Profile details are updating!.....', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

            let formData = new FormData(e.target);
            formData.append('action', 'updateProfile');

            fetch('customer_actions.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                setTimeout(() => {
                    if(data.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Success!', text: 'Profile updated successfully!', confirmButtonColor: '#3b82f6' });
                        document.getElementById('prof_display_name').innerText = document.getElementById('prof_name').value;
                        document.querySelector('.customer-name').innerText = 'Welcome, ' + document.getElementById('prof_name').value;
                    } else Swal.fire('Error', data.message, 'error');
                }, 1000);
            });
        }

        // --- BOOKING FLOW ---
        function startBooking(v_num) {
            const vehicle = allVehicles.find(v => v.vehical_number === v_num);
            if(!vehicle) return;

            document.getElementById('book_name').value = customerProfile.full_name;
            document.getElementById('book_email').value = customerProfile.email;
            document.getElementById('book_mobile').value = customerProfile.mobile;
            document.getElementById('book_nic').value = customerProfile.nic;
            
            document.getElementById('book_v_type').value = vehicle.vehical_type;
            document.getElementById('book_v_model').value = vehicle.vehical_model;
            document.getElementById('book_v_num').value = vehicle.vehical_number;
            document.getElementById('book_price_per_day').value = vehicle.price_per_day;

            switchView('booking-flow-view');
        }

        function handleProceedBooking() {
            // Manual Validation because HTML5 fails silently on hidden sections
            const takingDateVal = document.getElementById('book_taking_date').value;
            const handoverDateVal = document.getElementById('book_handover_date').value;
            const handoverType = document.getElementById('book_handover_type').value;

            if(!takingDateVal || !handoverDateVal || !handoverType) {
                Swal.fire({icon: 'warning', title: 'Missing Information', text: 'Please select taking date, handover date, and handover type to proceed.'});
                return;
            }

            const tDate = new Date(takingDateVal);
            const hDate = new Date(handoverDateVal);
            
            if(tDate > hDate) { 
                Swal.fire('Error', 'Handover date cannot be before Taking date!', 'error'); 
                return; 
            }

            // Calculate Day Difference
            const diffTime = Math.abs(hDate - tDate);
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if(diffDays === 0) diffDays = 1; // Minimum 1 day charge

            const pricePerDay = parseFloat(document.getElementById('book_price_per_day').value);
            
            // Apply Handover Type logic (Add 5000 if Delivered)
            let deliveryFee = 0;
            let deliveryHtml = "";

            if (handoverType === 'Deliver to the address') {
                deliveryFee = 5000;
                deliveryHtml = "<br><span style='color: #dc2626; font-weight: 600;'><i class='fa-solid fa-truck'></i> Delivery Fee Applied: LKR 5000.00</span>";
            }

            const totalCost = (diffDays * pricePerDay) + deliveryFee;

            // Populate Summary
            document.getElementById('sum_vehicle').innerText = document.getElementById('book_v_model').value;
            document.getElementById('sum_days').innerHTML = `${diffDays} Days (${takingDateVal} to ${handoverDateVal}) ${deliveryHtml}`;
            document.getElementById('sum_total_cost').innerText = totalCost.toFixed(2);
            document.getElementById('final_total_cost').value = totalCost.toFixed(2);

            // Expand Form
            document.getElementById('btn-proceed').style.display = 'none';
            document.getElementById('booking-summary-section').style.display = 'block';
        }

        function handleMakePayment() {
            Swal.fire({ title: 'Payment portal loading!....', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            setTimeout(() => {
                Swal.close();
                document.getElementById('payment-portal-section').style.display = 'block';
            }, 1000);
        }

        function togglePaymentMethods() {
            const method = document.getElementById('payment_method').value;
            const btnFinal = document.getElementById('btn-final-book');
            
            document.getElementById('card-payment-form').style.display = 'none';
            document.getElementById('bank-payment-form').style.display = 'none';
            btnFinal.style.display = 'none';

            if(method === 'Card Payment') {
                document.getElementById('card-payment-form').style.display = 'block';
                btnFinal.style.display = 'block';
            } else if (method === 'Bank Transfer') {
                document.getElementById('bank-payment-form').style.display = 'block';
                btnFinal.style.display = 'block';
            }
        }

        function submitFinalBooking() {
            // Manual check for payment fields
            const paymentMethod = document.getElementById('payment_method').value;
            if(!paymentMethod) {
                Swal.fire('Error', 'Please select a payment method!', 'error');
                return;
            }

            if(paymentMethod === 'Bank Transfer' && document.getElementById('payment_slip').files.length === 0) {
                Swal.fire('Error', 'Please upload your payment slip!', 'error');
                return;
            }

            Swal.fire({ title: 'Payment is processing!......', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

            let formData = new FormData(document.getElementById('bookingForm'));
            formData.append('action', 'processBooking');

            fetch('customer_actions.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                setTimeout(() => {
                    if(data.status === 'success') {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Success!', 
                            html: `Thank you! Your booking has been confirmed!<br><br><b>Booking Number: <span style="color:#dc2626;">${data.booking_number}</span></b>`, 
                            confirmButtonColor: '#3b82f6' 
                        }).then(() => {
                            switchView('my-bookings-view');
                            loadDashboardData(); 
                        });
                    } else Swal.fire('Error', data.message, 'error');
                }, 2000);
            });
        }

        // --- MY BOOKINGS RENDER ---
        function renderBookings(bookings) {
            const tbody = document.getElementById('bookings-table-body');
            if(!bookings || bookings.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No bookings found.</td></tr>';
                return;
            }
            tbody.innerHTML = bookings.map(b => `
                <tr>
                    <td style="font-weight:600; color:#3b82f6;">${b.booking_number}</td>
                    <td>${b.vehical_model} (${b.vehical_number})</td>
                    <td>${b.taking_date}</td>
                    <td>${b.handover_date}</td>
                    <td>${b.handover_type}</td>
                    <td style="font-weight:600;">LKR ${b.total_cost}</td>
                    <td><span style="padding:5px 10px; border-radius:6px; font-size:0.85rem; background:#f1f5f9;">${b.status}</span></td>
                </tr>
            `).join('');
        }

        // --- SUBMIT REVIEW LOGIC ---
        function handleReviewSubmit(e) {
            e.preventDefault();
            
            Swal.fire({ title: 'Submitting the review!.....', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

            let formData = new FormData(e.target);
            formData.append('action', 'submitReview');

            fetch('customer_actions.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                setTimeout(() => {
                    if(data.status === 'success') {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Success!', 
                            text: 'Thank You! Successfully submitted the review!.', 
                            confirmButtonColor: '#3b82f6' 
                        }).then(() => {
                            e.target.reset(); // Clear the form
                            switchView('home-view'); // Send user back to home view
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                }, 1500); // Premium animation delay
            }).catch(error => {
                Swal.fire('Error', 'Network Error. Please try again.', 'error');
            });
        }

        // --- LOGOUT LOGIC ---
        function handleLogout() {
            Swal.fire({
                title: 'Are you sure need to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Logging out from the panel!....', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                    setTimeout(() => window.location.href = 'customer_logout.php', 1500);
                }
            });
        }

        window.onload = loadDashboardData;
    </script>
</body>
</html>