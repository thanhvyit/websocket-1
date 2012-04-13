var socket=null;
var url;
var token="^";

function connect(){
	if($("name").value!="")
	{
		url = window.location.hostname;
		var host = "ws://" + url + ":12345";
		//var host = "ws://192.168.50.30:12345";
		//don't open another socket if already open
		if(socket==null){ 
			socket = new WebSocket(host);
		}
		socket.onopen = function (msg){
			socket.send($("name").value+" has joined");
		};
		socket.onmessage = function(msg){
			log(unwrap(msg.data));		
		};
		socket.onclose   = function(msg){
			//message sent from quit function
		};
		socket.onerror = function(msg){
			log("Error: message "+msg.data);
		}
		$("entry").focus();
	}
	else
	{
		alert("Type an user name!");
		$("name").focus();
	}
}

function unwrap(msg) {
	var message = msg.split(token);
	return message;
}

function send(msg) {	
	msg = $("name").value+token+msg;
	if(socket.readyState!=1){ 
		alert("Please connect before sending message"); 
		return; 
	}

	try{ 
		socket.send(msg); 
	} 
	catch(ex){ 
		log(ex); 
	}
	//reset the text field after sending
	$("entry").value="";
}

function quit() {
	socket.send($("name").value+" has left");
	socket.close();
	socket=null;
}

// Utilities
function $(id){ return document.getElementById(id); }
function log(msg){
	if(msg[1]) {
		$("log").innerHTML+="<br>"+"<strong class=\"nick\">"+msg[0]+":"+"</strong>"+msg[1];
	}
	else{
		$("log").innerHTML+="<br>"+"<strong class=\"nick\">"+msg[0];		
	}	
}
function onkey(event){ if(event.keyCode==13){ send($('entry').value); } }

function ShowQuestion(show)
{
	if(show)
		$("overlapDiv").style.display = "block";
	else
		$("overlapDiv").style.display = "none";
	return false;
}

function AddAnswer()
{	
	var table = $("EditQuestion");
	
	var rows = table.getElementsByTagName("tr");
	var answerNum = rows.length - 2;
	if(answerNum < 11)
	{	
		var newTd = document.createElement("td");	
		//newTd.setAttribute("colspan", 2);
		newTd.textContent = "Answer " + answerNum;
		
		var answerInput = document.createElement("input");
		answerInput.setAttribute("type", "text");	
		answerInput.setAttribute("name", "anwserText[]");
		answerInput.setAttribute("style", "width:300px;");
		
		var answerRemove = document.createElement("a");
		answerRemove.setAttribute("href", "#");	
		answerRemove.textContent = "Remove";
		answerRemove.setAttribute("onclick", "RemoveAnswer(" + answerNum + ");");
		
		var newTd2 = document.createElement("td");		
		newTd2.appendChild(answerInput);
		newTd2.appendChild(answerRemove);
		
		var newRow = document.createElement("tr");	
		newRow.appendChild(newTd);	
		newRow.appendChild(newTd2);
		
		table.appendChild(newRow);
		//var answerRow = $("AddAnswerRow");
		//table.insertBefore(newRow, answerRow);
	}
}

function RemoveAnswer(answerNum)
{
	var table = $("EditQuestion");	
	var rows = table.getElementsByTagName("tr");
	table.removeChild(rows[rows.length - 1]);
	
	return false;
}