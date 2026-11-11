<?php 
include 'db.php'; 
$cat_id = isset($_GET['category']) ? intval($_GET['category']) : 0;
if ($cat_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
    $stmt->bind_param("i", $cat_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM products");
}
$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Sneaker Hub — Minimalist Store</title>
    <meta name="description" content="แหล่งรวมรองเท้าผ้าใบแฟชั่นและสตรีทแวร์ ดีไซน์มินิมอล">
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

        body {
            font-family: 'Prompt', sans-serif;
            margin: 0;
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.6;
        }

        header {
            background-color: #ffffff;
            padding: 40px 20px 20px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }

        header h1 {
            font-weight: 600;
            font-size: 2rem;
            margin: 0;
            letter-spacing: 1px;
            color: var(--accent);
        }

        header p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-top: 5px;
            font-weight: 300;
        }

        nav {
            background-color: #ffffff;
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        nav a {
            color: var(--text-main);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 400;
            transition: color 0.2s;
        }

        nav a:hover {
            color: #000000;
            font-weight: 500;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            font-weight: 500;
            font-size: 1.2rem;
            margin-bottom: 25px;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }

        /* หมวดหมู่แบบ Minimal Pills */
        .filter {
            text-align: center;
            margin-bottom: 40px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter a {
            display: inline-block;
            padding: 8px 20px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 30px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .filter a.active, .filter a:hover {
            background: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
        }

        /* ตารางสินค้าสไตล์สะอาดตา */
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 30px;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.04);
        }

        .card-img-wrapper {
            width: 100%;
            height: 220px;
            background: #f7f7f7;
            overflow: hidden;
        }

        .card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card:hover img {
            transform: scale(1.03);
        }

        .card-body {
            padding: 20px;
            text-align: left;
        }

        .card h3 {
            font-size: 1rem;
            font-weight: 500;
            margin: 0 0 8px 0;
            color: var(--accent);
        }

        .price {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 400;
            margin: 0;
        }

        .card-footer {
            padding: 0 20px 20px 20px;
        }

        .btn {
            background-color: var(--accent);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: block;
            text-align: center;
            font-size: 0.85rem;
            font-weight: 400;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #333333;
        }

        footer {
            background-color: #ffffff;
            color: var(--text-muted);
            text-align: center;
            padding: 30px;
            margin-top: 60px;
            border-top: 1px solid var(--border-color);
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <header>
        <h1>SNEAKER HUB</h1>
        <p>Curated Footwear & Streetwear Collection</p>
    </header>

    <nav>
        <a href="index.php">หน้าแรก</a>
        <a href="contact.php">ติดต่อเรา</a>
        <a href="privacy.php">นโยบายความเป็นส่วนตัว</a>
        <a href="admin.php">ระบบหลังบ้าน</a>
    </nav>

    <div class="container">
        
        <div class="section-title">เลือกดูตามหมวดหมู่สินค้า</div>
        <div class="filter">
            <a href="index.php" class="<?php echo $cat_id == 0 ? 'active':''; ?>">ทั้งหมด</a>
            <?php while($cat = $categories->fetch_assoc()): ?>
                <a href="index.php?category=<?php echo $cat['id']; ?>" class="<?php echo $cat_id == $cat['id'] ? 'active':''; ?>">
                    <?php echo $cat['name']; ?>
                </a>
            <?php endwhile; ?>
        </div>

        <div class="section-title" style="margin-top: 40px;">รายการสินค้าทั้งหมด</div>
        <div class="products">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <div class="card-img-wrapper">
                        <img src="<?php echo $row['image']; ?>" alt="รองเท้าผ้าใบ <?php echo $row['name']; ?>">
                    </div>
                    <div class="card-body">
                        <h3><?php echo $row['name']; ?></h3>
                        <p class="price"><?php echo number_format($row['price']); ?> THB</p>
                    </div>
                    <div class="card-footer">
                        <a href="checkout.php?id=<?php echo $row['id']; ?>" class="btn">สั่งซื้อสินค้า</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Sneaker Hub Project. All rights reserved.</p>
    </footer>
<style>
    .site-footer {
        background-color: #ffffff;
        border-top: 1px solid #eaeaea;
        color: #2b2b2b;
        padding: 50px 20px 20px;
        font-family: 'Prompt', sans-serif;
        margin-top: 60px;
    }
    .footer-container {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 30px;
        padding-bottom: 40px;
        border-bottom: 1px solid #eaeaea;
    }
    .footer-col h3 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #111111;
    }
    .footer-col p, .footer-col a {
        font-size: 0.85rem;
        color: #777777;
        text-decoration: none;
        display: block;
        margin-bottom: 8px;
        line-height: 1.5;
    }
    .footer-col a:hover {
        color: #111111;
    }
    .footer-bottom {
        max-width: 1100px;
        margin: 20px auto 0;
        text-align: center;
        font-size: 0.85rem;
        color: #111111;
        font-weight: 500;
    }
</style>

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-col">
            <h3>Sneaker Hub</h3>
            <p>คัดสรรรองเท้าผ้าใบคุณภาพดี ดีไซน์มินิมอล เพื่อทุกลุคการแต่งตัวของคุณ มั่นใจบริการและจัดส่งรวดเร็ว</p>
        </div>
        <div class="footer-col">
            <h3>เมนูด่วน</h3>
            <a href="index.php">หน้าแรกเว็บไซต์</a>
            <a href="contact.php">ติดต่อเรา</a>
            <a href="privacy.php">นโยบายความเป็นส่วนตัว (PDPA)</a>
            <a href="admin.php">ระบบหลังบ้าน (Admin)</a>
        </div>
        <div class="footer-col">
            <h3>หมวดหมู่สินค้า</h3>
            <p>รองเท้าผ้าใบชาย</p>
            <p>รองเท้าผ้าใบหญิง</p>
            <p>รองเท้าแฟชั่นมินิมอล</p>
            <p>รองเท้าวิ่งออกกำลังกาย</p>
            <p>อุปกรณ์และถุงเท้า</p>
        </div>
        <div class="footer-col">
            <h3>ติดต่อเรา</h3>
            <p><strong>ที่อยู่:</strong> 123/45 สุขุมวิท กรุงเทพฯ</p>
            <p><strong>โทร:</strong> 02-123-4567</p>
            <p><strong>อีเมล:</strong> support@sneakerhub.com</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>2026 SNEAKER HUB เวอร์ชั่น2.0.0 @MAPLE</p>
    </div>
</footer>
</body>
</html>