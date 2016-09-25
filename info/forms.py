import bleach

from django import forms
from django.forms import ModelForm
from mainpage.util import MarkdownBlackList

from .models import NewsPost


class NewsForm(ModelForm):

	def clean_text(self):
		return bleach.clean(self.cleaned_data['text'], tags=MarkdownBlackList())

	class Meta:
		model = NewsPost
		fields = ['title', 'text']
