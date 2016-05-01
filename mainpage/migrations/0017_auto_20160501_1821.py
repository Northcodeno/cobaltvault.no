# -*- coding: utf-8 -*-
# Generated by Django 1.9.1 on 2016-05-01 16:21
from __future__ import unicode_literals

from django.db import migrations
from django.contrib.auth.models import User
from mainpage.models import RegUser

import MySQLdb, string, random

def get_cursor(db="northcode"):
	try:
		db = MySQLdb.connect(user="tmpcvmig", passwd="SYHRubmUPHHGsyDZ", db=db)
		return db.cursor()
	except:
		pass

def rand_generator(size=50, chars=string.ascii_uppercase + string.ascii_lowercase + string.digits):
	return ''.join(random.choice(chars) for _ in range(size))


def import_users(apps, schema_editor):
	cn = get_cursor()
	if cn:
		cn.execute("""SELECT username, email, info, registered FROM users""")
		users = cn.fetchall()

		for user in users:
			uobj = User.objects.create_user(user[0], user[1], rand_generator())
			if user[3]:
				ru = RegUser.objects.create(user=uobj, about=user[2], date_registered=user[3])
			else:
				ru = RegUser.objects.create(user=uobj, about=user[2])
			ru.save()
	else:
		print("Could not connect to db, ignoring")

class Migration(migrations.Migration):

    dependencies = [
        ('mainpage', '0016_auto_20160501_1820'),
    ]

    operations = [
    ]
