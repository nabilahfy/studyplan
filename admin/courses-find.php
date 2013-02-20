<!DOCTYPE html>
<html>
	<head>
		<title>Stevens' Study Planner &raquo; Find Courses</title>
		<?php require("../includes/styles.php"); ?>
		<?php include("../includes/databaseClass.php"); ?>
		
		<script type="text/javascript">
			//Sends value to main window
			function SendValueToParent(course)
			{
				//var myVal = document.getElementById('').value;
				window.opener.GetValueFromChild(course);
				window.close();
				return false;
			}
		</script>
		
	</head>
	<body>
			
		<div class="container">
		
<?php
	//If form is submitted & at least one field is filled
	if(isset($_POST["search"]) && (!empty($_POST["course"]) || !empty($_POST["coursename"]) || !empty($_POST["department"])))
	{
		//Setup database
		$db = new database();
		$db->setup("w3_studyplanner", "QcRo2mEC", "db0.stevens.edu", "w3_studyplanner");
		
		//Sanitize & extract values
		$course = strtolower(addslashes(strip_tags($_POST["course"])));
		$name = addslashes(strip_tags($_POST["coursename"]));
		$dept = strtolower(addslashes(strip_tags($_POST["department"])));
		
		//Check with database
		$sql = "SELECT * FROM course WHERE ";
		
		if(!empty($course))
			$sql .= "CONCAT(prefix, number) = '" . $course . "'";
		
		if(!empty($name))
		{
			if(!empty($course))
				$sql .= " AND ";
			
			$sql .= "course_name LIKE '%" . $name . "%'";
		}
		
		if(!empty($dept))
		{
			if(!empty($course) || !empty($name))
				$sql .= " AND ";
			
			$sql .= "department = '" . $dept . "'";
		}
		
		$res = $db->send_sql($sql);
		
		$rownum = mysql_num_rows($res);
		
		if(!$rownum)
			echo "Course doesn't exist in database.<br/>\n";
		else
		{
			while($rownum-- > 0)
			{
				$row = $db->next_row();
			
				$pre = $row[0];
				$num = $row[1];
				$cred = $row[2];
				$name = $row[3];
				$dept = $row[4];
				$oc = $row[5];
				$wc = $row[6];
				
				echo "</p><b><a href=\"Javascript:SendValueToParent('" . $pre . $num . "');\">Edit this course</a></b><br/>\n";
				echo "Course prefix: " . $pre . "<br/>\n";
				echo "Course number: " . $num . "<br/>\n";
				echo "Credits: " . $cred . "<br/>\n";
				echo "Course name: " . $name . "<br/>\n";
				echo "Department: " . $dept . "<br/>\n";
				echo "Term offered - On campus:" . $oc . "<br/>\n";
				echo "Term offered - Web campus:" . $wc . "<br/>\n</p>";
			}
		}
	}
	else
	{
?>
			
			<h4>Find Courses</h4>
			<p>Fill in at least one field to search the course catalog.<br/>
			
			</p>
			
			<form class="form-horizontal" action="courses-find.php" method="POST">
				<div class="control-group">
					<label class="control-label" for="Course">Course</label>
					<div class="controls">
						<input name="course" type="text" id="Course" class="input-small" placeholder="e.g. CS101" />
					</div>
				</div>					
				<div class="control-group">
					<label class="control-label" for="CourseName">Course Name</label>
					<div class="controls">
						<input name="coursename" type="text" id="CourseName" class="medium-small" placeholder="e.g. System Programming" />
					</div>
				</div>				
				<div class="control-group">
					<label class="control-label" for="Department">Department</label>
					<div class="controls">
						<select name="department">
							<option value="" selected>Select a department..</option>
							<option value="chemical">Chemical Engineering & Materials Science</option>
							<option value="chemistry">Chemistry, Biology & Biomedical Engineering</option>
							<option value="civil">Civil, Environmental & Ocean Engineering</option>
							<option value="computer">Computer Science</option>
							<option value="electrical">Electrical & Computer Engineering</option>
							<option value="mathematical">Mathematical Science</option>
							<option value="mechanical">Mechanical Engineering</option>
							<option value="physics">Physics & Engineering Physics</option>
							<option value="systems">Systems & Enterprises</option>
							<option value="business">Business and Technology</option>
							<option value="quantitative">Quantitative Finance</option>
							<option value="arts">Arts and Letters</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button type="submit" name="search" class="btn btn-info">Search</button>
					</div>
				</div>
			</form>
			
<?php
	}
?>
			
			<footer>
				<p>� Study Planner 2013</p>
			</footer>
		</div>
		
		<?php require("../includes/scripts.php"); ?>
	</body>
</html>