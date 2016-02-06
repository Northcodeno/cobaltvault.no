from django.db import models
from django_markdown.models import MarkdownField

# Create your models here.

class Donation(models.Model):
    name = models.CharField(max_length=80)
    amount = models.PositiveIntegerField()
    date = models.DateTimeField()

class Faq(models.Model):
    CATEGORIES = (
        (0, "Cobalt Vault"),
        (1, "Installation"),
        (2, "About"),
        )
    category = models.SmallIntegerField(choices=CATEGORIES,default=0)
    title = models.CharField(max_length=140)
    content = models.TextField()
    
class NewsPost(models.Model):
    title = models.CharField(max_length=45)
    text = MarkdownField()
    date = models.DateTimeField(auto_now_add=True)

class Stream(models.Model):
    username = models.CharField(max_length=255)
    display_name = models.CharField(max_length=255)

    
