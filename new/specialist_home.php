<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "", "speech_therapy");
$specialist_id = $_SESSION['user_id'];

// احصائيات
// 1. عدد الجلسات
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM sessions WHERE specialist_id = ?");
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
$total_sessions = $result->fetch_assoc()['total'] ?? 0;

// 2. عدد الاستشارات
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM consultations WHERE specialist_id = ?");
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
$total_consultations = $result->fetch_assoc()['total'] ?? 0;

// 3. عدد الأطفال
$stmt = $conn->prepare("SELECT COUNT(DISTINCT c.id) AS total 
                        FROM consultations cons 
                        JOIN parents p ON cons.parent_id = p.user_id 
                        JOIN children c ON c.parent_id = p.user_id 
                        WHERE cons.specialist_id = ?");
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
$total_children = $result->fetch_assoc()['total'] ?? 0;
?>

<div style="background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
    <h2 style="color: #4e73df;">مرحبًا بك في لوحة تحكم الأخصائي</h2>
    <p>يمكنك من هنا متابعة الجلسات، الملفات، والاستشارات.</p>

    <div style="display: flex; gap: 20px; margin-top: 30px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px; background-color: #f1f3f9; padding: 20px; border-radius: 10px;">
            <h4>الجلسات المحجوزة</h4>
            <p style="font-size: 24px; color: #4e73df;"><?php echo $total_sessions; ?></p>
        </div>

        <div style="flex: 1; min-width: 200px; background-color: #f1f3f9; padding: 20px; border-radius: 10px;">
            <h4>عدد الاستشارات</h4>
            <p style="font-size: 24px; color: #4e73df;"><?php echo $total_consultations; ?></p>
        </div>

        
</div>
