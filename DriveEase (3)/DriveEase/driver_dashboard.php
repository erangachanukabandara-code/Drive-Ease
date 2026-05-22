<?php
session_start();
if (!isset($_SESSION['driver_id']) || $_SESSION['user_role'] !== 'driver') {
    header("Location: driver_portal.php");
    exit;
}
$driver_name = $_SESSION['driver_name'] ?? 'Driver';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive Ease | Driver Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
        @font-face { font-family: 'Google Sans'; src: url('https://fonts.gstatic.com/s/productsans/v5/HYvgU2fE2nRJvZ5JFAumwegdm0LZdjqr5-oayXSOefg.woff2') format('woff2'); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Google Sans', 'Open Sans', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f7f6; color: #111827; overflow: hidden; }
        .sidebar { width: 260px; background: #0f172a; color: white; display: flex; flex-direction: column; height: 100%; z-index: 10; }
        .sidebar-header { padding: 30px 20px; font-size: 1.5rem; font-weight: 700; color: #3b82f6; text-align: center; border-bottom: 1px solid #1e293b; }
        .nav-links { list-style: none; padding: 20px 0; flex-grow: 1; }
        .nav-links li { padding: 16px 25px; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 15px; font-size: 1rem; color: #cbd5e1; }
        .nav-links li:hover, .nav-links li.active { background: #1e293b; color: #3b82f6; border-left: 4px solid #3b82f6; }
        .main-content { flex-grow: 1; padding: 30px; overflow-y: auto; height: 100vh; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 30px; border: 1px solid #e2e8f0; }
        .card h2 { margin-bottom: 25px; color: #0f172a; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; font-size: 1.5rem; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .input-group label { display: block; font-size: 0.9rem; color: #64748b; margin-bottom: 5px; font-weight: 500; }
        input, select, textarea { width: 100%; padding: 12px 15px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; background: #f8fafc; transition: 0.3s; font-size: 0.95rem; }
        input:focus, select:focus, textarea:focus { border-color: #3b82f6; background: white; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .btn-primary { padding: 14px 25px; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.3s; font-size: 1rem; display: inline-flex; align-items: center; gap: 8px; width: auto; }
        .btn-primary:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3); }
        .table-responsive { overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0; }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 0.95rem; }
        th { background: #f8fafc; color: #475569; font-weight: 600; white-space: nowrap; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">Drive Ease</div>
        <ul class="nav-links">
            <li class="active"><i class="fa-solid fa-location-dot"></i> Live Tracking</li>
            <li onclick="handleLogout()"><i class="fa-solid fa-right-from-bracket"></i> Logout</li>
        </ul>
    </div>
    <div class="main-content">
        <div class="card">
            <h2><i class="fa-solid fa-satellite-dish" style="color: #3b82f6;"></i> Update Current Location</h2>
            <form id="trackingForm" onsubmit="handleLocationSubmit(event)">
                <div class="form-grid">
                    <div class="input-group"><label>Vehicle Number</label><input type="text" name="vehical_number" required placeholder="e.g., WP-CBA-1234"></div>
                    <div class="input-group"><label>Current Location</label><input type="text" name="current_location" required placeholder="e.g., Galle Road, Colombo 03"></div>
                    <div class="input-group"><label>Time and Date</label><input type="datetime-local" name="time_and_date" required></div>
                    <div class="input-group"><label>Driver Name</label><input type="text" name="driver_name" required value="<?php echo htmlspecialchars($driver_name); ?>" readonly style="background-color: #e2e8f0;"></div>
                </div>
                <div class="input-group" style="margin-bottom: 20px;"><label>Trip Details</label><textarea name="trip_details" rows="3" required placeholder="e.g., Transporting clients from Airport to Hotel"></textarea></div>
                <button type="submit" class="btn-primary"><i class="fa-solid fa-paper-plane"></i> Add Current Location</button>
            </form>
        </div>
        <div class="card">
            <h2><i class="fa-solid fa-clock-rotate-left" style="color: #3b82f6;"></i> My Location Updates History</h2>
            <div class="table-responsive">
                <table>
                    <thead><tr><th>Vehicle Number</th><th>Trip Details</th><th>Current Location</th><th>Time and Date</th><th>Driver Name</th></tr></thead>
                    <tbody id="tracking-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function handleLocationSubmit(e) {
            e.preventDefault();
            Swal.fire({ title: 'Vehical current location is updating!......', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            let formData = new FormData(e.target);
            formData.append('action', 'addLocation');

            fetch('driver_actions.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                setTimeout(() => {
                    if(data.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Success!', text: 'Location updated successfully!.', confirmButtonColor: '#3b82f6' });
                        e.target.reset(); 
                        document.querySelector('input[name="driver_name"]').value = "<?php echo htmlspecialchars($driver_name); ?>";
                        loadTrackingData(); 
                    } else Swal.fire('Error', data.message, 'error');
                }, 1000);
            }).catch(error => Swal.fire('Error', 'Network error. Please try again.', 'error'));
        }

        function loadTrackingData() {
            fetch('driver_actions.php?action=fetchLocations')
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success' && data.data) {
                    document.getElementById('tracking-table-body').innerHTML = data.data.map(row => `<tr><td style="font-weight: 600;">${row.vehical_number}</td><td>${row.trip_details}</td><td><i class="fa-solid fa-location-dot" style="color: #ef4444; margin-right: 5px;"></i> ${row.current_location}</td><td>${formatDate(row.time_and_date)}</td><td>${row.driver_name}</td></tr>`).join('');
                }
            }).catch(error => console.error("Error fetching data:", error));
        }

        function formatDate(dateString) { return new Date(dateString).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }); }

        function handleLogout() {
            Swal.fire({ title: 'Logout?', text: 'Are you sure you want to end your session?', icon: 'question', showCancelButton: true, confirmButtonColor: '#3b82f6', cancelButtonColor: '#ef4444', confirmButtonText: 'Yes, Logout' })
            .then((result) => { if (result.isConfirmed) window.location.href = 'driver_logout.php'; });
        }
        window.onload = loadTrackingData;
    </script>
</body>
</html>