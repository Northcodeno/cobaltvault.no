# -*- coding: utf-8 -*-
# Generated by Django 1.9.1 on 2016-02-06 16:08
from __future__ import unicode_literals

from django.db import migrations
import easy_thumbnails.fields


class Migration(migrations.Migration):

    dependencies = [
        ('mainpage', '0003_comment'),
    ]

    operations = [
        migrations.AlterField(
            model_name='project',
            name='thumbnail',
            field=easy_thumbnails.fields.ThumbnailerImageField(upload_to='thumbnails/'),
        ),
    ]