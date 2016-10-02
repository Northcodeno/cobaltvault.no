from django.db import models

from mainpage.models import Project
from django.contrib.auth.models import User

# Create your models here.

class LegacyProject(models.Model):
	legacyid = models.PositiveIntegerField()
	legacyslug = models.CharField(max_length=255)
	project = models.ForeignKey(Project)

class LegacyUser(models.Model):
	legacyid = models.PositiveIntegerField()
	user = models.ForeignKey(User)
