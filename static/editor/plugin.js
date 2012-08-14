var c = document.createElement("link");
c.setAttribute("rel", "stylesheet");
c.setAttribute("type", "text/css");
c.setAttribute("href",  urlroot + "/static/editor/plugin.css");
document.getElementsByTagName("head")[0].appendChild(c);

if(kind_plugin_pagebreak) {
	KE.lang['pagebreak'] = '·ÖÒ³';
	KE.plugin['pagebreak'] = {
		icon:'pagebreak.gif',
		click : function(id) {
			KE.util.selection(id);
			KE.util.insertHtml(id, '[page]number[/page]');
			KE.util.focus(id);
		}
	};
}