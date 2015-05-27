<?php
define("ONEDAYDB", "");
define("REQUESTLIMIT", 10);
define("SERVERNAME", "localhost");
define("USERNAME", "onedayt54w");
define("PASSWORD", "onedaysfkl76jk");

class recomUpdate{
    
    function dbConnect(){
        $sqlCon = mysqli_connect(SERVERNAME, USERNAME, PASSWORD);
            if (!$sqlCon){
                die('Could not connect: ' . mysqli_error());
            }
        mysqli_select_db($sqlCon,ONEDAYDB);
        return $sqlCon;
    }
    
    function getNo($uid){
        
        $sqlCon = $this->dbConnect();
        $result = mysqli_query($sqlCon, "select no from course where uid = ".$uid);
        $datarow = mysqli_fetch_all($result);
        mysqli_close($sqlCon);
        return $datarow;
    }
    
    function updateCouAct($no, $aid, $inc){
        if ($inc ==0) return ;
        $logFile = "CouActLog.json";
        $fileHandle = fopen($logFile,"r");
        $logJson = fread($fileHandle, filesize ($logFile));
        $record = json_decode($logJson, true);
        fclose($fileHandle);
        
        $rLen = count($record);
        $ifAdd = 1;
        for($i=0; $i<$rLen; $i++) 
            if (($record[$i][0] ==$no) && ($record[$i][1]== $aid)){
                $record[$i][2] += $inc;
                $ifAdd = 0;
                break;
            }
        if ($ifAdd >0) {
            $record[$rLen][0] = $no;
            $record[$rLen][1] = $aid;
            $record[$rLen][2] = $inc;
        }    
        if ($rLen >=REQUESTLIMIT) {
            //connect to db
            $sqlCon = $this->dbConnect();
            //update likelihood
            for($i=0; $i<=$rLen; $i++) {
                mysqli_query($sqlCon, "update relate set likelihood = likelihood + ".$record[$i][2]." where no = ".$record[$i][0]." and aid = ".$record[$i][1]);
            }
            mysqli_close($sqlCon);
            $record =array();
            
        }
        $fileHandle = fopen($logFile,"w");
        $fwrite =fwrite($fileHandle, json_encode($record));
        fclose($fileHandle);
        
    }
    
    function updateUsrAct($uid, $aid, $inc){
        
        $no = $this->getNo($uid);
        
        $cLen = count($no);
        for($i=0; $i<cLen; $i++) {
            $this->updateCouAct($no[$i][0], $aid, $inc);
        }
    }
    
    function getRecomStat($uid, $$amount){
        function likeSort($a, $b){
            if ($a[2]==$b[2]) return 0;
            return ($a[2] > $b[2]) ? -1 : 1;
        }
        
        
        $no = $this->getNo($uid);
        $cLen = count($no);
        
        $sqlCon = $this->dbConnect();
        $noStr ="";
        for ($i=0; $i<$cLen-1;$i++)
            $noStr = $noStr.$no[$i][0].",";
        $noStr = $noStr.$no[$cLen-1][0];
        
        $sqlMax = mysqli_query($sqlCon, "select * from (select no,aid,likelihood from relate as a where likelihood = ( select max(likelihood) from relate as b where a.no = b.no))c where no in (".$noStr.")");
        mysqli_close($sqlCon);
        $result =$datarow[0];
        uasort($result, "likeSort");
        return $result;
    }
    
    function getRecom($uid, $amount){
        
        $datarow = $this->getRecomStar($uid, $amount);
        $dLen = count($datarow);
        $rLen = 0;
        for ($i=0; $i<$dLen; $i++){
            if ($datarow[$i][2] != 0)
                $result[$rLen++] =$datarow[$i][1];
        }
        $result = array_unique($result);
        return $result;
    }
}

    

?>