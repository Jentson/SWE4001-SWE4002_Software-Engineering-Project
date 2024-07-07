<?php
include '../database/db.php'; // 包含数据库连接文件

// 获取当前日期和时间
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

// 更新已经过期的时间表条目
$updateQuery = "
    UPDATE staff_timeschedule 
    SET book_avail = 'expired'
    WHERE book_avail = 'available'
    AND (schedule_date < '$currentDate' OR (schedule_date = '$currentDate' AND start_time < '$currentTime'))
";

if (!mysqli_query($con, $updateQuery)) {
    die('Error: ' . mysqli_error($con));
}

// 更新超过currentDate和end_time后10分钟还未完成的book_avail
$updateStaffScheduleQuery = "
    UPDATE staff_timeschedule 
    SET book_avail = 'incomplete'
    WHERE book_avail = 'booked'
    AND (schedule_date < '$currentDate' OR (schedule_date = '$currentDate' AND ADDTIME(end_time, '0:10:00') <= '$currentTime'))
";

if (mysqli_query($con, $updateStaffScheduleQuery)) {
    // 找到在staff_timeschedule表中更新为'incomplete'的time_id，并更新student_bookings表
    $updateIncompleteBookingsQuery = "
        UPDATE student_bookings sb
        JOIN (
            SELECT time_id, MAX(booking_id) AS max_booking_id
            FROM student_bookings
            WHERE status = 'booked'
            GROUP BY time_id
        ) latest_booking ON sb.booking_id = latest_booking.max_booking_id
        JOIN (
            SELECT time_id
            FROM staff_timeschedule
            WHERE book_avail = 'incomplete'
        ) sts ON sb.time_id = sts.time_id
        SET sb.status = 'incomplete'
    ";

    if (mysqli_query($con, $updateIncompleteBookingsQuery)) {
        // 插入到 appointment_history 表中，确保每个 time_id 只插入一次
        $insertHistoryQuery = "
            INSERT INTO appointment_history (time_id, student_id, student_name, schedule_date, start_time, end_time, modal, status, staff_id, reason, booking_timestamp)
            SELECT sb.time_id, sb.student_id, s.student_name, sb.schedule_date, sb.start_time, sb.end_time, sb.modal, sb.status, sb.staff_id, sb.reason, sb.booking_time
            FROM student_bookings sb
            JOIN student s ON sb.student_id = s.student_id
            LEFT JOIN appointment_history ah ON sb.time_id = ah.time_id
            WHERE sb.status = 'incomplete'
            AND ah.time_id IS NULL
            AND sb.booking_id IN (
                SELECT max_booking_id
                FROM (
                    SELECT MAX(booking_id) AS max_booking_id
                    FROM student_bookings
                    WHERE status = 'incomplete'
                    GROUP BY time_id
                ) subquery
            )
        ";
        if (!mysqli_query($con, $insertHistoryQuery)) {
            die('Error inserting into appointment_history: ' . mysqli_error($con));
        }
    } else {
        die('Error updating student_bookings: ' . mysqli_error($con));
    }
} else {
    die('Error updating staff_timeschedule: ' . mysqli_error($con));
}
?>
