# -*- coding: utf-8 -*-
# Generated by Django 1.9.1 on 2016-02-06 16:35
from __future__ import unicode_literals

from django.db import migrations
import django_markdown.models


class Migration(migrations.Migration):

    dependencies = [
        ('mainpage', '0004_auto_20160206_1608'),
    ]

    operations = [
        migrations.AlterField(
            model_name='comment',
            name='messsage',
            field=django_markdown.models.MarkdownField(max_length=500),
        ),
        migrations.AlterField(
            model_name='project',
            name='description',
            field=django_markdown.models.MarkdownField(),
        ),
    ]