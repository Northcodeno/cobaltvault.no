import django_tables2 as tables
from .models import Project

class ProjectTable(tables.Table):
	title = tables.Column(accessor='get_thumb', verbose_name='', orderable=False)
	description = tables.Column(accessor='get_desc', verbose_name='Name', order_by='name')
	maptype = tables.Column(verbose_name='Type')
	downloads = tables.Column(verbose_name='DL')
	date_created = tables.Column(accessor='date_created.date', verbose_name='Date Created')
	date_modified = tables.Column(accessor='date_modified.date', verbose_name='Last Update')
	author = tables.Column(accessor='get_pretty_authors', verbose_name='Author', orderable=False)

	class Meta:
		attrs = {'class': 'table'}