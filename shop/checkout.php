<?php
include 'db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();

if (!$product) {
    header("Location: index.php");
    exit();
}

$success = false;
if (isset($_POST['confirm_order'])) {
    $name = $_POST['customer_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment = $_POST['payment_method'];
    $latitude = !empty($_POST['latitude']) ? $_POST['latitude'] : null;
    $longitude = !empty($_POST['longitude']) ? $_POST['longitude'] : null;
    $pname = $product['name'];
    $pprice = $product['price'];
    $slip_path = null;

    if (isset($_FILES['slip']) && $_FILES['slip']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_ext = pathinfo($_FILES['slip']['name'], PATHINFO_EXTENSION);
        $file_name = "slip_" . time() . "_" . rand(1000, 9999) . "." . $file_ext;
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES['slip']['tmp_name'], $target_file)) {
            $slip_path = $target_file;
        }
    }

    $stmt = $conn->prepare("INSERT INTO orders (product_name, price, customer_name, address, phone, payment_method, slip_image, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsssssdd", $pname, $pprice, $name, $address, $phone, $payment, $slip_path, $latitude, $longitude);
    if ($stmt->execute()) {
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ชำระเงินและปักหมุดที่อยู่ - Sneaker Hub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Leaflet CSS สำหรับแสดงแผนที่ -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root {
            --bg-color: #fbfbf9;
            --card-bg: #ffffff;
            --text-main: #2b2b2b;
            --text-muted: #777777;
            --accent: #111111;
            --border-color: #eaeaea;
        }
        body { font-family: 'Prompt', sans-serif; margin: 0; background-color: var(--bg-color); color: var(--text-main); line-height: 1.6; }
        header { background-color: #ffffff; padding: 30px 20px; text-align: center; border-bottom: 1px solid var(--border-color); }
        header h1 { font-weight: 600; font-size: 1.5rem; margin: 0; color: var(--accent); }
        .container { max-width: 700px; margin: 40px auto; background: var(--card-bg); padding: 30px; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        .product-summary { display: flex; gap: 20px; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 20px; margin-bottom: 20px; }
        .product-summary img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: 500; margin-bottom: 5px; font-size: 0.9rem; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; box-sizing: border-box; font-family: 'Prompt', sans-serif; font-size: 0.9rem; }
        .btn { background-color: var(--accent); color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; width: 100%; font-size: 1rem; font-weight: 500; margin-top: 10px; }
        .btn:hover { background: #333333; }
        .success-box { text-align: center; padding: 40px 0; }
        .success-box h2 { color: #2e7d32; margin-bottom: 10px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: var(--text-muted); text-decoration: none; font-size: 0.9rem; }
        .bank-info { background: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px dashed var(--border-color); margin-bottom: 15px; font-size: 0.9rem; }
        #slip-section { display: none; margin-top: 15px; padding: 15px; background: #fdfdfd; border: 1px solid var(--border-color); border-radius: 6px; }
        /* สไตล์กรอบแผนที่ปักหมุด */
        #map { width: 100%; height: 280px; border-radius: 8px; border: 1px solid var(--border-color); margin-top: 5px; }
        .map-hint { font-size: 0.8rem; color: var(--text-muted); margin-top: 4px; }
    </style>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
    <header>
        <h1>CHECKOUT & LOCATION</h1>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 5px 0 0;">ยืนยันคำสั่งซื้อและปักหมุดที่อยู่จัดส่ง</p>
    </header>
    <div class="container">
        <a href="index.php" class="back-link">&larr; กลับไปหน้าเลือกสินค้า</a>
        
        <?php if ($success): ?>
            <div class="success-box">
                <h2>สั่งซื้อและบันทึกพิกัดสำเร็จ!</h2>
                <p>ขอบคุณที่ไว้วางใจเรา ทางร้านได้รับข้อมูลที่อยู่และพิกัดจัดส่งของคุณเรียบร้อยแล้ว</p>
                <a href="index.php" class="btn" style="display:inline-block; width:auto; padding:10px 30px; text-decoration:none; margin-top:20px;">กลับสู่หน้าแรก</a>
            </div>
        <?php else: ?>
            <div class="product-summary">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                <div>
                    <h3 style="margin:0 0 5px; font-size: 1.1rem;"><?php echo $product['name']; ?></h3>
                    <p style="margin:0; color: #e91e63; font-weight: 600; font-size: 1.1rem;"><?php echo number_format($product['price']); ?> THB</p>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <h3 style="font-size: 1rem; font-weight: 500; margin-bottom: 15px;">ข้อมูลการจัดส่ง</h3>
                <div class="form-group">
                    <label>ชื่อ-นามสกุล ผู้รับ:</label>
                    <input type="text" name="customer_name" placeholder="กรอกชื่อและนามสกุลของคุณ" required>
                </div>
                <div class="form-group">
                    <label>ที่อยู่จัดส่ง (บ้านเลขที่, ถนน, ตำบล, อำเภอ, จังหวัด):</label>
                    <textarea name="address" rows="3" placeholder="ระบุรายละเอียดที่อยู่" required></textarea>
                </div>

                <!-- ส่วนปักหมุดแผนที่ -->
                <div class="form-group">
                    <label>ปักหมุดตำแหน่งจัดส่งบนแผนที่:</label>
                    <div id="map"></div>
                    <div class="map-hint">💡 คลิกบนแผนที่หรือลากหมุดไปยังตำแหน่งบ้านของคุณ</div>
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                </div>

                <div class="form-group">
                    <label>เบอร์โทรศัพท์:</label>
                    <input type="text" name="phone" placeholder="เบอร์โทรศัพท์ที่ติดต่อได้" required>
                </div>
                
                <h3 style="font-size: 1rem; font-weight: 500; margin: 20px 0 15px;">ช่องทางการชำระเงิน</h3>
                <div class="form-group">
                    <select name="payment_method" id="payment_method" onchange="togglePaymentMethod(this)" required>
                        <option value="">-- เลือกวิธีชำระเงิน --</option>
                        <option value="โอนเงินผ่านธนาคาร (Mobile Banking)">โอนเงินผ่านธนาคาร (Mobile Banking)</option>
                        <option value="ชำระเงินปลายทาง (COD)">ชำระเงินปลายทาง (COD)</option>
                        <option value="บัตรเครดิต / เดบิต">บัตรเครดิต / เดบิต</option>
                    </select>
                </div>

                <div id="slip-section">
                    <div class="bank-info">
                        <strong>โอนเงินผ่านบัญชีธนาคาร:</strong><br>
                        ธนาคารกสิกรไทย (KBANK)<br>
                        เลขที่บัญชี: <strong>123-4-56789-0</strong><br>
                        ชื่อบัญชี: บจก. สนีกเกอร์ ฮับ
                    </div>
                    <div class="form-group">
                        <label>แนบหลักฐานการโอนเงิน (สลิป):</label>
                        <input type="file" name="slip" accept="image/*">
                    </div>
                </div>

                <button type="submit" name="confirm_order" class="btn">ยืนยันการสั่งซื้อและชำระเงิน</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        function togglePaymentMethod(select) {
            var slipSection = document.getElementById('slip-section');
            if (select.value === 'โอนเงินผ่านธนาคาร (Mobile Banking)') {
                slipSection.style.display = 'block';
            } else {
                slipSection.style.display = 'none';
            }
        }

        // ตั้งค่าแผนที่ Leaflet (เริ่มต้นที่กรุงเทพฯ)
        var defaultLat = 13.7563;
        var defaultLng = 100.5018;

        var map = L.map('map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

        function updateCoordinates(lat, lng) {
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }

        // เซ็ตค่าเริ่มต้น
        updateCoordinates(defaultLat, defaultLng);

        // เมื่อลากหมุด
        marker.on('dragend', function(e) {
            var pos = marker.getLatLng();
            updateCoordinates(pos.lat, pos.lng);
        });

        // เมื่อคลิกบนแผนที่
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng);
        });
    </script>
</body>
</html>