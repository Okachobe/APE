<html>
 
<head></head>
 
<body>


	<?php
		$request=$_GET["request"];

		$servername = "localhost";
		$username = "test";
		$password = "";
		$database = "ape_project";


		try {
			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			echo "Connected successfully <br>";
			}
		catch(PDOException $e)
			{
			echo "Connection failed: " . $e->getMessage();
			}



			
            //
			echo "<br>";
			echo "Grade, ID, First, Last of students with more than 2 attampts at the ape";
			getFailedStudents($conn);
			echo "<br> <br>";

			echo "Winter Results";
            //For the year it can take just a Season or a Season + year and generate averages on that. 
            //We'd attach year to whatever the button results are or an input string 
			$year = "Winter";
			yearReport($year, $conn);
			echo "<br> <br>";

		function getFailedStudents($conn) 
		{
			$query= "SELECT grade, student_id, f_name, l_name, name, date
			FROM exam_grade join exam join user
			WHERE student_id in (
			SELECT student_id
			FROM exam_grade
			WHERE passed=0
			GROUP BY student_id
			HAVING count(student_id)> 2) && exam_grade.exam_id=exam.exam_id && exam_grade.student_id=user.user_id
			Order by student_id, date";
			foreach ($conn->query($query) as $row) {
				echo "<br>";
				print $row['grade'] . "\t";
				print $row['student_id'] . "\t";
				print $row['f_name'] . "\t";
				print $row['l_name'] . "\r\n";
			}
		}

		function yearReport($year,$conn)
		{
	
			$query= "SELECT avg(grade) as average, count(grade) as 'number of students',(Select count(passed)
			From exam_grade join exam WHERE passed=1 && name like '%$year%' && exam_grade.exam_id=exam.exam_id) as passed,(Select avg(grade) from category_grade join exam_category join exam
			Where exam_category.exam_cat_id=category_grade.grader_exam_cat_id && cat_id=1 && exam_category.exam_id=exam.exam_id && exam.name like '%$year%' ) as 'Recursion',
			(Select count(passed)
			From exam_grade join exam WHERE passed=1 && name like '%$year%' && exam_grade.exam_id=exam.exam_id) as passed,(Select avg(grade) from category_grade join exam_category join exam
			Where exam_category.exam_cat_id=category_grade.grader_exam_cat_id && cat_id=2 && exam_category.exam_id=exam.exam_id && exam.name like '%$year%' ) as 'Linked List',
			(Select count(passed)
			From exam_grade join exam WHERE passed=1 && name like '%$year%' && exam_grade.exam_id=exam.exam_id) as passed,(Select avg(grade) from category_grade join exam_category join exam
			Where exam_category.exam_cat_id=category_grade.grader_exam_cat_id && cat_id=3 && exam_category.exam_id=exam.exam_id && exam.name like '%$year%' ) as 'General',
			(Select count(passed)
			From exam_grade join exam WHERE passed=1 && name like '%$year%' && exam_grade.exam_id=exam.exam_id) as passed,(Select avg(grade) from category_grade join exam_category join exam
			Where exam_category.exam_cat_id=category_grade.grader_exam_cat_id && cat_id=4 && exam_category.exam_id=exam.exam_id && exam.name like '%$year%') as 'Data Abstraction'
			FROM exam_grade join exam 
			WHERE name like '%$year%' && exam_grade.exam_id=exam.exam_id";

			
			echo "<br> Average \t Num Students \t Recursion \t Linked List \t General \t Data Abstraction";
		foreach ($conn->query($query) as $row) {
			echo "<br>";
			print $row['average'] . "\t";
			print $row['number of students'] . "\t";
			print $row['Recursion'] . "\t";
			print $row['Linked List'] . "\t";
			print $row['General'] . "\t";
			print $row['Data Abstraction'] . "\t";
		}
	
		}
	?>

</body>
 
</html>

