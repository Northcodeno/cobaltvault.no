import re, bleach

from captcha.fields import ReCaptchaField
from django import forms
from django.contrib.auth.forms import UserCreationForm
from django.contrib.auth.models import User
from django.core.mail import send_mail
from django.core.validators import validate_email, validate_slug
from django.forms import ModelForm
from django.template import Context
from django.template.defaultfilters import slugify
from django_markdown.widgets import MarkdownWidget
from mainpage.models import RegUser

from .models import Comment, Project, RegUser
from .util import MarkdownBlackList

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

	def clean_file(self):
		if not re.match('^.*\.(zip)$', self.files['file'].name):
			raise forms.ValidationError(
				'Invalid file type',
				code='invalid_filetype')

		return self.files['file']

	def clean_description(self):
		return bleach.clean(self.cleaned_data['description'], tags=MarkdownBlackList())

	def save(self, user):
		slug = slugify(self.cleaned_data['name'])
		if Project.objects.filter(idname=slug).exists():
			num = 2
			while Project.objects.filter(idname=(slug + str(num))).exists():
				num += 1
			slug = slug + str(num)

		proj = Project.objects.create(
			idname=slug,
			name=self.cleaned_data['name'],
			description=self.cleaned_data['description'],
			maptype=self.cleaned_data['maptype'],
			version=self.cleaned_data['version'],
			thumbnail=self.cleaned_data['thumbnail'],
			file=self.cleaned_data['file'])
		proj.save()

		return proj

	class Meta:
		model = Project
		fields = ['name', 'description', 'maptype', 'version', 'thumbnail', 'file']
		help_texts = {
			'name': 'The name of your map',
			'description': 'Provide a description of your map, the uploaded thumbnail will show up above the description',
			'version': '(Optional) Provide a version in your own format so that people know when your map has updated',
			'thumbnail': 'Upload a thumbnail which will show up on the frontpage, the list and on the project description (allowed file types: jpg,png)',
			'file': 'Upload a zip file with your project files (only zip files allowed)'
		}

class CommentForm(ModelForm):

	def save(self, user, project, replyto=None):
		c = Comment.objects.create(
			author=user,
			message=self.cleaned_data['message'],
			project=project,
			replyto=replyto
			)

		c.save()
		return c

	class Meta:
		model = Comment
		fields = ['message']
		labels = {'message': 'Submit a comment'}
		widgets = {
			'message': forms.Textarea(attrs={'rows': 4})
		}

class ProfileForm(ModelForm):

	class Meta:
		model = RegUser
		fields = ['about', 'profile_image']

class ProjectForm(ModelForm):

	def clean_description(self):
		return bleach.clean(self.cleaned_data['description'], tags=MarkdownBlackList())

	class Meta:
		model = Project
		fields = ['name', 'description', 'maptype', 'version', 'thumbnail', 'file']
		help_texts = {
			'name': 'The name of your map',
			'description': 'Provide a description of your map, the uploaded thumbnail will show up above the description',
			'version': '(Optional) Provide a version in your own format so that people know when your map has updated',
			'thumbnail': 'Upload a thumbnail which will show up on the frontpage, the list and on the project description (allowed file types: jpg,png)',
			'file': 'Upload a zip file with your project files (only zip files allowed)'
		}