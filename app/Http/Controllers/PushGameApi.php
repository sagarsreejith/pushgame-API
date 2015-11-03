<?php

namespace gleams\Http\Controllers;

use Illuminate\Http\Request;
use gleams\Http\Requests;
use gleams\Http\Controllers\Controller;
use DB;
use DateTime;
date_default_timezone_set("GMT");
class PushGameApi extends Controller
{
    //Push Game Login / Sign Up API
	public function PushGameLogin(Request $request)
    {
		//$input = $request->all();
		$iphone_data = file_get_contents("php://input");
		$json = json_decode($iphone_data, true);
		if(!empty($json['user_name']) AND !empty($json['facebook_id'])){
			$UserCount = DB::table('pushgame_userlog')->where('user_name', $json['user_name'])->where('facebookid', $json['facebook_id'])->count();
			$UserPoints = DB::table('dash_bord')->where('facebook_id', $json['facebook_id'])->count();
			$TotalUserCount=DB::table('pushgame_userlog')->count();				
			if($UserCount>0){
				$UserData = DB::table('pushgame_userlog')->where('user_name', $json['user_name'])->where('facebookid', $json['facebook_id'])->first();
				$Response = array('status' => "Success",'facbookID' =>$UserData->facebookid,'user_name' =>$UserData->user_name,'profile_photo' =>$UserData->profile_url,'devicetoken' =>$UserData->devicetoken,'TotalUser'=>$TotalUserCount,'points'=>$UserPoints);
				return $Response;	
			}else {
				DB::table('pushgame_userlog')->insert(
				['user_name' => $json['user_name'], 'facebookid' =>  $json['facebook_id'], 'profile_url' => $json['profile_pic'] ,'devicetoken' => $json['devicetoken']]
				);
				$Response = array('status' => 'Success','facbookID' =>$json['facebook_id'],'user_name' =>$json['user_name'],'profile_photo' =>$json['profile_pic'],'devicetoken' =>$json['devicetoken'],'TotalUser'=>$TotalUserCount,'points'=>$UserPoints);
				return $Response;
			}			
		}else {
		$Response = array('status' => "Error");
		return $Response;
		}
    }
	// Push Game Global TImer API
	public function GlobalTimer(Request $request)
    {
		date_default_timezone_set("GMT");
		$GlobalTime = DB::table('global_time')->where('id', 1)->first();
		$RandomTime = DB::table('ran_time_classic')->where('id', 1)->first();
		$CurrentTime = date("Y-m-d H:i:s");
		$StartDate = new DateTime($GlobalTime->time);
		$SinceStart = $StartDate->diff(new DateTime($CurrentTime));
		$Minutes = $SinceStart->days * 24 * 60;
		$Minutes += $SinceStart->h * 60;
		$Minutes += $SinceStart->i;
		$Minutes= $Minutes.' minutes';
		$Seconds = $SinceStart->s;
		if($Minutes>180){
			$glbal_time_1=$Minutes%180;
			$glbal_time=180-$glbal_time_1;
		}else {
			$glbal_time=179-$Minutes;
		}
		if($Seconds==0){ $Seconds=0; }
		else{ $Seconds=60-$Seconds; }
        if($glbal_time==180){ $Seconds=0; }
		$Response = array('global_time' => $glbal_time,'seconds' => $Seconds,'activate' => $RandomTime->ran_time);
		return $Response;
    }
	//Push Game Classic Leader Board(Partial) API
	public function PushgameLeaderBoard(Request $request)
    {
		$iphone_data = file_get_contents("php://input");
		$json = json_decode($iphone_data, true);
		$PointList = DB::table('points')->orderBy('points', 'desc')->skip(0)->take(10)->get();
		//print_r($PointList);
		foreach($PointList as $UserData){
		$fid=$UserData->facebook_id;
			$Data = DB::table('pushgame_userlog')->where('facebookid', $fid)->get();
			echo "<br />".$fid;
			//return $Data;
			//$sql2=mysql_query("SELECT * FROM  `pushgame_userlog` WHERE  `facebookid` =  '".$UserData->facebook_id."'");
			//$user_data2=mysql_fetch_assoc($sql2);
			//$Result[]=array('user_name'=>$Data->user_name,'facebook_id'=>$Data->facebookid,'profile_pic'=>$Data->profile_url,'total_score'=>$UserData->points);
			}
		//return 'mm';		
	}
	//Push Game Reset API Classic Mode 
	public function PushGameResetClassicMode(Request $request)
    {
		$iphone_data = file_get_contents("php://input");
		$json = json_decode($iphone_data, true);
		date_default_timezone_set("GMT");
		if(!empty($json['user_name']) AND !empty($json['facebook_id']) AND !empty($json['reset_time'])){
			$rest_time=explode(":",$json['reset_time']);
			if($rest_time[0]<5){
				$UserData = DB::table('dash_bord')->where('rank',1)->orderBy('time_stamp', 'desc')->first();
				//$user_full_fetch = mysql_query("SELECT * FROM  `dash_bord` WHERE `rank` = 1 ORDER BY  `dash_bord`.`time_stamp` DESC LIMIT 1", $this->db);
				//$user_data=mysql_fetch_assoc($user_full_fetch);					
				$CurrentTime=date("Y-m-d H:i:00");
				$FetchedTime=$UserData->time_stamp;
				$StartDate = new DateTime($time2);
				$SinceStart = $StartDate->diff(new DateTime($CurrentTime));
				$Minutes = $SinceStart->days * 24 * 60;
				$Minutes += $SinceStart->h * 60;
				$Minutes += $SinceStart->i;
				$Minutes= $Minutes.' minutes';
				$OldTimeStamp= DB::table('dash_bord')->where('rank',1)->orderBy('dash_id', 'desc')->first();
				//$OldTimeStamp= mysql_query("SELECT * FROM  `dash_bord` WHERE  `rank` =1 ORDER BY  `dash_bord`.`dash_id` DESC LIMIT 0 , 1", $this->db);
				//$old_time_stamp_result=mysql_fetch_assoc($old_time_stamp);
				//$time_stamp=$old_time_stamp_result['time_stamp'];
				$StartDate1 = new DateTime($OldTimeStamp->time_stamp);
				$SinceStart1 = $StartDate1->diff(new DateTime($CurrentTime));
				$Minutes1 = $SinceStart1->days * 24 * 60;
				$Minutes1 += $SinceStart1->h * 60;
				$Minutes1 += $SinceStart1->i;
				$Minutes1= $Minutes1;
				if($Minutes1>=176){
					$rank=1;
					$in_message="You won! Post a message to the world!";
					$c_global_time=180; 
					$updated_time=date("Y-m-d H:i:00");
					$RanTime=date("h")+date("i")+date("s")+40;
					mysql_query("UPDATE  `balparkm_push`.`ran_time_classic` SET  `ran_time` =  '".$RanTime."' WHERE  `ran_time_classic`.`id` =1");																				
					/*$points_query= mysql_query("SELECT * FROM  `points` WHERE `facebook_id`=".$json['facebook_id']."", $this->db);
					$points_result=mysql_fetch_assoc($points_query);
					if($points_result>0){
						$new_point=$points_result['points']+1;
						mysql_query("UPDATE  `balparkm_push`.`points` SET  `points` =  '".$new_point."' WHERE  `points`.`facebook_id` =  '".$json['facebook_id']."'");						
					}else {
							mysql_query("INSERT INTO `balparkm_push`.`points` (`facebook_id`, `points`) VALUES ('".$json['facebook_id']."', '1')");	
					} */
					$RandomTime = DB::table('ran_time_classic')->where('id',1)->first();
					//$ran_time_qery= mysql_query("SELECT * FROM  `ran_time_classic` WHERE `id`=1", $this->db);
					//$ran_time_result=mysql_fetch_assoc($ran_time_qery);
					$random_hour_classic = $RandomTime->ran_time;
					$random_hour_classic=$random_hour_classic+$rest_time[2];
					if($random_hour_classic>239){
						$random_hour_classic=66;
					}
					mysql_query("UPDATE  `balparkm_push`.`ran_time_classic` SET  `ran_time` =  '".$random_hour_classic."' WHERE  `ran_time_classic`.`id` =1");																										
					$con=0;
				}
			}
		}
	}
	//Push Game Leader Board Message Posting API
	public function PushgameLeaderBoardMessagePosting(Request $request)
    {
		$iphone_data = file_get_contents("php://input");
		$json = json_decode($iphone_data, true);
		if(!empty($json['facebook_id']) AND !empty($json['push_time'])){
			DB::table('dash_message')->insert(
			['msg_id' => $json['message_id'], 'facebook_id' =>  $json['facebook_id'], 'message' => $json['message'] ,'push_time' => $json['push_time']]
			);
			$Response = array('status' => 'Success');	
			return $Response;
		}  else {
			$Response = array('status' => 'Eroor');	
			return $Response;
		}
	}
	//Push Game Leader Board Individual(Pending) API
	public function PushGameLeaderBoardIndividual(Request $request)
    {
		$iphone_data = file_get_contents("php://input");
		$json = json_decode($iphone_data, true);
		if(!empty($json['facebook_id'])){
			$DashBoard = DB::table('dash_bord')->where('facebook_id', $json['facebook_id'])->where('rank',1)->orderBy('time_stamp', 'DESC')->get();
			if(count($DashBoard)>0){
				foreach($DashBoard as $DashBoard){
					$PushTime=$DashBoard->push_time;
					$DashMessage = DB::table('dash_message')->where('facebook_id', $json['facebook_id'])->where('push_time',$PushTime)->first();
					if(count($DashMessage)!=0){ 
						$str = date("m/d/Y", strtotime($DashBoard->time_stamp));
						$str_ex=explode("/",$str);				
						$str1=date("h:i A", strtotime($DashBoard->time_stamp));
						$a = array('/^0(\d+)/','/\.0(\d+)/');
						$b = array('\1','.\1');
						$str = preg_replace($a,$b,$str);
						$str_date= preg_replace($a,$b,$str_ex[0]);
						$str_date1=preg_replace($a,$b,$str_ex[1]);
						$str_date2 = substr($str_ex[2], -2);
						$Result[]=array('time_stamp'=>$str_date.'/'.$str_date1.'/'.$str_date2.' '.$str1,'push_time'=>$DashBoard->push_time,'message_id'=>$DashMessage->msg_id,'message'=>$DashMessage->message);						
					}					
				}
				$UserTotalPush=DB::table('dash_bord')->where('facebook_id', $json['facebook_id'])->count();
				$RemainUnlock=240-$UserTotalPush;
				$TotalPush=DB::table('dash_bord')->count();
				$Response = array('status' => 'Success','total_global_reset' => $UserTotalPush,'remaining_unlock' => $RemainUnlock,'total_pushes' => $TotalPush,'result'=>$Result);				
			}else {$Response = array(); }
			return($Response);
		}else{
			$Response = array('status' => 'Eroor'); 
			return $Response; 
		}
	}
	//Push Game Global Timer Product Mode API
	public function ProductModeGlobalTimer(Request $request)
    {
		$iphone_data = file_get_contents("php://input");
		$json = json_decode($iphone_data, true);
		$GlobalTime = DB::table('global_time_product')->where('id', 1)->first();
		$CurrentTime=date("Y-m-d H:i:s");
		$DbTIme=$GlobalTime->time;
		$StartDate = new DateTime($DbTIme);
		$SinceStart = $StartDate->diff(new DateTime($CurrentTime));
		$Hour=$SinceStart->days * 24;
		$Hour += $SinceStart->h;
		$RandomTime = DB::table('reset_time_product')->orderBy('id', 'ASC')->get();		
		foreach($RandomTime as $RandomTime){
			$RandomTimeArray[]=array('hit_time'=>$RandomTime->hit_time);
		}
		$Response = array('hour'=>$Hour,'minute'=>$SinceStart->i,'second'=>$SinceStart->s,'resetting_time'=>$RandomTimeArray);
		return $Response;
	}
	//Push Game Leader Board Product Post API
	public function ProductModeMessagePosting(Request $request)
    {
		$iphone_data = file_get_contents("php://input");
		$json = json_decode($iphone_data, true);
		if(!empty($json['facebook_id']) AND !empty($json['push_time']) AND !empty($json['message']) AND !empty($json['message_id'])){
			DB::table('product_dash_message')->insert(
			['msg_id' => $json['message_id'], 'facebook_id' =>  $json['facebook_id'], 'message' => $json['message'] ,'push_time' => $json['push_time']]
			);
			$Response = array('status' => 'Success');
			return $Response;
		}else {
			$Response = array('status' => 'Eroor');
			return $Response;
		}
	}
	//Push Game Profile API
	public function PushGameProfile(Request $request)
    {
		$iphone_data = file_get_contents("php://input");
		$json = json_decode($iphone_data, true);
		if(!empty($json['facebook_id'])){
			$FatestResponse = DB::table('dash_bord')->where('facebook_id', $json['facebook_id'])->where('fast_response', '!=' , 0)->orderBy('fast_response', 'asc')->skip(0)->take(1)->get();
			
			if(count($FatestResponse)>0){
				foreach($FatestResponse as $FatestResponse){$FatestResponse=$FatestResponse->fast_response;}				
				
			}else { $FatestResponse=0;	}
			$SlowestResponse = DB::table('dash_bord')->where('facebook_id', $json['facebook_id'])->where('fast_response', '!=' , 0)->orderBy('fast_response', 'desc')->skip(0)->take(1)->get();
			if(count($SlowestResponse)>0){
				foreach($SlowestResponse as $SlowestResponse){$SlowestResponse=$SlowestResponse->fast_response;}				
			} else { $SlowestResponse=0; }
			$AvgResponseCount = DB::table('dash_bord')->where('facebook_id', $json['facebook_id'])->count();	
			if($AvgResponseCount>0){
				$AvgResponse = DB::table('dash_bord')->where('facebook_id', $json['facebook_id'])->avg('fast_response');				
			}else{ $AvgResponse=0; }
			$Response = array('status' => 'Success','fastest_response' => $FatestResponse,'slowest_press_time' => $SlowestResponse,'AVG' => $AvgResponse,'count'=>$AvgResponseCount);
			return $Response;
		}
		
	}
	//Push Game Delelte Message API 
	public function PushGameDeleteMessage(Request $request)
	{
		$iphone_data = file_get_contents("php://input");
		$json = json_decode($iphone_data, true);
		if(!empty($json['message_id'])){
			DB::table('dash_message')->where('msg_id',$json['message_id'])->delete();
			$Response = array('status' => 'Success');
			return $Response;
		} else {
			$Response = array('status' => 'Eroor');
			return $Response;
		}
	}
	//Push Game Getting All Message API
	public function PushGameGetAllMessage(Request $request)
	{
		$iphone_data = file_get_contents("php://input");
		$json = json_decode($iphone_data, true);
		$TotalMessageCount = DB::table('dash_message')->count();
		$DashBoard = DB::table('dash_bord')->where('rank',1)->orderBy('dash_id', 'desc')->skip($json['start'])->take($json['end'])->get();
		if(count($DashBoard)!=0){
			foreach($DashBoard as $DashBoardNew){
				$SelectMessage = DB::table('dash_message')->where('msg_id',$DashBoardNew->dash_id)->first();
				if(count($SelectMessage)>0){
					$UserData = DB::table('pushgame_userlog')->where('facebookid',$SelectMessage->facebook_id)->first();
					$DashBoardNew->time_stamp = date('h:i:s A l,F d,Y', strtotime($DashBoardNew->time_stamp));
					$Result[]=array('profile_image'=>$UserData->profile_url,'user_name'=>$UserData->user_name,'message'=>$SelectMessage->message,'date'=>$DashBoardNew->time_stamp);					
				
				}
			}
		}else { $Result[]=array(); }
		$Response = array('status' => 'Success','total_count' => $TotalMessageCount,'Result' => $Result);	
		return $Response;
	}
	//Push Game Latest Message
	public function PushGameLatestMessage(Request $request)
	{
		$LatestMessage = DB::table('dash_message')->orderBy('msg_id', 'desc')->first();
		$UserData = DB::table('pushgame_userlog')->where('facebookid',$LatestMessage->facebook_id)->first();
		$Result = (object)array_merge_recursive((array)$LatestMessage , (array)$UserData);
		$Response = array('status' => 'Success','result' => $Result);
		return $Response;
	}
	//Push Game Mission
	public function PushGameMission(Request $request)
	{
		$MissionMessage = DB::table('push_game_mission')->get();
		$Response = array('status' => 'Success','result' => $MissionMessage);
		return $Response;
	}
}
