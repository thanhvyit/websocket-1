
<?php include "db.php";
	$db = new DB();
		
	if(isset($_POST["title"]))
	{
		$title = $_POST["title"];
		
		$item = new Question();
		$item->Id = 0;
		$item->Title = $title;
		$item->Description = $_POST["description"];
		$item->Modified = date("Y-m-d H:i:s");	
		
		
		if(isset($_POST["anwserText"]))
		{
			$answers = $_POST["anwserText"];
			if(count($answers) > 0)
			{
				$value = 0;
				foreach($answers as $answer)
				{
					$aItem = new Answer();
					$aItem->Value = ++$value;
					$aItem->Text = $answer;
					$aItem->Modified = date("Y-m-d H:i:s");	
					$list[$value - 1] = $aItem;
				}
				$item->Answers = $list;
			}
		}
		
		$db->CreateQuestion($item);
	}
 ?>
<html>
  <head>
    <title>WebSocket</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
    <script src="client.js" type="text/javascript"></script>
  </head>
  <body>
    <table width="100%" style="padding-top: 10px; vertical-align: top;">
      <tr>
        <td style="width: 350px; border-right-style: solid; border-right-width: 5px; border-right-color: White;">
          <div>
            User Name
            <input id="name" type="textbox" />
            <button onclick="connect()">
              Connect
            </button>
          </div>
          <div id="log" style="width: 350px;word-wrap: break-word;">
          </div>
          <div id="toolbar">
            <input id="entry" type="textbox" onkeypress="onkey(event)" />
            <button onclick="send($('entry').value);">
              Send
            </button>
            <button onclick="quit()">
              Quit
            </button>
          </div>
        </td>
        <td valign="top">
          <div style="width:100%; height:450px; overflow:auto;">
            <table style="border-width: 0px; width: 100%; font-weight: normal; border-collapse: collapse;"
                cellspacing="0">
              <tr style="background-color: gray; font-size: 0.9em;">
                <th style="width: 30%;" scope="col">
                  Title
                </th>
                <th style="width: 60%;" scope="col">
                  Description
                </th>
                <th style="width: 10%;" scope="col">
                  <a onclick="ShowQuestion(true);" href="#">Add </a>
                </th>
              </tr>
              <?php                   
                  $list = $db->GetQuestions();
                  for ($i = 0; $i < count($list); $i++ )
		              { 
                    if($i % 2 === 0)
                    {?>
                      <tr style="background-color: rgb(238, 238, 238); color: Black; font-size: 0.8em;">
                        <td valign="middle" align="left">
                          <span style="font-weight: bold; margin-left: 5px;">
                          <?php echo $list[$i]->Title ?> </span>
                        </td>
                        <td valign="middle" align="left">
                          <span style="margin-left: 5px;">
                            <?php echo $list[$i]->Description ?> </span>
                        </td>
                        <td valign="middle" align="center">
                          <a onclick="surgeonUI.editSurgeon('5')" href="#">Edit </a>
                          <a onclick="send('<?php echo $list[$i]->PollText ?>');"
                                            href="#">Create Poll </a>
                        </td>
                      </tr>
                    <?php } else
                    {?>
                      <tr style="background-color: rgb(238, 255, 255); color: Black; font-size: 0.8em;">
                        <td valign="middle" align="left">
                          <span style="font-weight: bold; margin-left: 5px;">
                          <?php echo $list[$i]->Title ?> </span>
                        </td>
                        <td valign="middle" align="left">
                          <span style="margin-left: 5px;">
                            <?php echo $list[$i]->Description ?> </span>
                        </td>
                        <td valign="middle" align="center">
                          <a onclick="surgeonUI.editSurgeon('5')" href="#">Edit </a>
                          <a onclick="send('<?php echo $list[$i]->PollText ?>');"
                                            href="#">Create Poll </a>
                        </td>
                      </tr>
                    <?php }
                   }                  
              ?>
            </table>
          </div>
        </td>
      </tr>
    </table>
	
	<div id="overlapDiv" style="left: 0px; top: 0px; padding: 0px; float: left; width: 100%; height: 100%; z-index: 9990;display: none; position:absolute">
		<div style="left: 0px; top: 0px; padding: 0px; float: left; background-color: #000000; width: 100%; height: 100%; z-index: 9990;
		filter: alpha(opacity=70); opacity:0.7; position:absolute;">		
		</div>
		<div style="margin-left: 25%; margin-top: 5%; padding: 20px; border: 1px solid #868686; float: left;
            background-color: #ffffff; width: 600px; height: 400px; z-index: 9991; position:relative; border-radius: 10px; overflow: auto;">
			<form action="" method="post">
				<table id="EditQuestion" width="100%" cellpadding="0px" cellspacing="0px" style="text-align: left;
                        color: Navy; padding: 5px;">
                        <tr>
                            <td style="width: 100px;">
                                Title
                            </td>
                            <td>
                                <input type="text" name="title" style="width:300px;" />
                            </td>                            
                        </tr>
						<tr>
							<td>
                                Description
                            </td>
							<td>
								<textarea name="description" rows="3" style="width:300px;"></textarea>
							</td>
						</tr>
						<tr>
                            <td>
                                Answer 1
                            </td>
                            <td>
                                <input type="text" name="anwserText[]" style="width:300px;" />
                            </td>                            
                        </tr>
						<tr>
                            <td>
                                Answer 2
                            </td>
                            <td>
                                <input type="text" name="anwserText[]" style="width:300px;" />
                            </td>                            
                        </tr>
						<tr>
                            <td>
                                Answer 3
                            </td>
                            <td>
                                <input type="text" name="anwserText[]" style="width:300px;" />
                            </td>                            
                        </tr>
						<tr id="AddAnswerRow">
							<td colspan="2">
                                <a href="#" onclick="AddAnswer();">Add Answers</a>
                            </td>							
						</tr>												
						</table>
                <input type="submit" id="NewQuestion" value="Save" />
				<input type="button" id="Cancel" value="Cancel" onclick="ShowQuestion(false);" />
            </form> 
		</div>
	</div>	
  </body>
</html>
