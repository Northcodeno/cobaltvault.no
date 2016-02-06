from django.db import models
from django.contrib.auth.models import User
from easy_thumbnails.signals import saved_file
from easy_thumbnails.signal_handlers import generate_aliases_global
from easy_thumbnails.fields import ThumbnailerImageField
from easy_thumbnails.files import get_thumbnailer
from easy_thumbnails.alias import aliases
from django_markdown.models import MarkdownField

from django.template import loader, Context

# Create your models here.
class ProjectMapType(models.Model):
    name = models.CharField(max_length=25)
    name_short = models.CharField(max_length=11)

    def __str__(self):
        return self.name

    
class Project(models.Model):
    idname = models.SlugField()
    name = models.CharField(max_length=40)
    description = MarkdownField()
    maptype = models.ForeignKey(ProjectMapType)
    downloads = models.PositiveIntegerField()
    date_created = models.DateTimeField(auto_now_add = True)
    date_modified = models.DateTimeField(auto_now = True)
    author = models.ManyToManyField(User)
    version = models.CharField(max_length=10)
    ispublic = models.BooleanField()
    thumbnail = ThumbnailerImageField(upload_to="thumbnails/",resize_source=dict(size=(1920,1080), sharpen=True))

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
    messsage = MarkdownField(max_length=500)
    date = models.DateTimeField(auto_now_add=True)
    project = models.ForeignKey(Project)
    replyto = models.ForeignKey("Comment",null=True)
    
saved_file.connect(generate_aliases_global)
