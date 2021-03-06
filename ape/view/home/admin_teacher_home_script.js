var _userId = "";
var _userType = "";
var _userSessionId = "";

var _formId = "main-form";

var _settings;

var _targetModal = "detail-modal";
var _tableId = "main-table";
var _formId = "main-form";

var _locData = Array();
var _catData = Array();
var _graderData = Array();

var _isEditing = false;

var _selectedTab = "Open";

var _isCreateClicked = false;

$(document).ready(loaded);

function loaded()
{
    $.get("../util/get_cur_user_info.php", {is_client: true}, loadUserInfo, "json");
    $(".msg-box").hide();
}

function init()
{

    var URLPage = getURLParameter("page");
    if(URLPage == "teacher_home")
    {
        _userType = "Teacher";
        $("#grading-panel").remove();
        $("#dashboard-title").html("Teacher Dashboard");
    }
    else if(URLPage == "admin_home")
    {
        _userType = "Admin";
        $("#dashboard-title").html("Admin Dashboard");
    }

    $.get("../settings/get_settings.php", {
        requester_id: _userId,
        requester_type: _userType,
        requester_session_id: _userSessionId
        }, function(data){_settings = data;}, "json");

        $("#requester-id").val(_userId);
        $("#requester-type").val(_userType);
        $("#requester-session").val(_userSessionId);
        
    getAllLoc();
    getAllCat();        
    getAllGraders();

    loadUpcomingExams();
    
    if (_userType == "Admin")
    {
        loadGradingExams();
    }
    

    
    
}


function loadUpcomingExams()
{
    buildExamsTable();
}

function buildExamsTable()
{
    headersArr = ["Name", "Date", "Start Time", "Location", "Registered Seats", "Action"];

    var table = buildMainTable(headersArr);
    $(".table-responsive").html(table)
        .find(".main-table>thead th").not("th:last-of-type")
        .click(onClickSort)
        .mousedown(function(e){ e.preventDefault(); });

    getOpenExams();
}

function getOpenExams()
{
    $(".table-responsive > ."+_tableId + " tbody").empty();
    
    $.get("../ape/get_all_apes.php", 
        {requester_id: _userId,
        requester_type: _userType,
        requester_session_id: _userSessionId,
        request: "get_by_state",
        state: "Open"}, 
        loadExamsTable,
        "json");
}

function loadExamsTable(data) 
{
    $.each(data, function(i, item) {
        var row = buildItemSummaryRow(item);

        $("." + _tableId).append(row);


    });

}


function buildItemSummaryRow(item)
{
    var locName = "";
    var locSeats = 0;

    $.each(_locData, function(i,locItem){
        if(item.location == locItem.loc_id)
        {
            locName = locItem.name;
            locSeats = locItem.seats;
        }
    });

    var summaryData = {
        id: item.exam_id,
        name: item.name,
        date: item.date,
        start_time: item.start_time,
        location: locName,
        registered_seats: locSeats - item.remaining_seats + "/" + locSeats
    };

    var row = buildItemRow(summaryData, false);

    var $btnDetails = $('<button type="button" class="btn btn-info btn-labeled" data-toggle="modal" data-target="#detail-modal"><span class="btn-label" aria-hidden="true"><i class="glyphicon glyphicon-list-alt"></i></span>Details</button>');
    $btnDetails.attr("data-id", summaryData.id);
    $btnDetails.click(onclickDetail);
    row.append($('<td>').append($('<div class="btn-group" role="group">').append($btnDetails)));

    return row;
}

function onclickDetail(e) 
{
    onOpenDetailModal(e);
}

function loadGradingExams()
{
     //get grading exams
     $.get("../ape/get_all_apes.php", 
     {requester_id: _userId,
     requester_type: _userType,
     requester_session_id: _userSessionId,
     request: "get_by_state",
     state: "Grading"}, 
     getExamRoster,
     "json");    
}

function getExamRoster(exams)
{
    $.each(exams, function (i, theExam){
        $.get("../ape/get_exam_roster.php",
        {
            requester_id: _userId,
            requester_type: _userType,
            requester_session_id: _userSessionId,
            exam_id: theExam.exam_id,
            get_grade: 0
        },
        function(examRoster){
            getGradersPerExam(theExam, examRoster.length);
        },
        "json");
    });
    
}

function getGradersPerExam(theExam, studentsNum)
{
    //console.log(exams);
    
        $.get("../grade/get_graders.php",
            {requester_id: _userId,
            requester_type: _userType,
            requester_session_id: _userSessionId,
            request: "get_by_exam_id",
            exam_id: theExam.exam_id},
            function(graders){
                getGradedSeats(graders, theExam, studentsNum);
            },
            "json");

}

function getGradedSeats(graders, theExam, studentsNum)
{
    //console.log(theExam.exam_id);
    $.each(graders, function (i, theGrader){
        $.get("../grade/get_graded_seats_per_grader.php",
        {requester_id: _userId,
        requester_type: _userType,
        requester_session_id: _userSessionId,
        grader_exam_cat_ids: theGrader["grader_exam_cat_id"]},
        function(gradedSeats){
            generateProgressBar(theExam, theGrader, gradedSeats, studentsNum);
        },
        "json");
    });
}




function generateProgressBar(theExam, theGrader, gradedSeats, studentsNum)
{
    
    //takenSeats = total number of students who took the exam = the location capacity
    var totalStudentsNum = studentsNum * theGrader["assigned_cat_num"];
    var percent = ((gradedSeats.length / totalStudentsNum) * 100).toFixed(2);

    
    var infohtml =  "<div class='row' style='margin-top:15px'>" +
                        "<div class='col-sm-4'><strong>" + theGrader.f_name + " " + theGrader.l_name + ":</strong></div>" +
                        "<div class='progress' style='margin-right:10px'>" +
                            "<div class='progress-bar progress-bar-striped' role='progressbar' aria-valuenow='" + percent +
                            "' aria-valuemin='0' aria-valuemax='100' style='width:" + percent + "%'>" +
                                "<span style='color:black'>" + percent + "%</span>" +
                            "</div>" +
                        "</div>" +
                    "</div>";
    
    if($("#" + theExam.exam_id).length !== 0)
    {
        $("#" + theExam.exam_id).append(infohtml);
    }
    else
    {
        var html =  "<div class='panel panel-default'>" +
                    "<div class='panel-heading'>" +
                        "<h3 class='panel-title'>" + theExam.name + "</h3>" +
                    "</div>" +
                    "<div class='panel-body' id='" + theExam.exam_id + "'>" + 
                        "<div class='row'>" +
                            "<div class='col-sm-4'><strong>Date: </strong>" + theExam.date + "</div>" +
                            "<div class='col-sm-4'><strong>Start Time: </strong>" + theExam.start_time + "</div>" +
                        "</div>" +
                        infohtml +
                    "</div>" +
                "</div>";
                    
    $("#main-grading-panel").append(html);
    }
}