$(document).ready(function () {
    $("#second").hide();

    $("#change").click(function () {
        $("#first").toggle();
        $("#second").toggle();
    });
    $("#goback").click(function () {
        $("#first").toggle();
        $("#second").toggle();
    });
});

function redirectQuiz(quizID) {
    window.location = 'index.php?menu=showQuiz&qid=' + quizID;
}

function openModal(Id) {
    let modalId = Id.concat("Content");
    if (document.getElementById(modalId)) {
        let modal = document.getElementById(modalId);
        modal.style.display = "block";

        let parent = document.querySelector("#" + modalId);
        let span = parent.querySelector(".close");

        span.onclick = function () {
            modal.style.display = "none";
        };

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    }
}

function popup(text) { //fixed popup window
    let div = document.createElement("div");
    div.textContent = text;
    div.classList.add("popup");

    $(".page-container").append(div);

    let jdiv = $(div);
    let duration = text.length > 30 ? text.length*50 : 1500;
    jdiv.show(800).delay(duration).hide(800);
}