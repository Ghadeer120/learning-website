<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$selected_courses = $_POST['course_ids'] ?? [];
$teacher_choices = $_POST['teacher_for'] ?? [];
$errors = [];

$already_selected_names = [];
$existing_names_stmt = $conn->prepare("
    SELECT c.course_name
    FROM enrollments e
    JOIN course c ON e.course_id = c.id
    WHERE e.user_id = ?
");
$existing_names_stmt->bind_param("i", $student_id);
$existing_names_stmt->execute();
$existing_result = $existing_names_stmt->get_result();
while ($row = $existing_result->fetch_assoc()) {
    $already_selected_names[] = strtolower(trim($row['course_name']));
}

$existing_major_id = null;
$major_check_stmt = $conn->prepare("
    SELECT DISTINCT c.major_id 
    FROM enrollments e 
    JOIN course c ON e.course_id = c.id 
    WHERE e.user_id = ?
");
$major_check_stmt->bind_param("i", $student_id);
$major_check_stmt->execute();
$major_result = $major_check_stmt->get_result();
if ($major_result->num_rows === 1) {
    $existing_major_id = $major_result->fetch_assoc()['major_id'];
} elseif ($major_result->num_rows > 1) {
    $errors[] = "❌ You are enrolled in courses from multiple majors, which is not allowed.";
}

$submitted_schedules = [];
$existing_schedule_stmt = $conn->prepare("
    SELECT cs.day_of_week, cs.start_time, cs.end_time
    FROM enrollments e
    JOIN course_schedule cs ON e.course_id = cs.course_id
    WHERE e.user_id = ?
");
$existing_schedule_stmt->bind_param("i", $student_id);
$existing_schedule_stmt->execute();
$schedule_result = $existing_schedule_stmt->get_result();
while ($row = $schedule_result->fetch_assoc()) {
    $submitted_schedules[] = [
        'day' => $row['day_of_week'],
        'start' => strtotime($row['start_time']),
        'end' => strtotime($row['end_time'])
    ];
}

foreach ($selected_courses as $course_id) {
    if (empty($teacher_choices[$course_id])) {
        $errors[] = "❌ Please select a teacher for course ID $course_id.";
        continue;
    }

    $info_stmt = $conn->prepare("
        SELECT c.course_name, c.major_id, cs.day_of_week, cs.start_time, cs.end_time
        FROM course c
        JOIN course_schedule cs ON c.id = cs.course_id
        WHERE c.id = ?
    ");
    $info_stmt->bind_param("i", $course_id);
    $info_stmt->execute();
    $schedules = $info_stmt->get_result();

    if ($schedules->num_rows === 0) {
        $errors[] = "❌ Schedule not found for course ID $course_id.";
        continue;
    }

    $row = $schedules->fetch_assoc();
    $major_id = $row['major_id'];
    $course_name = strtolower(trim($row['course_name']));

    if (in_array($course_name, $already_selected_names)) {
        $errors[] = "❌ You are already enrolled in or selected another version of '$course_name'. Choose only one.";
        continue;
    }

    $already_selected_names[] = $course_name;

    if ($existing_major_id && $major_id !== $existing_major_id) {
        $errors[] = "❌ Course ID $course_id is from a different major. You can only enroll in one major.";
        continue;
    }

    mysqli_data_seek($schedules, 0);
    while ($sched = $schedules->fetch_assoc()) {
        $day = $sched['day_of_week'];
        $start = strtotime($sched['start_time']);
        $end = strtotime($sched['end_time']);

        foreach ($submitted_schedules as $existing) {
            if (
                $existing['day'] === $day &&
                $start < $existing['end'] &&
                $end > $existing['start']
            ) {
                $errors[] = "❌ Time conflict on $day for course '$course_name'.";
                break 2;
            }
        }

        $submitted_schedules[] = ['day' => $day, 'start' => $start, 'end' => $end];
    }
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red; text-align:center;'>$error</p>";
    }
    exit();
}

$check_stmt = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
$insert_stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id, teacher_id) VALUES (?, ?, ?)");

foreach ($selected_courses as $course_id) {
    $teacher_id = $teacher_choices[$course_id];
    $check_stmt->bind_param("ii", $student_id, $course_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        $insert_stmt->bind_param("iii", $student_id, $course_id, $teacher_id);
        $insert_stmt->execute();
    } else {
        echo "<p style='color:orange; text-align:center;'>⚠️ Already registered for course ID $course_id.</p>";
    }
}

header("Location: my_courses.php");
exit();
?>
