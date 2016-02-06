import django_tables2 as tables
from .models import Project

class ProjectTable(tables.Table):
	name = tables.Column(verbose_name='Name')
	description = tables.Column(verbose_name='Description')
	maptype = tables.Column(verbose_name='Type')
	downloads = tables.Column(verbose_name='DL')
	date_created = tables.Column(verbose_name='Date Created')
	date_modified = tables.Column(verbose_name='Last Update')
	author = tables.Column(verbose_name='Author')

	class Meta:
		attrs = {'class': 'table'}