<?php include_once 'blocks/header.php'; ?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meistara konsultācijas</title>
    <link rel="stylesheet" type="text/css" href="/css/mastersconsultations.css">
    <link href='https://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <div class="content">
            <h1>Manas konsultācijas</h1>
            <div class="master-info">
                <div class="master-details">
                    <img src="<?php echo $master['mPhoto']; ?>" alt="<?php echo $master['mFullName']; ?>">
                    <h2><?php echo $master['mFullName']; ?></h2>
                    <p>E-pasts: <?php echo $master['mEmail']; ?></p>
                    <p>Darba laiks: <?php echo $master['workTimeOpen'] . ' - ' . $master['workTimeClose']; ?></p>
                </div>
            </div>

            <div class="consultations">
                <h2>Konsultāciju saraksts</h2>
                <?php if ($result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Datums</th>
                                <th>Laiks</th>
                                <th>Lietotāja vārds</th>
                                <th>Lietotāja e-pasts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['cons_date']; ?></td>
                                    <td><?php echo $row['cons_time']; ?></td>
                                    <td><?php echo $row['userName']; ?></td>
                                    <td><?php echo $row['userEmail']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Jums vēl nav ieplānotu konsultāciju.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
<script>
    const container = document.querySelector(".container");
    const primaryNav = document.querySelector(".nav__list");
    const toggleButton = document.querySelector(".nav-toggle");

    toggleButton.addEventListener("click", () => {
        const isExpanded = primaryNav.getAttribute("aria-expanded");
        primaryNav.setAttribute(
            "aria-expanded",
            isExpanded === "false" ? "true" : "false"
        );
    });

    container.addEventListener("click", (e) => {
        if (!primaryNav.contains(e.target) && !toggleButton.contains(e.target)) {
            primaryNav.setAttribute("aria-expanded", "false");
        }
    });
</script>
</html>
