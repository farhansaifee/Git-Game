CHALLENGE_ID = 1;

function setTerminal(){
    $("#window").toggle();
    $("#answer").val('');
    $(".user-command").remove();
}
let tasks=[];
let solutions = [];
let currentTask=0;
let finalTask;
$(document).ready(function () {

// get status of USER
// what Challenge is he on
// what tasks is he on
// It is asyns so the data will ba updated on load
  $.ajax({
    type: "GET",
    url: "serviceHandler.php",
    cache: false,
    async:false,
    data: { method: "user_challenge_id", "user_id": USERID },
    responseType: "json",
    success: function (response) {
      CHALLENGE_ID = response[0]["challenge_id"];
      $.ajax({
          type: "GET",
          url: "serviceHandler.php",
          cache: false,
          data: { method: "tasks", id: response[0]["challenge_id"] },
          responseType: "json",
          success: function (response) {
              finalTask = response.length;
              tasks = response;
              solutions = tasks.map(el=>el["solution"].trim().split("#"));
              console.log(solutions)
               $.ajax({
                  type: "GET",
                  url: "serviceHandler.php",
                  cache: false,
                  data: { method: "user_tasks", "user_id":USERID,"challenge_id":CHALLENGE_ID },
                  responseType: "json",
                  success: function (response) {
                       currentTask = response[0]["last_done_task"];
                       const finishedTasksMessage = `YOU HAVE FINISHED ${currentTask} TASKS  FOR
                       THIS CHALLENGE SO FAR`;
                       $(`#total_tasks_${CHALLENGE_ID}`).append(finishedTasksMessage);
                       for(let i =currentTask;i<tasks.length;i++){
                           const task = tasks[i];
                           $("#questions").append(`
                           <div  id="inner-task_${i}">
                              <div  id="task-question_${i}">Question ${Number.parseInt(i)+1}:${task["question"]}</div>
                              <div  id="task-hint_${i}">Hint ${Number.parseInt(i)+1}:${task["hint"]}</div>
                           </div>
                           `);
                           $(`#task-question_${i}`).hide();
                           $(`#task-hint_${i}`).hide();
                           $(`#task-solution_${i}`).hide();
                       }
                       $(`#task-question_${currentTask}`).toggle();
                  },
                  error: function (error) {
                      console.log(error);

                  }
              });
          },
          error: function (error) {
              console.log(error);
          }
      });
    },
    error: function (error) {
        console.log(error);
    }
});

    // get number of challenges
    let totalNumOfChallenges = $("#totalNumOfChallenges").data('num');

    for(let i = 1;i < totalNumOfChallenges;i++){
        $(`#Challenge_${i}`).hide();
        if(i>CHALLENGE_ID){
          $(`#card${i}`).css('background-color', 'gray');
        }else{
          // if a challenge box is clicked
          // show terminal for x challenge
          $(`#card${i}`).click(function () {
            $.ajax({
                type: "GET",
                url: "serviceHandler.php",
                cache: false,
                data: { method: "tasks", id: i },
                responseType: "json",
                success: function (response) {
                    tasks = response;
                    solutions = tasks.map(el=>el["solution"].trim().split("#"));
                    console.log(solutions)
                  },
                  error: function (error) {
                      console.log(error);

                  }
              });

              $("#dashboardContainer").toggle();
              $(`#Challenge_${i}`).toggle();
              setTerminal();
          });
        }

        // if back button is clicked
        // show all challenge boxes again
        $(`#back_button${i}`).click(function () {
            $("#dashboardContainer").toggle();
            $(`#Challenge_${i}`).toggle();
            setTerminal();
        });
        //WHEN THE USER WANTS TO RESET NUMBER OF TASKS
        $(`#start_over_button${i}`).click(function (){
            $.ajax({
                type: "POST",
                url: "serviceHandler.php",
                cache: false,
                data: { method: "reset_task", "user_id":USERID,"challenge_id":CHALLENGE_ID },
                dataType: "json",
                success: function (response) {
                    window.location.reload();
                },
                error: function (error) {
                  console.log(error)
                }
              });
        });

    }
    $("#window").hide();

    $("#window").click(function(){
        $("#answer").focus();
    });
    // If enter is clicked when terminal is opened
    // 13 = Enter Button
    $(document).on('keypress',function(e) {
        if(e.which == 13) {
            let answer = $("#answer").val();
            if(answer=="1"){
                $(`#task-hint_${currentTask}`).toggle();
                $(`<div class="user-command">~$ ${$("#answer").val()}</div>`).insertBefore('#lastBreak');
            }else if (answer==solutions[currentTask][0]){
                let numOfSolutions = solutions[currentTask].length;
                //IF THERE ARE MULTIPLE SOLUTIONS IN ONE ANSWER
                if(numOfSolutions>1){
                    $(`<div class="user-command">~$ CORRECT COMMAND -> ${$("#answer").val()} is valid answer</div>`).insertBefore('#lastBreak');
                    solutions[currentTask].shift();
                    $("#answer").val('')
                    console.log(solutions);
                    return;
                }
                $(`<div class="user-command">~$ CORRECT -> ${$("#answer").val()} is valid answer</div>`).insertBefore('#lastBreak');
                $(`#inner-task_${currentTask}`).css("display","none");
                currentTask++;
                const finishedTasksMessage = `YOU HAVE FINISHED ${currentTask} TASKS  FOR
                     THIS CHALLENGE SO FAR`;
                 $(`#total_tasks_${CHALLENGE_ID}`).empty();
                 $(`#total_tasks_${CHALLENGE_ID}`).append(finishedTasksMessage);
                $.ajax({
                    type: "POST",
                    url: "serviceHandler.php",
                    cache: false,
                    data: { method: "post_user_task", ...{currentTask,"user_id":USERID,"challenge_id":CHALLENGE_ID} },
                    dataType: "json",
                    success: function (response) {
                      console.log(response); //for better debugging
                    },
                    error: function (error) {
                      console.log(error)
                    }
                  });
                if(currentTask < finalTask){
                  $(`#task-question_${currentTask}`).toggle();
                }else{
                  console.log("Finished");
                  $.ajax({
                      type: "POST",
                      url: "serviceHandler.php",
                      cache: false,
                      data: { method: "challenge_passed", "user_id":USERID,"challenge_id":parseInt(CHALLENGE_ID) +1 },
                      dataType: "json",
                      success: function (response) {
                          window.location.reload();
                      },
                      error: function (error) {
                        console.log(error)
                      }
                    });
                }
            }
            else{
                $(`<div class="user-command">~$ ERROR->${$("#answer").val()} is not a valid answer</div>`).insertBefore('#lastBreak');
            }
            $("#answer").val('')
        }
    });
});
