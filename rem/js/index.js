$(".tasks").mouseup(function(params){
    if(params.button == 2){
        $(document).bind('contextmenu',function(){return false;});

        popoverShow(params.offsetX,params.pageX,params.pageY)
    }
});
function popoverShow(offsetX,pageX,pageY){
    var taskWidth = $('.taskItem-body').outerWidth(),
        popoverWidth = $('.popover').width();
    if( ( Number(taskWidth) - Number(offsetX) ) < popoverWidth ){
        $(".popover").css({'top':pageY+'px','right':'20px','left':'',});
    }else{
        $(".popover").css({'top':pageY+'px','left':pageX+'px','right':''});
    }
    
    $(".popover").show();
}
$('#app').click(function(){
	if( !$(".popover").is(":hidden") )
		$(".popover").hide();
})

$.fn.longTap = function(fn) {

    var timeout = undefined;
    var $this = this;
    for(var i = 0;i < $this.length; i += 1){
        $this[i].addEventListener('touchstart', function(event) {
            var that = this;
            timeout = setTimeout(function () { 
                fn(event,that); 
            }, 800); //长按时间超过800ms，则执行传入方法
        }, false);
        $this[i].addEventListener('touchend', function(event) {
            clearTimeout(timeout); //长按事件少于800ms，不会执行传入的方法
        }, false);
    }
};
//长按右键菜单
$('.taskItem-body').longTap(function(event,element){

    $(document).bind('contextmenu',function(){return false;});
    popoverShow(event.touches[0].pageX,event.touches[0].pageX,event.touches[0].pageY)
});