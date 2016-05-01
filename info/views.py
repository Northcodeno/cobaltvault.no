from django.contrib import messages
from django.contrib.auth.forms import PasswordResetForm, SetPasswordForm
from django.core.urlresolvers import reverse
from django.http import HttpResponseRedirect
from django.shortcuts import render
from mainpage.util import safeRedirect


# Create your views here.

def password_reset_sent(request):
	messages.success(request, 'An email has been sent to you with instructions to reset your password')
	return HttpResponseRedirect(reverse('index'))

def password_reset_complete(request):
	messages.success(request, 'Your password has been set')
	return HttpResponseRedirect(reverse('index'))
