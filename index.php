<?php include 'includes/header.php'; ?>

<!-- HERO -->
<section class="hero-premium">

    <div class="container text-center">

        <h1 class="hero-title mb-3">
    ⚡ Book Sports <span>Near You</span>
</h1>

        <p class="hero-subtitle mb-5">
            Discover premium grounds & coaching in Mumbai
        </p>

        <!-- SEARCH BOX -->
        <div class="search-box mx-auto">
            <form action="grounds.php" method="GET" class="row g-2">

                <div class="col-md-5">
                    <input type="text" name="location" class="form-input" placeholder="📍 Location (e.g. Andheri)">
                </div>

                <div class="col-md-4">
                    <select name="sport_id" class="form-input">
                        <option value="">All Sports</option>
                        <?php
                        $sports = $conn->query("SELECT * FROM sports");
                        while($s = $sports->fetch_assoc()):
                        ?>
                        <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-premium w-100">Search</button>
                </div>

            </form>
        </div>

    </div>
</section>

<hr class="my-5" style="opacity:0.1;">
<!-- SPORTS -->
<section class="sports-section">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">
    Explore <span style="color:#f97316;">Sports</span>
</h2>
            <a href="grounds.php" class="view-all">View All →</a>
        </div>

        <div class="row g-4">

            <?php
            $sports = $conn->query("SELECT * FROM sports");

            while($sport = $sports->fetch_assoc()):

                $name = strtolower($sport['name']);

                if($name == "cricket") 
                    $img = "https://images.unsplash.com/photo-1593341646782-e0b495cff86d";

                elseif($name == "football") 
                    $img = "https://images.unsplash.com/photo-1551958219-acbc608c6377";

                elseif($name == "badminton") 
                    $img = "https://images.unsplash.com/photo-1708312604109-16c0be9326cd?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8QkFETUlOVE9OfGVufDB8fDB8fHww";

                elseif($name == "tennis") 
                    $img = "https://images.unsplash.com/photo-1542144582-1ba00456b5e3";

                elseif($name == "basketball") 
                    $img = "https://images.unsplash.com/photo-1518063319789-7217e6706b04";

                elseif($name == "volleyball") 
                    $img = "https://images.unsplash.com/photo-1592656094267-764a45160876";

                elseif($name == "table tennis") 
                    $img = "https://images.unsplash.com/photo-1609710228159-0fa9bd7c0827";

                elseif($name == "swimming") 
                    $img = "https://images.unsplash.com/photo-1576610616656-d3aa5d1f4534?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fHN3aW1taW5nJTIwcG9vbHxlbnwwfHwwfHx8MA%3D%3D";

                else 
                    $img = "https://images.unsplash.com/photo-1517649763962-0c623066013b";
            ?>

            <div class="col-md-6 col-lg-4">
                <a href="grounds.php?sport_id=<?= $sport['id'] ?>" class="text-decoration-none">

                    <div class="sport-card-premium">

                        <img src="<?= $img ?>" alt="<?= $sport['name'] ?>">

                        <div class="overlay">
                            <div class="content">
                                <h4><?= $sport['name'] ?></h4>
                                <span>Explore Grounds →</span>
                            </div>
                        </div>

                    </div>

                </a>
            </div>

            <?php endwhile; ?>

        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>