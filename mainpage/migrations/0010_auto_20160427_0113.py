# -*- coding: utf-8 -*-
# Generated by Django 1.9.1 on 2016-04-26 23:13
from __future__ import unicode_literals

from django.db import migrations, models
import django.db.models.deletion


class Migration(migrations.Migration):

    dependencies = [
        ('mainpage', '0009_auto_20160426_2144'),
    ]

    operations = [
        migrations.AlterField(
            model_name='comment',
            name='replyto',
            field=models.ForeignKey(blank=True, null=True, on_delete=django.db.models.deletion.CASCADE, to='mainpage.Comment'),
        ),
    ]
