from django.db import models
from django_markdown.models import MarkdownField

# Create your models here.

class Donation(models.Model):
    name = models.CharField(max_length=80)
    amount = models.PositiveIntegerField()
    date = models.DateTimeField()
    
class NewsPost(models.Model):
    title = models.CharField(max_length=45)
    text = MarkdownField()
    date = models.DateTimeField(auto_now_add=True)