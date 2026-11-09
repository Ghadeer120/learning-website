<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];

$course_q = $conn->prepare("
    SELECT 
        c.id, 
        c.title, 
        GROUP_CONCAT(CONCAT(cs.day_of_week, ' ', DATE_FORMAT(cs.start_time, '%h:%i %p'), '-', DATE_FORMAT(cs.end_time, '%h:%i %p')) 
        ORDER BY FIELD(cs.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') 
        SEPARATOR ' | ') AS schedule
    FROM teacher_courses tc 
    JOIN course c ON c.id = tc.course_id 
    LEFT JOIN course_schedule cs ON cs.course_id = c.id
    WHERE tc.teacher_id = ?
    GROUP BY c.id
");

$course_q->bind_param("i", $teacher_id);
$course_q->execute();
$teacher_courses = $course_q->get_result();

$selected_course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grade Students</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body { padding: 30px; font-family: Arial; }
        h2 { margin-bottom: 20px; }
        table { width: 100%; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ccc; }
        input[type="text"] { width: 80px; }
        textarea { width: 100%; }
    </style>
</head>
<body>

<h2>Grade Students</h2>

<?php if ($success_message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($success_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($error_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="get">
    <label>Select Course:</label>
    <select name="course_id" onchange="this.form.submit()" class="form-select" style="width:auto;">
        <option value="">-- Choose Course --</option>
        <?php while ($course = $teacher_courses->fetch_assoc()): ?>
<option value="<?= $course['id'] ?>" <?= ($course['id'] == $selected_course_id ? 'selected' : '') ?>>
    <?= htmlspecialchars($course['title']) ?> — <?= htmlspecialchars($course['schedule']) ?>
</option>

        <?php endwhile; ?>
    </select>
</form>

<?php if ($selected_course_id): ?>
    <form method="post" action="submit_grades.php">
        <input type="hidden" name="course_id" value="<?= $selected_course_id ?>">

        <table class="table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Email</th>
                    <th>Grade</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $students = $conn->prepare("
SELECT u.id AS student_id, u.first_name, u.last_name, u.email
                    FROM enrollments e
                    JOIN users u ON u.id = e.user_id
                    WHERE e.course_id = ? AND e.teacher_id = ?
                ");
                $students->bind_param("ii", $selected_course_id, $teacher_id);
                $students->execute();
                $results = $students->get_result();

                if ($results->num_rows > 0):
                    while ($row = $results->fetch_assoc()):
                        $student_id = $row['student_id'];

                        $grade_stmt = $conn->prepare("
                            SELECT grade, comment 
                            FROM grades 
                            WHERE student_id = ? AND course_id = ? AND teacher_id = ?
                        ");
                        $grade_stmt->bind_param("iii", $student_id, $selected_course_id, $teacher_id);
                        $grade_stmt->execute();
                        $grade_result = $grade_stmt->get_result()->fetch_assoc();

                        $grade = $grade_result['grade'] ?? '';
                        $comment = $grade_result['comment'] ?? '';
                        $is_graded = $grade_result ? true : false;
                ?>
              <tr>
    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td>
       <input type="number" name="grades[<?= $student_id ?>][grade]" 
       value="<?= htmlspecialchars($grade) ?>" 
       class="form-control" 
       <?= $is_graded ? 'readonly' : 'required' ?> 
       id="grade-<?= $student_id ?>" 
       min="0" max="100">

    </td>
    <td>
        <textarea name="grades[<?= $student_id ?>][comment]" 
                  rows="2" 
                  class="form-control" 
                  <?= $is_graded ? 'readonly' : '' ?>
                  id="comment-<?= $student_id ?>"><?= htmlspecialchars($comment) ?></textarea>
    </td>
    <td>
        <?php if ($is_graded): ?>
            <button type="button" class="btn btn-warning btn-sm" onclick="enableEdit(<?= $student_id ?>)">Edit</button>
        <?php endif; ?>
    </td>
</tr>

                <?php 
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="4" class="text-center">No students enrolled in this course under your supervision.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <button class="btn btn-primary" type="submit">Submit Grades</button>
    </form>
<?php endif; ?>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
function enableEdit(studentId) {
    document.getElementById('grade-' + studentId).removeAttribute('readonly');
    document.getElementById('comment-' + studentId).removeAttribute('readonly');
}
</script>
<script>
document.querySelector("form[action='submit_grades.php']").addEventListener("submit", function(e) {
    let valid = true;
    document.querySelectorAll("input[type='number']").forEach(input => {
        const value = parseFloat(input.value);
        if (value > 100 || value < 0) {
            alert("❌ Grade must be between 0 and 100 for student ID " + input.id.replace('grade-', ''));
            valid = false;
        }
    });
    if (!valid) e.preventDefault();
});
</script>

</body>
</html>
