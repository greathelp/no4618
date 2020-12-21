/* -------------------------------------------
	MELLOWDOWN.NET
	http://www.mellowdown.net
------------------------------------------- */

var fadeTime = 3000;
var delayTime = 6000;
var autoFlg = true;
var naviFlg = true;
var currentNo = 0;
var oldNo = 0;
var imageLength;
var tid;
	
$(function()
{
	imageLength = $("#main_slide ul > li").size();
	
	if (imageLength < 2) return;
	
	$("ul#slide_list li").hide();
	$("ul#slide_list li:eq(0)").fadeIn(3000);

	if (naviFlg)
	{
		$("#main_slide ul#slide_list").after("<ul id=\"numbers\"></ul>");
		for (i = 0; i < imageLength; i++)
		{
			$("ul#numbers").append("<li id=\"imageNo_" + i + "\"><a href=\"javascript:void(0);\">" + eval(i + 1) + "</a></li>");
		}
		$("ul#numbers li a:eq(" + currentNo + ")").addClass("current");
		
		$("ul#numbers li").click(function()
		{
			var targetNo = $(this).attr("id").replace("imageNo_", "");
			if (targetNo != currentNo)
			{
				oldNo = currentNo;
				currentNo = targetNo;
				changeImage();
				if (autoFlg)
				{
					clearInterval(tid);
					tid = setInterval("setNo()",delayTime);
				}
			}
		});
	}
	if (autoFlg) tid = setInterval("setNo()",delayTime);
});

function setNo()
{
	oldNo = currentNo;
	currentNo++;
	if (currentNo >= imageLength)
	{
		currentNo = 0;
	}
	changeImage();
}
	
function changeImage()
{		
	$("ul#slide_list li:eq(" + oldNo + ")").fadeOut(fadeTime);
	$("ul#slide_list li:eq(" + currentNo + ")").fadeIn(fadeTime);
	
	if (naviFlg)
	{
		$("ul#numbers li a:eq(" + oldNo + ")").removeClass("current");
		$("ul#numbers li a:eq(" + currentNo + ")").addClass("current");
	}
}