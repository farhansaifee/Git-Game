
$(document).ready(function(){
    const urlParams = new URLSearchParams(window.location.search);
    const searchInput = urlParams.get('searchInput');
    $("#search-bar").val(searchInput);
        let git_commands = ["git push","git stash","git commit","git merge", "git add", "git branch", "git checkout", "git clean", "git clone", "git commit --amend", "git config", "git fetch", "git init", "git log", "git pull", "git rebase", "git rebase -i", "git reflog", "git remote", "git reset",
        "git revert", "git status"]
        let git_explain = ["The git push command is used to upload local repository content to a remote repository.",
        "Use git stash when you want to record the current state of the working directory and the index, but want to go back to a clean working directory.",
        "The commit command is used to save your changes to the local repository.",
        "Incorporates changes from the named commits (since the time their histories diverged from the current branch) into the current branch. ",
        "Moves changes from the working directory to the staging area. This gives you the opportunity to prepare a snapshot before committing it to the official history.",
        "This command is your general-purpose branch administration tool. It lets you create isolated development environments within a single repository.",
        "In addition to checking out old commits and old file revisions, git checkout is also the means to navigate existing branches. Combined with the basic Git commands, it’s a way to work on a particular line of development.",
        "Removes untracked files from the working directory. This is the logical counterpart to git reset, which (typically) only operates on tracked files.",
        "Creates a copy of an existing Git repository. Cloning is the most common way for developers to obtain a working copy of a central repository.",
        "Passing the --amend flag to git commit lets you amend the most recent commit. This is very useful when you forget to stage a file or omit important information from the commit message.",
        "A convenient way to set configuration options for your Git installation. You will typically only need to use this immediately after installing Git on a new development machine.",
        "Fetching downloads a branch from another repository, along with all of its associated commits and files. But, it doesn't try to integrate anything into your local repository. This gives you a chance to inspect changes before merging them with your project.",
        "Initializes a new Git repository. If you want to place a project under revision control, this is the first command you need to learn.",
        "Lets you explore the previous revisions of a project. It provides several formatting options for displaying committed snapshots.",
        "Pulling is the automated version of git fetch. It downloads a branch from a remote repository, then immediately merges it into the current branch. This is the Git equivalent of svn update.",
        "Rebasing lets you move branches around, which helps you avoid unnecessary merge commits. The resulting linear history is often much easier to understand and explore.",
        "The -i flag is used to begin an interactive rebasing session. This provides all the benefits of a normal rebase, but gives you the opportunity to add, edit, or delete commits along the way.",
        "Git keeps track of updates to the tip of branches using a mechanism called reflog. This allows you to go back to changesets even though they are not referenced by any branch or tag.",
        "A convenient tool for administering remote connections. Instead of passing the full URL to the fetch, pull, and push commands, it lets you use a more meaningful shortcut.",
        "Undoes changes to files in the working directory. Resetting lets you clean up or completely remove changes that have not been pushed to a public repository.",
        "Undoes a committed snapshot. When you discover a faulty commit, reverting is a safe and easy way to completely remove it from the code base.",
        "Displays the state of the working directory and the staged snapshot. You will want to run this in conjunction with git add and git commit to see exactly what’s being included in the next snapshot."
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