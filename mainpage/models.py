from django.db import models
from django.contrib.auth.models import User

# Create your models here.
class ProjectMapType(models.Model):
    name = models.CharField(max_length=25)
    name_short = models.CharField(max_length=11)

    
class Project(models.Model):
    idname = models.SlugField()
    name = models.CharField(max_length=40)
    description = models.TextField()
    maptype = models.ForeignKey(ProjectMapType)
    downloads = models.PositiveIntegerField()
    date_created = models.DateTimeField(auto_now_add = True)
    date_modified = models.DateTimeField(auto_now = True)
    author = models.ManyToManyField(User)
    version = models.CharField(max_length=10)
    ispublic = models.BooleanField()
    thumbnail = models.ImageField(upload_to="thumbnails/")

class ProjectFile(models.Model):
    project = models.ForeignKey(Project)
    filename = models.FileField()
    FILE_TYPES= (
        (1, "map"),
        (2, "localization"),
        (3, "music"),
        (4, "other"),
        )
    filetype = models.PositiveIntegerField(choices=FILE_TYPES,default=1)

class ProjectVote(models.Model):
    user = models.ForeignKey(User,on_delete = models.CASCADE)
    project = models.ForeignKey(Project, on_delete = models.CASCADE)
    is_upvote = models.BooleanField()

class Comment(models.Model):
    author = models.ForeignKey(User)
    messsage = models.CharField(max_length=500)
    date = models.DateTimeField(auto_now_add=True)
    project = models.ForeignKey(Project)
    replyto = models.ForeignKey("Comment",null=True)
    
