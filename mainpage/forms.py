from django import forms
from django.contrib.auth.forms import AuthenticationForm

class LoginForm(AuthenticationForm):
	def __init__(self):
		super(AuthenticationForm, self).__init__()
		print(self.username)
		return