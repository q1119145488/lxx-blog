function send(headSrc,str){
	var html="<div class='send'><div class='msg'><img src="+headSrc+" />"+"<p><i class='msg_input'></i>"+str+"</p></div></div>";
	upView(html);
}
function show(headSrc,str){
	var html="<div class='show'><div class='msg'><img src="+headSrc+" />"+"<p><i class='msg_input'></i>"+str+"</p></div></div>";
	upView(html);
}
function upView(html){
	$('.message').append(html);
	$('body').animate({
		scrollTop:$('.message').outerHeight()-window.innerHeight
	},200)}
function sj(){
	return parseInt(Math.random()*10)
}
/*$(function(){
	$('.footer').on('keyup','input',function(){
		if($(this).val().length>0){
			$(this).next().css('background','#114F8E').prop('disabled',true);
		}else{
			$(this).next().css('background','#ddd').prop('disabled',false);}
		}
	)
	$('.footer p').click(function(){
		show("./images/touxiangm.png",$(this).prev().val());
		//test();
	})
})
var arr=['我是小Q','好久没联系了！','你在想我么','怎么不和我说话','跟我聊会天吧'];
var imgarr=['images/touxiang.png','images/touxiangm.png']
test()
function test(){
	$(arr).each(function(i){setTimeout(function(){
		send("images/touxiang.png",arr[i])
	},sj()*500)})
}*/