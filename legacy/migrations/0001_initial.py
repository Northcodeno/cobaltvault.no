# -*- coding: utf-8 -*-
# Generated by Django 1.9.1 on 2016-10-02 14:37
from __future__ import unicode_literals

from django.conf import settings
from django.db import migrations, models
import django.db.models.deletion


class Migration(migrations.Migration):

    initial = True

    dependencies = [
        ('mainpage', '0016_auto_20160501_1820'),
        migrations.swappable_dependency(settings.AUTH_USER_MODEL),
    ]

    operations = [
        migrations.CreateModel(
            name='LegacyProject',
            fields=[
                ('id', models.AutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('legacyid', models.PositiveIntegerField()),
                ('legacyslug', models.CharField(max_length=255)),
                ('project', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='mainpage.Project')),
            ],
        ),
        migrations.CreateModel(
            name='LegacyUser',
            fields=[
                ('id', models.AutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('legacyid', models.PositiveIntegerField()),
                ('user', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to=settings.AUTH_USER_MODEL)),
            ],
        ),
    ]
