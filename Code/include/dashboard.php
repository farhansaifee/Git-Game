<?php
$user = $db->getUser($_SESSION["user"]["ID"]);
$challenges = $db->getAllChallenges();

//$ldap->connect("ldap.forumsys.com");
//$ldap->bind("cn=read-only-admin,dc=example,dc=com", "password");
//$ldap->bind("uid=euler,dc=example,dc=com", "password");


foreach($challenges as $challenge)
?>
        <div class="container" id="dashboardContainer">
            <?php
            $index = 1;
            foreach($challenges as $challenge){
                echo "<div class='card_ch' id='card{$index}'>
                <div>{$challenge["name"]}</div>
                    </div>";
            $index++;
            }
            ?>
        </div>
        <?php
        $index = 1;
        //show all the challenges from the DB
        foreach($challenges as $challenge){
            echo "
            <div class='container' id='Challenge_$index'>
                <div class='card_chal'>
                    <div>{$challenge["name"]}</div>
                    <div id='terminal{$index}'></div>
                    <div class='challenge-buttons'>
                    <div class='total-tasks' id='total_tasks_{$challenge["id"]}'></div>
                    <button class='back-button' id='back_button$index'><-Go Back</button>
                    <button class='back-button' id='start_over_button$index'>Start over</button>
                    </div>
                    <div id='progress_{$challenge["id"]}'></div>
                </div>  
            </div>";
        $index++;
        }
        // save the num of challenges in an input tag
        // retrieve later in the JS side
        echo "<input type='hidden' id='totalNumOfChallenges' data-num='{$index}'  />"
        ?>




<div id="window">
  <div class="terminal-new">
    <p class="command"> Console will start shortly</p>
    <p class="log">
      <span>
        <br />
        remote: Reusing existing pack: 1857, done.<br />
        remote: Total 1857 (delta 0), reused 0 (delta 0)<br />
        Receiving objects: 100% (1857/1857), 374.35 KiB | 268.00 KiB/s, done.<br />
      </span>
    </p>

    <p class="command"> Start your exercise by typing git commands</p>
    <p class="command"> TO SHOW HINT PRESS 1 IN CONSOLE</p>
    <div id="lastBreak"> </div>
    <div id="lastTerminalDiv">
      <div id ="questions">


      </div>
    <span id="span-answer">~$</span>

    <input type="text" name="" id="answer"  >
    </div>

  </div>
</div>

<script src="js/dashboard.js"></script>
