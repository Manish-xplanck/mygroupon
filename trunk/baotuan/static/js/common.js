//###########################
//   Name:�����Ź� 
//   Link:tttuangou.net
//   Date:2011.01.07
//   Intro:��JS
//#############################
var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
var is_safari = (userAgent.indexOf('webkit') != -1 || userAgent.indexOf('safari') != -1);

//����URL��ַ
function copyText(_sTxt){
	_sTxt=document.title+" "+_sTxt;
	if(is_ie) {
		clipboardData.setData('Text',_sTxt);
		alert ("��ַ��"+_sTxt+"��\n\n�Ѿ����Ƶ����ļ�������\n������ʹ��Ctrl+V��ݼ�ճ������Ҫ�ĵط�");
	} else {
		prompt("��ʹ��Ctrl+C��ݼ��������������:",_sTxt); 
	}
	return false;
}
//TAB�л���ť
function setTab(name,cursel,n){
	for(i=1;i<=n;i++){
	var menu=document.getElementById(name+i);
	var con=document.getElementById("con_"+name+"_"+i);
	menu.className=i==cursel?"hover":"";
	con.style.display=i==cursel?"block":"none";
}
}
//TAB�л���ť2
function hideAllClips(){
	for (i=1; i<5; i++){
		var allClips="topNews_"+i;
		var clipNum="clipNum"+i;
		document.getElementById(allClips).style.display="none";
		document.getElementById(clipNum).className="ts3_mbtn2";
		}
} 
function clip_Switch(n){
	var curClip="topNews_"+n;
	var curClipNum="clipNum"+n;
	hideAllClips();
	document.getElementById(curClip).style.display="block";
	document.getElementById(curClipNum).className="ts3_mbtn1";
	scrollNewsCt=n; 
} 

//��񶩵�����
(function($){
    $.fn.jExpand = function(){
        var element = this;

        $(element).find("tr:odd").addClass("odd");
        $(element).find("tr:not(.odd)").hide();
        $(element).find("tr:first-child").show();

        $(element).find("tr.odd").click(function() {
            $(this).next("tr").toggle();
        });
    }    
})(jQuery); 


//����ģ������ 2010.06.23
var arrCSS=[
    ["<img src='templates/default/images/placeholder.gif' width='30' height='20' class='themes themes1' title='Ĭ�Ϸ��'>","templates/default/styles/t1.css"],
    ["<img src='templates/default/images/placeholder.gif' width='30' height='20' class='themes themes2' title='��ɫ���'>","templates/tpl_2/styles/t1.css"],
    ["<img src='templates/default/images/placeholder.gif' width='30' height='20' class='themes themes3' title='ϲ����'>","templates/tpl_3/styles/t1.css"],
	["<img src='templates/default/images/placeholder.gif' width='30' height='20' class='themes themes4' title='ˮ�����'>","templates/tpl_4/styles/t1.css"],
	["<img src='templates/default/images/placeholder.gif' width='30' height='20' class='themes themes5' title='��ɫ���'>","templates/tpl_5/styles/t1.css"],
    ""
];

// ��ȡ��ʽ������
function v(){
	return;
}

// ���� Cookies ��¼ 
function writeCookie(name, value, expiredays){ 
	if (!expiredays) expiredays = 365;
	exp = new Date(); 
	exp.setTime(exp.getTime() + (86400 *1000 *expiredays));
	document.cookie = name + "=" + escape(value) + "; expires=" + exp.toGMTString() + "; path=/"; 
} 

function readCookie(name){ 
	var search; 
	search = name + "="; 
	offset = document.cookie.indexOf(search); 
	if (offset != -1) { 
		offset += search.length; 
		end = document.cookie.indexOf(";", offset);  
		if (end == -1){
			end = document.cookie.length;
		}
		return unescape(document.cookie.substring(offset, end)); 
	}else{
		return "";
	}
}

// Ĭ����ʽ��
function writeCSS(){
  for(var i=0;i<arrCSS.length-1;i++){
    document.write('<link title="styles'+i+'" href="'+arrCSS[i][1]+'" rel="stylesheet" disabled="true" type="text/css" />');
  }
    setStyleSheet(readCookie("stylesheet"));
}

function writeCSSLinks(){
  for(var i=0;i<arrCSS.length-1;i++){
    if(i>0) document.write('  '); 
    document.write('<a href="javascript:v()" onclick="setStyleSheet(\'styles'+i+'\')">'+arrCSS[i][0]+'</a>');
  } 
} 
function setStyleSheet(strCSS){
  var objs=document.getElementsByTagName("link");
  var intFound=0;
  for(var i=0;i<objs.length;i++){
    if(objs[i].type.indexOf("css")>-1&&objs[i].title){
      objs[i].disabled = true;
      if(objs[i].title==strCSS) intFound=i;
    }
  }
  
  objs[intFound].disabled = false; 
  writeCookie("stylesheet",objs[intFound].title);
}

writeCSS();
setStyleSheet(readCookie("stylesheet"));

// ������ʾ������
function ShowHideDiv(init) {
	if(document.getElementById("Sright").style.display == "block"){
	    document.getElementById("Sright").style.display = "none";
  }
  else{
  	document.getElementById("Sright").style.display = "block";
  }
}