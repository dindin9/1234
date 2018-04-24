<?php
// Include all required files:
// config - for configuration variables. They will be used later
// functions - for functions
// connect - for connect to db
// session - for checking user in session
// header - to show header html.
require_once 'config.php';
require_once 'functions.php';
require_once 'connect.php';
require_once 'session.php';
require_once 'header.php';

// Fetching following
$stmt = $db->prepare('SELECT DISTINCT follow_id FROM users_followers WHERE user_id = ?');
$stmt->execute(array($_SESSION['user']['id'])); 
$rows = $stmt->fetchAll(PDO::FETCH_COLUMN);

// if user has following
if(count($rows) > 0) {
    $rows[] = $_SESSION['user']['id'];
    $ids = implode(',', $rows);
    $stmt = $db->query("SELECT projects.*, users.last_name, users.first_name, 
        researches.start as research_start_date, 
        researches.end as research_end_date, 
        COUNT(project_recomendations.id) as recomend_count
        FROM projects
        INNER JOIN researches on projects.research_id = researches.id
        INNER JOIN users on projects.user_id = users.id 
        LEFT JOIN project_recomendations on project_recomendations.project_id = projects.id
        WHERE projects.user_id IN($ids) GROUP BY projects.id");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // If he has not them
} else {
    $stmt = $db->prepare('SELECT projects.*, users.last_name, users.first_name, 
        researches.start as research_start_date, 
        researches.end as research_end_date, 
        COUNT(project_recomendations.id) as recomend_count
        FROM projects
        INNER JOIN researches on projects.research_id = researches.id
        INNER JOIN users on projects.user_id = users.id 
        LEFT JOIN project_recomendations on project_recomendations.project_id = projects.id
        WHERE projects.user_id  = ? GROUP BY projects.id');
    $stmt->execute(array($_SESSION['user']['id']));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>
<div class="content-wrapper">
    <div class="row mb-2">
        <div class="col-lg-12 mb-4">
            <form action="/projects.php" method="get">
                <div class="input-group">
                    <input type="text" name="name" class="form-control p-input" />
                    <input type="hidden" name="action" value="add" class="form-control p-input" />
                    <span class="input-group-btn">
                        <button class="btn btn-success btn-lg" type="submit">Add</button>
                      </span>
                </div>
            </form>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Projects</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Date published</th>
                                <th>Research start date</th>
                                <th>Research end date</th>
                                <th>Author name</th>
                                <th>Recomandations</th>
                                <th>Number or reads</th>
                                <th>Reccomend</th>
                                <th>Hide/Show</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($rows as $project) {
                                ?>
                                <tr>
                                    <td <?= hide_project($project); ?>><a href="/projects.php?action=view&id=<?=$project['id']?>"><?= $project['name'] ?></a></td>
                                    <td <?= hide_project($project); ?>><?= $project['description'] ?></td>
                                    <td <?= hide_project($project); ?>><?= $project['date'] ?></td>
                                    <td <?= hide_project($project); ?>><?= $project['research_start_date'] ?></td>
                                    <td <?= hide_project($project); ?>><?= $project['research_end_date'] ?></td>
                                    <td <?= hide_project($project); ?>><?= $project['first_name'].' '.$project['last_name'] ?></td>
                                    <td <?= hide_project($project); ?>><?= isset($project['recomend_count']) ? $project['recomend_count'] : 0 ?></td>
                                    <td <?= hide_project($project); ?>><?= $project['reads'] ?></td>
                                    <td <?= hide_project($project); ?>>
                                        <?php if(i_can_reccomend($db, $_SESSION['user'], $project['id'])) {?>
                                            <a href="/projects.php?action=reccomend&id=<?=$project['id']?>" class="btn btn-success">Reccomend</a>
                                        <?php } else { ?>
                                            <a href="/projects.php?action=unreccomend&id=<?=$project['id']?>" class="btn btn-danger">Unrecomend</a>
                                        <?php }?>
                                    </td>
                                    <td>
                                        <?php
                                            if((bool) $project['is_hidden']) {
                                                echo '<a class="btn btn-success" href="/projects.php?action=show&id='.$project['id'].'">Show</a>';
                                            } else {
                                                echo '<a class="btn btn-success" href="/projects.php?action=hide&id='.$project['id'].'">Hide</a>';
                                            }
                                        ?>

                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    setTimeout(function(){
        window.location.reload(1);
    }, 20000);
</script>


<!-- footer content -->
<?php require_once 'footer.php' ?>
