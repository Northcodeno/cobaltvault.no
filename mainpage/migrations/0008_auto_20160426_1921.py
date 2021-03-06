# -*- coding: utf-8 -*-
# Generated by Django 1.9.1 on 2016-04-26 17:21
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('mainpage', '0007_project_file'),
    ]

    operations = [
        migrations.RemoveField(
            model_name='projectfile',
            name='project',
        ),
        migrations.AlterField(
            model_name='project',
            name='file',
            field=models.FileField(upload_to='project_files/'),
        ),
        migrations.AlterField(
            model_name='project',
            name='ispublic',
            field=models.BooleanField(default=True),
        ),
        migrations.AlterField(
            model_name='project',
            name='version',
            field=models.CharField(blank=True, max_length=10),
        ),
        migrations.DeleteModel(
            name='ProjectFile',
        ),
    ]
