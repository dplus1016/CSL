<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="../../favicon.ico">

  <title><?php echo $OJ_NAME?></title>  
  <?php include("template/$OJ_TEMPLATE/css.php");?>     

  <style>
    #source {
      width: 80%;
      height: 600px;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php include("template/$OJ_TEMPLATE/nav.php");?>     
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <center>
        <script src="include/checksource.js"></script>
        
        <form id=frmSolution action="submit.php" method="post" onsubmit='do_submit()'>
          <?php if (isset($id)){?>
            <br>
            <?php echo $MSG_PROBLEM_ID." : "?> <span class=blue><?php echo $id?></span>
            <br>
            <input id=problem_id type='hidden' value='<?php echo $id?>' name="id" >
            <br>
          <?php } else {
          //$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
          //if ($pid>25) $pid=25;
          ?>
            <br>
            <?php echo $MSG_PROBLEM_ID." : "?> <span class=blue><?php echo chr($pid+ord('A'))?></span>
            <br> of Contest <span class=blue> <?php echo $cid?> </span>
            <br>
            <input id="cid" type='hidden' value='<?php echo $cid?>' name="cid">
            <input id="pid" type='hidden' value='<?php echo $pid?>' name="pid">
          <?php }?>

          <span id="language_span"><?php echo $MSG_LANG?>:
            <select id="language" name="language" onChange="reloadtemplate($(this).val());" >
              <?php
                $lang_count=count($language_ext);
                
                if(isset($_GET['langmask']))
                  $langmask=$_GET['langmask'];
                else
                  $langmask=$OJ_LANGMASK;
                
                $lang=(~((int)$langmask))&((1<<($lang_count))-1);
                
                if(isset($_COOKIE['lastlang'])) $lastlang=$_COOKIE['lastlang'];
                else $lastlang=0;
                
                for($i=0;$i<$lang_count;$i++){
                  if($lang&(1<<$i))
                    echo"<option value=$i ".( $lastlang==$i?"selected":"").">".$language_name[$i]."</option>";
                }
              ?>
            </select>
          </span>
          <?php if($OJ_ACE_EDITOR){ ?>
            <pre style="width:80%;height:600;font-size:13pt" cols=180 rows=20 id="source"><?php if ($view_src=="") { if ($language_name[$lastlang]=="C" || $language_name[$lastlang]=="C++") {echo "\n\n//제출할 프로그래밍언어 종류를 먼저 선택한 후\n//작성한 코드를 붙여넣고 아래에서 제출 버튼을 누르세요.\n\n//코드 복사하기: 코드 편집기 클릭 - 전체선택(Ctrl+a) - 복사하기(Ctrl+c)\n//코드 붙여넣기:   이 편집창 클릭 - 전체선택(Ctrl+a) - 붙여넣기(Ctrl+v)";} if ($language_name[$lastlang]=="Python") {echo "\n\n#제출할 프로그래밍언어 종류를 먼저 선택한 후\n#작성한 코드를 붙여넣고 아래에서 제출 버튼을 누르세요.\n\n#코드 복사하기: 코드 편집기 클릭 - 전체선택(Ctrl+a) - 복사하기(Ctrl+c)\n#코드 붙여넣기:   이 편집창 클릭 - 전체선택(Ctrl+a) - 붙여넣기(Ctrl+v)";} } else {echo htmlentities($view_src,ENT_QUOTES,"UTF-8");}?></pre>
            <input type=hidden id="hide_source" name="source" value=""/>
          <?php }else{ ?>
            <textarea style="width:80%;height:600" cols=180 rows=20 id="source" name="source"> <?php echo htmlentities($view_src,ENT_QUOTES,"UTF-8")?></textarea>
          <?php }?>

          <?php if (isset($OJ_TEST_RUN)&&$OJ_TEST_RUN){?>
            <?php echo $MSG_Input?>:
            <textarea style="width:30%" cols=40 rows=5 id="input_text" name="input_text" ><?php echo $view_sample_input?></textarea>

            <?php echo $MSG_Output?>:
            <textarea style="width:30%" cols=10 rows=5 id="out" name="out" disabled="true" >SHOULD BE:<?php echo $view_sample_output?></textarea>
          <?php } ?>

          <?php if($OJ_VCODE){?>
            <?php echo $MSG_VCODE?>:
            <input name="vcode" size=4 type=text> <img id="vcode" alt="click to change" onclick="this.src='vcode.php?'+Math.random()">*
          <?php }?>
          <br><br>

          <input id="Submit" class="btn btn-info btn-sm" type=button value="<?php echo $MSG_SUBMIT?>" onclick="do_submit();" >

          <?php if (isset($OJ_ENCODE_SUBMIT)&&$OJ_ENCODE_SUBMIT){?>
            <input class="btn btn-success" title="WAF gives you reset ? try this." type=button value="Encoded <?php echo $MSG_SUBMIT?>"  onclick="encoded_submit();">
            <input type=hidden id="encoded_submit_mark" name="reverse2" value="reverse">
          <?php }?>

          <?php if (isset($OJ_TEST_RUN)&&$OJ_TEST_RUN){?>
            <input id="TestRun" class="btn btn-info" type=button value="<?php echo $MSG_TR?>" onclick=do_test_run();>
            <span class="btn" id=result>状态</span>
          <?php }?>

          <?php if (isset($OJ_BLOCKLY)&&$OJ_BLOCKLY){?>
            <input id="blockly_loader" type=button class="btn" onclick="openBlockly()" value="<?php echo $MSG_BLOCKLY_OPEN?>" style="color:white;background-color:rgb(169,91,128)">
            <input id="transrun" type=button  class="btn" onclick="loadFromBlockly() " value="<?php echo $MSG_BLOCKLY_TEST?>" style="display:none;color:white;background-color:rgb(90,164,139)">
            <div id="blockly" class="center">Blockly</div>
          <?php }?>

        </form>
      </center>
    </div>
  </div> <!-- /container -->


  <!-- Bootstrap core JavaScript
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <?php include("template/$OJ_TEMPLATE/js.php");?>      
  
  <script>
    var sid = 0;
    var i = 0;
    var using_blockly = false;
    var judge_result = [<?php
      foreach($judge_result as $result){
        echo "'$result',";
      }?>''];
    
    function print_result(solution_id)
    {
      sid = solution_id;
      $("#out").load("status-ajax.php?tr=1&solution_id="+solution_id);
    }
    
    function fresh_result(solution_id)
    {
      var tb = window.document.getElementById('result');
      if (solution_id==undefined) {
        tb.innerHTML="Vcode Error!";    
        if($("#vcode")!=null) $("#vcode").click();
        
        return ;
      }

      sid=solution_id;
      var xmlhttp;
      if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      } else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }

      xmlhttp.onreadystatechange=function()
      {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          var r=xmlhttp.responseText;
          var ra=r.split(",");
          //alert(r);
          // alert(judge_result[r]);
          var loader="<img width=18 src=image/loader.gif>";
          var tag="span";

          if(ra[0]<4)
            tag="span disabled=true";
          else
          {
            if(ra[0]==11)
              tb.innerHTML="<a href='ceinfo.php?sid="+solution_id+"' class='badge badge-info' target=_blank>"+judge_result[ra[0]]+"</a>";
            else
              tb.innerHTML="<a href='reinfo.php?sid="+solution_id+"' class='badge badge-info' target=_blank>"+judge_result[ra[0]]+"</a>";
          }

          if(ra[0]<4)tb.innerHTML+=loader;
          
          tb.innerHTML="Memory:"+ra[1];
          tb.innerHTML+="Time:"+ra[2];

          if(ra[0]<4)
            window.setTimeout("fresh_result("+solution_id+")",2000);
          else {
            window.setTimeout("print_result("+solution_id+")",2000);
            count=1;
          }
        }
      }

      xmlhttp.open("GET","status-ajax.php?solution_id="+solution_id,true);
      xmlhttp.send();
    }

    function getSID(){
      var ofrm1 = document.getElementById("testRun").document;
      var ret="0";
      if (ofrm1==undefined)
      {
        ofrm1 = document.getElementById("testRun").contentWindow.document;
        var ff = ofrm1;
        ret=ff.innerHTML;
      }
      else
      {
        var ie = document.frames["frame1"].document;
        ret=ie.innerText;
      }
      return ret+"";
    }

    var count=0;

    function encoded_submit(){
      var mark="<?php echo isset($id)?'problem_id':'cid';?>";
      var problem_id=document.getElementById(mark);

      if(typeof(editor) != "undefined")
        $("#hide_source").val(editor.getValue());
      if(mark=='problem_id')
        problem_id.value='<?php if(isset($id)) echo $id?>';
      else
        problem_id.value='<?php if(isset($cid))echo $cid?>';

      document.getElementById("frmSolution").target="_self";
      document.getElementById("encoded_submit_mark").name="encoded_submit";
      var source=$("#source").val();

      if(typeof(editor) != "undefined") {
        source=editor.getValue();
        $("#hide_source").val(encode64(utf16to8(source)));
      }else{
        $("#source").val(encode64(utf16to8(source)));
      }
      //      source.value=source.value.split("").reverse().join("");
      //      alert(source.value);
      document.getElementById("frmSolution").submit();
    }

    function do_submit(){
      <?php if($OJ_LONG_LOGIN==true&&isset($_COOKIE[$OJ_NAME."_user"])&&isset($_COOKIE[$OJ_NAME."_check"]))echo"let xhr=new XMLHttpRequest();xhr.open('GET','login.php',true);xhr.send();";?>
      if(using_blockly) 
        translate();
     
      if(typeof(editor) != "undefined"){ 
        $("#hide_source").val(editor.getValue());
      }

      var mark="<?php echo isset($id)?'problem_id':'cid';?>";
      var problem_id=document.getElementById(mark);

      if(mark=='problem_id')
        problem_id.value='<?php if (isset($id))echo $id?>';
      else
        problem_id.value='<?php if (isset($cid))echo $cid?>';

      document.getElementById("frmSolution").target="_self";
      document.getElementById("frmSolution").submit();
    }

    var handler_interval;

    function do_test_run(){
      if( handler_interval) window.clearInterval( handler_interval);

      var loader="<img width=18 src=image/loader.gif>";
      var tb=window.document.getElementById('result');
      var source=$("#source").val();

      if(typeof(editor) != "undefined") {
        source = editor.getValue();
        $("#hide_source").val(source);
      }

      if(source.length<10) return alert("too short!");

      if(tb!=null) tb.innerHTML=loader;

      var mark="<?php echo isset($id)?'problem_id':'cid';?>";
      var problem_id=document.getElementById(mark);
      problem_id.value=-problem_id.value;
      document.getElementById("frmSolution").target="testRun";
      //$("#hide_source").val(editor.getValue());
      //document.getElementById("frmSolution").submit();
      $.post("submit.php?ajax",$("#frmSolution").serialize(),function(data){fresh_result(data);});
      $("#Submit").prop('disabled', true);
      $("#TestRun").prop('disabled', true);
      problem_id.value=-problem_id.value;
      count=20;
      handler_interval= window.setTimeout("resume();",1000);
    }

    function resume(){
      count--;
      var s=$("#Submit")[0];
      var t=$("#TestRun")[0];
      if(count<0){
        s.disabled=false;
        if(t!=null)t.disabled=false;
        
        s.value="<?php echo $MSG_SUBMIT?>";
        
        if(t!=null)t.value="<?php echo $MSG_TR?>";
        
        if( handler_interval) window.clearInterval( handler_interval);
        
        if($("#vcode")!=null) $("#vcode").click();

        $("#csrf").load("csrf.php?"+sid);
        
      }else{
        s.value="<?php echo $MSG_SUBMIT?>("+count+")";
        
        if(t!=null)t.value="<?php echo $MSG_TR?>("+count+")";
        
        window.setTimeout("resume();",1000);
      }
    }

    function switchLang(lang){
      var langnames=new Array("c_cpp","c_cpp","pascal","java","ruby","sh","python","php","perl","csharp","objectivec","vbscript","scheme","c_cpp","c_cpp","lua","javascript","golang");
      editor.getSession().setMode("ace/mode/"+langnames[lang]);
    }

    function reloadtemplate(lang){
      console.log("lang="+lang);
      document.cookie="lastlang="+lang;
      //alert(document.cookie);
      var url=window.location.href;
      var i=url.indexOf("sid=");
      if(i!=-1) url=url.substring(0,i-1);
      
      <?php if (isset($OJ_APPENDCODE)&&$OJ_APPENDCODE){?>
        if(confirm("<?php echo  $MSG_LOAD_TEMPLATE_CONFIRM?>"))
          document.location.href=url;
      <?php }?>
      switchLang(lang);
    }


    function openBlockly(){
      $("#frame_source").hide();
      $("#TestRun").hide();
      $("#language")[0].scrollIntoView();
      $("#language").val(6).hide();
      $("#language_span").hide();
      $("#EditAreaArroundInfos_source").hide();
      $('#blockly').html('<iframe name=\'frmBlockly\' width=90% height=580 src=\'blockly/demos/code/index.html\'></iframe>'); 
      $("#blockly_loader").hide();
      $("#transrun").show();
      $("#Submit").prop('disabled', true);
      using_blockly=true;
    }

    function translate(){
      var blockly=$(window.frames['frmBlockly'].document);
      var tb=blockly.find('td[id=tab_python]');
      var python=blockly.find('pre[id=content_python]');
      tb.click();
      blockly.find('td[id=tab_blocks]').click();
      if(typeof(editor) != "undefined") editor.setValue(python.text());
      else $("#source").val(python.text());
      $("#language").val(6);
    }

    function loadFromBlockly(){
      translate();
      do_test_run();
      $("#frame_source").hide();
     //  $("#Submit").prop('disabled', false);
    }
  </script>

  <script language="Javascript" type="text/javascript" src="include/base64.js"></script>

  <?php if($OJ_ACE_EDITOR){ ?>
    <script src="ace/ace.js"></script>

    <script src="ace/ext-language_tools.js"></script>

    <script>
      ace.require("ace/ext/language_tools");
      var editor = ace.edit("source");
      editor.setTheme("ace/theme/chrome");
      switchLang(<?php echo isset($lastlang)?$lastlang:0 ;  ?>);
      editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: true
      });
    </script>
  <?php }?>

  <?php if ($OJ_VCODE) { ?>
    <script>
      $(document).ready(function () {
        $("#vcode").attr("src", "vcode.php?" + Math.random());
      })
    </script>
  <?php } ?>
  </body>
</html>
