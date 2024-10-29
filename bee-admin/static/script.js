jQuery(document).ready(function ($) {


	$("body.wp-admin[class*='appbeebee'] #poststuff").prepend("<div class='bee-admin-thead'><div class='bee-admin-thead__inside-container'><div class='bee-admin-thead__logo-container'><a class='bee-admin-thead__logo-link' href='admin.php?page=appbeebee'><svg width='26' height='26' viewBox='0 0 231 231' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><title>beebee-logo</title><defs><circle id='path-1' cx='115.5' cy='115.5' r='115.5'></circle></defs><g id='页面-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'><g id='beebee-logo'><mask id='mask-2' fill='white'><use xlink:href='#path-1'></use></mask><use id='椭圆_1' fill-opacity='0.8' fill='#FFB800' fill-rule='nonzero' xlink:href='#path-1'></use><path d='M115.5,0 C51.712,0 0,51.711 0,115.5 C0,152.49 17.391,185.415 44.438,206.554 L44.438,79 C44.438,69.335 52.273,61.5 61.938,61.5 C71.604,61.5 79.438,69.335 79.438,79 L79.438,86.393 L82.074,83.252 C88.287,75.849 99.325,74.883 106.729,81.095 C114.133,87.308 115.099,98.346 108.887,105.75 L79.438,140.844 L79.439,151.832 C85.711,144.814 96.468,144.002 103.729,150.095 C111.133,156.308 112.099,167.346 105.887,174.75 L79.532,206.158 L79.438,206.266 L79.438,225.25 C90.786,228.977 102.906,231 115.5,231 C120.144,231 124.721,230.718 129.221,230.185 C128.713,228.546 128.438,226.805 128.438,225 L128.438,60 C128.438,50.335 136.273,42.5 145.938,42.5 C155.604,42.5 163.438,50.335 163.438,60 L163.438,67.394 L166.074,64.252 C172.287,56.849 183.325,55.883 190.729,62.095 C198.133,68.308 199.099,79.346 192.886,86.75 L163.438,121.844 L163.438,132.861 C169.709,125.831 180.464,124.999 187.729,131.095 C195.133,137.308 196.099,148.346 189.886,155.75 L163.532,187.158 C163.502,187.193 163.469,187.223 163.438,187.258 L163.438,220.608 C203.299,202.398 231,162.186 231,115.5 C231,51.711 179.289,0 115.5,0' id='Fill-1' fill='#000000' mask='url(#mask-2)'></path></g></g></svg>比比小程序</a></div><div class='bee-admin-thead__nav'><span class='button-group'><a href='https://beebee.work/' target='_blank' type='button' class='button'>比比官网</a><a href='https://doc.beebee.work/' target='_blank' type='button' class='button'>使用帮助</a><a href='https://beebee.work/' target='_blank' type='button' class='button'>比比素材</a></span></div></div></div>")

	var $domain = 'https://beebee.work/'
	var $themechecked = $('#beeapp-form input[type=radio][name*="choosetheme"]:checked');
	var $text = $themechecked.data("title") + $themechecked.data("vision") + '<br/>' + $themechecked.data("subtit");
	if ($themechecked.length > 0) {
		$themechecked.parents('.theme').addClass('active')
		$('#themechecked-tit').html('<div class="bee-title-header"><h2>已选用：' + $text + '</h2></div><h2 class="bee-blurb">' + $themechecked.data("desc") + '</h2><p class="bee-blurb">适合人群：' + $themechecked.data("crowd") + '</p>');
		var outHtml = $themechecked.parents('.theme').prop("outerHTML"); //获取到Html，包括当前节点
		$("#theme-list").prepend(outHtml); //追加到div1内部
		$themechecked.parents('.theme').remove(); //删除原来的html
	}
	$('.theme-screenshot').on('click', function (e) {
		var $themelink = $(this).data("link") ? $(this).data("link") : $domain
		window.open($themelink);
	});

	$('.disabled > .acf-input input[type=text]').attr("disabled","disabled");

	// 媒体库调用
	$('.bee-choose-media').click(function (e) {
		//console.log(e);
		var mediaUploader;
		e.preventDefault();
		$this = $(this);
		//var upload = e.currentTarget.id;
		// var upload = $(this).attr('id');
		if (mediaUploader) {
			mediaUploader.open();
			return;
		}
		mediaUploader = wp.media({
			title: '选择图片',
			button: {
				text: '选择'
			},
			multiple: false
		});
		mediaUploader.on('select', function () {
			var attachment = mediaUploader.state().get('selection').first().toJSON();
			// var value_id = '#' + upload.replace(/-btn/, "")
			$this.parents('.bee-upload-media').find('input.acf-is-appended').val(attachment.url);
		});
		mediaUploader.open();
	});

	$(".input-disabled label,.input-disabled input").attr("disabled", "disabled");
	if ($(".acf-field[data-name=bee_book_dbpage] input").val() != '') {
		$(".acf-th[data-name=bee_book_dbpage] .beetips").attr('href', $(".acf-field[data-name=bee_book_dbpage] input").val()).css('display', 'inline');
	}
	if ($(".acf-field[data-name=bee_book_dbcover_l] input").val() != '') {
		$(".acf-th[data-name=bee_book_dbcover_l] .beetips").attr('href', $(".acf-field[data-name=bee_book_dbcover_l] input").val()).css('display', 'inline');
	}
	if ($(".acf-field[data-name=bee_book_dbcover_s] input").val() != '') {
		$(".acf-th[data-name=bee_book_dbcover_s] .beetips").attr('href', $(".acf-field[data-name=bee_book_dbcover_s] input").val()).css('display', 'inline');
	}

	$(".acf-field[data-name=bee_book_isbn] input").bind('input propertychange', function () {
		$("#bookautomsg span").remove();
		var isbn = $(".acf-field[data-name=bee_book_isbn] input").val();
		var reg = /^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/;
		var isbnreg = reg.test(isbn);
		if (!isbn) {
			$("#bookautomsg").prepend("<span style='color:#dba617;'>请先输入ISBN</span>");

		} else if (!isbnreg) {
			$("#bookautomsg").prepend("<span style='color:#d63638;'>请输入正确的ISBN</span>");
		} else {
			// query
			$("#bookautomsg .tips").remove();
			$.ajax({
				url: ajaxurl,
				type: 'GET',
				dataType: 'json',
				async: true,
				data: {
					action: 'automsglibrary',
					isbn: isbn
				},
				timeout: 15000,
				beforeSend: function () {
					$("#bookautomsg").prepend("<span style='color:#dba617;'>正在抓取，请稍等...</span>");
				},
				success: function (json) {
					console.log(json);
					var translator = json.translator ? ' / ' + json.translator : '';
					var publisher = json.publisher ? ' / ' + json.publisher : '';
					var published = json.published ? ' / ' + json.published : '';

					$(".acf-field[data-name=bee_book_dbid] input").val(json.id);
					$(".acf-field[data-name=bee_book_title] input").val(json.title);
					$(".acf-field[data-name=bee_book_price] input").val(json.price);

					$(".acf-field[data-name=bee_book_dbpage] input").val(json.url);
					$(".acf-field[data-name=bee_book_dbcover_l] input").val(json.logo);
					$(".acf-field[data-name=bee_book_dbcover_s] input").val(json.logos);

					$(".acf-field[data-name=bee_book_dbscore] input").val(json.dbscore);
					$(".acf-field[data-name=bee_book_author] input").val(json.author);
					$(".acf-field[data-name=bee_book_translator] input").val(json.translator);
					$(".acf-field[data-name=bee_book_publisher] input").val(json.publisher);
					$(".acf-field[data-name=bee_book_published] input").val(json.published);
					$(".acf-field[data-name=bee_book_page] input").val(json.page);
					$(".acf-field[data-name=bee_book_designed] input").val(json.designed);

					$("div.editor-post-title textarea").val(json.title);
					$("div.editor-post-excerpt textarea").val(json.author + translator + publisher + published)

					if (!json.title || json.title == null) {
						$(".beetips").css('display', 'none');
						$("#bookautomsg span").remove();
						$("#bookautomsg").prepend("<span style='color:#d63638;'>获取失败，请检查ISBN是否输出正确</span>")
					} else {
						$(".acf-field[data-name=bee_book_isbn] input").val(json.isbn);

						$(".acf-th[data-name=bee_book_dbpage] .beetips").attr('href', json.url).css('display', 'inline');
						$(".acf-th[data-name=bee_book_dbcover_l] .beetips").attr('href', json.logo).css('display', 'inline');
						$(".acf-th[data-name=bee_book_dbcover_s] .beetips").attr('href', json.logos).css('display', 'inline');

						$("#bookautomsg span").remove();
						$("#bookautomsg").prepend("<span style='color:#00a32a'>恭喜！书籍信息获取完成</span>");

					}

				},
				error: function (json) {
					//this.hideLoading();
					$("#bookautomsg span").remove();
					$("#bookautomsg").prepend("<span style='color:#d63638;'>获取失败，请检查网络</span>")

				},
				complete: function () {
					//this.hideLoading();
				}
			});
		}

	});

	$(".acf-field[data-name=bee_movie_dbid] input").bind('input propertychange', function () {
		$("#movieautomsg span").remove();
		var dbid = $(".acf-field[data-name=bee_movie_dbid] input").val();
		var reg = /^\d{6,}$/;
		var dbidreg = reg.test(dbid);
		if (!dbid) {
			$("#movieautomsg").prepend("<span style='color:#dba617;'>请先输入豆瓣影视id</span>");

		} else if (!dbidreg) {
			$("#movieautomsg").prepend("<span style='color:#d63638;'>请输入正确的豆瓣影视id</span>");
		} else {
			// query
			$("#movieautomsg .tips").remove();
			$.ajax({
				url: ajaxurl,
				type: 'GET',
				dataType: 'json',
				async: true,
				data: {
					action: 'automsgmovie',
					dbid: dbid
				},
				timeout: 15000,
				beforeSend: function () {
					$("#movieautomsg").prepend("<span style='color:#dba617;'>正在抓取，请稍等...</span>");
				},
				success: function (json) {
					console.log(json);
					var type = json.type ? ' / ' + json.type : '';
					var time = json.time ? ' / ' + json.time : '';
					var opened = json.opened ? ' / ' + json.opened : '';

					// $(".acf-field[data-name=bee_movie_dbid] input").val(json.id);
					$(".acf-field[data-name=bee_movie_title] input").val(json.title);
					$(".acf-field[data-name=bee_movie_alias] input").val(json.alias); //又名
					$(".acf-field[data-name=bee_movie_imdb] input").val(json.imdb);

					$(".acf-field[data-name=bee_movie_dbpage] input").val(json.url);
					$(".acf-field[data-name=bee_movie_dbcover_l] input").val(json.logo);
					$(".acf-field[data-name=bee_movie_dbcover_s] input").val(json.logos);

					$(".acf-field[data-name=bee_movie_dbscore] input").val(json.dbscore); //评分
					$(".acf-field[data-name=bee_movie_writer] input").val(json.writer); //编剧
					$(".acf-field[data-name=bee_movie_star] input").val(json.star); //演员
					$(".acf-field[data-name=bee_movie_director] input").val(json.director); //导演
					$(".acf-field[data-name=bee_movie_area] input").val(json.area); //制片国家
					$(".acf-field[data-name=bee_movie_type] input").val(json.type); //类型
					
					$(".acf-field[data-name=bee_movie_time] input").val(json.time); //片长
					$(".acf-field[data-name=bee_movie_opened] input").val(json.opened); //上映年份
					$(".acf-field[data-name=bee_movie_language] input").val(json.language); // 语言
					

					$("div.editor-post-title textarea").val(json.title);
					$("div.editor-post-excerpt textarea").val(json.type + time + opened)

					if (!json.title || json.title == null) {
						$(".beetips").css('display', 'none');
						$("#movieautomsg span").remove();
						$("#movieautomsg").prepend("<span style='color:#d63638;'>获取失败，请检查豆瓣影视ID是否输出正确</span>")
					} else {
						$(".acf-field[data-name=bee_movie_dbid] input").val(json.id);

						$(".acf-th[data-name=bee_movie_dbpage] .beetips").attr('href', json.url).css('display', 'inline');
						$(".acf-th[data-name=bee_movie_dbcover_l] .beetips").attr('href', json.logo).css('display', 'inline');
						$(".acf-th[data-name=bee_movie_dbcover_s] .beetips").attr('href', json.logos).css('display', 'inline');

						$("#movieautomsg span").remove();
						$("#movieautomsg").prepend("<span style='color:#00a32a'>恭喜！影视信息获取完成</span>");

					}

				},
				error: function (json) {
					//this.hideLoading();
					$("#movieautomsg span").remove();
					$("#movieautomsg").prepend("<span style='color:#d63638;'>获取失败，请检查网络</span>")

				},
				complete: function () {
					//this.hideLoading();
				}
			});
		}

	});

	$(".acf-field[data-name=bee_app_appid] input").bind('input propertychange', function () {
		$("#appautomsg span").remove();
		var appid = $(".acf-field[data-name=bee_app_appid] input").val();
		var reg = /^\d{6,}$/;
		var appidreg = reg.test(appid);
		if (!appid) {
			$("#appautomsg").prepend("<span style='color:#dba617;'>请先输入appid</span>");

		} else if (!appidreg) {
			$("#appautomsg").prepend("<span style='color:#d63638;'>请输入正确的appid</span>");
		} else {
			// query
			$("#appautomsg .tips").remove();
			$.ajax({
				url: ajaxurl,
				type: 'GET',
				dataType: 'json',
				async: true,
				data: {
					action: 'automsgapp',
					appid: appid
				},
				timeout: 15000,
				beforeSend: function () {
					$("#appautomsg").prepend("<span style='color:#dba617;'>正在抓取，请稍等...</span>");
				},
				success: function (json) {
					console.log(json);
					var version = json.version ? ' / ' + json.version : '';
					var size = json.size ? ' / ' + json.size : '';

					// $(".acf-field[data-name=bee_movie_dbid] input").val(json.id);
					$(".acf-field[data-name=bee_app_title] input").val(json.title); //标题
					$(".acf-field[data-name=bee_app_slogan] input").val(json.slogan); //简述
					$(".acf-field[data-name=bee_app_identity] input").val(json.identity); //开发者

					$(".acf-field[data-name=bee_app_page] input").val(json.url);
					$(".acf-field[data-name=bee_app_cover_l] input").val(json.logo);
					$(".acf-field[data-name=bee_app_cover_s] input").val(json.logos);

					$(".acf-field[data-name=bee_app_score] input").val(json.score); //评分
					$(".acf-field[data-name=bee_app_genre] input").val(json.genre); //分类
					$(".acf-field[data-name=bee_app_price] input").val(json.price); //价格
					$(".acf-field[data-name=bee_app_version] input").val(json.version); //最新版本
					$(".acf-field[data-name=bee_app_upload] input").val(json.upload); //更新时间
					$(".acf-field[data-name=bee_app_size] input").val(json.size); //大小
					
					$("div.editor-post-title textarea").val(json.title);
					$("div.editor-post-excerpt textarea").val(json.genre + version + size)

					if (!json.title || json.title == null) {
						$(".beetips").css('display', 'none');
						$("#appautomsg span").remove();
						$("#appautomsg").prepend("<span style='color:#d63638;'>获取失败，请检查APPID是否输出正确</span>")
					} else {
						$(".acf-field[data-name=bee_app_appid] input").val(json.id);

						$(".acf-th[data-name=bee_app_page] .beetips").attr('href', json.url).css('display', 'inline');
						$(".acf-th[data-name=bee_app_cover_l] .beetips").attr('href', json.logo).css('display', 'inline');
						$(".acf-th[data-name=bee_app_cover_s] .beetips").attr('href', json.logos).css('display', 'inline');

						$("#appautomsg span").remove();
						$("#appautomsg").prepend("<span style='color:#00a32a'>恭喜！APP信息获取完成</span>");

					}

				},
				error: function (json) {
					//this.hideLoading();
					$("#appautomsg span").remove();
					$("#appautomsg").prepend("<span style='color:#d63638;'>获取失败，请检查网络</span>")

				},
				complete: function () {
					//this.hideLoading();
				}
			});
		}

	});

	// $(window).load(function () {
		var outHtml = '<div class="bee_rule_top_w"></div><div class="bee_rule_card_h"></div><div class="bee_rule_body_h"></div><div class="bee_rule_head_h"></div>';
		$(".bee-card-rule").prepend(outHtml);
		// $('.bee-card-header').attr('height',$('.bee-card-header').height());
		var swiper = new Swiper('.swiper-container', {
			slidesPerView: 1,
			spaceBetween: 0,
			effect: 'slide',
			loop: true,
			speed: 300,
			mousewheel: {
				invert: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
				dynamicBullets: true
			},
			// Navigation arrows
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			}
		});
		
		var $demo_data_tit = $('.acf-field[data-name=bee_topic_cover] .acf-field[data-name=demo_data] .acf-field[data-name=title] input[type=text]');
		var $demo_data_desc = $('.acf-field[data-name=bee_topic_cover] .acf-field[data-name=demo_data] .acf-field[data-name=desc] input[type=text]');
		var $demo_data_cate = $('.acf-field[data-name=bee_topic_cover] .acf-field[data-name=demo_data] .acf-field[data-name=cate] input[type=text]');
		var $coverstyle = $('.acf-field[data-name=bee_topic_cover] .acf-field[data-name=coverstyle] input[type=radio]');
		var $style_position = $('.acf-field[data-name=bee_topic_cover] .acf-field[data-name=style_position] input[type=radio]');
		var $text_postion = $('.acf-field[data-name=bee_topic_cover] .acf-field[data-name=text_postion] input');
		var $text_show = $('.acf-field[data-name=bee_topic_cover] .acf-field[data-name=text_show] input[type=checkbox]');
		var $text_color = $('.acf-field[data-name=bee_topic_cover] .acf-field[data-name=text_color] input');
		var $text_bgcolor = $('.acf-field[data-name=bee_topic_cover] .acf-field[data-name=text_bgcolor] input');
		var $style_height = $('.acf-field[data-name=bee_topic_cover] .acf-field[data-name=style_height] input');
		var $text_color_set = $('.acf-field[data-name=bee_topic_cover] .acf-field[data-name=text_color_set] input');
		var $text_bgcolor_set = $('.acf-field[data-name=bee_topic_cover] .acf-field[data-name=text_bgcolor_set] input');

		// beebee_demo_data();
		beebee_coverstyle();
		beebee_style_position();
		beebee_text_postion();
		beebee_text_show();
		beebee_style_height();
		beebee_text_color();
		beebee_text_bgcolor();
		// beebee_demo_rule()

		$demo_data_tit.change(function () { beebee_text_show(); });
		$demo_data_desc.change(function () { beebee_text_show(); });
		$demo_data_cate.change(function () { beebee_text_show(); });
		$coverstyle.change(function () { beebee_coverstyle(); });
		$style_position.change(function () { beebee_style_position(); });
		$text_postion.change(function () { beebee_text_postion(); });
		$text_show.change(function () { beebee_text_show(); });
		$text_color_set.change(function () { beebee_text_color(); });
		$text_bgcolor_set.change(function () { beebee_text_bgcolor(); });
		$style_height.change(function () { beebee_style_height(); });
		$text_color.change(function () { beebee_text_color(); });
		$text_bgcolor.change(function () { beebee_text_bgcolor(); });
		function beebee_coverstyle() {
			if ($coverstyle.filter(":checked").attr("value") == '1') {
				$('.bee-bgimg-wrap').css({ 'transform': 'translateX(0px)' });
			}
			if ($coverstyle.filter(":checked").attr("value") == '2') {
				$('.bee-bgimg-wrap').css({ 'transform': 'translateX(-335px)' });

				// $('.swipslider').swipeslider();



			}
			if ($coverstyle.filter(":checked").attr("value") == '3') {
				$('.bee-bgimg-wrap').css({ 'transform': 'translateX(-670px)' });
			}
			if ($coverstyle.filter(":checked").attr("value") == '4') {
				$('.bee-bgimg-wrap').css({ 'transform': 'translateX(-1005px)' });
			}
			if ($coverstyle.filter(":checked").attr("value") == '5') {
				$('.bee-bgimg-wrap').css({ 'transform': 'translateX(-1340px)' });
			}
			if ($coverstyle.filter(":checked").attr("value") == '6') {
				$('.bee-bgimg-wrap').css({ 'transform': 'translateX(-1675px)' });
			}
			if ($coverstyle.filter(":checked").attr("value") == '7') {
				$('.bee-bgimg-wrap').css({ 'transform': 'translateX(-2010px)' });
			}

		}
		function beebee_style_height() {
			$('.bee-card-body,.bee-bgimg-cnt,.bee-bgimg').css({ 'height': $style_height.val() + 'px' });
			$('.bee-card').css({ 'min-height': $style_height.val() + 'px' });
			beebee_demo_rule();
		}
		function beebee_style_position() {
			if ($style_position.filter(":checked").attr("value") == 'relative') {
				$('.bee-card-body').css({ 'position': 'relative' });
			} else if ($style_position.filter(":checked").attr("value") == 'absolute') {
				$('.bee-card-body').css({ 'position': 'absolute' });
			}
			beebee_demo_rule();
		}
		function beebee_text_show() {
			$('.bee-card-header').removeClass('headshow');
			$('.bee-card-header .acf-field').css({ 'display': 'none' });
			$($text_show.filter(":checked")).each(function () {
				if ($(this).val() == 1) {
					$('.bee-card-header').addClass('headshow');
					$('.bee-card-header-title').css({ 'display': '-webkit-box' }).text($demo_data_tit.val());
				} else if ($(this).val() == 2) {
					$('.bee-card-header').addClass('headshow');
					$('.bee-card-header-subtitle').css({ 'display': '-webkit-box' }).text($demo_data_desc.val());;
				} else if ($(this).val() == 3) {
					$('.bee-card-header').addClass('headshow');
					$('.bee-card-header-label').css({ 'display': 'block' }).find('span').text($demo_data_cate.val());;
				} else if ($(this).val() == 4) {
					$('.bee-card-header').addClass('headshow');
					$('.bee-postlike').css({ 'display': 'flex' });
				}

			});

			beebee_demo_rule();
		}
		function beebee_text_postion() {
			if ($text_postion.filter(":checked").attr("value") == 1) {
				$('.bee-card').css({ 'display': 'block' });
			} else if ($text_postion.filter(":checked").attr("value") == 2) {
				$('.bee-card').css({ 'display': 'flex', 'flex-flow': 'column-reverse' });
			}
			beebee_demo_rule();
		}
		function beebee_text_color() {
			if ($text_color.filter(":checked").attr("value") == 1) {
				$('.bee-card-header').css({ 'color': '#000000' });
			} else if ($text_color.filter(":checked").attr("value") == 2) {
				$('.bee-card-header').css({ 'color': $text_color_set.val() });
			}
		}

		function beebee_text_bgcolor() {
			if ($text_bgcolor.filter(":checked").attr("value") == 1) {
				$('.bee-card-header').css({ 'background-color': '#ffffff' });
			} else if ($text_bgcolor.filter(":checked").attr("value") == 2) {
				$('.bee-card-header').css({ 'background-color': 'transparent' });
			} else if ($text_bgcolor.filter(":checked").attr("value") == 3) {
				$('.bee-card-header').css({ 'background-color': $text_bgcolor_set.val() });
			}
		}

		function beebee_demo_rule() {
			$('.bee_rule_body_h').show();
			$('.bee_rule_card_h').css({ 'top': '0', 'height': $('.bee-card').outerHeight() }).text($('.bee-card').outerHeight());
			$('.bee_rule_top_w').css({ 'left': '0', 'width': $('.bee-card').outerWidth() }).text($('.bee-card').outerWidth());
			
			if ($text_postion.filter(":checked").attr("value") == 1) {
				$('.bee_rule_head_h').css({ 'top': '0', 'height': $('.bee-card-header').outerHeight() }).text($('.bee-card-header').outerHeight());
			} else {
				if ($style_position.filter(":checked").attr("value") == 'absolute') {
					if ($('.bee-card-body').outerHeight() < $('.bee-card-header').outerHeight()) {
						$('.bee_rule_head_h').css({ 'top': 0, 'height': $('.bee-card-header').outerHeight() }).text($('.bee-card-header').outerHeight());
					} else {
						$('.bee_rule_head_h').css({ 'top': $('.bee-card-body').outerHeight() - $('.bee-card-header').outerHeight(), 'height': $('.bee-card-header').outerHeight() }).text($('.bee-card-header').outerHeight());
					}
				} else {
					$('.bee_rule_head_h').css({ 'top': $('.bee-card-body').outerHeight(), 'height': $('.bee-card-header').outerHeight() }).text($('.bee-card-header').outerHeight());
				}

			}
			if ($style_position.filter(":checked").attr("value") == 'absolute' && $text_postion.filter(":checked").attr("value") == 1) {
				$('.bee_rule_body_h').css({ 'top': $('.bee-card-header').outerHeight(), 'height': $('.bee-card-body').outerHeight() - $('.bee-card-header').outerHeight() }).text($('.bee-card-body').outerHeight() - $('.bee-card-header').outerHeight());
			} else if ($style_position.filter(":checked").attr("value") == 'relative' && $text_postion.filter(":checked").attr("value") == 1) {
				$('.bee_rule_body_h').css({ 'top': $('.bee-card-header').outerHeight(), 'height': $('.bee-card-body').outerHeight() }).text($('.bee-card-body').outerHeight());
			} else if ($style_position.filter(":checked").attr("value") == 'absolute' && $text_postion.filter(":checked").attr("value") == 2) {
				$('.bee_rule_body_h').css({ 'top': '0', 'height': $('.bee-card-body').outerHeight() - $('.bee-card-header').outerHeight() }).text($('.bee-card-body').outerHeight() - $('.bee-card-header').outerHeight());
			} else if ($style_position.filter(":checked").attr("value") == 'relative' && $text_postion.filter(":checked").attr("value") == 2) {
				$('.bee_rule_body_h').css({ 'top': '0', 'height': $('.bee-card-body').outerHeight() }).text($('.bee-card-body').outerHeight());
			}
			if ($style_position.filter(":checked").attr("value") == 'absolute' && $('.bee-card-body').outerHeight() < $('.bee-card-header').outerHeight()) {
				$('.bee_rule_body_h').hide();
			}
		}
	// })
})