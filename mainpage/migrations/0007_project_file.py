# -*- coding: utf-8 -*-
# Generated by Django 1.9.1 on 2016-04-26 15:46
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('mainpage', '0006_auto_20160312_0232'),
    ]

    operations = [
        migrations.AddField(
            model_name='project',
            name='file',
            field=models.FileField(default=1, upload_to=''),
            preserve_default=False,
        ),
    ]
