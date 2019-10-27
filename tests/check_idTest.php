<?php
    require_once "sql_exe.php";
    require_once "ape/util/check_id.php";  
     
    class InputValidationTest extends TestCase
    {
        /*
        Open Database Here


        I havent Messed with the method headers yet because I will have to change some things
        */        

        function checkExamExistsTest()
        {
            /*
            Use Open Database to insert some $examId
            $test->assertTrue("dummyExam", checkExamExists("dummyExam"));
            */
        }

        function checkInclassExamExists($examId)
        {
            /*
            Use Open Database to insert some $examId
            $test->assertTrue("dummyExam", checkInExamExists("dummyExam"));
            */
        }
        
        function checkStudentExists($studentId)
        {
            /*
            Use Open Database to insert some $studentId
            $test->assertTrue("dummyStudent", checkStudentExists("dummyStudent"));
            */
        }

        function checkInClassStudentExists($studentId, $teacherId, $startDate, $endDate)
        {
            /*
            Use Open Database to insert $studentId, $teacherId, $startDate, $endDate 
            $test->assertTrue("dummyStudent", checkInClassStudentExists("dummyStudent"));
            */
        }

        function checkUserExists($userId)
        {
            /*
            Use Open Database to insert some $userId
            $test->assertTrue("dummyUserExists", checkUserExists("dummyUserExists"));
            */
        }

        function checkFacultyExists($userId)
        {
            /*
            Use Open Database to insert some userId
            $test->assertTrue("dummyFaculty", checkFacultyExists("dummyFaculty"));
            */
        }

        function checkGraderExamCatExists($examCatId)
        {
            /*
            Use Open Database to insert some examCatId
            $test->assertTrue("dummyThing", checkGraderExamCatExists("dummyThing"));
            */
        }

        function checkStudentCatGradeExists($studentId, $examCatId)
        {
            /*
            Use Open Database to insert some examCatId, studentId
            $test->assertTrue("dummyThing", checkStudentCatGradeExists("dummyThing"));
            */
        }
}
?>