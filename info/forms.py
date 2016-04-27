from django import forms
from django.forms import ModelForm

from .models import NewsPost

class NewsForm(ModelForm):

	class Meta:
		model = NewsPost
		fields = ['title', 'text']