<?php
$users = $db->getAllUserscores();
$score = array_column($users, 'score');
array_multisort($score, SORT_DESC, $users);
?>
<div class="container" id="dashboardContainer">
    <?php
        foreach($users as $user){
           echo "<p>username: " . $user["username"] . ", score: " . $user["score"] . "</p>";
        }
    ?>     
</div>
        

<script src="js/dashboard.js"></script>