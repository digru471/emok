<?php
require_once 'includes/config.php';

// Page title and other meta information
$pageTitle = "Our Team";
include 'includes/header.php';

// First, create team_members table if it doesn't exist
try {
    $checkTable = $pdo->query("SHOW TABLES LIKE 'team_members'");
    if ($checkTable->rowCount() == 0) {
        // Create the table
        $pdo->exec("CREATE TABLE IF NOT EXISTS team_members (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            role VARCHAR(100) NOT NULL,
            photo VARCHAR(255),
            display_order INT
        )");
        
        // Insert sample data
        $pdo->exec("INSERT INTO team_members (name, role, photo, display_order) VALUES
            ('John Doe', 'Teacher', 'uploads/team_members/default.jpg', 1),
            ('Jane Smith', 'Developer', 'uploads/team_members/default.jpg', 2),
            ('Michael Brown', 'Teacher', 'uploads/team_members/default.jpg', 3)
        ");
    }
    
    // Check if the table is empty
    $count = $pdo->query("SELECT COUNT(*) FROM team_members")->fetchColumn();
    if ($count == 0) {
        // Insert sample data
        $pdo->exec("INSERT INTO team_members (name, role, photo, display_order) VALUES
            ('John Doe', 'Teacher', 'uploads/team_members/default.jpg', 1),
            ('Jane Smith', 'Developer', 'uploads/team_members/default.jpg', 2),
            ('Michael Brown', 'Teacher', 'uploads/team_members/default.jpg', 3)
        ");
    }
    
    // Get all team members
    $developers = $pdo->query("SELECT * FROM team_members WHERE role='Developer' ORDER BY display_order, name")->fetchAll();
    $teachers = $pdo->query("SELECT * FROM team_members WHERE role='Teacher' ORDER BY display_order, name")->fetchAll();
    $others = $pdo->query("SELECT * FROM team_members WHERE role NOT IN ('Developer', 'Teacher') ORDER BY display_order, name")->fetchAll();
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database error: " . $e->getMessage() . "</div>";
    $developers = [];
    $teachers = [];
    $others = [];
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="page-title">Meet Our Team</h1>
            <p class="lead">The talented people behind our platform</p>
        </div>
    </div>

    <?php if(!empty($developers)): ?>
    <section class="team-section mb-5">
        <h2 class="section-title">Our Developers</h2>
        <div class="row">
            <?php foreach($developers as $member): ?>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="team-card">
                    <div class="card-header">
                        <div class="member-img">
                            <?php if(!empty($member['photo'])): ?>
                                <img src="<?php echo $member['photo']; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                            <?php else: ?>
                                <div class="no-photo">
                                    <span><?php echo strtoupper(substr($member['name'], 0, 2)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="member-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                        <div class="member-role"><?php echo htmlspecialchars($member['role']); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if(!empty($teachers)): ?>
    <section class="team-section mb-5">
        <h2 class="section-title">Our Teachers</h2>
        <div class="row">
            <?php foreach($teachers as $member): ?>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="team-card">
                    <div class="card-header">
                        <div class="member-img">
                            <?php if(!empty($member['photo'])): ?>
                                <img src="<?php echo $member['photo']; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                            <?php else: ?>
                                <div class="no-photo">
                                    <span><?php echo strtoupper(substr($member['name'], 0, 2)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="member-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                        <div class="member-role"><?php echo htmlspecialchars($member['role']); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if(!empty($others)): ?>
    <section class="team-section mb-5">
        <h2 class="section-title">Other Team Members</h2>
        <div class="row">
            <?php foreach($others as $member): ?>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="team-card">
                    <div class="card-header">
                        <div class="member-img">
                            <?php if(!empty($member['photo'])): ?>
                                <img src="<?php echo $member['photo']; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                            <?php else: ?>
                                <div class="no-photo">
                                    <span><?php echo strtoupper(substr($member['name'], 0, 2)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="member-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                        <div class="member-role"><?php echo htmlspecialchars($member['role']); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<style>
/* Team Page Styling */
.page-title {
    color: var(--brand-purple, #9c27b0);
    margin-bottom: 15px;
    font-weight: 600;
}

.section-title {
    color: var(--brand-purple, #9c27b0);
    font-size: 24px;
    margin-bottom: 30px;
    padding-bottom: 10px;
    border-bottom: 2px solid rgba(156, 39, 176, 0.2);
}

.team-card {
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.team-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.card-header {
    background: linear-gradient(135deg, #9c27b0 0%, #673ab7 100%);
    padding: 30px 20px;
    text-align: center;
}

.member-img {
    width: 140px;
    height: 140px;
    margin: 0 auto;
    border-radius: 50%;
    overflow: hidden;
    border: 5px solid rgba(255, 255, 255, 0.3);
}

.member-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-photo {
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-photo span {
    color: white;
    font-size: 36px;
    font-weight: 600;
}

.card-body {
    padding: 25px 20px;
    text-align: center;
}

.member-name {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.member-role {
    color: var(--brand-purple, #9c27b0);
    font-weight: 500;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

@media (max-width: 767px) {
    .member-img {
        width: 120px;
        height: 120px;
    }
}
</style>

<?php include 'includes/footer.php'; ?> 