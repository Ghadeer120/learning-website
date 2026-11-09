<?php
session_start();
include 'db.php';

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$course_stmt = $conn->prepare("SELECT * FROM course WHERE id = ?");
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course = $course_stmt->get_result()->fetch_assoc();

$majors_result = $conn->query("SELECT id, major_name FROM major ORDER BY major_name ASC");

$schedule_stmt = $conn->prepare("SELECT day_of_week, start_time, end_time FROM course_schedule WHERE course_id = ?");
$schedule_stmt->bind_param("i", $course_id);
$schedule_stmt->execute();
$schedule_result = $schedule_stmt->get_result();

$schedule = [];
$schedule_days = [];

while ($row = $schedule_result->fetch_assoc()) {
    $day = $row['day_of_week'];
    $schedule[$day] = [
        'start_time' => $row['start_time'],
        'end_time' => $row['end_time']
    ];
    $schedule_days[] = $day;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = trim($_POST['course_name']);
    $course_title = trim($_POST['course_title']);
    $description = trim($_POST['course_description']);
    $course_price = floatval($_POST['course_price']);
    $schedule_duration = trim($_POST['schedule_duration']);
    $major_id = intval($_POST['major_id']);
    $selected_days = $_POST['course_days'] ?? [];
    $start_times = $_POST['start_time'] ?? [];
    $end_times = $_POST['end_time'] ?? [];
$room_number = trim($_POST['room_number'] ?? '');

$duplicate_check = $conn->prepare("SELECT id FROM course WHERE room_number = ? AND id != ?");
$duplicate_check->bind_param("si", $room_number, $course_id);
$duplicate_check->execute();
$duplicate_result = $duplicate_check->get_result();

if ($duplicate_result->num_rows > 0) {
    echo "<p style='color:red; text-align:center;'>❌ This room number is already assigned to another session of this course.</p>";
    exit();
}

    $image_path = $course['image'];
    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $tmp_name = $_FILES['course_image']['tmp_name'];
        $filename = uniqid() . '_' . basename($_FILES['course_image']['name']);
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($tmp_name, $target_file)) {
            $image_path = $target_file;
        }
    }

    $room_number = trim($_POST['room_number'] ?? '');

$update_stmt = $conn->prepare("UPDATE course SET course_name = ?, title = ?, price = ?, schedule_duration = ?, major_id = ?, image = ?, description = ?, room_number = ? WHERE id = ?");
$update_stmt->bind_param("ssdsssssi", $course_name, $course_title, $course_price, $schedule_duration, $major_id, $image_path, $description, $room_number, $course_id);


    $update_stmt->execute();

    $delete_stmt = $conn->prepare("DELETE FROM course_schedule WHERE course_id = ?");
    $delete_stmt->bind_param("i", $course_id);
    $delete_stmt->execute();

    $grouped_days = [];
    foreach ($selected_days as $day) {
        $start = $start_times[$day] ?? '';
        $end = $end_times[$day] ?? '';
        if ($start && $end) {
            $key = "$start|$end";
            $grouped_days[$key][] = $day;
        }
    }
    function isRoomAvailable($conn, $room_number, $day_of_week, $start_time, $end_time, $course_id = null) {
    $query = "SELECT COUNT(*) as count 
              FROM course_schedule cs
              JOIN course c ON cs.course_id = c.id
              WHERE c.room_number = ? AND cs.day_of_week = ? 
              AND (
                  (cs.start_time < ? AND cs.end_time > ?) OR
                  (cs.start_time >= ? AND cs.start_time < ?)
              )";

    if ($course_id) {
        $query .= " AND c.id != ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $room_number, $day_of_week, $end_time, $start_time, $start_time, $end_time, $course_id);
    } else {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $room_number, $day_of_week, $end_time, $start_time, $start_time, $end_time);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['count'] == 0;
}

    foreach ($grouped_days as $key => $days) {
    list($start, $end) = explode('|', $key);
    foreach ($days as $day) {
        if (!isRoomAvailable($conn, $room_number, $day, $start, $end, $course_id)) {
            echo "<p style='color:red; text-align:center;'>❌ Room $room_number is already booked on $day at $start - $end.</p>";
            exit();
        }

        $insert_stmt = $conn->prepare("INSERT INTO course_schedule (course_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("isss", $course_id, $day, $start, $end);
        $insert_stmt->execute();
    }
}




$roomAvailable = isRoomAvailable($conn, $room_id, $day_of_week, $start_time, $end_time, $course_id);
if (!$roomAvailable) {
    echo "The selected room is already booked during this time slot.";
}

    header("Location: courses.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Course - EDU ADMIN Dashboard</title>
  <link rel="stylesheet" href="styles-editable.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="courses.css">
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-header">
        <h1 class="logo">EDU ADMIN</h1>
      </div>
      <div class="sidebar-menu">
        <nav>
  <a href="dashboard.php" class="menu-item"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
        <a href="students.php" class="menu-item"><i class="fas fa-user-graduate"></i><span>Students</span></a>
        <a href="admin teacher.php" class="menu-item "><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a>
          <a href="majors.php" class="menu-item "><i class="fas fa-layer-group"></i><span>Majors</span></a>

        <a href="courses.php" class="menu-item active"><i class="fas fa-book"></i><span>Courses</span></a>
        </nav>
      </div>
      <div class="sidebar-footer">
        <a href="#" class="menu-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Top Navigation -->
      <header class="top-nav">
        <div class="mobile-toggle"><i class="fas fa-bars"></i></div>
        <div class="search-container">
          <i class="fas fa-search"></i>
          <input type="search" placeholder="Search courses" class="search-input">
        </div>
        <div class="user-menu">
          <div class="notifications"><i class="fas fa-bell"></i></div>
          <div class="user-avatar">A</div>
        </div>
      </header>

      <!-- Edit Course Content -->
      <main class="dashboard-content">
        <div class="content-header">
          <h1 class="page-title">Edit Course</h1>
          <a href="courses.php" class="back-button"><i class="fas fa-arrow-left"></i>Back to Courses</a>
        </div>
        
        <div class="add-course-container">
          <div class="add-course-card">
            <div class="card-header">
              <h2>Course Information</h2>
            </div>
            <div class="card-body">
              <form class="add-course-form" method="post" enctype="multipart/form-data">
                <div class="form-row">
                  <div class="form-group">
                    <label for="course-title">Course Code</label>
                    <input type="text" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
                  </div>
                  <div class="form-group">
                    <label for="course-code">Course Title</label>
                    <input type="text" name="course_title" value="<?php echo htmlspecialchars($course['title']); ?>" required>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="credits">Price</label>
                    <input type="number" name="course_price" value="<?php echo htmlspecialchars($course['price']); ?>" required>
                  </div>
                  <div class="form-group">
                    <label for="schedule">Schedule</label>
                    <select name="schedule_duration" required>
                      <option value="4 Weeks" <?php if($course['schedule_duration'] == '4 Weeks') echo 'selected'; ?>>4 Weeks</option>
                      <option value="6 Weeks" <?php if($course['schedule_duration'] == '6 Weeks') echo 'selected'; ?>>6 Weeks</option>
                      <option value="8 Weeks" <?php if($course['schedule_duration'] == '8 Weeks') echo 'selected'; ?>>8 Weeks</option>
                      <option value="12 Weeks" <?php if($course['schedule_duration'] == '12 Weeks') echo 'selected'; ?>>12 Weeks</option>
                    </select>
                  </div>
                </div>
<?php
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
foreach ($days as $day):
?>
  <div class="form-group full-width">
    <label>
      <input type="checkbox" name="course_days[]" value="<?= $day ?>" <?= in_array($day, $schedule_days) ? 'checked' : '' ?>>
      <?= $day ?>
    </label>
    <div class="form-row">
      <div class="form-group">
        <label>Start Time</label>
        <input type="time" name="start_time[<?= $day ?>]" value="<?= isset($schedule[$day]) ? $schedule[$day]['start_time'] : '' ?>">
      </div>
      <div class="form-group">
        <label>End Time</label>
        <input type="time" name="end_time[<?= $day ?>]" value="<?= isset($schedule[$day]) ? $schedule[$day]['end_time'] : '' ?>">
      </div>
    </div>
  </div>
<?php endforeach; ?>


                <div class="form-group full-width">
                  <label for="schedule">Major</label>
                  <select name="major_id" required>
                    <option value="">Select Major</option>
                    <?php while ($major = $majors_result->fetch_assoc()): ?>
                      <option value="<?php echo $major['id']; ?>" <?php if($course['major_id'] == $major['id']) echo 'selected'; ?>><?php echo htmlspecialchars($major['major_name']); ?></option>
                    <?php endwhile; ?>
                  </select>
                </div>
<div class="form-group">
  <label for="room_number">Room Number</label>
  <input type="text" name="room_number" value="<?= htmlspecialchars($course['room_number'] ?? '') ?>" required>
</div>

                <div class="form-group full-width">
                  <label for="schedule">Upload Course Image</label>
                  <input type="file" name="course_image" accept="image/*">
                  <p>Current Image: <img src="<?php echo $course['image']; ?>" alt="Course Image" width="100"></p>
                </div>

                <div class="form-group full-width">
                  <label for="description">Description</label>
                  <textarea id="description" name="course_description" rows="4"><?php echo htmlspecialchars($course['description']); ?></textarea>
                </div>

                <div class="form-actions">
                  <button type="submit" name="submit" value="Update Course" class="add-course-btn">Update Course</button>
                </div>
              </form>
           <script>
  document.querySelector('.add-course-form').addEventListener('submit', function(e) {
    const selectedDays = document.querySelectorAll('input[name="course_days[]"]:checked');
    for (const checkbox of selectedDays) {
      const day = checkbox.value;
      const startInput = document.querySelector(`[name="start_time[${day}]"]`);
      const endInput = document.querySelector(`[name="end_time[${day}]"]`);
      if (startInput && endInput) {
        const start = startInput.value;
        const end = endInput.value;
        if (start && end) {
          const startTime = new Date(`1970-01-01T${start}:00`);
          const endTime = new Date(`1970-01-01T${end}:00`);
          const diff = (endTime - startTime) / (1000 * 60);
          if (diff < 30 || diff > 120) {
            alert(`Course time on ${day} must be between 30 minutes and 2 hours.`);
            e.preventDefault();
            return;
          }
        }
      }
    }
  });
</script>


            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  