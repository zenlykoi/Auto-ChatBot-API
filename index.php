<!DOCTYPE html>
<html>
<head>
	<title>Simsimi</title>
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	<style type="text/css">
		body{
		    height:400px;
		    position: fixed;
		    bottom: 0;
		}
		.col-md-2, .col-md-10{
		    padding:0;
		}
		.panel{
		    margin-bottom: 0px;
		}
		.chat-window{
		    bottom:0;
		    position:fixed;
		    float:right;
		    margin-left:10px;
		}
		.chat-window > div > .panel{
		    border-radius: 5px 5px 0 0;
		}
		.icon_minim{
		    padding:2px 10px;
		}
		.msg_container_base{
		  background: #e5e5e5;
		  margin: 0;
		  padding: 0 10px 10px;
		  max-height:300px;
		  overflow-x:hidden;
		}
		.top-bar {
		  background: #666;
		  color: white;
		  padding: 10px;
		  position: relative;
		  overflow: hidden;
		}
		.msg_receive{
		    padding-left:0;
		    margin-left:0;
		}
		.msg_sent{
		    padding-bottom:20px !important;
		    margin-right:0;
		}
		.messages {
		  background: white;
		  padding: 10px;
		  border-radius: 2px;
		  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
		  max-width:100%;
		}
		.messages > p {
		    font-size: 13px;
		    margin: 0 0 0.2rem 0;
		  }
		.messages > time {
		    font-size: 11px;
		    color: #ccc;
		}
		.msg_container {
		    padding: 10px;
		    overflow: hidden;
		    display: flex;
		}
		img {
		    display: block;
		    width: 100%;
		}
		.avatar {
		    position: relative;
		}
		.base_receive > .avatar:after {
		    content: "";
		    position: absolute;
		    top: 0;
		    right: 0;
		    width: 0;
		    height: 0;
		    border: 5px solid #FFF;
		    border-left-color: rgba(0, 0, 0, 0);
		    border-bottom-color: rgba(0, 0, 0, 0);
		}

		.base_sent {
		  justify-content: flex-end;
		  align-items: flex-end;
		}
		.base_sent > .avatar:after {
		    content: "";
		    position: absolute;
		    bottom: 0;
		    left: 0;
		    width: 0;
		    height: 0;
		    border: 5px solid white;
		    border-right-color: transparent;
		    border-top-color: transparent;
		    box-shadow: 1px 1px 2px rgba(black, 0.2); // not quite perfect but close
		}

		.msg_sent > time{
		    float: right;
		}



		.msg_container_base::-webkit-scrollbar-track
		{
		    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
		    background-color: #F5F5F5;
		}

		.msg_container_base::-webkit-scrollbar
		{
		    width: 12px;
		    background-color: #F5F5F5;
		}

		.msg_container_base::-webkit-scrollbar-thumb
		{
		    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
		    background-color: #555;
		}

		.btn-group.dropup{
		    position:fixed;
		    left:0px;
		    bottom:0;
		}
	</style>
</head>
<body>
<div class="container">
    <div class="row chat-window col-xs-5 col-md-3" id="chat_window_1" style="margin-left:65%;">
        <div class="col-xs-12 col-md-12">
        	<div class="panel panel-default">
                <div class="panel-heading top-bar">
                    <div class="col-md-8 col-xs-8">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-comment"></span> Chat - Simsimi</h3>
                    </div>
                    <div class="col-md-4 col-xs-4" style="text-align: right;">
                        <a href="#"><span id="minim_chat_window" class="glyphicon glyphicon-minus icon_minim"></span></a>
                    </div>
                </div>
                <div id="conversation" class="panel-body msg_container_base">
                    <!-- chat conversation -->
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input id="chat-input" type="text" class="form-control input-sm chat_input" placeholder="Write your message here..." />
                        <span class="input-group-btn">
                        <button class="btn btn-primary btn-sm" id="send" onclick="main()">Send</button>
                        </span>
                    </div>
                </div>
    		</div>
        </div>
    </div>
    
    <div class="btn-group dropup">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-cog"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <select class="form-control" id="option" onchange="changeOption()">
            	<option selected>không biết thì hỏi</option>       <!-- 0 -->
			    <option>không biết trả lời bừa</option>   		   <!-- 1 -->
			    <li class="divider"></li>
  			</select>
        </ul>
    </div>
</div>
<script type="text/javascript">
	$(document).on('click', '.panel-heading span.icon_minim', function (e) {
	    var $this = $(this);
	    if (!$this.hasClass('panel-collapsed')) {
	        $this.parents('.panel').find('.panel-body').slideUp();
	        $this.addClass('panel-collapsed');
	        $this.removeClass('glyphicon-minus').addClass('glyphicon-plus');
	    } else {
	        $this.parents('.panel').find('.panel-body').slideDown();
	        $this.removeClass('panel-collapsed');
	        $this.removeClass('glyphicon-plus').addClass('glyphicon-minus');
	    }
	});
</script>
<script type="text/javascript">

	let config = {
		requestUrl : 'http://localhost:8080/simsimi/api_simsimi.php',
		key : 'phuongdz',
		action1 : 'chat',
		action2 : 'teach',
		option : 1,
		teaching : 0,
		teachQuestion : ''
	};
	class chat{
		constructor(message){
			this.left = `
				<div class="row msg_container base_receive">
	                <div class="col-md-2 col-xs-2 avatar">
	                    <img src="https://i.imgur.com/k0oDspw.png" class=" img-responsive ">
	                </div>
	                <div class="col-md-10 col-xs-10">
	                    <div class="messages msg_receive">
	                        <p>${message}</p>
	                    </div>
	                </div>
	            </div>
			`;
			this.right = `
				<div class="row msg_container base_sent">
	                <div class="col-md-10 col-xs-10">
	                    <div class="messages msg_sent" style="background-color:#3578e5">
	                        <p style="color:white">${message}</p>
	                    </div>
	                </div>
	                <div class="col-md-2 col-xs-2 avatar">
	                    <img src="http://i.imgur.com/EdxBYMh.jpg" class=" img-responsive ">
	                </div>
	            </div>
			`;
		}
	}
	function chating(question){
		if(config.teaching == 1){
			$.ajax({
		        url: config.requestUrl,
		        type: 'GET',
		        dataType: 'json',
		        data: {
		            'key' : config.key,
		            'action' : config.action2,
		            'ask' : config.teachQuestion,
		            'ans' : question
		        },
		        success: function (result) {
		        	let chat_right = new chat(question);
		        	let reply = `ok, vậy nếu lần sau m hỏi là -${config.teachQuestion}- có thể t sẽ trả lời là -${question}-`;
		        	let chat_left = new chat(reply);
		        	document.getElementById('conversation').insertAdjacentHTML('beforeend',chat_right.right);
		        	document.getElementById('conversation').insertAdjacentHTML('beforeend',chat_left.left);
		        	let chat_area = document.getElementById("conversation");
	        		chat_area.scrollTop = chat_area.scrollHeight;
	        		config.teaching = 0;
	        		config.teachQuestion = '';
		        }
		    });
		}
		else if(config.teaching == 0){
			$.ajax({
		        url: config.requestUrl,
		        type: 'GET',
		        dataType: 'json',
		        data: {
		            'key' : config.key,
		            'action' : config.action1,
		            'message' : question
		        },
		        success: function (result) {
		        	if(config.option == 0 || result.result == 200){
		        		let chat_right = new chat(question);
			        	let chat_left = new chat(result.message);
			        	document.getElementById('conversation').insertAdjacentHTML('beforeend',chat_right.right);
			        	document.getElementById('conversation').insertAdjacentHTML('beforeend',chat_left.left);
			        	let chat_area = document.getElementById("conversation");
		        		chat_area.scrollTop = chat_area.scrollHeight;
		        	}else if(config.option == 1 && result.result == 201){
		        		let chat_right = new chat(question);
			        	let chat_left = new chat('T không hiểu, m hỏi thế thì t phải trả lời ntn nhỉ?');
			        	document.getElementById('conversation').insertAdjacentHTML('beforeend',chat_right.right);
			        	document.getElementById('conversation').insertAdjacentHTML('beforeend',chat_left.left);
			        	let chat_area = document.getElementById("conversation");
		        		chat_area.scrollTop = chat_area.scrollHeight;
		        		config.teaching = 1;
		        		config.teachQuestion = question;
		        	}
		        }
		    });
		}
	}
	function changeOption(){
		if(config.option == 0){
			config.option = 1;
		}else if(config.option == 1){
			config.option = 0;
		}
	}
    function main(){
    	let message = document.getElementById('chat-input').value;
    	if(message){
    		chating(message);
    		document.getElementById('chat-input').value = '';
    	}
    }
    main();
</script>
</body>
</html>