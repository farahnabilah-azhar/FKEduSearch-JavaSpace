<?php
$page = 'profile';
include 'includes/header.php';
?>

<style>
  .th {
    font-weight: bold;
    text-transform: uppercase;

  }

  .optionCenter {
    align: center;

  }

  .profile-table {
    width: 100%;

  }
</style>



<?php

$link = mysqli_connect("localhost", "root") or die(mysqli_connect_error());
mysqli_select_db($link, "miniproject") or die(mysqli_error());

$user_ID = $_SESSION['userID'];

//AS = aliases
// FROM user sebab nak fetch dari specific user, sebab tu buat FROM user .... user table macam primary table untuk dalam SQL yang ni
//JOIN sume ni....untuk link dengan table mana yang relate.....
// kalo nak confirm kan...tengok kat table mana yg related.....
//user.user_ID/email...maksudnye dalam table user, user_ID, user_email...and so on....untuk research area/academic
$query = "SELECT user.user_email AS user_email, research_area.researchAreaName AS researchAreaName, academic_status.academicStatus_type AS academicStatus_type, socialmedia.instagram_userName AS instagram_userName, socialmedia.linkedin_userName AS linkedin_userName
					  FROM user
					  JOIN research_areauserexpert ON user.user_ID = research_areauserexpert.user_ID
					  JOIN research_area ON research_areauserexpert.researchArea_ID = research_area.researchArea_ID
					  JOIN academic_statususerexpert ON user.user_ID = academic_statususerexpert.user_id
					  JOIN academic_status ON academic_statususerexpert.academicStatus_ID = academic_status.academicStatus_ID
					  JOIN socialmedia ON user.user_ID = socialmedia.user_ID
					  WHERE user.user_ID = '$user_ID' " or die(mysqli_connect_error());

//echo $query; ...Syntax Untuk debug

$result = mysqli_query($link, $query);

$row = mysqli_fetch_assoc($result);

		if ($row) {
			// Data exists in the database for the user's profile
      //gune explode untuk display yg dari dropdown list........
			$academicStatus_type = $row["academicStatus_type"];
			$academicStatus_type = explode(',', $academicStatus_type);

			} else {
			// No data found in the database for the user's profile

			// Set default values or display an error message
			$academicStatus_type = [];  // Empty array for academic status types

			// Display an error message or redirect the user
			$error_message = "User Profile First Time Access!";
			echo "<script>alert('$error_message');</script>";
			echo "<script type='text/javascript'>window.location='userProfileForm.php'</script>";
}

?>

<form action="userProfileUpdate.php" method="post">
  <table class="profile-table" border="0">
    <tr>
      <th class="th">User profile information</th>
    </tr>

    <tr>
      <th class="th">Research Area:</th>
      <td>
      	<select name="researchAreaName">
    <option value="" selected disabled>- Select Research Area -</option>
    <option value="Computer Systems and Networking" <?php if ($row && $row['researchAreaName'] === 'Computer Systems and Networking') echo 'selected'; ?>>Computer Systems and Networking</option>
    <option value="Software Engineering" <?php if ($row && $row['researchAreaName'] === 'Software Engineering') echo 'selected'; ?>>Software Engineering</option>
    <option value="Graphic and Multimedia" <?php if ($row && $row['researchAreaName'] === 'Graphic and Multimedia') echo 'selected'; ?>>Graphic and Multimedia</option>
    <option value="Cyber Security" <?php if ($row && $row['researchAreaName'] === 'Cyber Security') echo 'selected'; ?>>Cyber Security</option>
	</select>
      </td>
    </tr>
	
	<tr>
	  <th class="th">Academic Status: </th>
    <td> <select name = "academicStatus_type[]" multiple>
	 <option value="Diploma" <?php if ($row && in_array('Diploma', $academicStatus_type)) echo 'selected'; ?>>Diploma</option>
     <option value="Degree" <?php if ($row && in_array('Degree', $academicStatus_type)) echo 'selected'; ?>>Degree</option>
     <option value="Master" <?php if ($row && in_array('Master', $academicStatus_type)) echo 'selected'; ?>>Master</option>
     <option value="Phd" <?php if ($row && in_array('Phd', $academicStatus_type)) echo 'selected'; ?>>Phd</option>
  </select> </td>

	</tr>



    <tr>
      <th class="th">Instagram Username:</th>
      <td>
        <input type="text" name="instagram_userName" style="width: 210px;" placeholder="Enter Instagram Username" value="<?php echo ($row) ? $row['instagram_userName'] : ''; ?>">
      </td>
    </tr>

    <tr>
      <th class="th">LinkedIn Username:</th>
      <td>
        <input type="text" name="linkedin_userName" style="width: 210px;" placeholder="Enter LinkedIn Username" value="<?php echo ($row) ? $row['linkedin_userName'] : ''; ?>">
      </td>
    </tr>

    <tr>
      <th class="th">Email:</th>
      <td>
        <input type="email" name="email" style="width: 300px;" placeholder="Enter Your Email" value="<?php echo ($row) ? $row['user_email'] : ''; ?>">
      </td>
      <td></td>
	   <input type="hidden" name="userID" value="<?php echo $_SESSION['userID']; ?>"></td>   
      <td><input type="submit" style="background-color: #18A0FB; color: #FFFFFF; border-radius: 5px;" value="UPDATE"></td>
    </tr>
  </table>
</form>

<?php

include 'includes/footer.php';

?>
