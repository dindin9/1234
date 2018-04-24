<?php
    // Include all required files:
    // config - for configuration variables. They will be used later
    // functions - for functions
    // connect - for connect to db
    // session - for checking user in session
    require_once 'config.php';
    require_once 'functions.php';
    require_once 'connect.php';
    require_once 'session.php';

    // Updating state for the messages - check functions file for description
    update_user_messages($db, $_SESSION['user']);
    clear_user_alerts($db, $_SESSION['user']);
    // Include header html
    require_once 'header.php';
?>


<div class="content-wrapper">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Notifications</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>From</th>
                            <th>Date</th>
                            <th>Text</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Getting all messages for user
                        foreach(get_list_user_messages($db, $_SESSION['user'], 2) as $message) {
                            ?>
                            <tr>
                                <td><a href="/profile.php?id=<?= $message['from_id'] ?>"><?= $message['first_name'].' '.$message['last_name'] ?></a></td>
                                <td><?= $message['date'] ?></td>
                                <td><?= $message['text'] ?> </td>
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

<?php require_once 'footer.php' ?>



