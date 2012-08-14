/********************************************************************************************************
 * D-ImageChange
 *----------------------------------------------------------------------------------------------------
 * @Desc ͼƬ�ֻ����
 *----------------------------------------------------------------------------------------------------
 * @Author D.����֪��
 * @Email DeclanZhang@gmail.com
 * @QQ 29540200
 * @Blog http://onblur.javaeye.com
 * @Date 2009-10-19
 * @Version V1.3@2010-03-16
 * @JQueryVersion 1.3.2+ (����ʹ��1.4���ϰ汾)
 * 
 * @update v1.1 �������ԭʼ���ݹ���,����ҳ��������֮ǰ��ʾ��Ƭ�հ�
 * 		   v1.2 ����IE6ÿ�δӷ�������ȡ����ͼƬ��BUG
 *         v1.3 �����˿�ȹ��������BUG, ����JQ1.4.1, ����ʹ��JQ1.4+, Ч�ʸ���
 **/

// ����IE6ÿ�δӷ�������ȡ����ͼƬ��BUG
try {
	document.execCommand('BackgroundImageCache', false, true);
}catch(e){

}


(function($){

jQuery.fn.extend({
	
	d_imagechange:function(setting){
		
		var config = $.extend({
			bg:true,						// �Ƿ񱳾�ɫ
			title:true,						// �Ƿ��б���
			desc:true,						// �Ƿ�������
			btn:true,						// �Ƿ���ʾ��ť
			repeat:'no-repeat',				// �ظ����� 'no-repeat' 'repeat-x' 'repeat-y' 'repeat' 'draw'
			
			bgColor:'#000',					// ����ɫ
			bgOpacity:.5,					// ����͸����
			bgHeight:23,					// ������
			
			titleSize:12,					// �������ִ�С
			titleFont:'Verdana,����',		// �����ı�����
			titleColor:'#FFF',				// �����ı���ɫ
			titleTop:4,						// �����ϱ߾�
			titleLeft:4,					// ������߾�
			
			descSize:10,					// �������ִ�С
			descFont:'Verdana,����',			// �����ı�����
			descColor:'#FFF',				// �����ı���ɫ
			descTop:2,						// �����ϱ߾�
			descLeft:4,						// ������߾�
			
			btnColor:'#FFF',				// ��ť��ɫ1 
			btnOpacity:.5,					// δѡ�а�ť͸����
			btnFont:'Verdana',				// ��ť�ı�����
			btnFontSize:12,					// ��ť���ִ�С(ע��:Chrome��Ĭ����С�ֺŵ�����)
			btnFontColor:'#000',			// ��ť�ı���ɫ
			btnText:true,					// �Ƿ���ʾ�ı�
			btnWidth:15,					// ��ť��
			btnHeight:15,					// ��ť��
			btnMargin:4,					// ��ť���
			btnTop:4,						// ��ť�ϱ߾�
			
			playTime:4000,					// �ֻ����ʱ��,��λ(����)
			animateTime:500,				// ����ִ��ʱ��,��λ(����)
			animateStyle:'o',				// ����Ч��:'o':���� 'x':������� 'y':������� 'show':ԭ��������չ 'show-x':����������չ 'show-y':����������չ' none':�޶���
			width:300,						// ��, ���趨���DOM��ȡ
			height:200						// ��, ���趨���DOM��ȡ
			
		},setting);
		
		return $(this).each(function(){

			var _this = $(this);
			//�Ӷ����ж�ȡ
			if(typeof (config.data)=='undefined') {
				var v = new Array();
				_this.find('a').each(function(i) {
					v[i] = {
						title:$(this).attr('title'),
						desc:$(this).attr('desc'),
						href:$(this).attr('href'),
						target:$(this).attr('target'),
						src:$(this).find('img').attr('src')
					};
				});
				config.data = v;
				$(this).html('');
			}

			var _w = config.width || _this.width();			// ��
			var _h = config.height || _this.height();		// ��
			var _n = config.data.length;					// ��Ŀ
			var _i = 0;										// ��ǰ��ʾ��item���

			_this.empty()
				 .css('overflow','hidden')
				 .width(_w)
				 .height(_h);
			
			// ��͸������
			if(config.bg){
			$('<div />').appendTo(_this)
						.width(_w)
						.height(config.bgHeight)
						.css('background-color',config.bgColor)
						.css('opacity',config.bgOpacity)
						.css('position','absolute')
						.css('marginTop',_h-config.bgHeight)
						.css('zIndex',200);
			}
			
			// ������
			var _textArea = 
			$('<div />').appendTo(_this)
						.width(_w)
						.height(config.bgHeight)
						.css('position','absolute')
						.css('marginTop',_h-config.bgHeight)
						.css('zIndex',201);
			// ��ť��
			var _btnArea = 
			$('<div />').appendTo(_this)
						.width(config.data.length * (config.btnWidth + config.btnMargin))
						.height(config.bgHeight)
						.css('position','absolute')
						.css('marginTop',_h-config.bgHeight)
						.css('marginLeft',_w-(config.btnWidth+config.btnMargin)*_n)
						.css('zIndex',202)
						.css('display',config.btn?'block':'none');
			
			// �����div����IE�ľ��Զ�λBUG
			$('<div />').appendTo(_this);
			
			// ͼƬ��
			var _imgArea = 
			$('<div />').appendTo(_this)
						.width('x,show-x'.indexOf(config.animateStyle)!=-1?_w*_n:_w)
						.height('y,show-y'.indexOf(config.animateStyle)!=-1?_h*_n:_h);			
	
			// ��ʼ��ͼƬ ���� ��ť
			$.each(config.data,function(i,n){
				var a = $('<a />');
				a.appendTo(_imgArea)
						  .width(_w)
						  .height(_h)
						  .attr('href',n.href?n.href:'')
						  .attr('target',n.target?n.target:'')
						  .css('display','block')
						  .css('float','x,show-x'.indexOf(config.animateStyle)!=-1?'left':'');

				if(config.repeat != 'draw') {
					a.css('background-image','url('+n.src+')')
						  .css('background-repeat',config.repeat)
						  .css('display','block');
				} else {
					$('<img />').appendTo(a)
							.attr('src', n.src)
							.attr('height',config.height)
							.attr('width',config.width);
				}

				if(config.title){
				$('<b />').appendTo(_textArea)
						  .html(n.title?n.title:'')
						  .css('display',i==0?'block':'none')
						  .css('fontSize',config.titleSize)
						  .css('fontFamily',config.titleFont)
						  .css('color',config.titleColor)
						  .css('marginTop',config.titleTop)
						  .css('marginLeft',config.titleLeft);
				}
				
				if(config.desc){
				$('<p />').appendTo(_textArea)
						  .html(n.desc?n.desc:'')
						  .css('display',i==0?'block':'none')
						  .css('fontSize',config.descSize)
						  .css('fontFamily',config.descFont)
						  .css('color',config.descColor)
						  .css('marginTop',config.descTop)
						  .css('marginLeft',config.descLeft);
				}

				$('<a />').appendTo(_btnArea)
						  .width(config.btnWidth)
						  .height(config.btnHeight)
						  .html(config.btnText?i+1:'')
						  .css('fontSize',config.btnFontSize)
						  .css('fontFamily',config.btnFont)
						  .css('textAlign','center')
						  .css('display','block')
						  .css('float','left')
						  .css('overflow','hidden')
						  .css('marginTop',config.btnTop)
						  .css('marginRight',config.btnMargin)
						  .css('background-color',config.btnColor)
						  .css('opacity',i==0?1:config.btnOpacity)
						  .css('color',config.btnFontColor)
						  .css('cursor','pointer')

			});

			// ��������Ԫ�ؼ��ϵ�����,�������¼���ʹ��
			var _bs = _btnArea.children('a');
			var _ts = _textArea.children('b');
			var _ds = _textArea.children('p');
			var _is = _imgArea.children('a');

			// ��Բ�ͬ�Ķ���Ч���ĸ�������, ��Ҫ��block������, ���ڳ�ʼ��ʱ����block:none�����֮����blockЧ��
			if('o,show,none'.indexOf(config.animateStyle)!=-1){
				_is.not(':first').hide();
				_is.css('position','absolute');
			}
			
			// ��Ӱ�ť�¼�
			_bs.click(function(){
				var ii = _bs.index(this);
				if(ii==_i){return;}
				
				_ts.eq(_i).css('display','none');
				_ts.eq(ii).css('display','block');
				_ds.eq(_i).css('display','none');
				_ds.eq(ii).css('display','block');
				_bs.eq(_i).css('opacity',config.bgOpacity);
				_bs.eq(ii).css('opacity',1)
				
				switch(config.animateStyle){
				case 'o' :
					_is.eq(_i).fadeOut(config.animateTime);
					_is.eq(ii).fadeIn(config.animateTime);
					break;
				case 'x' :
					_imgArea.animate({marginLeft:-ii*_w},config.animateTime);
					break;
				case 'y' :
					_imgArea.animate({marginTop:-ii*_h},config.animateTime);
					break;
				case 'show' :
				case 'show-x' :
				case 'show-y' :
					_is.eq(_i).hide(config.animateTime);
					_is.eq(ii).show(config.animateTime);
					break;				
				case 'none' :
					_is.eq(_i).hide();
					_is.eq(ii).show();
					break;				
				}
				_i = ii;
			});

			// ����ֻ�����
			var _play = setInterval(play,config.playTime);
			
			function play(){
				_bs.eq((_i+1)%_n).click()
			}		
			// �������¼�
			_this.mouseover(function(){
				clearInterval(_play);
			});
						
			// ����뿪�¼�
			_this.mouseout(function(){
				_play = setInterval(play,config.playTime);
			});
		});
	}
});
})(jQuery);