<?php
function logAction($con, $userId, $actionType, $leaveId) {
    $query = "INSERT INTO leave_actions (user_id, action_type, leave_id) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $userId, $actionType, $leaveId);
    mysqli_stmt_execute($stmt);
}

function canPerformAction($con, $userId, $actionType, $limit, $interval) {
    $query = "SELECT COUNT(*) as action_count FROM leave_actions 
              WHERE user_id = ? 
              AND action_type = ? 
              AND action_time > DATE_SUB(NOW(), INTERVAL ? MINUTE)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $userId, $actionType, $interval);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['action_count'] < $limit;
}
?>