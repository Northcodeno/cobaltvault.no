from django import forms
from django.forms import ModelForm

from .models import NewsPost
from mainpage.util import MarkdownBlackList

class NewsForm(ModelForm):

	def clean_text():
		return bleach.clean(self.cleaned_data['text'], tags=MarkdownBlackList())

	class Meta:
		model = NewsPost
		fields = ['title', 'text']