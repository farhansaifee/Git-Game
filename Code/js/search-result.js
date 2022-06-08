
$(document).ready(function(){
    const urlParams = new URLSearchParams(window.location.search);
    const searchInput = urlParams.get('searchInput');
    $("#search-bar").val(searchInput);
        let git_commands = ["git push","git stash","git commit -m ","git merge"]
        let git_explain = ["The git push command is used to upload local repository content to a remote repository.",
        "Use git stash when you want to record the current state of the working directory and the index, but want to go back to a clean working directory.",
        "The commit command is used to save your changes to the local repository.",
        "Incorporates changes from the named commits (since the time their histories diverged from the current branch) into the current branch. "
    ];
    for(let i=0;i<git_commands.length;i++){
        let command = git_commands[i];
        let explain = git_explain[i];
        if(command.includes(searchInput)){
            $("#search-result-div").append(`<div class='card_ch1'>
            <div class='card_ch1_flex'>
                <div>${command}</div>
                <button class="btn btn-info" id="search-command-${i}">Show more info</button>
            </div>
                <div class='card_chal' id="search-info-${i}">
                    <div>${explain}</div>
                    
                </div>
            </div`)
            $(`#search-info-${i}`).toggle();
            $(`#search-command-${i}`).click(function(){
                $(`#search-info-${i}`).toggle();
            });
        }
    };
});