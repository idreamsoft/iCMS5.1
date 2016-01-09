function menuGroup(obj,url){
    var id=$(obj).attr("id");
    $(".main_menu dl dd").removeClass('active').addClass('other');
    $("#"+id).addClass('active');
    $(".left_menu ul").css("display","none");
    $("."+id).css("display","block");
    $("."+id+" ul").css("display","block");
    $(".main_menu_title_left").html($(obj).text());
    $(".left").css("width","180px");
    $(".main_menu_title_left").css("display","block");
    $("iframe").attr("src",url);
}
function dropDown(){
    var timeOutID 	= null;
    var hidedropDown 	= function(){
        $("#dropDown").hide();
    };
    $(".dropDown").mouseover(function(){
        window.clearTimeout(timeOutID);
        $("#dropDown").remove();
        var id		= $(this).attr("id");
        var offset	= $(this).offset();
        var dropDown= $('<div id="dropDown"></div>')
        $("body").append(dropDown);
        dropDown.append($("."+id).clone().show())
        .css({
            position:"absolute",
            left:offset.left,
            top:offset.top+28
        })
        .mouseover(function(){
            window.clearTimeout(timeOutID);
            $("#dropDown").show();
        }).mouseout(function(){
            $("#dropDown").hide();
        });
    }).mouseout(function(){
        timeOutID = window.setTimeout(hidedropDown,1000);
    });
}
$(document).ready(function(){
	$(".datepicker").datepicker();
	!is_ie && $('.button').button();
	dropDown();
    $("#off").click(function (){
        if($(".left").width()>10){
            $(".left").width(10);
            $(".main_menu_title_left").hide();
            $(this).attr('class','main_menu_title_right_hover');
        }else{
            $(".left").width(180);
            $(".main_menu_title_left").show();
            $(this).attr('class','main_menu_title_right');
        }
    });

	$(".viewRule").click(function(){
		var offset 		= $(this).offset();
		var snapTop 	= offset.top+14;
		var snapLeft 	= offset.left;
//		alert(snapTop+"-"+snapLeft);
		var inid		= $(this).attr("to");
		$("#viewRule_"+inid).show().css({"top" : snapTop, "left" : snapLeft,"width":320})
		.slideDown("slow");
	});
	$(".close").click(function(){
		var parentdiv=$(this).attr("parent");
	    $("#"+parentdiv).hide();
	});
	$('.tipsdiv').draggable({ handle: 'th'});

	$('.tabs li').each(function(i){
		var a=this;
		$(this).click(function(){
			$('.tabs li').removeClass("active");
			$(a).addClass("active");
			$('[id^=tabs]').hide();
			var href = $(a).attr('href');
			if(href){
				window.location.href=href;
			}else{
				$('#'+$(a).attr('ref')).show();
			}
			//$(this).children().click();
		});
	});
});
function main_frame_src(url){
    $("iframe").attr("src",url);
}

function slider_menu(id,thisobj){
    if($("#"+id).css('display')=='none'){
        var type="block";
        $("#"+thisobj).addClass('menu_title');
        $("#"+thisobj).removeClass('menu_title2');
    }else{
        var type="none";
        $("#"+thisobj).removeClass('menu_title');
        $("#"+thisobj).addClass('menu_title2');
    }
    $("#"+id).css('display',type);
}

function moveUp(obj){
    with (obj){
        if(selectedIndex==0){
            options[length]=new Option(options[0].text,options[0].value)
            options[0]=null
            selectedIndex=length-1
        }
        else if(selectedIndex>0) moveG(obj,-1)
        }
}
function moveDown(obj){
    with (obj){
        try {
            if(selectedIndex==length-1){
                var otext=options[selectedIndex].text
                var ovalue=options[selectedIndex].value
                for(i=selectedIndex; i>0; i--){
                    options[i].text=options[i-1].text
                    options[i].value=options[i-1].value
                }
                options[i].text=otext
                options[i].value=ovalue
                selectedIndex=0
            }else if(selectedIndex<length-1) moveG(obj,+1)
        } catch(e) {
		
        }
        }
}
function del(obj) {
    with(obj) {
        try {
            options[selectedIndex]=null
            selectedIndex=length-1
        } catch(e) {}
     }
}
function moveG(obj,offset){
    with (obj){
        desIndex=selectedIndex+offset
        var otext=options[desIndex].text
        var ovalue=options[desIndex].value
        options[desIndex].text=options[selectedIndex].text
        options[desIndex].value=options[selectedIndex].value
        options[selectedIndex].text=otext
        options[selectedIndex].value=ovalue
        selectedIndex=desIndex
        }
}
function altStyle(obj) {
    function altStyleClear(obj) {
        var input, lis, i;
        lis = obj.parentNode.getElementsByTagName('li');
        for(i=0; i < lis.length; i++){
            lis[i].className = '';
        }
    }

    var input, lis, i, cc, o;
    cc = 0;
    lis = obj.getElementsByTagName('li');
    for(i=0; i < lis.length; i++){
        lis[i].onclick = function(e) {
            o = is_ie ? event.srcElement.tagName : e.target.tagName;
            if(cc) {
                return;
            }
            cc = 1;
            input = this.getElementsByTagName('input')[0];
            if(input.getAttribute('type') == 'checkbox' || input.getAttribute('type') == 'radio') {
                if(input.getAttribute('type') == 'radio') {
                    altStyleClear(this);
                }
                if(is_ie || o != 'INPUT' && input.onclick) {
                    input.click();
                }
                if(this.className != 'checked') {
                    this.className = 'checked';
                    input.checked = true;
                } else {
                    this.className = '';
                    input.checked = false;
                }
            }
        }
        lis[i].onmouseup = function(e) {
            cc = 0;
        }
    }
}