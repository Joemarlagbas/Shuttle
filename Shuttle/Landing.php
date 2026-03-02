

<?php
$conn = new mysqli("localhost","root","","shuttle_db");
if($conn->connect_error){
  http_response_code(500);
  exit("DB Error");
}


/* ===== HELPERS ===== */
function content($conn, $section)
{
  $q = $conn->prepare("SELECT title,content FROM site_content WHERE section=? LIMIT 1");
  $q->bind_param("s", $section);
  $q->execute();
  return $q->get_result()->fetch_assoc();
}

function image($conn, $section)
{
  $q = $conn->prepare("SELECT image FROM site_images WHERE section=? LIMIT 1");
  $q->bind_param("s", $section);
  $q->execute();
  return $q->get_result()->fetch_assoc();
}

/* ===== FETCH DATA ===== */
$home     = content($conn, 'home');
$about    = content($conn, 'about');
$tracking = content($conn, 'tracking');

$aboutImg = image($conn, 'about');
$schedule = $conn->query("SELECT * FROM schedule");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Missiah Highschool Shuttle Tracking</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      padding-top: 70px;
      background: #f8f9fa
    }

    nav,
    footer {
      background: #004080
    }

    .navbar-brand,
    .nav-link {
      color: #fff !important
    }

    .nav-link.active {
      border-bottom: 3px solid #4da3ff
    }

    .section-title {
      font-weight: 600;
      margin-bottom: 1.5rem
    }

    footer {
      color: #fff;
      text-align: center;
      padding: 1rem
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg fixed-top shadow">
    <div class="container">
      <a class="navbar-brand" href="#">My Messiah School of Cavite</a>
      <div class="collapse navbar-collapse show">
        <ul class="navbar-nav ms-auto">
          <li><a class="nav-link active" href="#home">Home</a></li>
          <li><a class="nav-link" href="#about">About School</a></li>
          <li><a class="nav-link" href="#schedule">Shuttle Schedule</a></li>
          <li><a class="nav-link" href="#tracking">How to Track</a></li>
          <li><a class="nav-link" href="#contact">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HOME -->
  <section id="home" class="text-center py-5 bg-light">
    <div class="container">
      <h1 class="display-4 fw-bold"><?= $home['title'] ?></h1>
      <p class="lead mt-3"><?= nl2br($home['content']) ?></p>
      <a href="#tracking" class="btn btn-lg mt-3" style="background:#004080;color:#fff">
        Start Tracking Now
      </a>
    </div>
  </section>

  <!-- ABOUT -->
  <section id="about" class="container py-5">
    <h2 class="section-title"></h2>
    <div class="row align-items-center">

      <div class="col-md-6">
        <img src="uploads/<?= $aboutImg['image'] ?>"
          class="img-fluid rounded shadow" width="60%">
      </div>

      <div class="col-md-6 mt-4 mt-md-0">
        <p>Missiah Highschool is committed to providing quality education and
          a safe, friendly environment for our students. Our shuttle service
          helps students commute comfortably and on time.</p>

        <p><?= nl2br($about['content']) ?></p>


      </div>

    </div>
  </section>

  <!-- SCHEDULE -->
  <section id="schedule" class="bg-white py-5">
    <div class="container">
      <h2 class="section-title text-center">Shuttle Schedule</h2>

      <div class="table-responsive shadow rounded">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-primary">
            <tr>
              <th>Route</th>
              <th>First Trip</th>
              <th>Last Trip</th>
              <th>Frequency</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $schedule->fetch_assoc()): ?>
              <tr>
                <td><?= $row['route'] ?></td>
                <td><?= $row['first_trip'] ?></td>
                <td><?= $row['last_trip'] ?></td>
                <td><?= $row['frequency'] ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </div>
  </section>

  <!-- TRACKING -->
  <section id="tracking" class="container py-5">
    <h2 class="section-title">How to Track Your Shuttle</h2>
    <ol class="fs-5">
      <li>Open this website on your device.</li>
      <li>Allow location permission if you want to send your current location.</li>
      <li>Check the map section to see the real-time shuttle location.</li>
      <li>Use the search box to enter a place name and see if the shuttle is nearby.</li>
    </ol>

    <div class="mt-4 text-center">
      <a href="Tracking.php" class="btn" style="background:#004080;color:#fff">
        Go to Tracker
      </a>
    </div>
  </section>

  <!-- CONTACT -->
  <section id="contact" class="bg-white py-5">
    <div class="container">
      <h2 class="section-title text-center">Contact Us</h2>

      <form action="save_contact.php" method="POST" class="col-md-6 mx-auto">
        <input class="form-control mb-3" name="name" placeholder="Full Name" required>
        <input class="form-control mb-3" name="email" placeholder="Email" required>
        <textarea class="form-control mb-3" name="message" rows="4" required></textarea>
        <button class="btn w-100" style="background:#004080;color:#fff">Send Message</button>
      </form>

    </div>
  </section>

  <footer>
    &copy; 2026 Missiah Highschool Shuttle Tracker | All rights reserved.
  </footer>

  <script>
    const links = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('section');
    window.addEventListener('scroll', () => {
      let current = '';
      sections.forEach(sec => {
        if (pageYOffset >= sec.offsetTop - 120) current = sec.id;
      });
      links.forEach(a => {
        a.classList.toggle('active', a.getAttribute('href') === '#' + current);
      });
    });
  </script>

</body>

</html>