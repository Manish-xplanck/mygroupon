Validator = {
	Require : /.+/,
	Email : /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
	Phone : /^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/,
	Mobile : /^((\(\d{2,3}\))|(\d{3}\-))?13\d{9}$/,
	Url : /^(https?\:\/\/|www\.)([A-Za-z0-9_\-]+\.)+[A-Za-z]{2,4}(\/[\w\d\/=\?%\-\&_~`@\[\]\:\+\#]*([^<>\'\"\n])*)?$/,
	IdCard : "this.IsIdCard(value)",
	Currency : /^\d+(\.\d+)?$/,
	Number : /^\d+$/,
	Zip : /^[1-9]\d{5}$/,
	QQ : /^[1-9]\d{4,9}$/,
	Integer : /^[-\+]?\d+$/,
	Double : /^[-\+]?\d+(\.\d+)?$/,
	English : /^[A-Za-z]+$/,
	Chinese :  /^[\u0391-\uFFE5]+$/,
	Username : /^[a-z]\w{3,}$/i,
	UnSafe : /^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/,
	IsSafe : function(str){return !this.UnSafe.test(str);},
	SafeString : "this.IsSafe(value)",
	Truename : "this.IsTruename(value)",
	Filter : "this.DoFilter(value, getAttribute('accept'))",
	Limit : "this.limit(value.length,getAttribute('min'),  getAttribute('max'))",
	LimitB : "this.limit(this.LenB(value), getAttribute('min'), getAttribute('max'))",
	Date : "this.IsDate(value, getAttribute('min'), getAttribute('format'))",
	Repeat : "value == obj[getAttribute('to')].value",
	Range : "getAttribute('min') < (value|0) && (value|0) < getAttribute('max')",
	Compare : "this.compare(value,getAttribute('operator'),getAttribute('to'))",
	Custom : "this.Exec(value, getAttribute('regexp'))",
	Group : "this.MustChecked(getAttribute('name'), getAttribute('min'), getAttribute('max'))",
	ErrorItem : [document.forms[0]],
	ErrorMessage : ["ÒÔÏÂÔ­Òòµ¼ÖÂÌá½»Ê§°Ü£º\t\t\t\t"],
	Validate : function(theForm,mode,field,showError){
		var displayError=true;
		if(showError!=undefined)displayError=showError;
		var obj = theForm || event.srcElement;
		var count = obj.elements.length;
		this.ErrorMessage.length = 1;
		this.ErrorItem.length = 1;
		this.ErrorItem[0] = obj;
		for(var i=0;i<count;i++){
			with(obj.elements[i]){
				var _dataType = getAttribute("dataType");
				if(typeof(field)!="undefined")
				{
					if(name!=field){continue;}
				}
				if(typeof(_dataType) == "object" || typeof(this[_dataType]) == "undefined")  continue;
				this.ClearState(obj.elements[i]);
				if(getAttribute("require") == "false" && value == "") continue;
				switch(_dataType){
					case "IdCard" :
					case "Date" :
					case "Repeat" :
					case "Range" :
					case "Compare" :
					case "Custom" :
					case "Group" : 
					case "Limit" :
					case "LimitB" :
					case "SafeString" :
					case "Truename" :
					case "Filter" :
						if(!eval(this[_dataType]))	{
							this.AddError(i, getAttribute("msg"));
						}
						break;
					default :
						if(!this[_dataType].test(value)){
							this.AddError(i, getAttribute("msg"));
						}
						break;
				}
			}
		}
		if(this.ErrorMessage.length > 1){
			//if(displayError==false)return false;
			if(displayError==false)
			{
				return false;
			}
			mode = mode || 1;
			var errCount = this.ErrorItem.length;
			switch(mode){
			case 2 :
				for(var i=1;i<errCount;i++)
					this.ErrorItem[i].style.color = "red";
			case 1 :
				alert(this.ErrorMessage.join("\n"));
				field || this.ErrorItem[1].focus();
				break;
			case 3 :
				for(var i=1;i<errCount;i++){
				try{
					var div = document.createElement("DIV");
					div.id = "__ErrorMessagePanel";
					div.style.color="#000";
					div.style.margin = "5px 5px 0px 0px";
					div.style.padding = "4px 4px 1px 4px";
					div.style.border = "1px solid #F60";
					div.style.background = "#fff2e9";
					this.ErrorItem[i].parentNode.appendChild(div);
					div.innerHTML = this.ErrorMessage[i].replace(/\d+:/,"<img src='templates/default/images/check_error.gif'> ");
					}
					catch(e){alert(e.description);}
				}
				field || this.ErrorItem[1].focus();
				break;
			default :
				alert(this.ErrorMessage.join("\n"));
				break;
			}
			return false;
		}
		return true;
	},
	limit : function(len,min, max){
		min = min || 0;
		max = max || Number.MAX_VALUE;
		return min <= len && len <= max;
	},
	LenB : function(str){
		return str.replace(/[^\x00-\xff]/g,"**").length;
	},
	ClearState : function(elem){
		with(elem){
			if(style.color == "red")
				style.color = "";
			var lastNode = parentNode.childNodes[parentNode.childNodes.length-1];
			if(lastNode.id == "__ErrorMessagePanel")
			{
				parentNode.removeChild(lastNode);
			}

		}
	},
	AddError : function(index, str){
		this.ErrorItem[this.ErrorItem.length] = this.ErrorItem[0].elements[index];
		this.ErrorMessage[this.ErrorMessage.length] = this.ErrorMessage.length + ":" + str;
	},
	Exec : function(op, reg){
		return new RegExp(reg,"g").test(op);
	},
	compare : function(op1,operator,op2){
		switch (operator) {
			case "NotEqual":
				return (op1 != op2);
			case "GreaterThan":
				return (op1 > op2);
			case "GreaterThanEqual":
				return (op1 >= op2);
			case "LessThan":
				return (op1 < op2);
			case "LessThanEqual":
				return (op1 <= op2);
			default:
				return (op1 == op2);            
		}
	},
	MustChecked : function(name, min, max){
		var groups = document.getElementsByName(name);
		var hasChecked = 0;
		min = min || 1;
		max = max || groups.length;
		for(var i=groups.length-1;i>=0;i--)
			if(groups[i].checked) hasChecked++;
		return min <= hasChecked && hasChecked <= max;
	},
	DoFilter : function(input, filter){
return new RegExp("^.+\.(?=EXT)(EXT)$".replace(/EXT/g, filter.split(/\s*,\s*/).join("|")), "gi").test(input);
	},
	IsIdCard : function(number){
		var date, Ai;
		var verify = "10x98765432";
		var Wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
		var area = ['','','','','','','','','','','','±±¾©','Ìì½ò','ºÓ±±','É½Î÷','ÄÚÃÉ¹Å','','','','','','ÁÉÄþ','¼ªÁÖ','ºÚÁú½­','','','','','','','','ÉÏº£','½­ËÕ','Õã½­','°²Î¢','¸£½¨','½­Î÷','É½¶«','','','','ºÓÄÏ','ºþ±±','ºþÄÏ','¹ã¶«','¹ãÎ÷','º£ÄÏ','','','','ÖØÇì','ËÄ´¨','¹óÖÝ','ÔÆÄÏ','Î÷²Ø','','','','','','','ÉÂÎ÷','¸ÊËà','Çàº£','ÄþÏÄ','ÐÂ½®','','','','','','Ì¨Íå','','','','','','','','','','Ïã¸Û','°ÄÃÅ','','','','','','','','','¹úÍâ'];
		var re = number.match(/^(\d{2})\d{4}(((\d{2})(\d{2})(\d{2})(\d{3}))|((\d{4})(\d{2})(\d{2})(\d{3}[x\d])))$/i);
		if(re == null) return false;
		if(re[1] >= area.length || area[re[1]] == "") return false;
		if(re[2].length == 12){
			Ai = number.substr(0, 17);
			date = [re[9], re[10], re[11]].join("-");
		}
		else{
			Ai = number.substr(0, 6) + "19" + number.substr(6);
			date = ["19" + re[4], re[5], re[6]].join("-");
		}
		if(!this.IsDate(date, "ymd")) return false;
		var sum = 0;
		for(var i = 0;i<=16;i++){
			sum += Ai.charAt(i) * Wi[i];
		}
		Ai +=  verify.charAt(sum%11);
		return (number.length ==15 || number.length == 18 && number == Ai);
	},
 	IsTruename:function (name)
  	{
  		name.toString();
		if(name.length==0)return false;
		var surnameList=['ÍòÙ¹','Ë¾Âí','ÉÏ¹Ù','Å·Ñô','ÏÄºî','Öî¸ð','ÎÅÈË','¶«·½','ºÕÁ¬','»Ê¸¦','Î¾³Ù','¹«Ñò','å£Ì¨','¹«Ò±','×ÚÕþ','å§Ñô','´¾ÓÚ','µ¥ÓÚ','Ì«Êå','ÉêÍÀ','¹«Ëï','ÖÙËï','ÐùÔ¯','Áîºü','ÐìÀë','ÓîÎÄ','³¤Ëï','Ä½ÈÝ','Ë¾Í½','Ë¾¿Õ','ÕÔ','Ç®','Ëï','´Ô','Àî','ÖÜ','Îâ','Ö£','Íõ','·ë','³Â','ñÒ','ÎÀ','½¯','Éò','º«','Ñî','Öì','ÇØ','ÓÈ','Ðí','ºÎ','ÂÀ','Ê©','ÕÅ','¿×','²Ü','ÑÏ','»ª','½ð','Îº','ÌÕ','½ª','ÆÝ','Ð»','×Þ','Ó÷','°Ø','Ë®','ñ¼','ÕÂ','ÔÆ','ËÕ','ÅË','¸ð','ÞÉ','·¶','Åí','ÀÉ','Â³','Î¤','²ý','Âí','Ãç','·ï','»¨','·½','Óá','ÈÎ','Ô¬','Áø','Ûº','±«','Ê·','ÌÆ','·Ñ','Á®','á¯','Ñ¦','À×','ºØ','Äß','ÌÀ','ëø','Òó','ÂÞ','±Ï','ºÂ','Úù','°²','³£','ÀÖ','ÓÚ','Ê±','¸µ','Æ¤','±å','Æë','¿µ','Îé','Óà','Ôª','²·','¹Ë','ÃÏ','Æ½','»Æ','ºÍ','ÄÂ','Ïô','Òü','Ò¦','ÉÛ','Õ¿','Íô','Æî','Ã«','Óí','µÒ','Ã×','±´','Ã÷','ê°','¼Æ','·ü','³É','´÷','Ì¸','ËÎ','Ã©','ÅÓ','ÐÜ','¼Í','Êæ','Çü','Ïî','×£','¶­','Áº','¶Å','Èî','À¶','ãÉ','Ï¯','¼¾','Âé','Ç¿','¼Ö','Â·','Â¦','Î£','½­','Í¯','ÑÕ','¹ù','Ã·','Ê¢','ÁÖ','µó','ÖÓ','Ðì','Çñ','Âæ','¸ß','ÏÄ','²Ì','Ìï','·®','ºú','Áè','»ô','ÓÝ','Íò','Ö§','¿Â','êÃ','¹Ü','Â¬','Äª','¿Â','·¿','ôÃ','çÑ','¸É','½â','Ó¦','×Ú','¶¡','Ðû','êÚ','µË','Óô','µ¥','º¼','ºé','°ü','Öî','×ó','Ê¯','´Þ','¼ª','Å¥','¹¨','³Ì','ïú','ÐÏ','»¬','Åá','Â½','ÈÙ','ÎÌ','Ü÷','Ñò','ÓÚ','»Ý','Õç','Çú','¼Ò','·â','ÜÇ','ôà','´¢','½ù','¼³','Úû','ÃÓ','ËÉ','¾®','¶Î','¸»','Î×','ÎÚ','½¹','°Í','¹­','ÄÁ','Úó','É½','¹È','³µ','ºî','åµ','Åî','È«','Û­','°à','Ñö','Çï','ÖÙ','ÒÁ','¹¬','Äþ','³ð','èï','±©','¸Ê','î×','Àú','ÈÖ','×æ','Îä','·û','Áõ','¾°','Õ²','Êø','Áú','Ò¶','ÐÒ','Ë¾','ÉØ','Û¬','Àè','¼»','äß','Ó¡','ËÞ','°×','»³','ÆÑ','Û¢','´Ó','¶õ','Ë÷','ÏÌ','¼®','Àµ','×¿','Ýþ','ÍÀ','ÃÉ','³Ø','ÇÇ','Ñô','Óô','ñã','ÄÜ','²Ô','Ë«','ÎÅ','Ý·','µ³','µÔ','Ì·','¹±','ÀÍ','åÌ','¼§','Éê','·ö','¶Â','È½','Ô×','Ûª','Óº','È´','è³','É£','¹ð','å§','Å£','ÊÙ','Í¨','±ß','ìè','Ñà','¼½','ÆÖ','ÉÐ','Å©','ÎÂ','±ð','×¯','êÌ','²ñ','öÄ','ÑÖ','³ä','Ä½','Á¬','Èã','Ï°','»Â','°¬','Óã','ÈÝ','Ïò','¹Å','Ò×','É÷','¸ê','ÁÎ','â×','ÖÕ','ôß','¾Ó','ºâ','²½','¶¼','¹¢','Âú','ºë','¿ï','¹ú','ÎÄ','¿Ü','¹ã','Â»','ãÚ','¶«','Å·','ì¯','ÎÖ','Àû','Îµ','Ô½','Ùç','Â¡','Ê¦','¹®','ØÇ','Äô','êË','¹´','°½','ÈÚ','Àä','ö¤','ÐÁ','ãÛ','ÄÇ','¼ò','ÈÄ','¿Õ','Ôø','Îã','É³','Ø¿','Ñø','¾Ï','Ðë','·á','³²','¹Ø','Øá','Ïà','²é','ºó','¾£','ºì','ÓÎ','óÃ','È¨','´þ','îÁ','Òæ','»¸','¹«'];
		for(var i=0;i<surnameList.length;i++)
		{
			if(name.indexOf(surnameList[i])===0)
			{
				if(name.length<=surnameList[i].length || name.length>surnameList[i].length+2)return false;
				return this.Chinese.test(name);
			}
		}
		return false;
	},
	IsDate : function(op, formatString){
		formatString = formatString || "ymd";
		var m, year, month, day;
		switch(formatString){
			case "ymd" :
				m = op.match(new RegExp("^((\\d{4})|(\\d{2}))([-./])(\\d{1,2})\\4(\\d{1,2})$"));
				if(m == null ) return false;
				day = m[6];
				month = m[5]*1;
				year =  (m[2].length == 4) ? m[2] : GetFullYear(parseInt(m[3], 10));
				break;
			case "dmy" :
				m = op.match(new RegExp("^(\\d{1,2})([-./])(\\d{1,2})\\2((\\d{4})|(\\d{2}))$"));
				if(m == null ) return false;
				day = m[1];
				month = m[3]*1;
				year = (m[5].length == 4) ? m[5] : GetFullYear(parseInt(m[6], 10));
				break;
			default :
				break;
		}
		if(!parseInt(month)) return false;
		month = month==0 ?12:month;
		var date = new Date(year, month-1, day);
        return (typeof(date) == "object" && year == date.getFullYear() && month == (date.getMonth()+1) && day == date.getDate());
		function GetFullYear(y){return ((y<30 ? "20" : "19") + y)|0;}
	},
	SetRegular:function (form,validateList)
	{
		if(typeof form=='string')form=document.getElementById(form) || document.forms[form];
		for (var i=0;i<form.length ;i++ )
		{
			var ele=form.elements[i];
			if(typeof validateList[ele.name]!='undefined')
			{
				this.SetAttr(ele,validateList[ele.name]);
			}
		}
	},
	SetAttr:function (element,attributeList)
	{
		if(attributeList==undefined)return false;
		for (var i in attributeList)
		{
			element.setAttribute(i,attributeList[i]);
		}
	}
 }