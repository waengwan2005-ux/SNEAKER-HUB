<?php
include 'db.php';
$result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการสั่งซื้อ - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
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
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: var(--text-muted); text-decoration: none; font-size: 0.9rem; }
        table { width: 100%; border-collapse: collapse; background: var(--card-bg); border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color); box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid var(--border-color); font-size: 0.9rem; }
        th { background-color: #f7f7f7; font-weight: 500; color: var(--accent); }
        tr:last-child td { border-bottom: none; }
        .slip-img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color); }
        .no-slip { color: var(--text-muted); font-size: 0.85rem; }
        .map-btn { display: inline-block; margin-top: 5px; background: #e3f2fd; color: #1565c0; padding: 3px 8px; border-radius: 4px; text-decoration: none; font-size: 0.8rem; border: 1px solid #bbdefb; }
        .map-btn:hover { background: #bbdefb; }
    </style>
</head>
<body>
    <header>
        <h1>ADMIN ORDERS</h1>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 5px 0 0;">รายการคำสั่งซื้อ สลิป และพิกัดแผนที่จัดส่ง</p>
    </header>
    <div class="container">
        <a href="admin.php" class="back-link">&larr; กลับหน้าจัดการระบบหลังบ้าน</a>
        <h2 style="font-size: 1.1rem; font-weight: 500; margin-bottom: 20px;">รายการสั่งซื้อทั้งหมด</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>สินค้า / ราคา</th>
                    <th>ชื่อผู้ซื้อ / ที่อยู่ / พิกัด</th>
                    <th>การชำระเงิน</th>
                    <th>สลิปโอนเงิน</th>
                    <th>เวลาสั่งซื้อ</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td>
                        <strong><?php echo $row['product_name']; ?></strong><br>
                        <span style="color: #e91e63; font-weight: 500;"><?php echo number_format($row['price']); ?> THB</span>
                    </td>
                    <td>
                        <strong><?php echo $row['customer_name']; ?></strong><br>
                        <span style="font-size: 0.85rem; color: var(--text-muted);"><?php echo $row['address']; ?></span><br>
                        <span style="font-size: 0.85rem;">📞 <?php echo $row['phone']; ?></span><br>
                        <?php if (!empty($row['latitude']) && !empty($row['longitude'])): ?>
                            <a href="https://www.google.com/maps?q=<?php echo $row['latitude']; ?>,<?php echo $row['longitude']; ?>" target="_blank" class="map-btn">📍 ดูพิกัดแผนที่จัดส่ง</a>
                        <?php else: ?>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">- ไม่ได้ปักหมุด -</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['payment_method']; ?></td>
                    <td>
                        <?php if (!empty($row['slip_image'])): ?>
                            <a href="<?php echo $row['slip_image']; ?>" target="_blank">
                                <img src="<?php echo $row['slip_image']; ?>" class="slip-img" alt="สลิปโอนเงิน">
                            </a>
                        <?php else: ?>
                            <span class="no-slip">- ไม่มีสลิป -</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size: 0.85rem; color: var(--text-muted);"><?php echo $row['order_date']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>