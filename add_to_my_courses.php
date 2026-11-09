<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["course_id"])) {
    $course_id = (int)$_POST["course_id"];

    $course_stmt = $conn->prepare("
        SELECT c.course_name, cs.day_of_week, cs.start_time, cs.end_time 
        FROM course c
        JOIN course_schedule cs ON c.id = cs.course_id
        WHERE c.id = ?
    ");
    $course_stmt->bind_param("i", $course_id);
    $course_stmt->execute();
    $result = $course_stmt->get_result();

    if ($result->num_rows === 0) {
        echo "❌ Invalid course selected.";
        exit();
    }

    $course_schedules = [];
    $course_name = null;
    while ($row = $result->fetch_assoc()) {
        $course_name = $row["course_name"];
        $course_schedules[] = [
            "day" => $row["day_of_week"],
            "start" => strtotime($row["start_time"]),
            "end" => strtotime($row["end_time"])
        ];
    }

    $check_stmt = $conn->prepare("SELECT id FROM teacher_courses WHERE teacher_id = ? AND course_id = ?");
    $check_stmt->bind_param("ii", $teacher_id, $course_id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        echo "⚠️ You are already assigned to \"$course_name\".";
        exit();
    }

    $existing_stmt = $conn->prepare("
        SELECT c.course_name, cs.day_of_week, cs.start_time, cs.end_time
        FROM teacher_courses tc
        JOIN course c ON tc.course_id = c.id
        JOIN course_schedule cs ON c.id = cs.course_id
        WHERE tc.teacher_id = ?
    ");
    $existing_stmt->bind_param("i", $teacher_id);
    $existing_stmt->execute();
    $existing_result = $existing_stmt->get_result();

    while ($existing = $existing_result->fetch_assoc()) {
        foreach ($course_schedules as $new) {
            if ($existing['day_of_week'] === $new['day']) {
                $existing_start = strtotime($existing['start_time']);
                $existing_end = strtotime($existing['end_time']);

                if ($new['start'] < $existing_end && $new['end'] > $existing_start) {
                    echo "❌ Conflict: Already teaching \"{$existing['course_name']}\" on {$existing['day_of_week']} from " .
                         date("g:i A", $existing_start) . " to " . date("g:i A", $existing_end) . ".";
                    exit();
                }
            }
        }
    }

    $insert_stmt = $conn->prepare("INSERT INTO teacher_courses (teacher_id, course_id) VALUES (?, ?)");
    $insert_stmt->bind_param("ii", $teacher_id, $course_id);
    if ($insert_stmt->execute()) {
        echo "✅ Successfully added \"$course_name\" to your courses.";
    } else {
        echo "❌ Failed to assign course.";
    }
} else {
    echo "❌ Invalid request.";
}
?>
<br><br>
<a href="teacher_courses2.php">⬅️ Back to Courses</a>
