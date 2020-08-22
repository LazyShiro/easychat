$(function () {
	var uri = '/api/roomApi/getList';
	var process = false;

	function aJiaKeSi(uri, data, type, callback = null) {
		$.ajax({
			url: uri,
			data: data,
			type: type,
			success: function (result) {
				if (callback) {
					callback(result);
				}
			}
		})
	}

	function refreshDom(data) {
		$("#RoomList").html('');
		var dom = '';
		$.each(data, function (index, value) {
			dom = dom + '<article class="white-panel"> <img src="' + value.bg + '" class="thumb"> <h1><a href="#">' + value.names + '</a></h1> <p>' + value.descs + '</p> </article>';
		});
		$("#RoomList").html(dom);
	}

	function appendDom(data) {
		var dom = '';
		$.each(data, function (index, value) {
			dom = dom + '<article class="white-panel"> <img src="' + value.bg + '" class="thumb"> <h1><a href="#">' + value.names + '</a></h1> <p>' + value.descs + '</p> </article>';
		});
		$("#RoomList").append(dom);
	}

	function tabChange(index) {
		$('.nav_link').removeClass('active');
		$('.nav_link').eq(index).addClass('active');
	}

	aJiaKeSi(uri, {type: 0, page: 1, limit: 9}, 'get', function (data) {
		if (data.data) {
			refreshDom(data.data)
		}
	});

	$('.nav_link').click(function () {
		var index = $("li").index(this);
		tabChange(index);
		var type = $(this).data('type');
		$('#RoomList').data('type', type);
		var page = 1;
		$('#RoomList').data('page', page);
		aJiaKeSi(uri, {type: type, page: page, limit: 9}, 'get', function (data) {
			refreshDom(data.data)
		});
		process = false;
	})

	$(this).scroll(function () {
		var viewHeight = $(this).height();//可见高度
		var contentHeight = $("#RoomList").get(0).scrollHeight;//内容高度
		var scrollHeight = $(this).scrollTop();//滚动高度

		if (process == false && contentHeight - viewHeight < scrollHeight) {
			process = true;
			var type = $('#RoomList').data('type');
			var currentPage = $('#RoomList').data('page');
			if (!type) {
				type = 0;
			}
			if (!currentPage) {
				currentPage = 2;
			}
			aJiaKeSi(uri, {type: type, page: currentPage, limit: 9}, 'get', function (data) {
				if (data.data.length > 0) {
					dataPage = parseInt(currentPage) + 1;
					appendDom(data.data);
					$('#RoomList').data('page', dataPage);
					process = false;
				}
			});
		}
	});

});