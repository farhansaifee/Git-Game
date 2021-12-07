<?php
$user = $db->getUser($_SESSION["user"]["ID"]);
?>

<div class="container">
  <div class="main-body">
    <div class="row gutters-sm">
      <div id="profileSection" class="col-md-4">
        <div class="section">
          <div class="section-body">
            <div class="d-flex flex-column align-items-center text-center">
              <form action="index.php?menu=profile" method="post" enctype="multipart/form-data">
                <div class="profileImage">
                  <img class="profileImage" src="<?php
                                                  if ($user->getAvatar() == null) {
                                                    if ($user->getGender() == "male") {
                                                      echo "resources/Logo_Blue.png";
                                                    } else {
                                                      echo "resources/Logo_Orange.png";
                                                    }
                                                  } else {
                                                    echo "avatars/" . $user->getAvatar();
                                                  }
                                                  ?>">
                  <label class="editIcon" for="fileInput">
                    <i class="fa fa-pencil-square-o fa-lg avatarIcon" aria-hidden="true"></i>
                  </label>
                  <input style="display:none" id="fileInput" name="avatar" type="file" accept="image/*">
                </div>
                <div class="mt-3">
                  <h4><?php echo $user->getUsername(); ?></h4>
                  <h6 class="text-secondary"><?php echo $user->getFirstname();
                                              echo ' ';
                                              echo $user->getLastname(); ?></h6>
                </div>
                <input type="hidden" name="method" value="editAvatar">
                <button type="submit" class="btn btn-primary">Save new Avatar</button>
              </form>
            </div>
          </div>
        </div>

        <div id="dataSection" class="Section mt-3 mb-3">
          <ul class="section list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
              <h6>Username</h6>
              <span class="text-secondary"><?php echo $user->getUsername(); ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
              <h6>Full Name</h6>
              <span class="text-secondary"><?php echo $user->getFirstname();
                                            echo ' ';
                                            echo $user->getLastname(); ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
              <h6>E-mail</h6>
              <span class="text-secondary"><?php echo $user->getEmail(); ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
              <h6>Gender</h6>
              <span class="text-secondary"><?php if ($user->getGender() == '') echo 'other';
                                            else echo $user->getGender() ?></span>
            </li>

          </ul>
        </div>
      </div>

      <div class="col-md-8">
        <div class="section">
          <form action="index.php?menu=profile" method="post">
            <input type="hidden" name="method" value="edit">
            <div class="section-body">
              <h2 class="sectionHead">Edit Account Information</h2>
              <hr class="new">
              <div class="row">
                <div class="col-sm-3">
                  <h6>Username</h6>
                </div>
                <div class="col-sm-4 text-secondary">
                  <?php echo $user->getUsername(); ?>
                </div>
                <div class="col-sm-3">
                  <input type="text" placeholder="Username" name="username">
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6>Firstname</h6>
                </div>
                <div class="col-sm-4 text-secondary">
                  <?php echo $user->getFirstname(); ?>
                </div>
                <div class="col-sm-3">
                  <input type="text" placeholder="Firstname" name="firstname">
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6>Lastname</h6>
                </div>
                <div class="col-sm-4 text-secondary">
                  <?php echo $user->getLastname() ?>
                </div>
                <div class="col-sm-3">
                  <input type="text" placeholder="Lastname" name="lastname">
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6>Email</h6>
                </div>
                <div class="col-sm-4 text-secondary">
                  <?php echo $user->getEmail() ?>
                </div>
                <div class="col-sm-4">
                  <input type="email" placeholder="Email" name="email">
                </div>
              </div>
              <hr>

              <div class="row">
                <div class="col-sm-3">
                  <h6>Gender</h6>
                </div>
                <div class="col-sm-4 text-secondary">
                  <?php if ($user->getGender() == '') echo 'other';
                  else echo $user->getGender() ?>
                </div>
                <div class="col-sm-3">
                  <select style="text-align:center" id="gender" name="gender">
                    <option value="" disabled selected>Select your gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                  </select>
                </div>
              </div>
              <hr>

              <div class="row">
                <div class="col-sm-3">
                  <h6>Password</h6>
                </div>
                <div class="col-sm-4 text-secondary">
                  *Your Password*
                </div>
                <div class="col-sm-3">
                  <input type="password" placeholder="New Password" name="password">
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-7 offset-5">
                  <label style="font-weight: 500" id="pass" name="oldPassword">Enter old Password:</label>
                  <input id="pass" type="password" name="oldPassword" required>
                  <label class="reset" for="resetButton">
                    <i class="fa fa-refresh fa-lg" aria-hidden="true"></i>
                  </label>
                  <input style="display:none" id="resetButton" type="reset">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 offset-8">
                  <button type="submit" id="saveChanges" class="btn btn-success">Save Changes</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>