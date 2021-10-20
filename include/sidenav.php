<div id="mySidenav" class="sidenav">
    <img src="resources/Logo_Orange.png" alt="logo" />
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><span onclick="openNav()">&times;</span></a>
    <a href="index.php?menu=dashboard">Dashboard</a>
    <a href="index.php?menu=highscore">Highscore</a>
    <a href="index.php?menu=profile">Profile</a>
</div>

<div class="container-fluid row">
    <div class="col-md-2">
        <a href="index.php?menu=dashboard"><img src="resources/Logo_Blue.png" class="logo" width="50" alt="logo"></a><span class="openbtn" onclick="openNav()">&nbsp;&#9776;</span>
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