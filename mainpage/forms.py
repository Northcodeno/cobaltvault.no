from django import forms
from django.contrib.auth.models import User
from django.contrib.auth.forms import UserCreationForm
from captcha.fields import ReCaptchaField

class RegForm(forms.Form):
	username = forms.CharField(label="Username", max_length=30, min_length=3, validators=[isValidUsername, validators.validate_slug])
	email = forms.EmailField(label="Email",max_length=100, error_messages={'invalid': ("Invalid email.")},validators=[isValidEmail])
	password1 = forms.CharField(label="Password",max_length=50,min_length=6)
    password2 = forms.CharField(label="Confirm Password",max_length=50,min_length=6)
	captcha = ReCaptchaField()

	#class Meta:
	#	model = User

	def clean(self):
		def clean(self):
        password1 = self.cleaned_data.get('password1')
        password2 = self.cleaned_data.get('password2')

        if password1 and password1 != password2:
            self._errors['password2'] = ErrorList([u"The passwords do not match."])

        return self.cleaned_data

	def save(self, data):
		u = User.objects.create_user(data['username'], data['email'], data['password1'])
		u.is_active = False
		yuki = Yuki()
		yuki.user = u
		yuki.activation_key = data['activation_key']
		yuki.save()
		return u

	def sendEmail(self, data):
		