from django import forms
from django.forms import ModelForm
from django.core.mail import send_mail
from django.contrib.auth.models import User
from django.contrib.auth.forms import UserCreationForm
from captcha.fields import ReCaptchaField
from django.core.validators import validate_slug, validate_email
from mainpage.models import RegUser
from django.template import Context

from django_markdown.widgets import MarkdownWidget

from .models import Project

class RegForm(forms.Form):
	username = forms.CharField(label="Username", max_length=30, min_length=3, validators=[validate_slug])
	email = forms.EmailField(label="Email",max_length=100, error_messages={'invalid': ("Invalid email.")},validators=[validate_email])
	password1 = forms.CharField(label="Password",max_length=50,min_length=6)
	password2 = forms.CharField(label="Confirm Password",max_length=50,min_length=6)
	captcha = ReCaptchaField()

	def clean(self):
		password1 = self.cleaned_data.get('password1')
		password2 = self.cleaned_data.get('password2')

		if password1 and password1 != password2:
			raise forms.ValidationError(
				'Passwords do not match',
				code='password_mismatch')

		return self.cleaned_data

	def save(self, data):
		u = User.objects.create_user(data['username'], data['email'], data['password1'])
		u.is_active = False
		u.save()
		yuki = RegUser()
		yuki.user = u
		yuki.activation_key = data['activation_key']
		yuki.save()
		return u

	def send_email(self, data):
		"""link = "https://cobaltvault.no/activate/" + data['activation_key']
		c = Context({'url': link, 'username': data['username']})
		text = render_to_string("mail/activate.html", c)
		send_mail('Activate your account on cobaltvault', text, 'Cobaltvault (NC) <info@northcode.no>', [data['email']], fail_silently=False)"""
		return

class CreateForm(ModelForm):

	def clean(self):

		# TODO: Check filetypes

		return self.cleaned_data

	def save(self, data, user):
		p = Project()
		p.name = data['name']
		slug = ''.join(e for e in data['name'] if e.isalnum())
		if Project.objects.filter(idname=slug).exists():
			num = 2
			while Projects.objects.filter(idname=slug.join(num)).exists():
				num += 1
			slug = slug.join(num)

		p.idname = slug
		p.maptype = data['maptype']
		p.author = user
		p.version = data['version']
		p.thumbnail = data['thumbnail']
		p.file = data['file']
		p.save()

	class Meta:
		model = Project
		fields = ['name', 'description', 'maptype', 'version', 'thumbnail', 'file']