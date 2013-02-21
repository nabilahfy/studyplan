<!DOCTYPE html>
<html>
	<head>
		<title>Stevens' Study Planner &raquo; Add Course</title>
		<?php require("../includes/styles.php"); ?>
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
				<li class="active"><a href="courses-add.php">Add Course</a></li>
				<li><a href="courses-edit.php">Edit Course</a></li>
				<li><a href="courses-delete.php">Delete Course</a></li>
			</ul>
			
			<hr/>
			
<?php
	//If form is submitted
	if(isset($_POST["submit"]) && (!empty($_POST["courseprefix"]) && !empty($_POST["coursenumber"])))
	{
		//Setup database
		$host = "db0.stevens.edu";
		$dbname = "w3_studyplanner";
		$user = "w3_studyplanner";
		$pass = "QcRo2mEC";
		
		$dbh = new PDO("mysql:host=" . $host . ";dbname=" . $dbname, $user, $pass);
		
		//$db = new database();
		//$db->setup("w3_studyplanner", "QcRo2mEC", "db0.stevens.edu", "w3_studyplanner");
		
		//Sanitize & extract values
		$pre = strtolower(addslashes(strip_tags($_POST["courseprefix"])));
		$num = strtolower(addslashes(strip_tags($_POST["coursenumber"])));
		$cred = strtolower(addslashes(strip_tags($_POST["credits"])));
		$name = addslashes(strip_tags($_POST["coursename"]));
		$dept = strtolower(addslashes(strip_tags($_POST["department"])));
		$ocarr = $_POST["oncampus"];
		$wcarr = $_POST["webcampus"];
		
		$oc = "";
		$wc = "";
		
		if(!empty($ocarr))
			foreach($ocarr as $sem)
			{
				if(!empty($oc))
					$oc .= ",";
				
				$oc .= strtolower(addslashes(strip_tags($sem)));
			}
		
		if(!empty($wcarr))
			foreach($wcarr as $sem)
			{
				if(!empty($wc))
					$wc .= ",";
				
				$wc .= strtolower(addslashes(strip_tags($sem)));
			}
		
		//Insert to database
		$sql = "INSERT INTO course(prefix, number, no_of_credits, course_name, department, on_campus_semesters, web_campus_semesters) VALUES (:pre, :num, :cred, :name, :dept, :oc, :wc)";
		
		$sth = $dbh->prepare($sql);
		
		$sth->bindParam(":pre", $pre);
		$sth->bindParam(":num", $num);
		$sth->bindParam(":cred", $cred);
		$sth->bindParam(":name", $name);
		$sth->bindParam(":dept", $dept);	
		$sth->bindParam(":oc", $oc);
		$sth->bindParam(":wc", $wc);
			
		$sth->execute();
		
		//$sql = "INSERT INTO course(prefix, number, no_of_credits, course_name, department, on_campus_semesters, web_campus_semesters) VALUES ('" . $pre . "', " . $num . ", " . $cred . ", '" . $name . "', '" . $dept . "', '" . $oc . "', '" . $wc . "')";
		//$db->send_sql($sql);
				
		echo "Course successfully added.<br/>\n";
	}
	else
	{
?>
			
			<h4>Add Course</h4>
			<p>Please fill the required fields and click on <em>"Add Course"</em> button.</p>
			
			<form class="form-horizontal" action="courses-add.php" method="POST">
				<div class="control-group">
					<label class="control-label" for="CoursePrefix">Course Prefix</label>
					<div class="controls">
						<input type="text" name="courseprefix" id="CoursePrefix" class="input-small" placeholder="e.g. CS" /> 
					</div>
				</div>				
				<div class="control-group">
					<label class="control-label" for="CourseNumber">Course Number</label>
					<div class="controls">
						<input type="text" name="coursenumber" id="CourseNumber" class="input-small" placeholder="e.g. 101" /> 
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="Credits">Credits</label>
					<div class="controls">
						<input type="text" name="credits" id="Credits" class="input-small" /> 
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="CourseName">Course Name</label>
					<div class="controls">
						<input type="text" name="coursename" id="CourseName"> 
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="Department">Department</label>
					<div class="controls">
						<select name="department">
							<option value="arts">Arts and Letters</option>
							<option value="business">Business and Technology</option>
							<option value="chemical">Chemical Engineering & Materials Science</option>
							<option value="chemistry">Chemistry, Biology & Biomedical Engineering</option>
							<option value="civil">Civil, Environmental & Ocean Engineering</option>
							<option value="computer">Computer Science</option>
							<option value="electrical">Electrical & Computer Engineering</option>
							<option value="mathematical">Mathematical Science</option>
							<option value="mechanical">Mechanical Engineering</option>
							<option value="physics">Physics & Engineering Physics</option>
							<option value="quantitative">Quantitative Finance</option>
							<option value="systems">Systems & Enterprises</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Prerequisites</label>
					<div class="controls">
						<a href="#"><button type="button" class="btn">Select Prerequisites</button></a>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Corequisites</label>
					<div class="controls">
						<a href="#"><button type="button" class="btn">Select Corequisites</button></a>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Term Offered</label>
					<div class="controls">
						<div class="control-group">
							<label class="control-label">On Campus</label>
							<div class="controls">
								<label class="checkbox inline">
									Fall<input type="checkbox" name="oncampus[]" id="Fall" value="fall" />
								</label>
								<label class="checkbox inline">
									Spring<input type="checkbox" name="oncampus[]" id="Spring" value="spring" />
								</label>
								<label class="checkbox inline">
									Summer 1<input type="checkbox" name="oncampus[]" id="Summer1" value="summer1" />
								</label>
								<label class="checkbox inline">
									Summer 2<input type="checkbox" name="oncampus[]" id="Summer2" value="summer2" />
								</label>
							</div>
						</div>
					</div>
					<div class="controls">
						<div class="control-group">
							<label class="control-label">Web Campus</label>
							<div class="controls">
								<label class="checkbox inline">
									Fall<input type="checkbox" name="webcampus[]" id="Fall" value="fall" />
								</label>
								<label class="checkbox inline">
									Spring<input type="checkbox" name="webcampus[]" id="Spring" value="spring" />
								</label>
								<label class="checkbox inline">
									Summer 1<input type="checkbox" name="webcampus[]" id="Summer1" value="summer1" />
								</label>
								<label class="checkbox inline">
									Summer 2<input type="checkbox" name="webcampus[]" id="Summer2" value="summer2" />
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button type="submit" name="submit" class="btn btn-primary">Add Course</button>
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