/*

Left and Right arrows will move to the next and previous page. This script locates the "next" and "previous" ids, and on button click, it changes the window location.

If there isn't a next or a previous page, make sure there is no "next" or "prev" id inside the page.

*/


var nextpage;
var prevpage;

window.onload=function()
{
	if (document.getElementById("next") != null) nextpage = document.getElementById("next").getAttribute("href");
	if (document.getElementById("prev") != null) prevpage = document.getElementById("prev").getAttribute("href");
}

document.onkeydown = function(evt)
{
	evt = evt || window.event;
	switch (evt.keyCode)
	{
		case 37:
			if (typeof prevpage !== 'undefined') window.location = prevpage;
			break;
		case 39:
			if (typeof nextpage !== 'undefined') window.location = nextpage;
			break;
	}
};

