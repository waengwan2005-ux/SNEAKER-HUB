<?php
include 'db.php';

// เพิ่มสินค้าใหม่
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $image = $_POST['image'];

    $stmt = $conn->prepare("INSERT INTO products (name, price, category_id, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $name, $price, $category_id, $image);
    $stmt->execute();
    header("Location: admin.php");
    exit();
}

// ลบสินค้า
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: admin.php");
    exit();
}

$products = $conn->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบหลังบ้าน - Sneaker Hub</title>
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
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .nav-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; background: #ffffff; padding: 15px 20px; border-radius: 8px; border: 1px solid var(--border-color); }
        .btn-order { background-color: #2e7d32; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 500; display: inline-block; transition: background 0.2s; }
        .btn-order:hover { background: #1b5e20; }
        .card { background: var(--card-bg); padding: 25px; border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        h2 { font-size: 1.1rem; font-weight: 500; margin-top: 0; margin-bottom: 20px; color: var(--accent); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: 500; margin-bottom: 5px; font-size: 0.9rem; }
        input, select { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; box-sizing: border-box; font-family: 'Prompt', sans-serif; font-size: 0.9rem; }
        .btn { background-color: var(--accent); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 0.9rem; font-weight: 500; }
        .btn:hover { background: #333333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid var(--border-color); font-size: 0.9rem; }
        th { background-color: #f7f7f7; font-weight: 500; color: var(--accent); }
        .thumb { width: 45px; height: 45px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color); }
        .btn-del { color: #d32f2f; text-decoration: none; font-size: 0.85rem; border: 1px solid #ffcdd2; padding: 5px 10px; border-radius: 4px; background: #ffebee; }
        .btn-del:hover { background: #ffcdd2; }
        .back-home { color: var(--text-muted); text-decoration: none; font-size: 0.9rem; }
        .back-home:hover { color: var(--accent); }
    </style>
</head>
<body>
    <header>
        <h1>ADMIN PANEL</h1>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 5px 0 0;">ระบบจัดการข้อมูลเว็บไซต์</p>
    </header>

    <div class="container">
        <!-- แถบเมนูด่วนด้านบน -->
        <div class="nav-bar">
            <a href="index.php" class="back-home">&larr; กลับสู่หน้าเว็บไซต์หลัก</a>
            <a href="admin_orders.php" class="btn-order">📦 ไปดูรายการสั่งซื้อและสลิปของลูกค้า</a>
        </div>

        <!-- ฟอร์มเพิ่มสินค้า -->
        <div class="card">
            <h2>เพิ่มสินค้าใหม่เข้าร้าน</h2>
            <form method="POST">
                <div class="form-group">
                    <label>ชื่อสินค้า:</label>
                    <input type="text" name="name" placeholder="เช่น Nike Air Force 1" required>
                </div>
                <div class="form-group">
                    <label>ราคา (THB):</label>
                    <input type="number" step="0.01" name="price" placeholder="เช่น 3500" required>
                </div>
                <div class="form-group">
                    <label>หมวดหมู่สินค้า:</label>
                    <select name="category_id" required>
                        <option value="">-- เลือกหมวดหมู่ --</option>
                        <?php while($cat = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>ลิงก์รูปภาพสินค้า (Image URL):</label>
                    <input type="text" name="image" placeholder="วางลิงก์รูปภาพ เช่น https://..." required>
                </div>
                <button type="submit" name="add_product" class="btn">บันทึกเพิ่มสินค้า</button>
            </form>
        </div>

        <!-- ตารางจัดการสินค้าเดิม -->
        <div class="card">
            <h2>รายการสินค้าทั้งหมดในระบบ</h2>
            <table>
                <thead>
                    <tr>
                        <th>รูปภาพ</th>
                        <th>ชื่อสินค้า</th>
                        <th>หมวดหมู่</th>
                        <th>ราคา</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($p = $products->fetch_assoc()): ?>
                    <tr>
                        <td><img src="<?php echo $p['image']; ?>" class="thumb" alt="สินค้า"></td>
                        <td><strong><?php echo $p['name']; ?></strong></td>
                        <td><?php echo $p['cat_name']; ?></td>
                        <td><?php echo number_format($p['price']); ?> THB</td>
                        <td>
                            <a href="admin.php?delete=<?php echo $p['id']; ?>" class="btn-del" onclick="return confirm('ยืนยันการลบสินค้านี้ใช่มั้ย?')">ลบ</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>