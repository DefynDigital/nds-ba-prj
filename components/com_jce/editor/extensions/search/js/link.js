/* jce - 2.6.11 | 2017-04-12 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2017 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
var WFLinkSearch=WFExtensions.add("LinkSearch",{options:{element:"#search-input",button:"#search-button",clear:"#search-clear",empty:"No Results",onClick:$.noop},init:function(options){$.extend(this.options,options);var self=this,el=this.options.element,btn=this.options.button;$(btn).click(function(e){self.search(),e.preventDefault()}).button({icons:{primary:"uk-icon-search"}}),$("#search-clear").click(function(e){$(this).hasClass("uk-active")&&($(this).removeClass("uk-active"),$(el).val(""),$("#search-result").empty().hide())}),$("#search-options-button").click(function(e){e.preventDefault(),$(this).addClass("uk-active");var $p=$("#search-options").parent();$("#search-options").height($p.parent().height()-$p.outerHeight()-15).toggle()}).on("close",function(){$(this).removeClass("uk-active"),$("#search-options").hide()}),$(el).on("change keyup",function(){""===this.value&&($("#search-result").empty().hide(),$("#search-clear").removeClass("uk-active"))})},search:function(){var self=this,s=this.options,el=s.element,$p=(s.button,$("#search-result").parent()),query=$(el).val();query&&!$(el).hasClass("placeholder")&&($("#search-clear").removeClass("uk-active"),$("#search-browser").addClass("loading"),query=$.trim(query.replace(/[\/\/\/<>#]/g,"")),Wf.JSON.request("doSearch",{json:[query]},function(o){o&&(o.error?Wf.Dialog.alert(o.error):($("#search-result").empty(),o.length?($.each(o,function(i,n){var $dl=$('<dl class="uk-margin-small" />').appendTo("#search-result");$('<dt class="link uk-margin-small" />').text(n.title).click(function(){$.isFunction(self.options.onClick)&&self.options.onClick.call(this,Wf.String.decode(n.link))}).prepend('<i class="uk-icon uk-icon-file-text-o uk-margin-small-right" />').appendTo($dl),$('<dd class="text">'+n.text+"</dd>").appendTo($dl),n.anchors&&$.each(n.anchors,function(i,a){$('<dd class="anchor" />').text(a).click(function(){self.options.onClick.call(this,Wf.String.decode(n.link+"#"+a))}).appendTo($dl)})}),$("dl:odd","#search-result").addClass("odd")):$("#search-result").append("<p>"+s.empty+"</p>"),$("#search-options-button").trigger("close"),$("#search-result").height($p.parent().height()-$p.outerHeight()-5).show())),$("#search-browser").removeClass("loading"),$("#search-clear").addClass("uk-active")},self))}});