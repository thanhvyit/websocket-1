<!--<?php phpinfo(); 

$serverName = "VYPHANPC";
$connectionInfo = array( "Database"=>"SocketPoll");

/* Connect using Windows Authentication. */
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false )
{
     echo "Unable to connect.</br>";
     die( print_r( sqlsrv_errors(), true));
}
else
     echo "Connected.</br>";


sqlsrv_close( $conn);


?>-->
<?php include "db.php"; ?>
<html>
  <head>
    <title>WebSocket</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
    <script src="client.js" type="text/javascript"></script>
  </head>
  <body>
    <table width="100%" style="padding-top: 10px; vertical-align: top;">
      <tr>
        <td style="width: 30%; border-right-style: solid; border-right-width: 5px; border-right-color: White;">
          <div>
            User Name
            <input id="name" type="textbox" />
            <button onclick="connect()">
              Connect
            </button>
          </div>
          <div id="log">
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
                  <a onclick="surgeonUI.editSurgeon('7')" href="#">Add </a>
                </th>
              </tr>
              <?php 
                  $db = new DB();
                  $list = $db->GetQuestions();
                  for ($i = 0; $i < 2; $i++ )
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
                          <a onclick="send('<?php echo $list[$i]->Title ?>');"
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
                          <a onclick="send('<?php echo $list[$i]->Title ?>');"
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
  </body>
</html>
