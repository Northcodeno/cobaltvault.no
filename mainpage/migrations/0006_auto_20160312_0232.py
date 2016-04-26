# -*- coding: utf-8 -*-
# Generated by Django 1.9.1 on 2016-03-12 02:32
from __future__ import unicode_literals

import datetime
from django.db import migrations, models
from django.utils.timezone import utc


class Migration(migrations.Migration):

    dependencies = [
        ('mainpage', '0005_auto_20160308_1805'),
    ]

    operations = [
        migrations.AddField(
            model_name='reguser',
            name='about',
            field=models.TextField(default="I'm a metalface"),
        ),
        migrations.AddField(
            model_name='reguser',
            name='date_registered',
            field=models.DateField(auto_now_add=True, default=datetime.datetime(2016, 3, 12, 2, 32, 46, 266133, tzinfo=utc)),
            preserve_default=False,
        ),
    ]