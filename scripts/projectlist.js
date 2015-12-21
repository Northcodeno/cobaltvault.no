

function projectsData(options, callback) {

	var columns = [
		{
			label: '',
			property: 'thumbnail',
			width: '5%'
		},
		{
			label: 'Title',
			property: 'title',
			sortable: false,
			width: '35%' 
		},
		{
			label: 'Type',
			property: 'type',
			sortable: true
		},
		{
			label: 'Author',
			property: 'author',
			sortable: true
		},
		{
			label: 'Rating',
			property: 'rating',
			sortable: true
		},
		{
			label: 'Downloads',
			property: 'downloads',
			sortable: true
		},
		{
			label: 'Created',
			property: 'date_created',
			sortable: true
		},
		{
			label: 'Updated',
			property: 'date_modified',
			sortable: true
		}
	];

	var pageIndex = options.pageIndex;
	var pageSize = options.pageSize;
	var options = {
		'pageIndex': pageIndex,
		'pageSize': pageSize,
		'sortDirection': options.sortDirection,
		'sortBy': options.sortProperty,
		'filterBy': options.filter.value || '',
		'searchBy': options.search || ''
	};

	console.log(options);
	  // call API, posting options
	$.ajax({
		'type': 'get',
		'url': '/api/1.1/project/repeater.php',
		'timeout': 15000,
		'data': options
	}).done(function(data) {
		var items = data.items;
		var totalItems = data.total;
		var totalPages = Math.ceil(totalItems / pageSize);
		var startIndex = (pageIndex * pageSize) + 1;
		var endIndex = (startIndex + pageSize) - 1;

		if(endIndex > items.length) {
			endIndex = items.length;
		}

		// configure datasource
		var dataSource = {
			'page':    pageIndex,
			'pages':   totalPages,
			'count':   totalItems,
			'start':   startIndex,
			'end':     endIndex,
			'columns': columns,
			'items':   items
		};

		// pass the datasource back to the repeater
		callback(dataSource);
	});

}