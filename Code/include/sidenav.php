<div id="mySidenav" class="sidenav">
    <img src="resources/Logo_Orange.png" alt="logo" />
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><span onclick="openNav()">&times;</span></a>
    <a href="index.php?menu=dashboard">Dashboard</a>
<!-- <a href="index.php?menu=highscore">Highscore</a> -->
    <a href="index.php?menu=profile">Profile</a>
    <a href="index.php?menu=contact">Contact</a>
    <a href="index.php?menu=highscore">Highscore</a>
</div>


<div class="container-fluid row">
    <div class="col-md-2">
        <a href="index.php?menu=dashboard"><img src="resources/Logo_Blue.png" class="logo" width="50" alt="logo"></a><span class="openbtn" onclick="openNav()">&nbsp;&#9776;</span>
    </div>
    <div class="col-md-2 offset-md-3 mb-3" id="search">
        <form class="mt-2" action="index.php" method="GET">
            <span style="position:relative">
                <input id="search-bar" search='{"searchMethod":"searchQuizes","resultHandler":"createLinks"}' name="searchInput">
                <button id="search-btn" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                    </svg>
                </button>
                <input type="hidden" name="method" value="mainSearch">
                <input type="hidden" name="menu" value="searchResult">
            </span>
        </form>
    </div>
</div>


<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "200px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>