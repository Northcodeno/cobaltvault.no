from django import forms
from django.contrib.auth.forms import UserCreationForm
from captcha.fields import ReCaptchaField

class RegForm(UserCreationForm):
	email = forms.EmailField()
	captcha = ReCaptchaField()