<?php 
session_start();
include 'db.php';

$major_stmt = $conn->prepare("SELECT id, major_name FROM major");
$major_stmt->execute();
$majors_result = $major_stmt->get_result();

if (isset($_POST['submit'])) {
    $course_name = trim($_POST['course_name']);
    $course_title = trim($_POST['course_title']);
    $course_description = trim($_POST['course_description']);
    $selected_days = $_POST['days'] ?? [];
    $start_times = $_POST['start_time'] ?? [];
    $end_times = $_POST['end_time'] ?? [];
    $course_price = floatval($_POST['course_price']);
    $schedule_duration = trim($_POST['schedule_duration']);
    $major_id = intval($_POST['major_id']);
        $room_number = $_POST['room_number']; 


    if (empty($course_name) || empty($course_title) || empty($course_description) ||
        empty($selected_days) || $course_price <= 0 || empty($schedule_duration) || $major_id <= 0) {
        die("❌ Invalid input. Please check your data.");
    }
$room_number = trim($_POST['room_number']); 

if (empty($course_name) || empty($course_title) || empty($course_description) ||
    empty($selected_days) || $course_price <= 0 || empty($schedule_duration) || $major_id <= 0 || empty($room_number)) {
    die("❌ Invalid input. Please check your data.");
}

    $image_path = '';
    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] == 0) {
        $image_name = uniqid() . '_' . basename($_FILES['course_image']['name']);
        $image_tmp = $_FILES['course_image']['tmp_name'];
        $image_destination = 'uploads/courses/' . $image_name;

        if (move_uploaded_file($image_tmp, $image_destination)) {
            $image_path = $image_destination;
        } else {
            die("❌ Error uploading image.");
        }
    }

    $grouped_days = [];
    foreach ($selected_days as $day) {
        $start = trim($start_times[$day] ?? '');
        $end = trim($end_times[$day] ?? '');

        if ($start && $end) {
            $key = "$start|$end";
            $grouped_days[$key][] = $day;
        }
    }

    foreach ($grouped_days as $time_key => $days) {
        list($start_time, $end_time) = explode('|', $time_key);

$stmt = $conn->prepare("INSERT INTO course (course_name, title, description, price, image, schedule_duration, major_id, room_number) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssdssis", $course_name, $course_title, $course_description, $course_price, $image_path, $schedule_duration, $major_id, $room_number);


        if ($stmt->execute()) {
            $course_id = $stmt->insert_id;
            $schedule_stmt = $conn->prepare("INSERT INTO course_schedule (course_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)");

            foreach ($days as $day) {
                $schedule_stmt->bind_param("isss", $course_id, $day, $start_time, $end_time);
                $schedule_stmt->execute();
            }
        } else {
            echo "<p style='color:red;'>❌ Error adding course: " . $stmt->error . "</p>";
        }
    }

    echo "<p style='color:green;text-align:center;'>✅ Course(s) added successfully.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Course - EDU ADMIN Dashboard</title>
  <link rel="stylesheet" href="styles-editable.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="courses.css">
</head>
<body>
  <div class="dashboard-container">
    <div class="sidebar">
      <div class="sidebar-header"><h1 class="logo">EDU ADMIN</h1></div>
      <div class="sidebar-menu">
        <nav>
          <a href="dashboard.php" class="menu-item"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
          <a href="students.php" class="menu-item"><i class="fas fa-user-graduate"></i><span>Students</span></a>
          <a href="admin teacher.php" class="menu-item"><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a>
          <a href="majors.php" class="menu-item"><i class="fas fa-layer-group"></i><span>Majors</span></a>
          <a href="courses.php" class="menu-item active"><i class="fas fa-book"></i><span>Courses</span></a>
        </nav>
      </div>
      <div class="sidebar-footer">
        <a href="#" class="menu-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
      </div>
    </div>

    <div class="main-content">
      <header class="top-nav">
        <div class="mobile-toggle"><i class="fas fa-bars"></i></div>
        <div class="search-container"><i class="fas fa-search"></i><input type="search" placeholder="Search courses" class="search-input"></div>
        <div class="user-menu"><div class="notifications"><i class="fas fa-bell"></i></div><div class="user-avatar">A</div></div>
      </header>

      <main class="dashboard-content">
        <div class="content-header">
          <h1 class="page-title">Add New Course</h1>
          <a href="courses.php" class="back-button"><i class="fas fa-arrow-left"></i>Back to Courses</a>
        </div>
        
        <div class="add-course-container">
          <div class="add-course-card">
            <div class="card-header"><h2>Course Information</h2></div>
            <div class="card-body">
              <form class="add-course-form" method="post" enctype="multipart/form-data">
                <div class="form-row">
                  <div class="form-group">
                    <label>Course Code</label>
                    <input type="text" name="course_name" placeholder="Enter course code" required>
                  </div>
                  <div class="form-group">
                    <label>Course Title</label>
                    <input type="text" name="course_title" placeholder="Enter course title" required>
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="course_price" placeholder="e.g., 100" required>
                  </div>
                  <div class="form-group">
                    <label>Schedule Duration</label>
                    <select name="schedule_duration" required>
                      <option value="4 Weeks">4 Weeks</option>
                      <option value="6 Weeks">6 Weeks</option>
                      <option value="8 Weeks">8 Weeks</option>
                      <option value="12 Weeks">12 Weeks</option>
                    </select>
                  </div>
                </div>

                <div class="form-group full-width">
                  <label>Days of the Week with Times</label>
                  <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                    <?php foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day): ?>
                      <div style="border:1px solid #ccc; padding:10px; border-radius:5px; flex:1 0 220px;">
                        <label><input type="checkbox" name="days[]" value="<?= $day ?>"> <?= $day ?></label><br>
                        <label>Start: <input type="time" name="start_time[<?= $day ?>]"></label><br>
                        <label>End: <input type="time" name="end_time[<?= $day ?>]"></label>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>

                <div class="form-group full-width">
                  <label>Major</label>
                  <select name="major_id" required>
                    <option value="">Select Major</option>
                    <?php while ($major = $majors_result->fetch_assoc()): ?>
                      <option value="<?= $major['id'] ?>"><?= htmlspecialchars($major['major_name']) ?></option>
                    <?php endwhile; ?>
                  </select>
                </div>
<div class="form-group full-width">
  <label>Room Number</label>
  <input type="text" name="room_number" placeholder="Enter room number" required>
</div>

                <div class="form-group full-width">
                  <label>Course Image</label>
                  <input type="file" name="course_image" accept="image/*" required>
                </div>

                <div class="form-group full-width">
                  <label>Description</label>
                  <textarea name="course_description" rows="4" placeholder="Enter course description"></textarea>
                </div>

                <div class="form-actions">
                  <button type="submit" name="submit" value="Add Course" class="add-course-btn">Add Course</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    document.querySelector('.add-course-form').addEventListener('submit', function(e) {
      const checked = document.querySelectorAll('input[name="days[]"]:checked');
      for (const box of checked) {
        const day = box.value;
        const start = document.querySelector(`[name="start_time[${day}]"]`).value;
        const end = document.querySelector(`[name="end_time[${day}]"]`).value;

        if (start && end) {
          const s = new Date(`1970-01-01T${start}:00`);
          const e = new Date(`1970-01-01T${end}:00`);
          const diff = (e - s) / 60000;
          if (diff < 30 || diff > 120) {
            alert(`${day}: Time must be between 30 minutes and 2 hours.`);
            e.preventDefault();
            return;
          }
        }
      }
    });
  </script>
</body>
</html>
