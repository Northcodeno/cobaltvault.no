# -*- coding: utf-8 -*-
# Generated by Django 1.9.1 on 2016-04-27 11:17
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('mainpage', '0011_auto_20160427_1313'),
    ]

    operations = [
        migrations.AlterField(
            model_name='comment',
            name='message',
            field=models.TextField(),
        ),
    ]
