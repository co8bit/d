class recomUpdate{
    #connect to database
    function dbConnect();
    #get student courses
    function getNo($uid);
    #update relate table with course and activity stats
    function updateCouAct($no, $aid, $inc);
    #update relate table with student and activity stats
    function updateUsrAct($uid, $aid, $inc);
    #get recommendations stat with student id
    function getRecomStat($uid, $amount);
    #get recommended activities with student id
    function getRecom($uid, $amount)
}

CouActLog.json is the log buffer file.