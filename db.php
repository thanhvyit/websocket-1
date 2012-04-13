<?php  

class DB{
	var $serverName;
	var $connectionInfo;
	var $conn;
	var $result;

	function __construct(){
		$this->serverName = "VYPHANPC";
		$this->connectionInfo = array( "Database"=>"SocketPoll");
		date_default_timezone_set('UTC');
	}

	function Open(){
		/* Connect using Windows Authentication. */
		$this->conn = sqlsrv_connect( $this->serverName, $this->connectionInfo);
        if( $this->conn === false )
        {
             echo "Unable to connect.</br>";
             die( print_r( sqlsrv_errors(), true));
        }
	}

	function Close(){		
		if(isset($this->result))
			sqlsrv_free_stmt($this->result);
		if(isset($this->conn))
			sqlsrv_close($this->conn);
	}

  function GetQuestions(){  
		$this->Open();
		                
        $tsql = "select q.*, PollText = (select '/n' + cast(Value as varchar(9)) + ' :' + [Text] 
					from answer where questionid=q.id order by value for xml path('')) from question q where statusid=?";
        $statusid = 1;
        
        $this->result = sqlsrv_query($this->conn, $tsql, Array($statusid));
        if($this->result === false)
        {
          echo "Error in statement execution.</br>";
          die( print_r( sqlsrv_errors(), true));
        }        
		$index = 0;
		while($row = sqlsrv_fetch_array($this->result))		
		{
			$item = new Question();
			$item->Id = (int)$row["Id"];
			$item->Title = $this->utf8($row["Title"]);
			$item->Description = $this->utf8($row["Description"]);						
			//$item->PollText = $item->Title." [POPS ".$this->utf8($item->Id)." X] with X =".str_replace("/n", "<br>", $this->utf8($row["PollText"]));
			$item->PollText = $item->Title." POPS ".$this->utf8($item->Id)." X".str_replace("/n", "<br>", $this->utf8($row["PollText"]));
			
			$list[$index++] = $item;
		}

		$this->Close();
		return $list;
	}
	
	function CreateQuestion($question)
	{
		$this->Open();
		
		$id = $question->Id;
		if($id <= 0)
		{
			$tsql = "declare @qId int;
					Insert into Question(Title, Description, Modified, StatusId) values(?, ?, ?, 1);
					select @qId =@@identity;
					";		
			
			if(isset($question->Answers))
			{
				$value = 0;
				foreach($question->Answers as $answer)
				{
					$tsql .= "Insert into Answer(QuestionId, Value, [Text], Modified, StatusId) values (@qId, ".$answer->Value.", '".addslashes($answer->Text)."', '".$answer->Modified."', 1); ";
				}
			}			
			$this->result = sqlsrv_query($this->conn, $tsql, Array( $question->Title, $question->Description, $question->Modified));
			if($this->result === false)
			{
				echo "Error in statement execution. </br>";
				die ( print_r( sqlsrv_errors(), true ) );
			}
		}
		
		$this->Close();
	}
  
  function Vote($questionId, $voteValue)
  {
    $this->Open();
    
    $tsql = 'declare @qId int, @aId int;
    set @qId = 0;
    set @aId = 0;
    select @qId = q.id, @aId = a.Id from question q inner join answer a on q.Id = a. QuestionId
    where q.id = ? and a.Value = ? and (q.FinishVote is null or datediff(minute, q.FinishVote, getdate()) >= 0);

    if(@qId > 0)
	    insert into Choices(QuestionId, AnswerId, Modified, Statusid) values(@qId, @aId, getdate(), 1);';
    
    $this->result = sqlsrv_query($this->conn, $tsql, Array($questionId, $voteValue));
    if($this->result === false)
        {
          echo "Error in statement execution.</br>";
          die( print_r( sqlsrv_errors(), true));
        }
     
    $this->Close();
  }

	function utf8($val)
    {
        if(isset($val))
         return utf8_encode($val);
        return '';
    }	
}

class Question{
	public $Id;
	public $TagId;
	public $Title;
	public $Description;
	public $MaxNumVote;
	public $Modified;
	public $StatusId;
	
	public $PollText;	
	public $Answers;
}

class Answer{
	public $Id;
	public $QuestionId;
	public $Value;
	public $Text;
	public $Modified;
	public $StatusId;	
}
?>
