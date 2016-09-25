from django.db import models
from django_markdown.models import MarkdownField

# Create your models here.

class Donation(models.Model):
    name = models.CharField(max_length=80)
    amount = models.PositiveIntegerField()
    date = models.DateTimeField()

    def __str__(self):
    	return self.name
    
class NewsPost(models.Model):
    title = models.CharField(max_length=45)
    text = MarkdownField()
    date = models.DateTimeField(auto_now_add=True)

    def __str__(self):
    	return self.title