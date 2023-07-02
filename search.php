<?php
$page = 'userPost';


// Retrieve the search query from the URL parameter
$searchQuery = $_REQUEST['searchQuery'] ?? '';
?>



   <div data-aos="fade" class="page-title">
          
      <nav class="breadcrumbs">
    
      </nav>
    </div><!-- End Page Title -->
	
	<style>
	
	.th{
		font-weight: bold;
		text-transform: uppercase;
		 width: auto;

		
	}
	

	
	  .publication-table {
    width: 100%;

	}
  
	</style>
	

    <form method="get" action="userPost.php" style="text-align: center;">
    <?php echo '<input type="text" name="searchQuery" style="width: 30%; height: 20px" placeholder="Search Post Category, Title or Content">'; ?>
    <?php echo '<input type="submit" value="Search">'; ?>
</form>
	


 <?php
    $link = mysqli_connect("localhost", "root") or die(mysqli_connect_error());
    mysqli_select_db($link, "fkedusearch") or die(mysqli_error());

  if (isset($_REQUEST['searchQuery']) && !empty($_REQUEST['searchQuery'])) {
    $searchQuery = $_GET['searchQuery'];

    $querySearch = "SELECT * FROM userpost WHERE PostCategory LIKE '%$searchQuery%' OR PostTitle LIKE '%$searchQuery%' OR PostContent LIKE '%$searchQuery%'";
    $resultSearch = mysqli_query($link, $querySearch) or die(mysqli_error($link));

    if (mysqli_num_rows($resultSearch) > 0) {
        $numberIncrement = 1;
        ?>

        <table class="post-table" border="1"> 
            <tr>
                <th class="th">Result of Searched Posts</th>  
            </tr>
            <tr></tr>
        </table>

        <table border="2" style="width: 100%;">
            <tr>
                <th class="th">No.</th>
                <th class="th">Post Categories</th>
                <th class="th">Post Title</th>
                <th class="th">Post Content</th>
                <th></th>
            </tr>

            <?php
            while ($row = mysqli_fetch_assoc($resultSearch)) {
                ?>
                <tr class="trlist">
                    <td align="center"><?php echo $numberIncrement; ?></td>
                    <td align="center"><?php echo $row['PostCategory']; ?></td>
                    <td align="center"><?php echo $row['PostTitle']; ?></td>
                    <td align="center"><?php echo $row['PostContent']; ?></td>

                    
                </tr>
                <?php
                $numberIncrement++; // Increment the numberIncrement variable
            }
            ?>

        </table>

        <?php

    
}else {
        ?>
        <table class="posts-table" border="1"> 
            <tr>
                <th class="th">Result of Searched Post</th>  
            </tr>
            <tr>
                <td align="center">No Post Found.</td>
            </tr>
        </table>
        <?php
    }
}else {
    ?>
    <table class="posts-table" border="1"> 
        <tr>
            <th class="th">Result of Searched Post</th>  
        </tr>
        <tr>
            <td align="center">Please Search Your Post</td>
        </tr>
    </table>
    <?php
}
    ?>


<div style="margin-bottom: 20px;">
    <table border="2" style="width: 100%;">
        <!-- Table content -->
    </table>
</div>
