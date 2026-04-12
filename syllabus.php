<?php
    $xml = simplexml_load_file('syllabus.xml');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coaching Syllabus | PlayArena</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-glass sticky-top mb-5">
  <div class="container">
    <a class="navbar-brand text-warning" href="index.php">PlayArena</a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link text-white" href="index.php">Back to Booking</a></li>
    </ul>
  </div>
</nav>

<div class="container">
    <div class="text-center mb-5">
        <h1 class="hero-title">Coaching Syllabus</h1>
        <p class="lead text-muted">A comprehensive guide to your athletic development.</p>
    </div>

    <div class="row">
        <?php if($xml): ?>
            <?php foreach($xml->sport as $sport): ?>
            <div class="col-md-4 mb-4">
                <div class="glass-card p-4 h-100">
                    <h3 class="text-primary mb-4"><?= htmlspecialchars($sport['name']) ?></h3>
                    
                    <?php foreach($sport->level as $level): ?>
                        <div class="mb-3">
                            <h5 class="text-warning"><?= htmlspecialchars($level['title']) ?></h5>
                            <ul class="text-muted">
                                <?php foreach($level->topic as $topic): ?>
                                    <li><?= htmlspecialchars($topic) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-danger">Unable to load syllabus XML data.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
