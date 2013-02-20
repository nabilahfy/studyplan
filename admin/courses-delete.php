<!DOCTYPE html>
<html>
	<head>
		<title>Stevens' Study Planner &raquo; Delete Course</title>
		<?php require("../includes/styles.php"); ?>
		
		<script type="text/javascript">
			//Popup window code
			function newPopup(url)
			{
				popupWindow = window.open(url,'popUpWindow', 'height=500, width=600, left=10, top=10, resizable=yes,menubar=no, location=no, directories=no, status=yes');
			}
			
			//Get value from child window
			function GetValueFromChild(course)
			{
				document.getElementById('Course').value = course;
			}
		</script>
		
	</head>
	<body>
		<?php require("../includes/navigation.php"); ?>

			
		<div class="container">
			<?php
				echo "<p>Welcome, " . $_ENV["REDIRECT_displayName"] . "</p>";
			?>
			
			
			<ul class="nav nav-tabs">
				<li><a href="/studyplanner/admin">Admin Home</a></li>
				<li><a href="dprograms.php">Degree Programs</a></li>
				<li class="active"><a href="courses.php">Courses</a></li>
				<li><a href="cgroups.php">Course Groups</a></li>
			</ul>
			
			<ul class="nav nav-pills">
				<li><a href="courses-add.php">Add Course</a></li>
				<li><a href="courses-edit.php">Edit Course</a></li>
				<li class="active"><a href="courses-delete.php">Delete Course</a></li>
			</ul>
			
			<hr/>
			
<?php
				include("../includes/databaseClass.php");
				if(isset($_POST['submit']) && !empty($_POST["course"]))
				{
					
				$db = new database();
				$db->setup("w3_studyplanner", "QcRo2mEC", "db0.stevens.edu", "w3_studyplanner");
				
				$course = strtolower(strip_tags($_POST["course"]));
				
				$query = "SELECT * FROM w3_studyplanner.course WHERE CONCAT(prefix, number) = '$course'";
				$res = $db->send_sql($query);
				$rows = mysql_num_rows($res);
					if($rows==0)
					{
						
						echo "$course does not exist in database.";
					}
					else
					{
						$query = "DELETE FROM w3_studyplanner.course WHERE CONCAT(prefix,  number) = '$course'";
						$res = $db->send_sql($query);
						echo "$course has been deleted.";
					}
				}
				else
				{

?>
			<h4>Delete Course</h4>
			<p>Please enter the course number and click <em>"Delete Course"</em> button.</p>
		
			<form class="form-horizontal" method="post" action="courses-delete.php">
				<div class="control-group">
					<label class="control-label" for="Course">Course</label>
					<div class="controls">
						<input name="course" type="text" id="Course" class="input-small" placeholder="e.g. CS101" />
						<a href="Javascript:newPopup('courses-find.php');"><button type="button" class="btn btn-info">Find</button></a>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button name="submit" type="submit" class="btn btn-primary">Delete Course</button>
					</div>
				</div>
			</form>
<?php
	}
?>
			<footer>
				<p>© Study Planner 2013</p>
			</footer>
		</div>
		
		<?php require("../includes/scripts.php"); ?>
	</body>
</html>