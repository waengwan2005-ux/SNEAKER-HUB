<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ติดต่อเรา - Sneaker Hub</title>
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
            font-size: 1.8rem;
            margin: 0;
            color: var(--accent);
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            background: var(--card-bg);
            padding: 40px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--accent);
        }

        h2 {
            font-size: 1.2rem;
            font-weight: 500;
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--accent);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }

        p {
            margin: 12px 0;
            font-size: 0.95rem;
            color: var(--text-main);
        }

        strong {
            font-weight: 500;
            color: var(--accent);
        }

        /* ดีไซน์กรอบแผนที่ */
        .map-container {
            margin-top: 25px;
            width: 100%;
            height: 300px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
</head>
<body>

    <header>
        <h1>CONTACT US</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 5px; font-weight: 300;">ช่องทางการติดต่อและแผนที่ร้านค้า</p>
    </header>

    <div class="container">
        <a href="index.php" class="back-link">&larr; กลับหน้าหลัก</a>
        <h2>ข้อมูลการติดต่อ</h2>
        
        <p><strong>ชื่อร้าน:</strong> Sneaker Hub Online Store</p>
        <p><strong>ที่อยู่:</strong> 123/45 ถนนสุขุมวิท แขวงคลองเตย เขตคลองเตย กรุงเทพมหานคร 10110</p>
        <p><strong>เบอร์โทรศัพท์:</strong> 02-123-4567 / 089-123-4567</p>
        <p><strong>อีเมล:</strong> support@sneakerhub.com</p>
        <p><strong>เวลาทำการ:</strong> เปิดบริการทุกวัน จันทร์ - อาทิตย์ เวลา 09.00 น. - 20.00 น.</p>

        <h2 style="margin-top: 35px;">แผนที่ตั้งร้านค้า</h2>
        <div class="map-container">
            <!-- Google Maps Embed -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.644483777598!2d100.5600674750904!3d13.729904986646547!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29ee1da6ca3b7%3A0xc38d011bf1917f54!2sSukhumvit%20Rd%2C%20Bangkok!5e0!3m2!1sen!2sth!4v1700000000000!5m2!1sen!2sth" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>

</body>
</html>