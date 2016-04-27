import datetime

from django.contrib.auth.models import User
from django.db import models
from django.template import Context, loader
from django_markdown.models import MarkdownField
from easy_thumbnails.alias import aliases
from easy_thumbnails.fields import ThumbnailerImageField
from easy_thumbnails.files import get_thumbnailer
from easy_thumbnails.signal_handlers import generate_aliases_global
from easy_thumbnails.signals import saved_file


# Create your models here.
class ProjectMapType(models.Model):
    name = models.CharField(max_length=25)
    name_short = models.CharField(max_length=11)

    def __str__(self):
        return self.name

class RegUser(models.Model):
    user = models.OneToOneField(User)
    activation_key = models.CharField(max_length=255)
    # profile_image = ThumbnailerImageField(upload_to="")
    date_registered = models.DateField(auto_now_add=True)
    about = models.TextField(default="I'm a metalface")

class Project(models.Model):
    idname = models.SlugField()
    name = models.CharField(max_length=40, unique=True)
    description = MarkdownField()
    maptype = models.ForeignKey(ProjectMapType)
    downloads = models.PositiveIntegerField(default=0)
    date_created = models.DateTimeField(auto_now_add = True)
    date_modified = models.DateTimeField(auto_now = True)
    author = models.ManyToManyField(User)
    version = models.CharField(max_length=10, blank=True)
    ispublic = models.BooleanField(default=True)
    thumbnail = ThumbnailerImageField(upload_to="thumbnails/",resize_source=dict(size=(1920,1080), sharpen=True))
    file = models.FileField(upload_to="projects/")


    def __str__(self):
        return self.name

    def get_desc(self):
        return loader.get_template('table/name_field.html').render(Context({'project':self}))

    def get_thumb(self):
        t = loader.get_template('table/thumb_field.html')
        c = Context({'project': self})
        return t.render(c)

    def get_pretty_authors(self):
        return loader.get_template('table/author_field.html').render(Context({'authors':self.author.all()}))

class ProjectVote(models.Model):
    user = models.ForeignKey(User,on_delete = models.CASCADE)
    project = models.ForeignKey(Project, on_delete = models.CASCADE)
    is_upvote = models.BooleanField()

class Comment(models.Model):
    author = models.ForeignKey(User)
    message = models.TextField()
    date = models.DateTimeField(auto_now_add=True)
    project = models.ForeignKey(Project)
    replyto = models.ForeignKey('self', null=True, blank=True, on_delete=models.CASCADE)
    
    def get_replies(self):
        return Comment.objects.filter(project=self.project, replyto=self)

saved_file.connect(generate_aliases_global)
