<?php
require_once 'includes/database.php';
require_once 'includes/functions.php';
requireLogin();

$database = new Database();
$db = $database->getConnection();

// إحصائيات
$stats = [
    'employees' => 0,
    'departments' => 0,
    'present_today' => 0,
    'pending_leaves' => 0
];

// عدد الموظفين
$query = "SELECT COUNT(*) as count FROM employees WHERE status = 'active'";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['employees'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// عدد الأقسام
$query = "SELECT COUNT(*) as count FROM departments";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['departments'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// الحاضرون اليوم
$today = date('Y-m-d');
$query = "SELECT COUNT(*) as count FROM attendance WHERE date = :today AND status = 'present'";
$stmt = $db->prepare($query);
$stmt->bindParam(':today', $today);
$stmt->execute();
$stats['present_today'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// طلبات الإجازة المعلقة
$query = "SELECT COUNT(*) as count FROM leave_requests WHERE status = 'pending'";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['pending_leaves'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
?><!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرئيسية - نظام إدارة الموارد البشرية</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-box h3 {
            color: #333;
            margin-bottom: 10px;
        }
        .stat-box .number {
            font-size: 36px;
            color: #3498db;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>نظام إدارة الموارد البشرية</h1>
    </div>    
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="index.php">الرئيسية</a></li>
                <?php if (isHR()): ?>
                <li><a href="modules/employees/">الموظفون</a></li>
                <li><a href="modules/departments/">الأقسام</a></li>
                <li><a href="modules/attendance/">الحضور والانصراف</a></li>
                <li><a href="modules/leaves/">الإجازات</a></li>
                <li><a href="modules/payroll/">الرواتب</a></li>
                <li><a href="modules/organizational_structure/">الهيكل التنظيمي</a></li>
                <?php endif; ?>
                <li><a href="profile.php">الملف الشخصي</a></li>
                <li><a href="logout.php">خروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <h2>مرحباً <?php echo escape($_SESSION['username']); ?></h2>
            <p>مرحباً بك في نظام إدارة الموارد البشرية</p>
            
            <?php if (isHR()): ?>
            <div class="stats-container">
                <div class="stat-box">
                    <h3>إجمالي الموظفين</h3>
                    <div class="number"><?php echo $stats['employees']; ?></div>
                </div>
                
                <div class="stat-box">
                    <h3>عدد الأقسام</h3>
                    <div class="number"><?php echo $stats['departments']; ?></div>
                </div>
                
                <div class="stat-box">
                    <h3>الحاضرون اليوم</h3>
                    <div class="number"><?php echo $stats['present_today']; ?></div>
                </div>
                
                <div class="stat-box">
                    <h3>طلبات إجازة معلقة</h3>
                    <div class="number"><?php echo $stats['pending_leaves']; ?></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>