from django.utils.translation import ungettext_lazy
from django.shortcuts import render
from django.http import Http404
from django.views.generic.list import ListView
from django.utils.encoding import smart_str
from django.db.models import Q
from django.shortcuts import get_object_or_404
from django_tables2 import RequestConfig
from django.contrib.auth import login
from django.contrib.auth import logout
from django.contrib.auth import authenticate
from django.contrib.auth.forms import AuthenticationForm
from django.contrib import messages
from django.core.urlresolvers import reverse
from django.http import HttpResponseRedirect
from django import forms

from pprint import pprint
import sys
import hashlib
import random
import uuid

from .models import Project
from .models import RegUser
from .tables import ProjectTable
from .forms import RegForm
from info.models import NewsPost

# Create your views here.
def index(request):
	latest = Project.objects.order_by('-date_modified')[:5]
	mostdl = Project.objects.order_by('-downloads')[:5]
	news = NewsPost.objects.all()[:5]
	return render(request, "mainpage/index.html", { 'latest': latest, 'mostdl': mostdl, 'news': news })

def list(request):
	table = ProjectTable(Project.objects.all())
	table.paginate(page=request.GET.get('page',1), per_page=25)
	RequestConfig(request).configure(table)
	return render(request, "mainpage/list.html", {'table': table })

def project(request, project_id):
	if (project_id.isdigit()):
		project = get_object_or_404(Project, pk=project_id)
	else:
		project = get_object_or_404(Project, idname=project_id)
	return render(request, "mainpage/project.html", {'project': project})

def project_download(request, project_id):
	return
	#project = get_object_or_404(Project, Q(pk=project_id)|Q(idname=project_id))
	
	#response = HttpResponse(mimetype='application/force-download')
	#response['Content-Disposition'] = 'attachment; filename=%s' % smart_str()

def register_view(request):
	if request.user.is_authenticated():
		return HttpResponseRedirect(reverse('index'))

	form = RegForm()
	if request.method == 'POST':
		form = RegForm(data=request.POST)
		if form.is_valid():
			data = {
				'username': form.cleaned_data['username'],
				'email': form.cleaned_data['email'],
				'password1': form.cleaned_data['password1'],
			}

			salt = str(uuid.uuid4().int)[:5]
			usernamesalt = data['username']
			data['activation_key'] = hashlib.sha1((salt + usernamesalt).encode('utf8')).hexdigest()
			form.send_email(data)
			form.save(data)
			messages.success(request, 'You have successfully registered. Please check your mail and click the activation link')
			return HttpResponseRedirect(reverse('index'))

	return render(request, "mainpage/register.html", {'form': form})

def activate(request, activation_id):
	try:
		yuki = RegUser.objects.get(activation_id=activation_id)
		if yuki.user.is_active == False:
			yuki.user.is_active == True
			yuki.user.save()
			messages.success(request, 'Your account has been successfully activated. You can log in now')
		else:
			messages.warning(request, 'Your account is already activated')
	except RegUser.DoesNotExist as err:
		messages.error(request, 'Invalid activation id')

	return HttpResponseRedirect(reverse('index'))

def login_view(request):
	if request.user.is_authenticated():
		messages.info(request, 'You are already logged in')
	else:
		form = AuthenticationForm(data=request.POST)
		try:
			form.is_valid()
			form.clean()
			user = form.get_user()
			login(request, user)
			messages.success(request, 'You have logged in')
		except forms.ValidationError as err:
			messages.error(request, str(err)[2:-2])
		except AttributeError:
			messages.error(request, 'Could not log you in')
	

	return HttpResponseRedirect(request.META.get('HTTP_REFERER', '/'))

def logout_view(request):
	if not request.user.is_authenticated():
		messages.info(request, 'You are not logged in')
	else:
		logout(request)
		messages.info(request, 'You have been logged out')
	return HttpResponseRedirect(request.META.get('HTTP_REFERER'))